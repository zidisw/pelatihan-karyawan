<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Training;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // public function index(Request $request)
    // {
    //     $search = $request->search;

    //     // Data karyawan + pencarian
    //     $employees = Employee::with('department')
    //         ->when($search, function ($query) use ($search) {
    //             $query->where(function ($q) use ($search) {
    //                 $q->where('nama', 'like', "%{$search}%")
    //                     ->orWhere('nik', 'like', "%{$search}%");
    //             });
    //         })
    //         ->paginate(10)
    //         ->withQueryString();

    //     // Statistik training
    //     $today = now()->toDateString();

    //     $completedTrainings = Training::where('tanggal_selesai', '<', $today)->count();

    //     $onProgressTrainings = Training::where('tanggal_mulai', '<=', $today)
    //         ->where('tanggal_selesai', '>=', $today)
    //         ->count();

    //     $pendingTrainings = Training::where(function ($query) use ($today) {
    //         $query->where('tanggal_mulai', '>', $today)
    //             ->orWhereNull('tanggal_mulai');
    //     })
    //         ->count();

    //     return view('hrd.employees.index', compact(
    //         'employees',
    //         'completedTrainings',
    //         'onProgressTrainings',
    //         'pendingTrainings'
    //     ));
    // }

    public function index(Request $request)
{
    $search = $request->search;
    $departmentId = $request->department_id;

    $departments = Department::withCount('employees')
        ->orderBy('nama_departemen')
        ->get();

    $employees = Employee::with('department')
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        })
        ->when($departmentId, function ($query) use ($departmentId) {
            $query->where('department_id', $departmentId);
        })
        ->paginate(10)
        ->withQueryString();

    $today = now()->toDateString();

    $completedTrainings = Training::where('tanggal_selesai', '<', $today)->count();

    $onProgressTrainings = Training::where('tanggal_mulai', '<=', $today)
        ->where('tanggal_selesai', '>=', $today)
        ->count();

    $pendingTrainings = Training::where(function ($query) use ($today) {
        $query->where('tanggal_mulai', '>', $today)
            ->orWhereNull('tanggal_mulai');
    })->count();

    return view('hrd.employees.index', compact(
        'employees',
        'departments',
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

        // dd($request->all());
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|unique:employees,nik',
            'department_id' => 'required|exists:departments,id',
            'jabatan' => 'nullable|string|max:255',
            'status_karyawan' => 'required|in:tetap,kontrak',
            'email' => 'nullable|email',
            'nomor_hp' => 'nullable|string',
            'tanggal_join' => 'nullable|date',
        ]);

        Employee::create($request->all());

        return redirect()->route('hrd.employees.index')->with('success', 'Data karyawan berhasil ditambahkan!');
    }

    // public function show(Employee $employee)
    // {
    //     return view('hrd.employees.show', compact('employee'));
    // }
    // public function edit(Employee $employee)
    // {
    //     $departments = Department::all();

    //     return view('hrd.employees.edit', compact('employee', 'departments'));
    // }

    public function show(Employee $employee)
    {
        $employee->load([
            'department',
            'trainings' => function ($query) {
                $query->latest();
            },
        ]);

        return view('hrd.employees.show', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|unique:employees,nik,'.$employee->id,
            'department_id' => 'required|exists:departments,id',
            'jabatan' => 'nullable|string|max:255',
            'status_karyawan' => 'required|in:tetap,kontrak',
            'email' => 'nullable|email',
            'nomor_hp' => 'nullable|string',
            'tanggal_join' => 'nullable|date',
        ]);

        $employee->update($request->all());

        return redirect()->route('hrd.employees.index')->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('hrd.employees.index')->with('success', 'Data karyawan berhasil dihapus!');
    }

    // public function search(Request $request)
    // {
    //     $search = $request->search;

    //     $employees = Employee::with('department')
    //         ->when($search, function ($q) use ($search) {
    //             $q->where(function ($query) use ($search) {
    //                 $query->where('nama', 'like', "%{$search}%")
    //                       ->orWhere('nik', 'like', "%{$search}%");
    //             });
    //         })
    //         ->paginate(10)
    //         ->withQueryString();

    //     return view('hrd.employees.index', compact('employees'));
    // }
}
