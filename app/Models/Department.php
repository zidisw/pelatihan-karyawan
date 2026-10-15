<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
  use HasFactory;

    // Kolom yang diizinkan untuk diisi massal
    protected $fillable = [
        'nama_departemen',
        'deskripsi',
    ];

    // Relasi: Satu Departemen bisa memiliki banyak User (Admin Departemen)
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relasi: Satu departemen memiliki banyak Karyawan
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    // Relasi: Satu departemen memiliki banyak Pengajuan Pelatihan
    public function trainingRequests()
    {
        return $this->hasMany(TrainingRequest::class);
    }
}
