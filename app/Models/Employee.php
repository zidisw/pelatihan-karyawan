<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama', 'nik', 'department_id', 'jabatan', 'status_karyawan', 'email', 'nomor_hp', 'tanggal_join', 'foto'])]
class Employee extends Model
{
    use HasFactory;

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function trainingRequests()
    {
        return $this->hasMany(TrainingRequest::class);
    }

    public function trainingParticipants()
    {
        return $this->hasMany(TrainingParticipant::class);
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }

    public function trainings()
    {
        return $this->belongsToMany(
            Training::class,
            'training_participants',
            'employee_id',
            'training_id'
        )->withPivot('status_peserta', 'created_at');
    }
}
