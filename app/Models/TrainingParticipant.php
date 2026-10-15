<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'training_request_id',
    'employee_id',
    'training_id',
    'status_peserta',
    'kehadiran',
    'hasil_evaluasi',
])]
class TrainingParticipant extends Model
{
    use HasFactory;

    public function trainingRequest()
    {
        return $this->belongsTo(TrainingRequest::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function documents()
    {
        return $this->hasMany(TrainingDocument::class);
    }
}
