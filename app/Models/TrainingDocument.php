<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'training_id',
    'training_request_id',
    'training_participant_id',
    'jenis_dokumen',
    'nama_file',
    'file_path',
    'mime_type',
    'ukuran_file',
    'uploaded_by',
])]
class TrainingDocument extends Model
{
    use HasFactory;

    public function training()
    {
        return $this->belongsTo(Training::class);
    }

    public function trainingRequest()
    {
        return $this->belongsTo(TrainingRequest::class);
    }

    public function trainingParticipant()
    {
        return $this->belongsTo(TrainingParticipant::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
