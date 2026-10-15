<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            ['nama_departemen' => 'HRGA', 'deskripsi' => 'Human Resources & General Affairs', 'created_at' => now(), 'updated_at' => now()],
            ['nama_departemen' => 'TC', 'deskripsi' => 'Training Center & Technical Control', 'created_at' => now(), 'updated_at' => now()],
            ['nama_departemen' => 'CIVIL', 'deskripsi' => 'Civil Engineering & Infrastructure', 'created_at' => now(), 'updated_at' => now()],
            ['nama_departemen' => 'PLM', 'deskripsi' => 'Plant Maintenance & Equipment', 'created_at' => now(), 'updated_at' => now()],
            ['nama_departemen' => 'MED', 'deskripsi' => 'Medical & Occupational Health', 'created_at' => now(), 'updated_at' => now()],
            ['nama_departemen' => 'LOGISTIK', 'deskripsi' => 'Logistics & Supply Chain Management', 'created_at' => now(), 'updated_at' => now()],
            ['nama_departemen' => 'MOD', 'deskripsi' => 'Mine Operation Department', 'created_at' => now(), 'updated_at' => now()],
            ['nama_departemen' => 'HSE', 'deskripsi' => 'Health, Safety, and Environment', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
