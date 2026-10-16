<?php

use App\Http\Controllers\Department\DashboardController as DepartmentDashboardController;
use App\Http\Controllers\Department\EmployeeController as DepartmentEmployeeController;
use App\Http\Controllers\Department\TrainingRequestController as DepartmentTrainingRequestController;
use App\Http\Controllers\HRD\CertificationController;
use App\Http\Controllers\HRD\DashboardController as HrdDashboardController;
use App\Http\Controllers\HRD\EmployeeController;
use App\Http\Controllers\HRD\ExportController;
use App\Http\Controllers\HRD\TrainingController;
use App\Http\Controllers\HRD\TrainingParticipantController;
use App\Http\Controllers\HRD\TrainingRequestController as HrdTrainingRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Superadmin\DashboardController as SuperadminDashboardController;
use App\Http\Controllers\Superadmin\DepartmentController as SuperadminDepartmentController;
use App\Http\Controllers\Superadmin\UserController as SuperadminUserController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Depan
Route::get('/', function () {
    return view('welcome');
});

// Rute "Polisi Lalu Lintas" untuk Dashboard bawaan Breeze
Route::middleware(['auth'])->get('/dashboard', function () {
    $role = auth()->user()->role;

    if ($role === 'superadmin') {
        return redirect()->route('superadmin.dashboard');
    } elseif ($role === 'hrd') {
        return redirect()->route('hrd.dashboard');
    } else {
        return redirect()->route('department.dashboard');
    }
})->name('dashboard');

// 2. Rute Khusus Superadmin (Master Departemen & Manajemen Akun)
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperadminDashboardController::class, 'index'])->name('superadmin.dashboard');

    // Rute Manajemen User
    Route::get('/superadmin/users', [SuperadminUserController::class, 'index'])->name('superadmin.users.index');
    Route::get('/superadmin/users/create', [SuperadminUserController::class, 'create'])->name('superadmin.users.create');
    Route::post('/superadmin/users', [SuperadminUserController::class, 'store'])->name('superadmin.users.store');
    Route::get('/superadmin/users/{user}/edit', [SuperadminUserController::class, 'edit'])->name('superadmin.users.edit');
    Route::put('/superadmin/users/{user}', [SuperadminUserController::class, 'update'])->name('superadmin.users.update');
    Route::delete('/superadmin/users/{user}', [SuperadminUserController::class, 'destroy'])->name('superadmin.users.destroy');

    // Rute Master Departemen Superadmin
    Route::get('/superadmin/departments', [SuperadminDepartmentController::class, 'index'])->name('superadmin.departments.index');
    Route::get('/superadmin/departments/create', [SuperadminDepartmentController::class, 'create'])->name('superadmin.departments.create');
    Route::post('/superadmin/departments', [SuperadminDepartmentController::class, 'store'])->name('superadmin.departments.store');
    Route::get('/superadmin/departments/{department}/edit', [SuperadminDepartmentController::class, 'edit'])->name('superadmin.departments.edit');
    Route::put('/superadmin/departments/{department}', [SuperadminDepartmentController::class, 'update'])->name('superadmin.departments.update');
    Route::delete('/superadmin/departments/{department}', [SuperadminDepartmentController::class, 'destroy'])->name('superadmin.departments.destroy');
});

// 3. Rute Khusus Admin Portal (HRD)
Route::middleware(['auth', 'role:hrd'])->group(function () {
    Route::get('/hrd/dashboard', [HrdDashboardController::class, 'index'])->name('hrd.dashboard');

    // Rute Data Karyawan
    Route::get('/hrd/employees', [EmployeeController::class, 'index'])->name('hrd.employees.index');
    Route::get('/hrd/employees/create', [EmployeeController::class, 'create'])->name('hrd.employees.create');
    Route::post('/hrd/employees', [EmployeeController::class, 'store'])->name('hrd.employees.store');
    Route::get('/hrd/employees/{employee}', [EmployeeController::class, 'show'])->name('hrd.employees.show');
    Route::get('/hrd/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('hrd.employees.edit');
    Route::put('/hrd/employees/{employee}', [EmployeeController::class, 'update'])->name('hrd.employees.update');
    Route::delete('/hrd/employees/{employee}', [EmployeeController::class, 'destroy'])->name('hrd.employees.destroy');

    // Rute CRUD Sertifikat Karyawan (Issue #8)
    Route::post('/hrd/certifications', [CertificationController::class, 'store'])->name('hrd.certifications.store');
    Route::put('/hrd/certifications/{certification}', [CertificationController::class, 'update'])->name('hrd.certifications.update');
    Route::delete('/hrd/certifications/{certification}', [CertificationController::class, 'destroy'])->name('hrd.certifications.destroy');

    // Rute Katalog Pelatihan
    Route::get('/hrd/trainings', [TrainingController::class, 'index'])->name('hrd.trainings.index');
    Route::get('/hrd/trainings/create', [TrainingController::class, 'create'])->name('hrd.trainings.create');
    Route::post('/hrd/trainings', [TrainingController::class, 'store'])->name('hrd.trainings.store');
    Route::get('/hrd/trainings/{training}/edit', [TrainingController::class, 'edit'])->name('hrd.trainings.edit');
    Route::get('/hrd/trainings/{training}/show', [TrainingController::class, 'show'])->name('hrd.trainings.show');
    Route::put('/hrd/trainings/{training}', [TrainingController::class, 'update'])->name('hrd.trainings.update');
    Route::patch('/hrd/trainings/{training}/archive', [TrainingController::class, 'archive'])->name('hrd.trainings.archive');
    Route::delete('/hrd/trainings/documents/{document}', [TrainingController::class, 'destroyDocument'])->name('hrd.trainings.documents.destroy');
    Route::delete('/hrd/trainings/{training}', [TrainingController::class, 'destroy'])->name('hrd.trainings.destroy');

    // Rute Request Training (Verifikasi Pengajuan Pelatihan oleh HRD)
    Route::get('/hrd/requests', [HrdTrainingRequestController::class, 'index'])->name('hrd.requests.index');
    Route::patch('/hrd/requests/{trainingRequest}/process', [HrdTrainingRequestController::class, 'process'])->name('hrd.requests.process');

    // Rute Riwayat Pelatihan & Realisasi (Manajemen Peserta Pelatihan - Issue #9)
    Route::get('/hrd/participants', [TrainingParticipantController::class, 'index'])->name('hrd.participants.index');
    Route::patch('/hrd/participants/{participant}/status', [TrainingParticipantController::class, 'updateStatus'])->name('hrd.participants.updateStatus');

    // Rute Ekspor Laporan CSV/Excel & Cetak PDF (Issue #10)
    Route::get('/hrd/export/employees', [ExportController::class, 'employees'])->name('hrd.export.employees');
    Route::get('/hrd/export/participants', [ExportController::class, 'participants'])->name('hrd.export.participants');
    Route::get('/hrd/export/requests', [ExportController::class, 'requests'])->name('hrd.export.requests');
});

// 4. Rute Khusus User Portal (Admin Departemen)
Route::middleware(['auth', 'role:departemen,department'])->group(function () {
    Route::get('/department/dashboard', [DepartmentDashboardController::class, 'index'])->name('department.dashboard');
    Route::get('/departemen/dashboard', fn() => redirect()->route('department.dashboard'));

    // Rute melihat & mengajukan pelatihan (dari Katalog maupun Request Training Baru di luar Katalog)
    Route::get('/department/trainings', [DepartmentTrainingRequestController::class, 'index'])->name('department.trainings.index');
    Route::get('/department/requests/new', [DepartmentTrainingRequestController::class, 'createNew'])->name('department.requests.createNew');
    Route::post('/department/requests/new', [DepartmentTrainingRequestController::class, 'storeNew'])->name('department.requests.storeNew');
    Route::get('/department/trainings/{training}', [DepartmentTrainingRequestController::class, 'show'])->name('department.trainings.show');
    Route::get('/department/trainings/{training}/request', [DepartmentTrainingRequestController::class, 'create'])->name('department.trainings.request');
    Route::post('/department/trainings/{training}/request', [DepartmentTrainingRequestController::class, 'store'])->name('department.trainings.store');

    // Rute Riwayat Pengajuan & Pembatalan Pengajuan
    Route::get('/department/history', [DepartmentTrainingRequestController::class, 'history'])->name('department.trainings.history');
    Route::patch('/department/requests/{trainingRequest}/cancel', [DepartmentTrainingRequestController::class, 'cancel'])->name('department.requests.cancel');

    // Rute Read-Only Data Karyawan & Sertifikat Departemen Sendiri
    Route::get('/department/employees', [DepartmentEmployeeController::class, 'index'])->name('department.employees.index');
    Route::get('/department/employees/{employee}', [DepartmentEmployeeController::class, 'show'])->name('department.employees.show');
});

// 5. Rute Terautentikasi Lintas Role (Profil, Notifikasi In-App, & Unduh Sertifikat Aman)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Kontrol Akses Lihat / Unduh Sertifikat Aman (PRD Bab 14)
    Route::get('/certifications/{certification}/download', [CertificationController::class, 'download'])->name('certifications.download');

    // Notifikasi In-App (PRD Bab 12)
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

// 6. Rute Autentikasi
require __DIR__.'/auth.php';
