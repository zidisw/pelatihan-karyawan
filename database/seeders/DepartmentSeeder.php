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
        $departments = [
            ['nama_departemen' => 'HRGA', 'deskripsi' => 'Human Resources & General Affairs'],
            ['nama_departemen' => 'TC', 'deskripsi' => 'Training Center & Technical Control'],
            ['nama_departemen' => 'CIVIL', 'deskripsi' => 'Civil Engineering & Infrastructure'],
            ['nama_departemen' => 'PLM', 'deskripsi' => 'Plant Maintenance & Equipment'],
            ['nama_departemen' => 'MED', 'deskripsi' => 'Medical & Occupational Health'],
            ['nama_departemen' => 'LOGISTIK', 'deskripsi' => 'Logistics & Supply Chain Management'],
            ['nama_departemen' => 'MOD', 'deskripsi' => 'Mine Operation Department'],
            ['nama_departemen' => 'HSE', 'deskripsi' => 'Health, Safety, and Environment'],
        ];

        foreach ($departments as $dept) {
            DB::table('departments')->updateOrInsert(
                ['nama_departemen' => $dept['nama_departemen']],
                [
                    'deskripsi'  => $dept['deskripsi'],
                    'updated_at' => now(),
                ]
            );
        }
    }
}
