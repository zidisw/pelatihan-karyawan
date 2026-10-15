<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Training;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // public function index()
    // {
    //     // Mengambil semua data karyawan beserta nama departemennya
    //     $employees = Employee::with('department')->paginate(10); // Menggunakan pagination untuk menampilkan 10 data per halaman

    //     return view('hrd.employees.index', compact('employees'));
    // }

    public function index(Request $request)
    {
        // 1. Mengambil data karyawan dengan filter pencarian & pagination
        $query = Employee::with('department');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('level_jabatan', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });
        }

        $employees = $query->paginate(10)->withQueryString();

        // 2. Menghitung data statistik pelatihan berdasarkan tanggal
        $today = now()->toDateString();

        $completedTrainings = Training::where('tanggal_selesai', '<', $today)->count();

        $onProgressTrainings = Training::where('tanggal_mulai', '<=', $today)
                                      ->where('tanggal_selesai', '>=', $today)
                                      ->count();

        $pendingTrainings = Training::where('tanggal_mulai', '>', $today)
                                    ->orWhereNull('tanggal_mulai')
                                    ->count();

        // 3. Mengirimkan semua data ke view
        return view('hrd.employees.index', compact(
            'employees',
            'completedTrainings',
            'onProgressTrainings',
            'pendingTrainings'
        ));
    }

    public function create()
    {
        $departments = Department::all();
        return view('hrd.employees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|unique:employees,nik',
            'department_id' => 'required|exists:departments,id',
            'level_jabatan' => 'required|in:Group Leader,Head Of Section,Head Of Department,Penanggung Jawab Operational',
            'jabatan' => 'nullable|string|max:255',
            'status_karyawan' => 'required|in:tetap,kontrak',
            'email' => 'nullable|email',
            'nomor_hp' => 'nullable|string',
            'tanggal_join' => 'nullable|date',
        ]);

        Employee::create($request->all());

        return redirect()->route('hrd.employees.index')->with('success', 'Data karyawan berhasil ditambahkan!');
    }

    public function edit(Employee $employee)
    {
        $departments = Department::all();
        return view('hrd.employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|unique:employees,nik,' . $employee->id,
            'department_id' => 'required|exists:departments,id',
            'level_jabatan' => 'required|in:Group Leader,Head Of Section,Head Of Department,Penanggung Jawab Operational',
            'jabatan' => 'nullable|string|max:255',
            'status_karyawan' => 'required|in:tetap,kontrak',
            'email' => 'nullable|email',
            'nomor_hp' => 'nullable|string',
            'tanggal_join' => 'nullable|date',
        ]);

        $employee->update($request->all());

        return redirect()->route('hrd.employees.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function show(Employee $employee)
    {
        $employee->load([
            'department',
            'trainings',
            'trainingRequests.training',
            'trainingRequests.diajukanOleh',
            'certifications',
        ]);

        // Hitung ringkasan statistik pelatihan untuk karyawan ini
        $totalTrainings = $employee->trainings->count();
        $completedTrainings = $employee->trainings->where('pivot.status_peserta', 'selesai')->count();
        $inProgressTrainings = $employee->trainings->where('pivot.status_peserta', 'sedang_pelatihan')->count();
        $pendingRequests = $employee->trainingRequests->where('status', 'pending')->count();

        // Pelatihan yang sedang dibuka jika ingin mendaftarkan langsung
        $availableTrainings = Training::where('status_pendaftaran', 'dibuka')->get();

        return view('hrd.employees.show', compact(
            'employee',
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
