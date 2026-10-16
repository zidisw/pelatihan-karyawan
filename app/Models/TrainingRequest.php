<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'nomor_request',
    'department_id',
    'employee_id',
    'training_id',
    'diajukan_oleh',
    'jenis_request',
    'periode_pengajuan',
    'scope_program',
    'kondisi_saat_ini',
    'training_need',
    'target_kpi',
    'estimasi_biaya',
    'pelaksana',
    'metode_pelaksanaan',
    'status',
    'catatan_admin',
    'reviewed_by',
    'reviewed_at',
])]
class TrainingRequest extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'estimasi_biaya' => 'decimal:2',
            'reviewed_at' => 'datetime',
        ];
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function diajukanOleh()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function participants()
    {
        return $this->hasMany(TrainingRequestParticipant::class);
    }

    public function requestParticipants()
    {
        return $this->hasMany(TrainingRequestParticipant::class);
    }

    public function documents()
    {
        return $this->hasMany(TrainingDocument::class);
    }
}
