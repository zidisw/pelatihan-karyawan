<?php

namespace Database\Seeders;

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
                'name' => 'Admin HRD',
                'email' => 'hrd@pelatihan.com',
                'password' => Hash::make('password123'),
                'role' => 'hrd',
                'department_id' => 1, // ID 1 = Human Resources
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin IT',
                'email' => 'admin.it@pelatihan.com',
                'password' => Hash::make('password123'),
                'role' => 'departemen',
                'department_id' => 2, // ID 2 = Teknologi Informasi
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Engineering',
                'email' => 'admin.engineering@pelatihan.com',
                'password' => Hash::make('password123'),
                'role' => 'departemen',
                'department_id' => 3, // ID 3 = Engineering
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
