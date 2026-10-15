<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Menampilkan daftar karyawan & status sertifikasi di departemen sendiri (Read-Only).
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $department = $user->department;

        $query = Employee::with(['department', 'certifications', 'trainings'])
            ->where('department_id', $user->department_id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        $employees = $query->orderBy('nama')->paginate(10)->withQueryString();

        // Ringkasan statistik khusus departemen ini
        $totalEmployees = Employee::where('department_id', $user->department_id)->count();
        $activeEmployees = Employee::where('department_id', $user->department_id)
            ->where('status_aktif', 'aktif')
            ->count();

        $totalCertifications = Certification::whereHas('employee', function ($q) use ($user) {
            $q->where('department_id', $user->department_id);
        })->count();

        $expiringCertifications = Certification::whereHas('employee', function ($q) use ($user) {
            $q->where('department_id', $user->department_id);
        })
            ->where('is_permanent', false)
            ->whereNotNull('tanggal_expired')
            ->whereDate('tanggal_expired', '<=', now()->addDays(90))
            ->count();

        return view('department.employees.index', compact(
            'department',
            'employees',
            'totalEmployees',
            'activeEmployees',
            'totalCertifications',
            'expiringCertifications'
        ));
    }

    /**
     * Menampilkan detail profil, riwayat pelatihan, dan sertifikat karyawan departemen sendiri (Read-Only).
     */
    public function show(Employee $employee)
    {
        $user = auth()->user();

        // Pastikan Admin Departemen hanya bisa melihat karyawan di departemennya sendiri
        if ($user->role === 'departemen' && $employee->department_id !== $user->department_id) {
            abort(403, 'Akses ditolak. Karyawan ini bukan anggota departemen Anda.');
        }

        $employee->load([
            'department',
            'trainings',
            'trainingRequests.training',
            'trainingRequests.diajukanOleh',
            'certifications',
        ]);

        $totalTrainings = $employee->trainings->count();
        $completedTrainings = $employee->trainings->whereIn('pivot.status_peserta', ['selesai', 'lulus'])->count();
        $inProgressTrainings = $employee->trainings->whereIn('pivot.status_peserta', ['sedang_pelatihan', 'terjadwal', 'disetujui'])->count();
        $pendingRequests = $employee->trainingRequests->whereIn('status', ['pending', 'menunggu_review'])->count();

        return view('department.employees.show', compact(
            'employee',
            'totalTrainings',
            'completedTrainings',
            'inProgressTrainings',
            'pendingRequests'
        ));
    }
}
