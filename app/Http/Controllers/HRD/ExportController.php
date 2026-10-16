<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Employee;
use App\Models\TrainingParticipant;
use App\Models\TrainingRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * Ekspor Laporan Data Karyawan sesuai filter aktif ke CSV (Excel) atau Cetak PDF (PRD Bab 7.2 & 17).
     */
    public function employees(Request $request)
    {
        $query = Employee::with(['department'])
            ->withCount(['trainingParticipants', 'certifications']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('level_jabatan', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%")
                  ->orWhere('site_unit', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('level_jabatan')) {
            $query->where('level_jabatan', $request->level_jabatan);
        }

        if ($request->filled('status_aktif')) {
            $query->where('status_aktif', $request->status_aktif);
        }

        $employees = $query->orderBy('nama')->get();

        $headers = [
            'No',
            'NIK',
            'Nama Karyawan',
            'Departemen',
            'Level Jabatan',
            'Job Title',
            'Site / Unit',
            'Status Karyawan',
            'Status Aktif',
            'Total Pelatihan',
            'Total Sertifikat',
        ];

        $rows = [];
        foreach ($employees as $idx => $emp) {
            $rows[] = [
                $idx + 1,
                $emp->nik,
                $emp->nama,
                $emp->department?->nama_departemen ?? '-',
                $emp->level_jabatan ?? '-',
                $emp->jabatan ?? '-',
                $emp->site_unit ?? 'Head Office',
                strtoupper($emp->status_karyawan ?? 'TETAP'),
                strtoupper($emp->status_aktif ?? 'AKTIF'),
                $emp->training_participants_count,
                $emp->certifications_count,
            ];
        }

        if ($request->input('format') === 'print') {
            return view('hrd.exports.print', [
                'title' => 'Laporan Master Data Karyawan & Kompetensi',
                'subtitle' => 'Dicetak pada ' . now()->format('d M Y H:i'),
                'headers' => $headers,
                'rows' => $rows,
            ]);
        }

        return $this->streamCsv('Laporan_Data_Karyawan_' . date('Ymd_His') . '.csv', $headers, $rows);
    }

    /**
     * Ekspor Laporan Riwayat Pelatihan & Sertifikasi sesuai kategori & filter aktif (PRD Bab 7.3 & 17).
     */
    public function participants(Request $request)
    {
        $category = $request->input('category', 'sertifikasi');
        $deptFilter = $request->input('department');
        $levelFilter = $request->input('level_jabatan');
        $reminderFilter = $request->input('reminder');
        $search = strtolower(trim((string) $request->input('search', '')));

        if ($category === 'sertifikasi') {
            $certifications = Certification::with(['employee.department', 'training'])
                ->latest('tanggal_terbit')
                ->get();

            $headers = [
                'No',
                'NIK',
                'Nama',
                'Department',
                'Level Jabatan',
                'Job Title',
                'Nama Sertifikat/Pelatihan',
                'Nomor Regist',
                'Tanggal Sertifikat Terbit',
                'Masa Berlaku',
                'Expired',
                'Time Reminder',
            ];

            $rows = [];
            $no = 1;

            foreach ($certifications as $cert) {
                $emp = $cert->employee;
                $deptName = $emp?->department?->nama_departemen ?? '-';
                $levelJabatan = $emp?->level_jabatan ?: 'Group Leader';

                if ($deptFilter && $deptFilter !== 'ALL' && $deptName !== $deptFilter) {
                    continue;
                }
                if ($levelFilter && $levelJabatan !== $levelFilter) {
                    continue;
                }

                $reminderCode = 'permanen';
                $reminderLabel = 'Permanen';
                if (!$cert->is_permanent && $cert->tanggal_expired) {
                    $diffDays = (int) Carbon::today()->diffInDays(Carbon::parse($cert->tanggal_expired)->startOfDay(), false);
                    if ($diffDays < 0) {
                        $reminderCode = 'expired';
                        $reminderLabel = 'Expired (' . abs($diffDays) . ' Hari Lalu)';
                    } elseif ($diffDays <= 30) {
                        $reminderCode = 'h_30';
                        $reminderLabel = 'Sisa ' . $diffDays . ' Hari';
                    } elseif ($diffDays <= 90) {
                        $reminderCode = 'h_90';
                        $reminderLabel = 'Sisa ' . $diffDays . ' Hari';
                    } else {
                        $reminderCode = 'aktif';
                        $reminderLabel = 'Aktif (' . $diffDays . ' Hari)';
                    }
                }

                if ($reminderFilter && $reminderCode !== $reminderFilter) {
                    continue;
                }

                if ($search !== '') {
                    $haystack = strtolower(implode(' ', [
                        $emp?->nik,
                        $emp?->nama,
                        $deptName,
                        $levelJabatan,
                        $emp?->jabatan,
                        $cert->nama_sertifikasi,
                        $cert->nomor_sertifikat,
                    ]));
                    if (!str_contains($haystack, $search)) {
                        continue;
                    }
                }

                $rows[] = [
                    $no++,
                    $emp?->nik ?? '-',
                    $emp?->nama ?? '-',
                    $deptName,
                    $levelJabatan,
                    $emp?->jabatan ?? '-',
                    $cert->nama_sertifikasi,
                    $cert->nomor_sertifikat ?? '-',
                    $cert->tanggal_terbit ? Carbon::parse($cert->tanggal_terbit)->format('d M Y') : '-',
                    $cert->is_permanent ? 'Permanen' : ($cert->masa_berlaku ?? '-'),
                    ($cert->is_permanent || !$cert->tanggal_expired) ? 'Permanen' : Carbon::parse($cert->tanggal_expired)->format('d M Y'),
                    $reminderLabel,
                ];
            }

            if ($request->input('format') === 'print') {
                return view('hrd.exports.print', [
                    'title' => 'Laporan Riwayat Sertifikasi & Masa Berlaku (Time Reminder)',
                    'subtitle' => 'Dicetak pada ' . now()->format('d M Y H:i'),
                    'headers' => $headers,
                    'rows' => $rows,
                ]);
            }

            return $this->streamCsv('Laporan_Riwayat_Sertifikasi_' . date('Ymd_His') . '.csv', $headers, $rows);
        }

        // Kategori Technical Skill atau Soft Skill
        $targetKategori = $category === 'soft' ? 'Soft Skill' : 'Technical Skill';

        $participants = TrainingParticipant::with(['employee.department', 'training'])
            ->whereHas('training', fn($q) => $q->where('kategori', $targetKategori))
            ->latest()
            ->get();

        $headers = [
            'No',
            'NIK',
            'Nama',
            'Department',
            'Level Jabatan',
            'Job Title',
            'Nama Pelatihan',
            'Tanggal Pelatihan',
            'Kehadiran',
            'Status Penyelesaian',
            'Hasil Evaluasi',
        ];

        $rows = [];
        $no = 1;

        foreach ($participants as $tp) {
            $emp = $tp->employee;
            $tr = $tp->training;
            $deptName = $emp?->department?->nama_departemen ?? '-';
            $levelJabatan = $emp?->level_jabatan ?: 'Group Leader';

            if ($deptFilter && $deptFilter !== 'ALL' && $deptName !== $deptFilter) {
                continue;
            }
            if ($levelFilter && $levelJabatan !== $levelFilter) {
                continue;
            }

            $tgl = '-';
            if ($tr?->tanggal_mulai && $tr?->tanggal_selesai) {
                $tgl = Carbon::parse($tr->tanggal_mulai)->format('d M Y') . ' - ' . Carbon::parse($tr->tanggal_selesai)->format('d M Y');
            } elseif ($tr?->tanggal_mulai) {
                $tgl = Carbon::parse($tr->tanggal_mulai)->format('d M Y');
            }

            $rows[] = [
                $no++,
                $emp?->nik ?? '-',
                $emp?->nama ?? '-',
                $deptName,
                $levelJabatan,
                $emp?->jabatan ?? '-',
                $tr?->nama_pelatihan ?? '-',
                $tgl,
                strtoupper(str_replace('_', ' ', $tp->kehadiran ?? 'belum_dicatat')),
                strtoupper(str_replace('_', ' ', $tp->status_peserta ?? 'disetujui')),
                $tp->hasil_evaluasi ?? '-',
            ];
        }

        if ($request->input('format') === 'print') {
            return view('hrd.exports.print', [
                'title' => "Laporan Riwayat Pelatihan ({$targetKategori})",
                'subtitle' => 'Dicetak pada ' . now()->format('d M Y H:i'),
                'headers' => $headers,
                'rows' => $rows,
            ]);
        }

        $safeSlug = strtolower(str_replace(' ', '_', $targetKategori));
        return $this->streamCsv("Laporan_Riwayat_{$safeSlug}_" . date('Ymd_His') . '.csv', $headers, $rows);
    }

    /**
     * Ekspor Laporan Request Training sesuai filter aktif (PRD Bab 7.5 & 17).
     */
    public function requests(Request $request)
    {
        $query = TrainingRequest::with([
            'training',
            'employee.department',
            'department',
            'diajukanOleh',
            'participants.employee',
        ]);

        if ($request->filled('jenis_request')) {
            $query->where('jenis_request', $request->jenis_request);
        }

        if ($request->filled('department_id')) {
            $deptId = $request->department_id;
            $query->where(function ($q) use ($deptId) {
                $q->where('department_id', $deptId)
                  ->orWhereHas('employee', fn($eq) => $eq->where('department_id', $deptId));
            });
        }

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

        $headers = [
            'No',
            'Nomor Request',
            'Tanggal Pengajuan',
            'Jenis Request',
            'Departemen',
            'Program / Scope Pelatihan',
            'Jumlah Peserta',
            'Daftar Peserta',
            'Estimasi Biaya (Rp)',
            'Status Keputusan',
            'Catatan Review',
        ];

        $rows = [];
        foreach ($requests as $idx => $req) {
            $partNames = $req->participants->count() > 0
                ? $req->participants->map(fn($p) => $p->employee?->nama ?? $p->nama_peserta)->implode(', ')
                : ($req->employee?->nama ?? '-');

            $partCount = max(1, $req->participants->count());

            $rows[] = [
                $idx + 1,
                $req->nomor_request ?? ('#REQ-' . str_pad((string) $req->id, 4, '0', STR_PAD_LEFT)),
                $req->created_at?->format('d M Y H:i') ?? '-',
                $req->jenis_request === 'training_baru' ? 'Training Baru' : 'Dari Katalog',
                $req->department?->nama_departemen ?? $req->employee?->department?->nama_departemen ?? '-',
                $req->training?->nama_pelatihan ?? $req->scope_program ?? $req->training_need ?? '-',
                $partCount . ' Orang',
                $partNames,
                $req->estimasi_biaya !== null ? number_format((float) $req->estimasi_biaya, 0, ',', '.') : '-',
                strtoupper($req->status ?? 'MENUNGGU_REVIEW'),
                $req->catatan_admin ?? '-',
            ];
        }

        if ($request->input('format') === 'print') {
            return view('hrd.exports.print', [
                'title' => 'Laporan Pengajuan & Persetujuan Pelatihan (Request Training)',
                'subtitle' => 'Dicetak pada ' . now()->format('d M Y H:i'),
                'headers' => $headers,
                'rows' => $rows,
            ]);
        }

        return $this->streamCsv('Laporan_Request_Training_' . date('Ymd_His') . '.csv', $headers, $rows);
    }

    /**
     * Helper streaming CSV dengan UTF-8 BOM agar langsung rapi saat dibuka di Microsoft Excel.
     */
    private function streamCsv(string $filename, array $headers, array $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            // Tambahkan UTF-8 BOM untuk kompatibilitas penuh dengan Microsoft Excel
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, $headers);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
