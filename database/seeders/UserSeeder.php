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

        $defaultPassword = Hash::make('password123');

        $users = [
            [
                'name' => 'Jordi',
                'email' => 'superadmin@pelatihan.com',
                'password' => $defaultPassword,
                'role' => 'superadmin',
                'department_id' => null,
            ],
            [
                'name' => 'Admin HRGA',
                'email' => 'hrd@pelatihan.com',
                'password' => $defaultPassword,
                'role' => 'hrd',
                'department_id' => $hrga?->id ?? 1,
            ],
            [
                'name' => 'Admin TC / IT',
                'email' => 'admin.it@pelatihan.com',
                'password' => $defaultPassword,
                'role' => 'departemen',
                'department_id' => $tc?->id ?? 2,
            ],
            [
                'name' => 'Admin Engineering (PLM)',
                'email' => 'admin.engineering@pelatihan.com',
                'password' => $defaultPassword,
                'role' => 'departemen',
                'department_id' => $plm?->id ?? 4,
            ],
            [
                'name' => 'Admin HSE',
                'email' => 'admin.hse@pelatihan.com',
                'password' => $defaultPassword,
                'role' => 'departemen',
                'department_id' => $hse?->id ?? 8,
            ],
        ];

        foreach ($users as $u) {
            DB::table('users')->updateOrInsert(
                ['email' => $u['email']],
                [
                    'name'          => $u['name'],
                    'password'      => $u['password'],
                    'role'          => $u['role'],
                    'department_id' => $u['department_id'],
                    'updated_at'    => now(),
                ]
            );
        }
    }
}
