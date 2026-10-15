<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'entitas',
    'entitas_id',
    'tindakan',
    'perubahan_lama',
    'perubahan_baru',
    'catatan',
    'ip_address',
])]
class AuditLog extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'perubahan_lama' => 'array',
            'perubahan_baru' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
