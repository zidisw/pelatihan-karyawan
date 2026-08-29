<?php

use App\Http\Controllers\Department\TrainingRequestController;
use App\Http\Controllers\HRD\DashboardController;
use App\Http\Controllers\HRD\EmployeeController;
use App\Http\Controllers\HRD\TrainingController;
use App\Http\Controllers\HRD\TrainingParticipantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Superadmin\DepartmentController;
use App\Http\Controllers\Superadmin\UserController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Depan (Bawaan Laravel)
Route::get('/', function () {
    return view('welcome');
});

// Rute "Polisi Lalu Lintas" untuk Dashboard bawaan Breeze
Route::middleware(['auth'])->get('/dashboard', function () {
    $role = auth()->user()->role;

    if ($role === 'superadmin') {
        return redirect('/superadmin/dashboard');
    } elseif ($role === 'hrd') {
        return redirect('/hrd/dashboard');
    } else {
        return redirect('/departemen/dashboard');
    }
})->name('dashboard');

// 2. Rute Khusus Superadmin
Route::middleware(['auth', 'role:superadmin'])->group(function () {
       Route::get('/superadmin/dashboard', [DashboardController::class, 'index'])->name('superadmin.dashboard');

    // Rute Daftar User (Index)
    Route::get('/superadmin/users', [UserController::class, 'index'])->name('superadmin.users.index');

    // Rute Form Tambah User (Create) -> TAMBAHKAN BARIS INI
    Route::get('/superadmin/users/create', [UserController::class, 'create'])->name('superadmin.users.create');
    // Rute untuk memproses form simpan data
    Route::post('/superadmin/users', [UserController::class, 'store'])->name('superadmin.users.store');

    // Rute untuk menampilkan form edit
    Route::get('/superadmin/users/{user}/edit', [UserController::class, 'edit'])->name('superadmin.users.edit');
    // Rute untuk memproses update data
    Route::put('/superadmin/users/{user}', [UserController::class, 'update'])->name('superadmin.users.update');
    // Rute untuk menghapus data
    Route::delete('/superadmin/users/{user}', [UserController::class, 'destroy'])->name('superadmin.users.destroy');

    // Rute Master Departemen
    Route::get('/superadmin/departments', [DepartmentController::class, 'index'])->name('superadmin.departments.index');
    Route::get('/superadmin/departments/create', [DepartmentController::class, 'create'])->name('superadmin.departments.create');
    Route::post('/superadmin/departments', [DepartmentController::class, 'store'])->name('superadmin.departments.store');
    Route::get('/superadmin/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('superadmin.departments.edit');
    Route::put('/superadmin/departments/{department}', [DepartmentController::class, 'update'])->name('superadmin.departments.update');
    Route::delete('/superadmin/departments/{department}', [DepartmentController::class, 'destroy'])->name('superadmin.departments.destroy');
});

// 3. Rute Khusus HRD
Route::middleware(['auth', 'role:hrd'])->group(function () {
    Route::get('/hrd/dashboard', [DashboardController::class, 'index'])->name('hrd.dashboard');

    // Rute Master Karyawan
    Route::get('/hrd/employees', [EmployeeController::class, 'index'])->name('hrd.employees.index');
    Route::get('/hrd/employees/create', [EmployeeController::class, 'create'])->name('hrd.employees.create');
    Route::post('/hrd/employees', [EmployeeController::class, 'store'])->name('hrd.employees.store');
    Route::get('/hrd/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('hrd.employees.edit');
    Route::put('/hrd/employees/{employee}', [EmployeeController::class, 'update'])->name('hrd.employees.update');
    Route::delete('/hrd/employees/{employee}', [EmployeeController::class, 'destroy'])->name('hrd.employees.destroy');
    Route::get('/hrd/employees/{employee}/show', [EmployeeController::class, 'show'])->name('hrd.employees.show'); // Rute untuk menampilkan detail karyawan
    // Route::get('hrd.employees.search', [EmployeeController::class, 'search'])->name('hrd.employees.search');

    // Rute Master Pelatihan
    Route::get('/hrd/trainings', [TrainingController::class, 'index'])->name('hrd.trainings.index');
    Route::get('/hrd/trainings/create', [TrainingController::class, 'create'])->name('hrd.trainings.create');
    Route::post('/hrd/trainings', [TrainingController::class, 'store'])->name('hrd.trainings.store');
    Route::get('/hrd/trainings/{training}/edit', [TrainingController::class, 'edit'])->name('hrd.trainings.edit');
    Route::get('/hrd/trainings/{training}/show', [TrainingController::class, 'show'])->name('hrd.trainings.show'); // Rute untuk menampilkan detail pelatihan
    Route::put('/hrd/trainings/{training}', [TrainingController::class, 'update'])->name('hrd.trainings.update');
    Route::delete('/hrd/trainings/{training}', [TrainingController::class, 'destroy'])->name('hrd.trainings.destroy');

    // Rute Verifikasi Pengajuan Pelatihan oleh HRD
    Route::get('/hrd/requests', [App\Http\Controllers\HRD\TrainingRequestController::class, 'index'])->name('hrd.requests.index');
    Route::patch('/hrd/requests/{trainingRequest}/process', [App\Http\Controllers\HRD\TrainingRequestController::class, 'process'])->name('hrd.requests.process');

    // Rute Manajemen Peserta Pelatihan
    Route::get('/hrd/participants', [TrainingParticipantController::class, 'index'])->name('hrd.participants.index');
    Route::patch('/hrd/participants/{participant}/status', [TrainingParticipantController::class, 'updateStatus'])->name('hrd.participants.updateStatus');

    // Master Data Departemen
    Route::get('/hrd/departments', [App\Http\Controllers\HRD\DepartmentController::class, 'index'])->name('hrd.departments.index');
    Route::post('/hrd/departments', [App\Http\Controllers\HRD\DepartmentController::class, 'store'])->name('hrd.departments.store');
    // Ini rute Edit dan Update-nya:
    Route::get('/hrd/departments/{department}/edit', [App\Http\Controllers\HRD\DepartmentController::class, 'edit'])->name('hrd.departments.edit');
    Route::put('/hrd/departments/{department}', [App\Http\Controllers\HRD\DepartmentController::class, 'update'])->name('hrd.departments.update');
    Route::delete('/hrd/departments/{department}', [App\Http\Controllers\HRD\DepartmentController::class, 'destroy'])->name('hrd.departments.destroy');

});

// 4. Rute Khusus Admin Departemen
Route::middleware(['auth', 'role:departemen'])->group(function () {
    Route::get('departemen/dashboard', [DashboardController::class, 'index'])
        ->name('department.dashboard');

    // Rute untuk melihat pelatihan dan mengajukan pelatihan
    Route::get('/department/trainings', [TrainingRequestController::class, 'index'])->name('department.trainings.index');
    // Rute Pengajuan Pelatihan oleh Admin Departemen
    Route::get('/department/trainings/{training}/request', [TrainingRequestController::class, 'create'])->name('department.trainings.request');
    Route::post('/department/trainings/{training}/request', [TrainingRequestController::class, 'store'])->name('department.trainings.store');

    // Rute Riwayat Pengajuan
    Route::get('/department/history', [TrainingRequestController::class, 'history'])->name('department.trainings.history');

});

// 5. Rute Profil (Bawaan Breeze - Biarkan agar user bisa edit profil)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 6. Rute Autentikasi Login/Register (Bawaan Breeze - WAJIB ADA)
require __DIR__.'/auth.php';
