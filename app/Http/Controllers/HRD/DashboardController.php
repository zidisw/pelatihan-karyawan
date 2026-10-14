<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Training;
use App\Models\TrainingRequest;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Karyawan
        $employees = Employee::count();

        // 2. Karyawan yang telah menyelesaikan pelatihan
        $trainedEmployees = DB::table('training_participants')
            ->where('status_peserta', 'selesai')
            ->distinct('employee_id')
            ->count('employee_id');

        // 3. Pelatihan aktif yang sedang dibuka
        $activeTrainings = Training::where('status_pendaftaran', 'dibuka')->count();

        // 4. Pengajuan yang menunggu persetujuan HRD
        $pendingRequestsCount = TrainingRequest::where('status', 'pending')->count();

        // 5. Total sertifikasi
        $totalCertifications = Certification::count();

        // 6. Notifikasi permintaan pelatihan pending terbaru
        $recentPendingRequests = TrainingRequest::with(['employee.department', 'training', 'diajukanOleh'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // 7. Data riil untuk grafik partisipasi per departemen
        $departments = Department::withCount('employees')->get();
        $chartLabels = $departments->pluck('nama_departemen')->toArray();
        $chartData = $departments->pluck('employees_count')->toArray();

        // 8. Karyawan terbaru untuk widget pencarian/tabel cepat
        $recentEmployees = Employee::with('department')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'employees',
            'trainedEmployees',
            'activeTrainings',
            'pendingRequestsCount',
            'totalCertifications',
            'recentPendingRequests',
            'chartLabels',
            'chartData',
            'recentEmployees'
        ));
    }
}
