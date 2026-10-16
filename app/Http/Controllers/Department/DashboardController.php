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
        $validParticipantStatuses = ['disetujui', 'terjadwal', 'sedang_pelatihan', 'selesai', 'lulus'];

        // Jumlah karyawan di departemen pengguna ini
        $departmentEmployeesCount = $user->department_id
            ? Employee::where('department_id', $user->department_id)->count()
            : 0;

        // Pelatihan yang status pendaftarannya sedang dibuka & program aktif
        $availableTrainingsCount = Training::where('status_pendaftaran', 'dibuka')
            ->whereNotIn('status_program', ['dibatalkan', 'selesai'])
            ->count();

        // Scope dasar pengajuan milik departemen / user ini
        $deptRequestScope = function ($q) use ($user) {
            $q->where('diajukan_oleh', $user->id);
            if ($user->department_id) {
                $q->orWhere('department_id', $user->department_id);
            }
        };

        // Statistik pengajuan oleh departemen ini (Menunggu Review / Pending)
        $pendingRequestsCount = TrainingRequest::where($deptRequestScope)
            ->whereIn('status', ['menunggu_review', 'pending'])
            ->count();

        // Statistik pengajuan yang Disetujui (Penuh maupun Disetujui Sebagian)
        $approvedRequestsCount = TrainingRequest::where($deptRequestScope)
            ->whereIn('status', ['approved', 'partially_approved'])
            ->count();

        // 5 Pengajuan terakhir yang diajukan oleh departemen ini (lengkap dengan multi-peserta)
        $recentRequests = TrainingRequest::with([
            'training',
            'employee',
            'participants.employee',
        ])
            ->where($deptRequestScope)
            ->latest()
            ->take(5)
            ->get();

        // 4 Pelatihan yang sedang dibuka untuk diajukan (lengkap dengan hitungan kuota dinamis)
        $availableTrainings = Training::withCount([
            'participants as approved_participants_count' => function ($q) use ($validParticipantStatuses) {
                $q->whereIn('status_peserta', $validParticipantStatuses);
            },
        ])
            ->where('status_pendaftaran', 'dibuka')
            ->whereNotIn('status_program', ['dibatalkan', 'selesai'])
            ->orderBy('tanggal_mulai', 'asc')
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
