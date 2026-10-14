<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Training;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalDepartments = Department::count();
        $totalEmployees = Employee::count();
        $totalTrainings = Training::count();

        // Breakdown jumlah user per role
        $usersByRole = [
            'superadmin' => User::where('role', 'superadmin')->count(),
            'hrd' => User::where('role', 'hrd')->count(),
            'departemen' => User::where('role', 'departemen')->count(),
        ];

        // Daftar akun terbaru
        $recentUsers = User::with('department')
            ->latest()
            ->take(6)
            ->get();

        // Departemen beserta jumlah karyawannya
        $departments = Department::withCount('employees')
            ->latest()
            ->take(5)
            ->get();

        return view('superadmin.dashboard', compact(
            'totalUsers',
            'totalDepartments',
            'totalEmployees',
            'totalTrainings',
            'usersByRole',
            'recentUsers',
            'departments'
        ));
    }
}
