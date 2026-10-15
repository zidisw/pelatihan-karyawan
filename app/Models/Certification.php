<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'employee_id',
    'training_id',
    'jenis_sertifikasi',
    'nama_sertifikasi',
    'nomor_sertifikat',
    'tanggal_terbit',
    'masa_berlaku',
    'tanggal_expired',
    'is_permanent',
    'file_sertifikat',
])]
class Certification extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_permanent' => 'boolean',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function reminders()
    {
        return $this->hasMany(CertificateReminder::class);
    }
}
