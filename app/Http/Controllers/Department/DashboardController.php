<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Training;
use App\Models\TrainingRequest;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $department = $user->department;

        // Jumlah karyawan di departemen pengguna ini
        $departmentEmployeesCount = $user->department_id
            ? Employee::where('department_id', $user->department_id)->count()
            : 0;

        // Pelatihan yang statusnya sedang dibuka
        $availableTrainingsCount = Training::where('status_pendaftaran', 'dibuka')->count();

        // Statistik pengajuan oleh akun departemen ini
        $pendingRequestsCount = TrainingRequest::where('diajukan_oleh', $user->id)
            ->where('status', 'pending')
            ->count();

        $approvedRequestsCount = TrainingRequest::where('diajukan_oleh', $user->id)
            ->where('status', 'approved')
            ->count();

        // 5 Pengajuan terakhir yang diajukan oleh departemen ini
        $recentRequests = TrainingRequest::with(['training', 'employee'])
            ->where('diajukan_oleh', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // 4 Pelatihan yang sedang dibuka untuk diajukan
        $availableTrainings = Training::where('status_pendaftaran', 'dibuka')
            ->latest()
            ->take(4)
            ->get();

        return view('department.dashboard', compact(
            'user',
            'department',
            'departmentEmployeesCount',
            'availableTrainingsCount',
            'pendingRequestsCount',
            'approvedRequestsCount',
            'recentRequests',
            'availableTrainings'
        ));
    }
}
