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
}
