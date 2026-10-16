<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Department;
use App\Models\Employee;
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
     * Menampilkan daftar pelatihan yang tersedia di katalog (status_pendaftaran: dibuka).
     */
    public function index(Request $request)
    {
        $validStatuses = ['disetujui', 'terjadwal', 'sedang_pelatihan', 'selesai', 'lulus'];

        $query = Training::with(['documents'])
            ->withCount([
                'participants as approved_participants_count' => function ($q) use ($validStatuses) {
                    $q->whereIn('status_peserta', $validStatuses);
                },
            ])
            ->where('status_pendaftaran', 'dibuka')
            ->whereNotIn('status_program', ['dibatalkan', 'selesai']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelatihan', 'like', "%{$search}%")
                  ->orWhere('kode_program', 'like', "%{$search}%")
                  ->orWhere('instruktur', 'like', "%{$search}%")
                  ->orWhere('jenis_sertifikasi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('metode_pelaksanaan')) {
            $query->where('metode_pelaksanaan', $request->metode_pelaksanaan);
        }

        $trainings = $query->orderBy('tanggal_mulai', 'asc')->get();

        return view('department.trainings.index', compact('trainings'));
    }

    /**
     * Menampilkan form pengajuan multi-peserta dari katalog untuk pelatihan tertentu (PRD 8.2.A).
     */
    public function create(Training $training)
    {
        $user = auth()->user();
        $validStatuses = ['disetujui', 'terjadwal', 'sedang_pelatihan', 'selesai', 'lulus'];

        $training->loadCount([
            'participants as approved_participants_count' => function ($q) use ($validStatuses) {
                $q->whereIn('status_peserta', $validStatuses);
            },
        ]);

        // Ambil hanya karyawan aktif yang satu departemen dengan User Departemen ini
        $employees = Employee::with('department')
            ->when($user->department_id, fn($q) => $q->where('department_id', $user->department_id))
            ->where('status_aktif', 'aktif')
            ->orderBy('nama')
            ->get();

        // Identifikasi karyawan yang sudah terdaftar atau sedang diajukan pada pelatihan ini
        $existingEmployeeIds = $this->getExistingEmployeeIdsForTraining($training->id);

        return view('department.trainings.create', compact('training', 'employees', 'existingEmployeeIds'));
    }

    /**
     * Menyimpan pengajuan multi-peserta dari katalog ke database (PRD 8.2.A).
     */
    public function store(Request $request, Training $training)
    {
        $user = auth()->user();

        // 1. Validasi status pendaftaran & batas waktu pendaftaran
        if ($training->status_pendaftaran !== 'dibuka' || in_array($training->status_program, ['dibatalkan', 'selesai'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Pendaftaran untuk program pelatihan ini sedang ditutup atau tidak aktif.');
        }

        if ($training->batas_pendaftaran && now()->toDateString() > $training->batas_pendaftaran) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Batas waktu pendaftaran untuk pelatihan ini telah berakhir pada ' . \Carbon\Carbon::parse($training->batas_pendaftaran)->format('d M Y') . '.');
        }

        // Dukung input employee_ids[] (multi-select) maupun employee_id (single fallback)
        if (!$request->has('employee_ids') && $request->filled('employee_id')) {
            $request->merge(['employee_ids' => [$request->employee_id]]);
        }

        $validated = $request->validate([
            'employee_ids' => 'required|array|min:1',
            'employee_ids.*' => 'required|exists:employees,id',
            'alasan_pengajuan' => 'nullable|string|max:1000',
        ], [
            'employee_ids.required' => 'Pilih minimal 1 karyawan untuk diajukan mengikuti pelatihan.',
            'employee_ids.min' => 'Pilih minimal 1 karyawan untuk diajukan mengikuti pelatihan.',
        ]);

        $employeeIds = array_values(array_unique(array_map('intval', $validated['employee_ids'])));

        // 2. Validasi kepemilikan departemen & status aktif karyawan
        $employees = Employee::whereIn('id', $employeeIds)
            ->when($user->department_id, fn($q) => $q->where('department_id', $user->department_id))
            ->get();

        if ($employees->count() !== count($employeeIds)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terdapat karyawan yang tidak valid atau berasal dari luar departemen Anda.');
        }

        // 3. Validasi Sisa Kuota Dinamis (BR-08)
        $remainingQuota = $training->remainingQuota();
        if (count($employeeIds) > $remainingQuota) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Jumlah peserta yang diajukan (" . count($employeeIds) . " orang) melebihi sisa kuota pelatihan yang tersedia ({$remainingQuota} kursi).");
        }

        // 4. Validasi Duplikasi Peserta pada pelatihan yang sama
        $existingIds = $this->getExistingEmployeeIdsForTraining($training->id);
        $duplicateIds = array_intersect($employeeIds, $existingIds);

        if (!empty($duplicateIds)) {
            $duplicateNames = $employees->whereIn('id', $duplicateIds)->pluck('nama')->implode(', ');
            return redirect()->back()
                ->withInput()
                ->with('error', "Karyawan berikut sudah terdaftar atau sedang diajukan pada pelatihan ini: {$duplicateNames}.");
        }

        // 5. Simpan Header Pengajuan & Daftar Peserta dalam Database Transaction
        $createdRequest = null;
        DB::transaction(function () use ($user, $training, $employees, $validated, &$createdRequest) {
            $firstEmployee = $employees->first();

            $createdRequest = TrainingRequest::create([
                'nomor_request' => TrainingRequest::generateNomorRequest(),
                'department_id' => $user->department_id ?? $firstEmployee->department_id,
                'employee_id' => $firstEmployee->id,
                'training_id' => $training->id,
                'diajukan_oleh' => $user->id,
                'jenis_request' => 'katalog',
                'scope_program' => $training->nama_pelatihan,
                'kondisi_saat_ini' => $validated['alasan_pengajuan'] ?? null,
                'metode_pelaksanaan' => ucfirst(strtolower($training->metode_pelaksanaan ?? 'Offline')),
                'estimasi_biaya' => $training->estimasi_biaya,
                'status' => 'menunggu_review',
            ]);

            foreach ($employees as $emp) {
                TrainingRequestParticipant::create([
                    'training_request_id' => $createdRequest->id,
                    'employee_id' => $emp->id,
                    'nama_peserta' => $emp->nama,
                    'jabatan_peserta' => $emp->jabatan,
                    'status_peserta' => 'menunggu_review',
                ]);
            }
        });

        if ($createdRequest) {
            AuditLog::record(
                'TrainingRequest',
                $createdRequest->id,
                'CREATE_KATALOG',
                null,
                $createdRequest->toArray(),
                "Pengajuan katalog {$createdRequest->nomor_request} untuk " . count($employeeIds) . " karyawan"
            );

            $hrdUserIds = User::where('role', 'hrd')->pluck('id');
            foreach ($hrdUserIds as $hrdId) {
                Notification::create([
                    'user_id' => $hrdId,
                    'judul' => "Pengajuan Pelatihan Baru ({$createdRequest->nomor_request})",
                    'pesan' => "{$user->name} mengajukan " . count($employeeIds) . " peserta untuk program katalog \"{$training->nama_pelatihan}\".",
                    'jenis' => 'info',
                    'reference_type' => 'TrainingRequest',
                    'reference_id' => $createdRequest->id,
                    'is_read' => false,
                ]);
            }
        }

        return redirect()->route('department.trainings.history')
            ->with('success', 'Pengajuan pelatihan untuk ' . count($employeeIds) . ' karyawan berhasil dikirim ke Admin HRGA!');
    }

    /**
     * Menampilkan form "+ Request Training Baru" di luar katalog dengan 10 field (PRD 8.2.B).
     */
    public function createNew()
    {
        $user = auth()->user();
        $user->load('department');

        $employees = Employee::with('department')
            ->when($user->department_id, fn($q) => $q->where('department_id', $user->department_id))
            ->where('status_aktif', 'aktif')
            ->orderBy('nama')
            ->get();

        return view('department.trainings.create_new', compact('user', 'employees'));
    }

    /**
     * Menyimpan pengajuan "+ Request Training Baru" di luar katalog (10 field lengkap).
     */
    public function storeNew(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'periode_pengajuan' => 'required|string|max:50',
            'scope_program' => 'required|string|max:255',
            'kondisi_saat_ini' => 'required|string|max:2000',
            'training_need' => 'required|string|max:2000',
            'target_kpi' => 'required|string|max:2000',
            'estimasi_biaya' => 'required|numeric|min:0',
            'pelaksana' => 'required|in:Internal,Eksternal',
            'employee_ids' => 'required|array|min:1',
            'employee_ids.*' => 'required|exists:employees,id',
            'metode_pelaksanaan' => 'required|in:Online,Offline,Hybrid',
        ], [
            'periode_pengajuan.required' => 'Periode pengajuan wajib diisi.',
            'scope_program.required' => 'Scope / Program wajib diisi.',
            'kondisi_saat_ini.required' => 'Kondisi karyawan saat ini wajib diisi.',
            'training_need.required' => 'Training Need wajib diisi.',
            'target_kpi.required' => 'Target / KPI setelah mengikuti pelatihan wajib diisi.',
            'estimasi_biaya.required' => 'Estimasi biaya wajib diisi.',
            'pelaksana.required' => 'Pilih pelaksana pelatihan (Internal / Eksternal).',
            'employee_ids.required' => 'Pilih minimal 1 peserta yang didaftarkan.',
            'employee_ids.min' => 'Pilih minimal 1 peserta yang didaftarkan.',
            'metode_pelaksanaan.required' => 'Pilih metode pelaksanaan (Online / Offline).',
        ]);

        $employeeIds = array_values(array_unique(array_map('intval', $validated['employee_ids'])));

        $employees = Employee::whereIn('id', $employeeIds)
            ->when($user->department_id, fn($q) => $q->where('department_id', $user->department_id))
            ->get();

        if ($employees->count() !== count($employeeIds)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terdapat peserta yang tidak valid atau berasal dari luar departemen Anda.');
        }

        $createdRequest = null;
        DB::transaction(function () use ($user, $employees, $validated, &$createdRequest) {
            $firstEmployee = $employees->first();

            $createdRequest = TrainingRequest::create([
                'nomor_request' => TrainingRequest::generateNomorRequest(),
                'department_id' => $user->department_id ?? $firstEmployee->department_id,
                'employee_id' => $firstEmployee->id,
                'training_id' => null,
                'diajukan_oleh' => $user->id,
                'jenis_request' => 'training_baru',
                'periode_pengajuan' => $validated['periode_pengajuan'],
                'scope_program' => $validated['scope_program'],
                'kondisi_saat_ini' => $validated['kondisi_saat_ini'],
                'training_need' => $validated['training_need'],
                'target_kpi' => $validated['target_kpi'],
                'estimasi_biaya' => $validated['estimasi_biaya'],
                'pelaksana' => $validated['pelaksana'],
                'metode_pelaksanaan' => $validated['metode_pelaksanaan'],
                'status' => 'menunggu_review',
            ]);

            foreach ($employees as $emp) {
                TrainingRequestParticipant::create([
                    'training_request_id' => $createdRequest->id,
                    'employee_id' => $emp->id,
                    'nama_peserta' => $emp->nama,
                    'jabatan_peserta' => $emp->jabatan,
                    'status_peserta' => 'menunggu_review',
                ]);
            }
        });

        if ($createdRequest) {
            AuditLog::record(
                'TrainingRequest',
                $createdRequest->id,
                'CREATE_TRAINING_BARU',
                null,
                $createdRequest->toArray(),
                "Usulan Training Baru {$createdRequest->nomor_request} ({$validated['scope_program']}) untuk " . count($employeeIds) . " peserta"
            );

            $hrdUserIds = User::where('role', 'hrd')->pluck('id');
            foreach ($hrdUserIds as $hrdId) {
                Notification::create([
                    'user_id' => $hrdId,
                    'judul' => "Usulan Training Baru ({$createdRequest->nomor_request})",
                    'pesan' => "{$user->name} mengusulkan program baru \"{$validated['scope_program']}\" untuk " . count($employeeIds) . " peserta.",
                    'jenis' => 'info',
                    'reference_type' => 'TrainingRequest',
                    'reference_id' => $createdRequest->id,
                    'is_read' => false,
                ]);
            }
        }

        return redirect()->route('department.trainings.history')
            ->with('success', 'Usulan Request Training Baru (' . $validated['scope_program'] . ') untuk ' . count($employeeIds) . ' peserta berhasil dikirim ke Admin HRGA!');
    }

    /**
     * Menampilkan riwayat pengajuan yang pernah dibuat oleh departemen ini.
     */
    public function history(Request $request)
    {
        $user = auth()->user();

        $query = TrainingRequest::with([
            'training',
            'employee',
            'department',
            'participants.employee',
            'reviewer',
        ])->where(function ($q) use ($user) {
            $q->where('diajukan_oleh', $user->id);
            if ($user->department_id) {
                $q->orWhere('department_id', $user->department_id);
            }
        });

        if ($request->filled('jenis_request')) {
            $query->where('jenis_request', $request->jenis_request);
        }

        if ($request->filled('status')) {
            if ($request->status === 'pending_group') {
                $query->whereIn('status', ['menunggu_review', 'pending']);
            } else {
                $query->where('status', $request->status);
            }
        }

        $requests = $query->latest()->paginate(10)->withQueryString();

        return view('department.trainings.history', compact('requests'));
    }

    /**
     * Menampilkan halaman Detail Katalog Pelatihan untuk User Departemen (PRD 7.4.D & 8.2).
     */
    public function show(Training $training)
    {
        $user = auth()->user();
        $validStatuses = ['disetujui', 'terjadwal', 'sedang_pelatihan', 'selesai', 'lulus'];

        $training->load(['documents']);
        $training->loadCount([
            'participants as approved_participants_count' => function ($q) use ($validStatuses) {
                $q->whereIn('status_peserta', $validStatuses);
            },
        ]);

        // Ambil nama target departemen jika diatur
        $targetDepartmentNames = [];
        if (is_array($training->target_departemen) && !empty($training->target_departemen)) {
            $targetDepartmentNames = Department::whereIn('id', $training->target_departemen)
                ->pluck('nama_departemen')
                ->toArray();
        }

        // Daftar karyawan dari departemen user ini yang sudah terdaftar di pelatihan ini
        $departmentParticipants = TrainingParticipant::with('employee')
            ->where('training_id', $training->id)
            ->when($user->department_id, function ($q) use ($user) {
                $q->whereHas('employee', fn($eq) => $eq->where('department_id', $user->department_id));
            })
            ->whereNotIn('status_peserta', ['dibatalkan'])
            ->get();

        // Daftar pengajuan dari departemen user ini untuk pelatihan ini
        $departmentRequests = TrainingRequest::with(['participants.employee', 'employee'])
            ->where('training_id', $training->id)
            ->where(function ($q) use ($user) {
                $q->where('diajukan_oleh', $user->id);
                if ($user->department_id) {
                    $q->orWhere('department_id', $user->department_id);
                }
            })
            ->latest()
            ->get();

        return view('department.trainings.show', compact(
            'training',
            'targetDepartmentNames',
            'departmentParticipants',
            'departmentRequests'
        ));
    }

    /**
     * Membatalkan pengajuan pelatihan yang masih berstatus Menunggu Review / Pending (PRD 8.3).
     */
    public function cancel(TrainingRequest $trainingRequest)
    {
        $user = auth()->user();

        // Pastikan pengajuan ini milik user atau departemennya
        $isOwner = ($trainingRequest->diajukan_oleh === $user->id)
            || ($user->department_id && $trainingRequest->department_id === $user->department_id);

        if (!$isOwner) {
            abort(403, 'Akses ditolak. Anda tidak berhak membatalkan pengajuan dari departemen lain.');
        }

        if (!in_array($trainingRequest->status, ['menunggu_review', 'pending'])) {
            return redirect()->back()
                ->with('error', 'Pengajuan ini sudah diproses oleh Admin HRGA sehingga tidak dapat dibatalkan.');
        }

        $trainingRequest->update([
            'status' => 'cancelled',
        ]);

        return redirect()->back()
            ->with('success', "Pengajuan pelatihan {$trainingRequest->nomor_request} berhasil dibatalkan.");
    }

    /**
     * Helper: Dapatkan daftar employee_id yang sudah terdaftar atau sedang diajukan pada training tertentu.
     */
    private function getExistingEmployeeIdsForTraining(int $trainingId): array
    {
        $participantIds = TrainingParticipant::where('training_id', $trainingId)
            ->whereNotIn('status_peserta', ['dibatalkan'])
            ->pluck('employee_id')
            ->toArray();

        $requestParticipantIds = TrainingRequestParticipant::whereHas('trainingRequest', function ($q) use ($trainingId) {
                $q->where('training_id', $trainingId)
                  ->whereNotIn('status', ['rejected', 'cancelled']);
            })
            ->whereNotIn('status_peserta', ['rejected'])
            ->whereNotNull('employee_id')
            ->pluck('employee_id')
            ->toArray();

        $legacyRequestIds = TrainingRequest::where('training_id', $trainingId)
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->whereNotNull('employee_id')
            ->pluck('employee_id')
            ->toArray();

        return array_values(array_unique(array_map('intval', array_merge($participantIds, $requestParticipantIds, $legacyRequestIds))));
    }
}
