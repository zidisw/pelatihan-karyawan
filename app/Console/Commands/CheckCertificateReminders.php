<?php

namespace App\Console\Commands;

use App\Models\CertificateReminder;
use App\Models\Certification;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckCertificateReminders extends Command
{
    /**
     * Signature dan deskripsi Artisan Command (PRD Bab 7.3.D).
     */
    protected $signature = 'certificates:check-reminders';

    protected $description = 'Mengecek masa berlaku sertifikat karyawan (H-90, H-60, H-30, H-14, H-7, H-0, EXPIRED) dan mengirim notifikasi pengingat tanpa duplikasi.';

    public function handle(): int
    {
        $today = Carbon::today();
        $hrdUserIds = User::where('role', 'hrd')->pluck('id')->toArray();

        $certifications = Certification::with(['employee.department'])
            ->where('is_permanent', false)
            ->whereNotNull('tanggal_expired')
            ->get();

        $sentCount = 0;

        foreach ($certifications as $cert) {
            $expDate = Carbon::parse($cert->tanggal_expired)->startOfDay();
            $diffDays = (int) $today->diffInDays($expDate, false);

            $tipeReminder = $this->resolveReminderType($diffDays);
            if (!$tipeReminder) {
                continue;
            }

            // Cegah duplikasi pengiriman pada tipe_reminder yang sama untuk sertifikat ini
            $alreadySent = CertificateReminder::where('certification_id', $cert->id)
                ->where('tipe_reminder', $tipeReminder)
                ->exists();

            if ($alreadySent) {
                continue;
            }

            CertificateReminder::create([
                'certification_id' => $cert->id,
                'tipe_reminder' => $tipeReminder,
                'tanggal_reminder' => $today->toDateString(),
                'status_pengiriman' => 'terkirim',
            ]);

            $empName = $cert->employee?->nama ?? 'Karyawan';
            $empNik = $cert->employee?->nik ?? '-';
            $deptName = $cert->employee?->department?->nama_departemen ?? '-';
            $expFormatted = $expDate->format('d M Y');

            if ($tipeReminder === 'EXPIRED') {
                $judul = "Sertifikat Kedaluwarsa ({$cert->nama_sertifikasi})";
                $pesan = "Sertifikat \"{$cert->nama_sertifikasi}\" milik {$empName} ({$empNik} - {$deptName}) telah kedaluwarsa pada {$expFormatted}. Segera jadwalkan pembaruan sertifikasi.";
                $jenisNotif = 'danger';
            } elseif ($tipeReminder === 'H-0') {
                $judul = "Hari-H Kedaluwarsa Sertifikat ({$cert->nama_sertifikasi})";
                $pesan = "Sertifikat \"{$cert->nama_sertifikasi}\" milik {$empName} ({$empNik} - {$deptName}) habis masa berlakunya hari ini ({$expFormatted}).";
                $jenisNotif = 'warning';
            } else {
                $judul = "Pengingat {$tipeReminder} Sertifikat ({$cert->nama_sertifikasi})";
                $pesan = "Sertifikat \"{$cert->nama_sertifikasi}\" milik {$empName} ({$empNik} - {$deptName}) akan berakhir dalam {$diffDays} hari pada {$expFormatted}.";
                $jenisNotif = $diffDays <= 30 ? 'danger' : 'warning';
            }

            // Target penerima: seluruh Admin HRD + User Departemen tempat karyawan bernaung
            $recipientIds = $hrdUserIds;
            if ($cert->employee?->department_id) {
                $deptUserIds = User::where('role', 'departemen')
                    ->where('department_id', $cert->employee->department_id)
                    ->pluck('id')
                    ->toArray();
                $recipientIds = array_values(array_unique(array_merge($recipientIds, $deptUserIds)));
            }

            foreach ($recipientIds as $uid) {
                Notification::create([
                    'user_id' => $uid,
                    'judul' => $judul,
                    'pesan' => $pesan,
                    'jenis' => $jenisNotif,
                    'reference_type' => 'Certification',
                    'reference_id' => $cert->id,
                    'is_read' => false,
                ]);
            }

            $sentCount++;
        }

        $this->info("Pengecekan selesai. {$sentCount} pengingat sertifikat baru berhasil dicatat dan dikirim.");

        return self::SUCCESS;
    }

    /**
     * Menentukan bucket tipe_reminder berdasarkan sisa hari menuju tanggal_expired (PRD 7.3.D).
     */
    private function resolveReminderType(int $diffDays): ?string
    {
        if ($diffDays < 0) {
            return 'EXPIRED';
        }
        if ($diffDays === 0) {
            return 'H-0';
        }
        if ($diffDays <= 7) {
            return 'H-7';
        }
        if ($diffDays <= 14) {
            return 'H-14';
        }
        if ($diffDays <= 30) {
            return 'H-30';
        }
        if ($diffDays <= 60) {
            return 'H-60';
        }
        if ($diffDays <= 90) {
            return 'H-90';
        }

        return null;
    }
}
