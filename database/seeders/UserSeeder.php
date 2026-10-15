<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hrga = Department::where('nama_departemen', 'HRGA')->first();
        $tc   = Department::where('nama_departemen', 'TC')->first();
        $plm  = Department::where('nama_departemen', 'PLM')->first();
        $hse  = Department::where('nama_departemen', 'HSE')->first();

        DB::table('users')->insert([
            [
                'name' => 'Jordi',
                'email' => 'superadmin@pelatihan.com',
                'password' => Hash::make('password123'),
                'role' => 'superadmin',
                'department_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin HRGA',
                'email' => 'hrd@pelatihan.com',
                'password' => Hash::make('password123'),
                'role' => 'hrd',
                'department_id' => $hrga?->id ?? 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin TC / IT',
                'email' => 'admin.it@pelatihan.com',
                'password' => Hash::make('password123'),
                'role' => 'departemen',
                'department_id' => $tc?->id ?? 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Engineering (PLM)',
                'email' => 'admin.engineering@pelatihan.com',
                'password' => Hash::make('password123'),
                'role' => 'departemen',
                'department_id' => $plm?->id ?? 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin HSE',
                'email' => 'admin.hse@pelatihan.com',
                'password' => Hash::make('password123'),
                'role' => 'departemen',
                'department_id' => $hse?->id ?? 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
