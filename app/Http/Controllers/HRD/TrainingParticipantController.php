<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Department;
use App\Models\Employee;
use App\Models\TrainingParticipant;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TrainingParticipantController extends Controller
{
    /**
     * Menampilkan halaman Riwayat Pelatihan dengan 3 Widget Utama:
     * 1. Sertifikasi
     * 2. Technical Skill
     * 3. Soft Skill
     */
    public function index(Request $request)
    {
        $departments = Department::orderBy('id')->get();

        // 1. Ambil seluruh data Sertifikasi beserta relasi karyawan & departemen
        $certifications = Certification::with(['employee.department', 'training'])
            ->latest('tanggal_terbit')
            ->get()
            ->map(function (Certification $cert) {
                $emp = $cert->employee;
                $deptName = $emp?->department?->nama_departemen ?? '-';
                $levelJabatan = $emp?->level_jabatan ?: $this->inferLevelJabatan($emp?->jabatan);

                // Hitung Masa Berlaku jika belum terisi
                $masaBerlaku = $cert->masa_berlaku;
                if (!$masaBerlaku) {
                    if ($cert->is_permanent || !$cert->tanggal_expired) {
                        $masaBerlaku = 'Permanen';
                    } elseif ($cert->tanggal_terbit && $cert->tanggal_expired) {
                        $years = Carbon::parse($cert->tanggal_terbit)->diffInYears(Carbon::parse($cert->tanggal_expired));
                        $masaBerlaku = $years > 0 ? $years . ' Tahun' : Carbon::parse($cert->tanggal_terbit)->diffInMonths(Carbon::parse($cert->tanggal_expired)) . ' Bulan';
                    } else {
                        $masaBerlaku = '-';
                    }
                }

                // Hitung Time Reminder (Waktu Kadaluarsa Sertifikasi)
                $reminderCode = 'permanen';
                $reminderLabel = 'Permanen';
                $reminderBadge = 'bg-slate-100 text-slate-600 border-slate-200';
                $reminderDot = 'bg-slate-400';

                if (!$cert->is_permanent && $cert->tanggal_expired) {
                    $today = Carbon::today();
                    $expDate = Carbon::parse($cert->tanggal_expired)->startOfDay();
                    $diffDays = (int) $today->diffInDays($expDate, false);

                    if ($diffDays < 0) {
                        $reminderCode = 'expired';
                        $reminderLabel = 'Expired (' . abs($diffDays) . ' Hari Lalu)';
                        $reminderBadge = 'bg-rose-50 text-rose-700 border-rose-200';
                        $reminderDot = 'bg-rose-500';
                    } elseif ($diffDays <= 30) {
                        $reminderCode = 'h_30';
                        $reminderLabel = 'Sisa ' . $diffDays . ' Hari';
                        $reminderBadge = 'bg-rose-50 text-rose-600 border-rose-200';
                        $reminderDot = 'bg-rose-500';
                    } elseif ($diffDays <= 90) {
                        $reminderCode = 'h_90';
                        $reminderLabel = 'Sisa ' . $diffDays . ' Hari';
                        $reminderBadge = 'bg-amber-50 text-amber-700 border-amber-200';
                        $reminderDot = 'bg-amber-500';
                    } else {
                        $reminderCode = 'aktif';
                        $reminderLabel = 'Aktif (' . $diffDays . ' Hari)';
                        $reminderBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                        $reminderDot = 'bg-emerald-500';
                    }
                }

                return [
                    'id' => $cert->id,
                    'nik' => $emp?->nik ?? '-',
                    'nama' => $emp?->nama ?? '-',
                    'department' => $deptName,
                    'level_jabatan' => $levelJabatan,
                    'job_title' => $emp?->jabatan ?? '-',
                    'nama_pelatihan' => $cert->nama_sertifikasi,
                    'nomor_regist' => $cert->nomor_sertifikat ?? '-',
                    'tanggal_terbit' => $cert->tanggal_terbit ? Carbon::parse($cert->tanggal_terbit)->format('d M Y') : '-',
                    'masa_berlaku' => $masaBerlaku,
                    'expired' => ($cert->is_permanent || !$cert->tanggal_expired)
                        ? 'Permanen'
                        : Carbon::parse($cert->tanggal_expired)->format('d M Y'),
                    'reminder_code' => $reminderCode,
                    'reminder_label' => $reminderLabel,
                    'reminder_badge' => $reminderBadge,
                    'reminder_dot' => $reminderDot,
                ];
            })
            ->values();

        // 2. Ambil seluruh data peserta Technical Skill
        $technicalSkills = TrainingParticipant::with(['employee.department', 'training'])
            ->whereHas('training', function ($q) {
                $q->where('kategori', 'Technical Skill');
            })
            ->latest()
            ->get()
            ->map(function (TrainingParticipant $tp) {
                $emp = $tp->employee;
                $tr = $tp->training;
                $levelJabatan = $emp?->level_jabatan ?: $this->inferLevelJabatan($emp?->jabatan);

                $tanggalPelatihan = '-';
                if ($tr?->tanggal_mulai && $tr?->tanggal_selesai) {
                    $tanggalPelatihan = Carbon::parse($tr->tanggal_mulai)->format('d M Y') . ' - ' . Carbon::parse($tr->tanggal_selesai)->format('d M Y');
                } elseif ($tr?->tanggal_mulai) {
                    $tanggalPelatihan = Carbon::parse($tr->tanggal_mulai)->format('d M Y');
                }

                return [
                    'id' => $tp->id,
                    'nik' => $emp?->nik ?? '-',
                    'nama' => $emp?->nama ?? '-',
                    'department' => $emp?->department?->nama_departemen ?? '-',
                    'level_jabatan' => $levelJabatan,
                    'job_title' => $emp?->jabatan ?? '-',
                    'nama_pelatihan' => $tr?->nama_pelatihan ?? '-',
                    'tanggal_pelatihan' => $tanggalPelatihan,
                    'status_peserta' => $tp->status_peserta,
                ];
            })
            ->values();

        // 3. Ambil seluruh data peserta Soft Skill
        $softSkills = TrainingParticipant::with(['employee.department', 'training'])
            ->whereHas('training', function ($q) {
                $q->where('kategori', 'Soft Skill');
            })
            ->latest()
            ->get()
            ->map(function (TrainingParticipant $tp) {
                $emp = $tp->employee;
                $tr = $tp->training;
                $levelJabatan = $emp?->level_jabatan ?: $this->inferLevelJabatan($emp?->jabatan);

                $tanggalPelatihan = '-';
                if ($tr?->tanggal_mulai && $tr?->tanggal_selesai) {
                    $tanggalPelatihan = Carbon::parse($tr->tanggal_mulai)->format('d M Y') . ' - ' . Carbon::parse($tr->tanggal_selesai)->format('d M Y');
                } elseif ($tr?->tanggal_mulai) {
                    $tanggalPelatihan = Carbon::parse($tr->tanggal_mulai)->format('d M Y');
                }

                return [
                    'id' => $tp->id,
                    'nik' => $emp?->nik ?? '-',
                    'nama' => $emp?->nama ?? '-',
                    'department' => $emp?->department?->nama_departemen ?? '-',
                    'level_jabatan' => $levelJabatan,
                    'job_title' => $emp?->jabatan ?? '-',
                    'nama_pelatihan' => $tr?->nama_pelatihan ?? '-',
                    'tanggal_pelatihan' => $tanggalPelatihan,
                    'status_peserta' => $tp->status_peserta,
                ];
            })
            ->values();

        // 4 Pilihan Resmi Level Jabatan untuk dropdown filter
        $levelJabatanOptions = collect([
            'Group Leader',
            'Head Of Section',
            'Head Of Department',
            'Penanggung Jawab Operational',
        ]);

        return view('hrd.participants.index', compact(
            'departments',
            'certifications',
            'technicalSkills',
            'softSkills',
            'levelJabatanOptions'
        ));
    }

    /**
     * Memperbarui status progres peserta pelatihan.
     */
    public function updateStatus(Request $request, TrainingParticipant $participant)
    {
        $request->validate([
            'status_peserta' => 'required|in:belum_diajukan,diajukan,disetujui,terjadwal,sedang_pelatihan,selesai,lulus,tidak_hadir,dibatalkan',
        ]);

        $participant->update([
            'status_peserta' => $request->status_peserta,
        ]);

        return back()->with('success', 'Status peserta pelatihan berhasil diperbarui!');
    }

    /**
     * Fallback penentuan Level Jabatan apabila kolom level_jabatan kosong.
     */
    private function inferLevelJabatan(?string $jabatan): string
    {
        if (!$jabatan) {
            return 'Group Leader';
        }

        $lower = strtolower($jabatan);
        if (str_contains($lower, 'pjo') || str_contains($lower, 'operational')) {
            return 'Penanggung Jawab Operational';
        }
        if (str_contains($lower, 'manager') || str_contains($lower, 'department')) {
            return 'Head Of Department';
        }
        if (str_contains($lower, 'supervisor') || str_contains($lower, 'senior') || str_contains($lower, 'section')) {
            return 'Head Of Section';
        }

        return 'Group Leader';
    }
}
