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

    /**
     * Mencatat jejak audit (Audit Trail) perubahan data penting (PRD Bab 14 & BR-12).
     */
    public static function record(
        string $entitas,
        int $entitasId,
        string $tindakan,
        ?array $perubahanLama = null,
        ?array $perubahanBaru = null,
        ?string $catatan = null
    ): self {
        return self::create([
            'user_id' => auth()->id(),
            'entitas' => $entitas,
            'entitas_id' => $entitasId,
            'tindakan' => $tindakan,
            'perubahan_lama' => $perubahanLama,
            'perubahan_baru' => $perubahanBaru,
            'catatan' => $catatan,
            'ip_address' => request()?->ip(),
        ]);
    }
}
