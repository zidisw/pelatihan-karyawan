<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Certification;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Support\SafeFileStorage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $existingTrainings = Training::orderBy('nama_pelatihan')->pluck('nama_pelatihan')->unique()->values();
        return view('hrd.employees.create', compact('departments', 'existingTrainings'));
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
            'status_karyawan' => 'nullable|in:tetap,kontrak',
            'status_aktif' => 'required|in:aktif,nonaktif',
            'email' => 'nullable|email|max:255',
            'nomor_hp' => 'nullable|string|max:30',
            'tanggal_join' => 'nullable|date',

            // Validasi opsional untuk histori Sertifikat / Pelatihan yang sudah pernah diikuti
            'histories' => 'nullable|array',
            'histories.*.kategori' => 'nullable|in:Sertifikasi,Technical Skill,Soft Skill',
            'histories.*.nama_pelatihan' => 'nullable|string|max:255',
            'histories.*.tanggal_pelatihan' => 'nullable|date',
            'histories.*.jenis_sertifikasi' => 'nullable|in:POP,POM,POU,K3,Lainnya',
            'histories.*.nomor_sertifikat' => 'nullable|string|max:255',
            'histories.*.masa_berlaku' => 'nullable|string|max:100',
            'histories.*.is_permanent' => 'nullable|boolean',
            'histories.*.tanggal_expired' => 'nullable|date',
            'histories.*.penyelenggara' => 'nullable|string|max:255',
            'histories.*.hasil_evaluasi' => 'nullable|string|max:1000',
            'histories.*.file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $employeeData = collect($validated)->except('histories')->toArray();
        // Default status_karyawan = 'tetap' karena kolom database bersifat NOT NULL
        $employeeData['status_karyawan'] = $employeeData['status_karyawan'] ?? 'tetap';

        $employee = DB::transaction(function () use ($request, $employeeData) {
            $employee = Employee::create($employeeData);

            $histories = $request->input('histories', []);
            if (is_array($histories)) {
                foreach ($histories as $idx => $item) {
                    $namaPelatihan = trim((string) ($item['nama_pelatihan'] ?? ''));
                    if ($namaPelatihan === '') {
                        continue;
                    }

                    $kategori = $item['kategori'] ?? 'Sertifikasi';
                    if (!in_array($kategori, ['Sertifikasi', 'Technical Skill', 'Soft Skill'], true)) {
                        $kategori = 'Sertifikasi';
                    }

                    $tanggalPelatihan = !empty($item['tanggal_pelatihan'])
                        ? Carbon::parse($item['tanggal_pelatihan'])->toDateString()
                        : now()->toDateString();

                    $jenisSert = !empty($item['jenis_sertifikasi']) ? $item['jenis_sertifikasi'] : 'Lainnya';
                    $penyelenggara = trim((string) ($item['penyelenggara'] ?? '')) ?: 'Penyelenggara Internal / Eksternal';
                    $hasilEvaluasi = trim((string) ($item['hasil_evaluasi'] ?? '')) ?: 'Riwayat pelatihan sebelummya (tercatat saat registrasi karyawan)';

                    // Cari atau buat program pelatihan histori agar terhubung di Riwayat Pelatihan
                    $training = Training::where('nama_pelatihan', $namaPelatihan)
                        ->where('kategori', $kategori)
                        ->first();

                    if (!$training) {
                        $kodeUnik = 'HIST-' . date('Y') . '-' . strtoupper(Str::random(4));
                        while (Training::where('kode_program', $kodeUnik)->exists()) {
                            $kodeUnik = 'HIST-' . date('Y') . '-' . strtoupper(Str::random(4));
                        }

                        $training = Training::create([
                            'kode_program' => $kodeUnik,
                            'nama_pelatihan' => $namaPelatihan,
                            'kategori' => $kategori,
                            'jenis_sertifikasi' => $kategori === 'Sertifikasi' && in_array($jenisSert, ['POP', 'POM', 'POU', 'K3']) ? $jenisSert : null,
                            'deskripsi' => 'Riwayat pelatihan / sertifikasi yang telah diikuti karyawan.',
                            'instruktur' => $penyelenggara,
                            'tanggal_mulai' => $tanggalPelatihan,
                            'tanggal_selesai' => $tanggalPelatihan,
                            'kuota' => 100,
                            'lokasi' => $employee->site_unit ?? 'Head Office',
                            'metode_pelaksanaan' => 'OFFLINE',
                            'status_pendaftaran' => 'ditutup',
                            'status_program' => 'selesai',
                        ]);
                    }

                    // Catat sebagai peserta yang telah selesai mengikuti pelatihan tersebut
                    TrainingParticipant::updateOrCreate(
                        [
                            'employee_id' => $employee->id,
                            'training_id' => $training->id,
                        ],
                        [
                            'status_peserta' => 'selesai',
                            'kehadiran' => 'hadir',
                            'hasil_evaluasi' => $hasilEvaluasi,
                        ]
                    );

                    // Apabila kategori adalah Sertifikasi (atau nomor sertifikat / file sertifikat diisi), simpan juga ke tabel certifications
                    if ($kategori === 'Sertifikasi' || !empty($item['nomor_sertifikat']) || $request->hasFile("histories.{$idx}.file_sertifikat")) {
                        $isPermanent = !empty($item['is_permanent']);
                        $tanggalExpired = (!$isPermanent && !empty($item['tanggal_expired']))
                            ? Carbon::parse($item['tanggal_expired'])->toDateString()
                            : null;

                        if (!$tanggalExpired && !$isPermanent) {
                            $isPermanent = true;
                        }

                        $masaBerlaku = trim((string) ($item['masa_berlaku'] ?? ''));
                        if ($isPermanent) {
                            $masaBerlaku = 'Permanen';
                        } elseif ($masaBerlaku === '' && $tanggalExpired) {
                            $years = (int) Carbon::parse($tanggalPelatihan)->diffInYears(Carbon::parse($tanggalExpired));
                            $months = max(1, (int) round(Carbon::parse($tanggalPelatihan)->diffInMonths(Carbon::parse($tanggalExpired))));
                            $masaBerlaku = $years > 0
                                ? $years . ' Tahun'
                                : $months . ' Bulan';
                        }

                        $filePath = null;
                        if ($request->hasFile("histories.{$idx}.file_sertifikat") && $request->file("histories.{$idx}.file_sertifikat")->isValid()) {
                            $filePath = SafeFileStorage::store($request->file("histories.{$idx}.file_sertifikat"), 'certificates');
                        }

                        Certification::create([
                            'employee_id' => $employee->id,
                            'training_id' => $training->id,
                            'nama_sertifikasi' => $namaPelatihan,
                            'jenis_sertifikasi' => $jenisSert,
                            'nomor_sertifikat' => trim((string) ($item['nomor_sertifikat'] ?? '')) ?: null,
                            'tanggal_terbit' => $tanggalPelatihan,
                            'masa_berlaku' => $masaBerlaku ?: 'Permanen',
                            'is_permanent' => $isPermanent,
                            'tanggal_expired' => $tanggalExpired,
                            'file_sertifikat' => $filePath,
                        ]);
                    }
                }
            }

            return $employee;
        });

        AuditLog::record(
            'Employee',
            $employee->id,
            'CREATE',
            null,
            $employee->load(['certifications', 'trainingParticipants'])->toArray(),
            "Menambahkan data karyawan baru beserta histori pelatihan/sertifikat: {$employee->nama} ({$employee->nik})"
        );

        return redirect()->route('hrd.employees.index')->with('success', 'Data karyawan beserta riwayat pelatihan/sertifikat berhasil ditambahkan!');
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
            'status_karyawan' => 'nullable|in:tetap,kontrak',
            'status_aktif' => 'required|in:aktif,nonaktif',
            'email' => 'nullable|email|max:255',
            'nomor_hp' => 'nullable|string|max:30',
            'tanggal_join' => 'nullable|date',
        ]);

        $validated['status_karyawan'] = $validated['status_karyawan'] ?? $employee->status_karyawan ?? 'tetap';

        $oldData = $employee->toArray();
        $employee->update($validated);

        AuditLog::record(
            'Employee',
            $employee->id,
            'UPDATE',
            $oldData,
            $employee->fresh()->toArray(),
            "Memperbarui data karyawan: {$employee->nama} ({$employee->nik})"
        );

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

        // Pelatihan yang sedang dibuka jika ingin mendaftarkan langsung & seluruh pelatihan untuk tautan sertifikat
        $availableTrainings = Training::where('status_pendaftaran', 'dibuka')->get();
        $allTrainings = Training::orderBy('nama_pelatihan')->get();

        return view('hrd.employees.show', compact(
            'employee',
            'allRequests',
            'totalTrainings',
            'completedTrainings',
            'inProgressTrainings',
            'pendingRequests',
            'availableTrainings',
            'allTrainings'
        ));
    }

    public function destroy(Employee $employee)
    {
        $oldData = $employee->toArray();
        $nama = $employee->nama;
        $nik = $employee->nik;
        $empId = $employee->id;

        $employee->delete();

        AuditLog::record(
            'Employee',
            $empId,
            'DELETE',
            $oldData,
            null,
            "Menghapus data karyawan: {$nama} ({$nik})"
        );

        return redirect()->route('hrd.employees.index')->with('success', 'Data karyawan berhasil dihapus!');
    }
}
