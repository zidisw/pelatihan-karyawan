<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\Employee;
use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Models\TrainingRequest;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Widget Pertama: Total Karyawan
        $employees = Employee::count();

        // 2. Widget Kedua: Total Riwayat Pelatihan (Sertifikasi + Partisipasi Pelatihan)
        $totalCertifications = Certification::count();
        $totalParticipantRecords = TrainingParticipant::whereHas('training', function ($q) {
            $q->whereIn('kategori', ['Technical Skill', 'Soft Skill']);
        })->count();
        $trainingHistoryCount = $totalCertifications + $totalParticipantRecords;

        // 3. List Katalog Pelatihan yang Tersedia (Bagian Kiri Bawah)
        $availableTrainings = Training::withCount('participants')
            ->orderByRaw("CASE WHEN status_pendaftaran = 'dibuka' THEN 0 ELSE 1 END")
            ->orderBy('tanggal_mulai', 'asc')
            ->get();

        // 4. Pengajuan Menunggu Persetujuan HRD (Bagian Kanan Bawah)
        $pendingRequestsCount = TrainingRequest::whereIn('status', ['pending', 'menunggu_review'])->count();

        $recentPendingRequests = TrainingRequest::with(['department', 'employee.department', 'training', 'diajukanOleh'])
            ->whereIn('status', ['pending', 'menunggu_review'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'employees',
            'trainingHistoryCount',
            'totalCertifications',
            'totalParticipantRecords',
            'availableTrainings',
            'pendingRequestsCount',
            'recentPendingRequests'
        ));
    }
}
