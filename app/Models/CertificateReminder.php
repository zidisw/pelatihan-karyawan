<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'certification_id',
    'tipe_reminder',
    'tanggal_reminder',
    'status_pengiriman',
])]
class CertificateReminder extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'tanggal_reminder' => 'date',
        ];
    }

    public function certification()
    {
        return $this->belongsTo(Certification::class);
    }
}
