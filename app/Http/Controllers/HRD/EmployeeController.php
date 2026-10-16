<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Training;
use App\Models\TrainingParticipant;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Query dasar karyawan beserta relasi untuk tabel & pop-up modal riwayat singkat
        $query = Employee::with([
            'department',
            'trainingParticipants.training',
            'trainingParticipants.trainingRequest',
            'certifications.training',
        ])->withCount(['trainingParticipants', 'certifications']);

        // Filter Pencarian Teks (Nama, NIK, Level Jabatan, Job Title, Site/Unit)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('level_jabatan', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%")
                  ->orWhere('site_unit', 'like', "%{$search}%");
            });
        }

        // Filter Departemen
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Filter Level Jabatan
        if ($request->filled('level_jabatan')) {
            $query->where('level_jabatan', $request->level_jabatan);
        }

        // Filter Status Keaktifan Karyawan (aktif / nonaktif)
        if ($request->filled('status_aktif')) {
            $query->where('status_aktif', $request->status_aktif);
        }

        // Filter Status Pelatihan (juga terhubung dengan klik 4 Kartu Statistik di atas)
        if ($request->filled('training_status')) {
            switch ($request->training_status) {
                case 'selesai':
                    $query->whereHas('trainingParticipants', function ($q) {
                        $q->whereIn('status_peserta', ['selesai', 'lulus']);
                    });
                    break;
                case 'berjalan':
                    $query->whereHas('trainingParticipants', function ($q) {
                        $q->whereIn('status_peserta', ['sedang_pelatihan', 'terjadwal']);
                    });
                    break;
                case 'menunggu':
                    $query->where(function ($q) {
                        $q->whereHas('trainingParticipants', function ($sub) {
                            $sub->whereIn('status_peserta', ['disetujui', 'terdaftar', 'belum_diajukan']);
                        })->orWhereHas('trainingRequests', function ($sub) {
                            $sub->whereIn('status', ['pending', 'menunggu_review']);
                        });
                    });
                    break;
                case 'belum_pernah':
                    $query->doesntHave('trainingParticipants');
                    break;
            }
        }

        // Filter Status Masa Berlaku Sertifikat
        if ($request->filled('cert_status')) {
            $today = now()->toDateString();
            $warningDate = now()->addDays(90)->toDateString();

            switch ($request->cert_status) {
                case 'aktif':
                    $query->whereHas('certifications', function ($q) use ($warningDate) {
                        $q->where('is_permanent', true)
                          ->orWhereDate('tanggal_expired', '>', $warningDate);
                    });
                    break;
                case 'segera_expired':
                    $query->whereHas('certifications', function ($q) use ($today, $warningDate) {
                        $q->where('is_permanent', false)
                          ->whereNotNull('tanggal_expired')
                          ->whereDate('tanggal_expired', '>=', $today)
                          ->whereDate('tanggal_expired', '<=', $warningDate);
                    });
                    break;
                case 'expired':
                    $query->whereHas('certifications', function ($q) use ($today) {
                        $q->where('is_permanent', false)
                          ->whereNotNull('tanggal_expired')
                          ->whereDate('tanggal_expired', '<', $today);
                    });
                    break;
                case 'belum_ada':
                    $query->doesntHave('certifications');
                    break;
            }
        }

        // Urutan Data (Sorting)
        $sort = $request->get('sort', 'terbaru');
        if ($sort === 'nama_asc') {
            $query->orderBy('nama', 'asc');
        } elseif ($sort === 'nama_desc') {
            $query->orderBy('nama', 'desc');
        } elseif ($sort === 'pelatihan_terbanyak') {
            $query->orderByDesc('training_participants_count');
        } else {
            $query->latest();
        }

        $employees = $query->paginate(10)->withQueryString();
        $departments = Department::orderBy('nama_departemen')->get();

        // 2. Menghitung 4 Kartu Ringkasan Statistik secara akurat (PRD 7.2.A)
        $totalEmployees = Employee::count();

        $completedTrainings = TrainingParticipant::whereIn('status_peserta', ['selesai', 'lulus'])->count();

        $onProgressTrainings = TrainingParticipant::whereIn('status_peserta', ['sedang_pelatihan', 'terjadwal'])->count();

        $pendingTrainings = TrainingParticipant::whereIn('status_peserta', ['disetujui', 'terdaftar', 'belum_diajukan'])->count();

        // 3. Mengirimkan semua data ke view
        return view('hrd.employees.index', compact(
            'employees',
            'departments',
            'totalEmployees',
            'completedTrainings',
            'onProgressTrainings',
            'pendingTrainings'
        ));
    }

    public function create()
    {
        $departments = Department::orderBy('nama_departemen')->get();
        return view('hrd.employees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:50|unique:employees,nik',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'department_id' => 'required|exists:departments,id',
            'level_jabatan' => 'required|in:Group Leader,Head Of Section,Head Of Department,Penanggung Jawab Operational',
            'jabatan' => 'nullable|string|max:255',
            'site_unit' => 'nullable|string|max:255',
            'status_karyawan' => 'required|in:tetap,kontrak',
            'status_aktif' => 'required|in:aktif,nonaktif',
            'email' => 'nullable|email|max:255',
            'nomor_hp' => 'nullable|string|max:30',
            'tanggal_join' => 'nullable|date',
        ]);

        Employee::create($validated);

        return redirect()->route('hrd.employees.index')->with('success', 'Data karyawan berhasil ditambahkan!');
    }

    public function edit(Employee $employee)
    {
        $departments = Department::orderBy('nama_departemen')->get();
        return view('hrd.employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:50|unique:employees,nik,' . $employee->id,
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'department_id' => 'required|exists:departments,id',
            'level_jabatan' => 'required|in:Group Leader,Head Of Section,Head Of Department,Penanggung Jawab Operational',
            'jabatan' => 'nullable|string|max:255',
            'site_unit' => 'nullable|string|max:255',
            'status_karyawan' => 'required|in:tetap,kontrak',
            'status_aktif' => 'required|in:aktif,nonaktif',
            'email' => 'nullable|email|max:255',
            'nomor_hp' => 'nullable|string|max:30',
            'tanggal_join' => 'nullable|date',
        ]);

        $employee->update($validated);

        return redirect()->route('hrd.employees.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function show(Employee $employee)
    {
        $employee->load([
            'department',
            'trainings',
            'trainingParticipants.training',
            'trainingParticipants.trainingRequest',
            'trainingParticipants.documents',
            'trainingRequests.training',
            'trainingRequests.diajukanOleh',
            'requestParticipants.trainingRequest.training',
            'requestParticipants.trainingRequest.diajukanOleh',
            'certifications.training',
        ]);

        // Gabungkan pengajuan langsung (employee_id) & pengajuan kolektif (training_request_participants)
        $directRequests = $employee->trainingRequests;
        $collectiveRequests = $employee->requestParticipants
            ->map(fn ($rp) => $rp->trainingRequest)
            ->filter();

        $allRequests = $directRequests
            ->concat($collectiveRequests)
            ->unique('id')
            ->sortByDesc('created_at')
            ->values();

        // Hitung ringkasan statistik pelatihan untuk karyawan ini
        $totalTrainings = $employee->trainingParticipants->count();
        $completedTrainings = $employee->trainingParticipants->whereIn('status_peserta', ['selesai', 'lulus'])->count();
        $inProgressTrainings = $employee->trainingParticipants->whereIn('status_peserta', ['sedang_pelatihan', 'terjadwal', 'disetujui'])->count();
        $pendingRequests = $allRequests->whereIn('status', ['pending', 'menunggu_review'])->count();

        // Pelatihan yang sedang dibuka jika ingin mendaftarkan langsung
        $availableTrainings = Training::where('status_pendaftaran', 'dibuka')->get();

        return view('hrd.employees.show', compact(
            'employee',
            'allRequests',
            'totalTrainings',
            'completedTrainings',
            'inProgressTrainings',
            'pendingRequests',
            'availableTrainings'
        ));
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('hrd.employees.index')->with('success', 'Data karyawan berhasil dihapus!');
    }
}
