<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Notification;
use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Models\TrainingRequest;
use App\Models\TrainingRequestParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrainingRequestController extends Controller
{
    /**
     * Menampilkan daftar pengajuan pelatihan beserta statistik dan filter (PRD Bab 7.5).
     */
    public function index(Request $request)
    {
        $query = TrainingRequest::with([
            'training',
            'employee.department',
            'department',
            'diajukanOleh.department',
            'reviewer',
            'participants.employee.department',
        ]);

        // Pencarian berdasarkan nomor request, nama program/scope, training need, atau nama/NIK peserta
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nomor_request', 'like', "%{$search}%")
                  ->orWhere('scope_program', 'like', "%{$search}%")
                  ->orWhere('training_need', 'like', "%{$search}%")
                  ->orWhereHas('training', function ($tq) use ($search) {
                      $tq->where('nama_pelatihan', 'like', "%{$search}%")
                         ->orWhere('kode_program', 'like', "%{$search}%");
                  })
                  ->orWhereHas('employee', function ($eq) use ($search) {
                      $eq->where('nama', 'like', "%{$search}%")
                         ->orWhere('nik', 'like', "%{$search}%");
                  })
                  ->orWhereHas('participants', function ($pq) use ($search) {
                      $pq->where('nama_peserta', 'like', "%{$search}%")
                         ->orWhereHas('employee', function ($peq) use ($search) {
                             $peq->where('nama', 'like', "%{$search}%")
                                 ->orWhere('nik', 'like', "%{$search}%");
                         });
                  });
            });
        }

        // Filter Jenis Request (katalog / training_baru)
        if ($request->filled('jenis_request')) {
            $query->where('jenis_request', $request->jenis_request);
        }

        // Filter Departemen Pengaju
        if ($request->filled('department_id')) {
            $deptId = $request->department_id;
            $query->where(function ($q) use ($deptId) {
                $q->where('department_id', $deptId)
                  ->orWhereHas('employee', fn($eq) => $eq->where('department_id', $deptId))
                  ->orWhereHas('diajukanOleh', fn($uq) => $uq->where('department_id', $deptId));
            });
        }

        // Filter Status Pengajuan
        if ($request->filled('status')) {
            if ($request->status === 'waiting') {
                $query->whereIn('status', ['menunggu_review', 'pending']);
            } elseif ($request->status === 'approved_group') {
                $query->whereIn('status', ['approved', 'partially_approved']);
            } else {
                $query->where('status', $request->status);
            }
        }

        $requests = $query->latest()->get();

        // Statistik Kartu Ringkasan
        $totalCount = TrainingRequest::count();
        $waitingCount = TrainingRequest::whereIn('status', ['menunggu_review', 'pending'])->count();
        $approvedCount = TrainingRequest::whereIn('status', ['approved', 'partially_approved'])->count();
        $rejectedCount = TrainingRequest::where('status', 'rejected')->count();

        $departments = Department::orderBy('nama_departemen')->get();

        return view('hrd.requests.index', compact(
            'requests',
            'departments',
            'totalCount',
            'waitingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Memproses keputusan pengajuan pelatihan: Keseluruhan maupun Per Peserta (PRD 7.5.C, 7.5.D & BR-07).
     */
    public function process(Request $request, TrainingRequest $trainingRequest)
    {
        $trainingRequest->load(['participants.employee', 'training', 'employee']);

        // Pastikan bila request lama belum punya record di training_request_participants, dibuatkan 1 baris otomatis
        if ($trainingRequest->participants->isEmpty() && $trainingRequest->employee_id) {
            $emp = $trainingRequest->employee;
            $createdPart = TrainingRequestParticipant::create([
                'training_request_id' => $trainingRequest->id,
                'employee_id' => $emp->id,
                'nama_peserta' => $emp->nama,
                'jabatan_peserta' => $emp->jabatan,
                'status_peserta' => in_array($trainingRequest->status, ['approved', 'rejected', 'pending'])
                    ? $trainingRequest->status
                    : 'menunggu_review',
            ]);
            $trainingRequest->setRelation('participants', collect([$createdPart]));
        }

        $decisionMode = $request->input('decision_mode', 'bulk');
        $participantDecisions = $request->input('participant_decisions', []);
        $participantNotes = $request->input('participant_notes', []);
        $catatanAdmin = trim((string) $request->input('catatan_admin', ''));

        // Mode Per Peserta jika decision_mode === 'per_participant' atau array participant_decisions dikirim
        if ($decisionMode === 'per_participant' && !empty($participantDecisions)) {
            $request->validate([
                'participant_decisions' => 'required|array|min:1',
                'participant_decisions.*' => 'required|in:approved,rejected,pending',
                'participant_notes' => 'nullable|array',
                'participant_notes.*' => 'nullable|string|max:500',
                'catatan_admin' => 'nullable|string|max:1000',
            ]);

            $statuses = array_values($participantDecisions);
            $totalParts = count($statuses);
            $approvedParts = count(array_filter($statuses, fn($s) => $s === 'approved'));
            $rejectedParts = count(array_filter($statuses, fn($s) => $s === 'rejected'));
            $pendingParts = count(array_filter($statuses, fn($s) => $s === 'pending'));

            if ($approvedParts === $totalParts) {
                $finalStatus = 'approved';
            } elseif ($rejectedParts === $totalParts) {
                $finalStatus = 'rejected';
            } elseif ($pendingParts === $totalParts) {
                $finalStatus = 'pending';
            } elseif ($approvedParts > 0) {
                $finalStatus = 'partially_approved';
            } else {
                $finalStatus = 'pending';
            }

            // BR-07: Wajib ada catatan jika ada peserta yang ditolak atau dipending
            if (($rejectedParts > 0 || $pendingParts > 0) && $catatanAdmin === '') {
                $missingNote = false;
                foreach ($participantDecisions as $partId => $decStatus) {
                    if (in_array($decStatus, ['rejected', 'pending']) && empty(trim((string) ($participantNotes[$partId] ?? '')))) {
                        $missingNote = true;
                        break;
                    }
                }

                if ($missingNote) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Sesuai aturan BR-07, Catatan Admin atau Catatan Keputusan Peserta wajib diisi apabila terdapat peserta yang Ditolak atau Pending.');
                }
            }
        } else {
            // Mode Keputusan Sekaligus (Bulk)
            $request->validate([
                'status' => 'required|in:approved,rejected,pending,partially_approved',
                'catatan_admin' => 'nullable|string|max:1000',
            ]);

            $finalStatus = $request->status;

            // BR-07: Wajib mengisi catatan_admin apabila status Ditolak (rejected) atau Pending
            if (in_array($finalStatus, ['rejected', 'pending']) && $catatanAdmin === '') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Sesuai aturan BR-07, Catatan / Alasan wajib diisi saat memilih keputusan Ditolak atau Pending.');
            }

            // Terapkan keputusan bulk ke semua peserta dalam pengajuan ini
            $participantStatus = $finalStatus === 'partially_approved' ? 'approved' : $finalStatus;
            foreach ($trainingRequest->participants as $part) {
                $participantDecisions[$part->id] = $participantStatus;
            }
        }

        // Kumpulkan daftar employee_id yang disetujui (approved)
        $approvedEmployeeIds = [];
        foreach ($trainingRequest->participants as $part) {
            $dec = $participantDecisions[$part->id] ?? 'pending';
            if ($dec === 'approved' && $part->employee_id) {
                $approvedEmployeeIds[] = (int) $part->employee_id;
            }
        }
        if (empty($approvedEmployeeIds) && $finalStatus === 'approved' && $trainingRequest->employee_id) {
            $approvedEmployeeIds[] = (int) $trainingRequest->employee_id;
        }
        $approvedEmployeeIds = array_values(array_unique($approvedEmployeeIds));

        // Validasi Sisa Kuota (BR-08) jika terhubung ke Katalog Pelatihan yang sudah ada
        if ($trainingRequest->training && !empty($approvedEmployeeIds)) {
            $training = $trainingRequest->training;
            $alreadyRegisteredIds = TrainingParticipant::where('training_id', $training->id)
                ->whereIn('employee_id', $approvedEmployeeIds)
                ->whereIn('status_peserta', ['disetujui', 'terjadwal', 'sedang_pelatihan', 'selesai', 'lulus'])
                ->pluck('employee_id')
                ->map(fn($id) => (int) $id)
                ->toArray();

            $newlyApprovedCount = count(array_diff($approvedEmployeeIds, $alreadyRegisteredIds));
            $remainingQuota = $training->remainingQuota();

            if ($newlyApprovedCount > $remainingQuota) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', "Gagal menyetujui: Jumlah peserta baru yang disetujui ({$newlyApprovedCount} orang) melebihi sisa kuota program \"{$training->nama_pelatihan}\" ({$remainingQuota} kursi).");
            }
        }

        DB::transaction(function () use (
            $trainingRequest,
            $finalStatus,
            $catatanAdmin,
            $participantDecisions,
            $participantNotes,
            $approvedEmployeeIds
        ) {
            $reviewerId = auth()->id();
            $now = now();

            // Jika ini adalah Request Training Baru yang disetujui (approved / partially_approved) dan belum punya training_id,
            // buatkan program pelatihannya secara otomatis di Katalog agar peserta dapat masuk ke Riwayat Pelatihan.
            $trainingId = $trainingRequest->training_id;
            if (!$trainingId && in_array($finalStatus, ['approved', 'partially_approved']) && !empty($approvedEmployeeIds)) {
                $createdTraining = Training::create([
                    'kode_program' => $this->generateNewTrainingCode(),
                    'nama_pelatihan' => $trainingRequest->scope_program ?: ($trainingRequest->training_need ?: 'Program Pelatihan Usulan Departemen'),
                    'kategori' => 'Technical Skill',
                    'deskripsi' => trim("Usulan Training Baru ({$trainingRequest->nomor_request}).\nKondisi Saat Ini: {$trainingRequest->kondisi_saat_ini}\nTraining Need: {$trainingRequest->training_need}\nTarget / KPI: {$trainingRequest->target_kpi}"),
                    'instruktur' => $trainingRequest->pelaksana ? "Pelaksana {$trainingRequest->pelaksana}" : 'Tim Instruktur',
                    'tanggal_mulai' => now()->addDays(7)->toDateString(),
                    'tanggal_selesai' => now()->addDays(9)->toDateString(),
                    'batas_pendaftaran' => now()->addDays(5)->toDateString(),
                    'kuota' => max(10, count($approvedEmployeeIds)),
                    'lokasi' => ($trainingRequest->metode_pelaksanaan === 'Online') ? 'Online Meeting' : 'Training Center / Site',
                    'metode_pelaksanaan' => strtoupper($trainingRequest->metode_pelaksanaan ?: 'OFFLINE'),
                    'status_pendaftaran' => 'ditutup',
                    'status_program' => 'akan_datang',
                    'target_departemen' => $trainingRequest->department_id ? [(int) $trainingRequest->department_id] : null,
                    'estimasi_biaya' => $trainingRequest->estimasi_biaya,
                    'catatan_internal' => "Dibuat otomatis dari persetujuan Request Training Baru #{$trainingRequest->nomor_request}",
                ]);

                $trainingId = $createdTraining->id;
            }

            // 1. Update Header TrainingRequest
            $trainingRequest->update([
                'training_id' => $trainingId,
                'status' => $finalStatus,
                'catatan_admin' => $catatanAdmin !== '' ? $catatanAdmin : $trainingRequest->catatan_admin,
                'reviewed_by' => $reviewerId,
                'reviewed_at' => $now,
            ]);

            // 2. Update setiap baris di training_request_participants
            foreach ($trainingRequest->participants as $part) {
                $partStatus = $participantDecisions[$part->id] ?? ($finalStatus === 'partially_approved' ? 'pending' : $finalStatus);
                $partNote = isset($participantNotes[$part->id]) && trim((string) $participantNotes[$part->id]) !== ''
                    ? trim((string) $participantNotes[$part->id])
                    : ($catatanAdmin !== '' ? $catatanAdmin : $part->catatan_keputusan);

                $part->update([
                    'status_peserta' => $partStatus,
                    'catatan_keputusan' => $partNote,
                    'reviewed_by' => $reviewerId,
                    'reviewed_at' => $now,
                ]);

                // 3. Sinkronkan ke tabel training_participants jika training_id tersedia
                if ($trainingId && $part->employee_id) {
                    if ($partStatus === 'approved') {
                        TrainingParticipant::updateOrCreate(
                            [
                                'employee_id' => $part->employee_id,
                                'training_id' => $trainingId,
                            ],
                            [
                                'training_request_id' => $trainingRequest->id,
                                'status_peserta' => 'disetujui',
                            ]
                        );
                    } elseif ($partStatus === 'rejected') {
                        TrainingParticipant::where('training_request_id', $trainingRequest->id)
                            ->where('employee_id', $part->employee_id)
                            ->where('training_id', $trainingId)
                            ->update(['status_peserta' => 'dibatalkan']);
                    }
                }
            }
        });

        $statusLabel = match ($finalStatus) {
            'approved' => 'Disetujui Seluruhnya',
            'partially_approved' => 'Disetujui Sebagian',
            'rejected' => 'Ditolak',
            'pending' => 'Ditunda (Pending)',
            default => ucfirst($finalStatus),
        };

        // Catat Audit Trail keputusan review pengajuan (PRD Bab 14 & BR-12)
        AuditLog::record(
            'TrainingRequest',
            $trainingRequest->id,
            'REVIEW_DECISION',
            null,
            [
                'nomor_request' => $trainingRequest->nomor_request,
                'status' => $finalStatus,
                'catatan_admin' => $catatanAdmin,
                'participant_decisions' => $participantDecisions,
            ],
            "Review pengajuan {$trainingRequest->nomor_request}: {$statusLabel}"
        );

        // Kirim Notifikasi In-App ke User Departemen pengaju (PRD Bab 12)
        $recipientIds = [];
        if ($trainingRequest->diajukan_oleh) {
            $recipientIds[] = (int) $trainingRequest->diajukan_oleh;
        }
        if ($trainingRequest->department_id) {
            $deptUserIds = User::where('role', 'departemen')
                ->where('department_id', $trainingRequest->department_id)
                ->pluck('id')
                ->map(fn($id) => (int) $id)
                ->toArray();
            $recipientIds = array_values(array_unique(array_merge($recipientIds, $deptUserIds)));
        }

        $judulProgram = $trainingRequest->training?->nama_pelatihan ?? $trainingRequest->scope_program ?? 'Pelatihan';
        $notifJenis = match ($finalStatus) {
            'approved', 'partially_approved' => 'success',
            'rejected' => 'danger',
            default => 'warning',
        };

        foreach ($recipientIds as $uid) {
            Notification::create([
                'user_id' => $uid,
                'judul' => "Keputusan Pengajuan {$trainingRequest->nomor_request}: {$statusLabel}",
                'pesan' => "Pengajuan pelatihan \"{$judulProgram}\" telah ditinjau oleh Admin HRGA dengan keputusan: {$statusLabel}." . ($catatanAdmin ? " Catatan: {$catatanAdmin}" : ''),
                'jenis' => $notifJenis,
                'reference_type' => 'TrainingRequest',
                'reference_id' => $trainingRequest->id,
                'is_read' => false,
            ]);
        }

        return redirect()->route('hrd.requests.index')
            ->with('success', "Keputusan pengajuan {$trainingRequest->nomor_request} berhasil disimpan dengan status: {$statusLabel}!");
    }

    /**
     * Helper untuk men-generate kode program otomatis saat usulan Training Baru disetujui.
     */
    private function generateNewTrainingCode(): string
    {
        $year = date('Y');
        $prefix = "TRG-{$year}-";
        $nextNumber = Training::count() + 1;

        do {
            $code = $prefix . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
            $exists = Training::where('kode_program', $code)->exists();
            if ($exists) {
                $nextNumber++;
            }
        } while ($exists);

        return $code;
    }
}
