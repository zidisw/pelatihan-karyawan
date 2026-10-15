<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'training_request_id',
    'employee_id',
    'nama_peserta',
    'jabatan_peserta',
    'status_peserta',
    'catatan_keputusan',
    'reviewed_by',
    'reviewed_at',
])]
class TrainingRequestParticipant extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public function trainingRequest()
    {
        return $this->belongsTo(TrainingRequest::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
