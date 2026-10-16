<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'kode_program',
    'nama_pelatihan',
    'kategori',
    'jenis_sertifikasi',
    'deskripsi',
    'instruktur',
    'tanggal_mulai',
    'tanggal_selesai',
    'batas_pendaftaran',
    'kuota',
    'lokasi',
    'metode_pelaksanaan',
    'status_pendaftaran',
    'status_program',
    'persyaratan_peserta',
    'target_departemen',
    'estimasi_biaya',
    'catatan_internal',
])]
class Training extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'target_departemen' => 'array',
            'estimasi_biaya' => 'decimal:2',
        ];
    }

    public function requests()
    {
        return $this->hasMany(TrainingRequest::class);
    }

    public function participants()
    {
        return $this->hasMany(TrainingParticipant::class);
    }

    public function documents()
    {
        return $this->hasMany(TrainingDocument::class);
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'training_participants', 'training_id', 'employee_id')
            ->withPivot('id', 'status_peserta', 'kehadiran', 'hasil_evaluasi', 'created_at');
    }

    /**
     * Hitung jumlah peserta yang sudah disetujui / aktif mengikuti pelatihan (BR-08).
     */
    public function approvedParticipantsCount(): int
    {
        if (array_key_exists('approved_participants_count', $this->attributes)) {
            return (int) $this->attributes['approved_participants_count'];
        }

        $validStatuses = ['disetujui', 'terjadwal', 'sedang_pelatihan', 'selesai', 'lulus'];

        if ($this->relationLoaded('participants')) {
            return $this->participants->whereIn('status_peserta', $validStatuses)->count();
        }

        return $this->participants()->whereIn('status_peserta', $validStatuses)->count();
    }

    /**
     * Hitung sisa kuota kursi pelatihan secara dinamis (BR-08).
     */
    public function remainingQuota(): int
    {
        return max(0, (int) $this->kuota - $this->approvedParticipantsCount());
    }

    /**
     * Hitung persentase keterisian kuota (0 - 100%).
     */
    public function quotaPercentage(): int
    {
        $kuota = max(1, (int) $this->kuota);
        return (int) min(100, round(($this->approvedParticipantsCount() / $kuota) * 100));
    }

    /**
     * Cek apakah pelatihan sudah memiliki transaksi peserta atau pengajuan (Proteksi Hapus BR-11).
     */
    public function hasTransactions(): bool
    {
        $hasParticipants = $this->relationLoaded('participants')
            ? $this->participants->isNotEmpty()
            : $this->participants()->exists();

        $hasRequests = $this->relationLoaded('requests')
            ? $this->requests->isNotEmpty()
            : $this->requests()->exists();

        return $hasParticipants || $hasRequests;
    }
}
