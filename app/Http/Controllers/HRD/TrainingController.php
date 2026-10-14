<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::paginate(10); // Menggunakan pagination untuk menampilkan 10 data per halaman

        return view('hrd.trainings.index', compact('trainings'));
    }

    public function create()
    {
        return view('hrd.trainings.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelatihan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'instruktur' => 'nullable|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'kuota' => 'required|integer|min:1',
            'lokasi' => 'nullable|string|max:255',
            // Sesuaikan persis dengan database:
            'metode_pelaksanaan' => 'required|in:OFFLINE,ONLINE,HYBRID',
            'status_pendaftaran' => 'required|in:dibuka,ditutup',
        ]);

        Training::create($request->all());

        return redirect()->route('hrd.trainings.index')->with('success', 'Data pelatihan berhasil ditambahkan!');
    }

    public function edit(Training $training)
    {
        return view('hrd.trainings.edit', compact('training'));
    }

    public function show(Training $training)
    {
        // Memuat data pelatihan beserta karyawan yang terdaftar dan riwayat request
        $training->load([
            'employees.department',
            'requests.employee.department',
            'requests.diajukanOleh',
        ]);

        return view('hrd.trainings.show', compact('training'));
    }

    public function update(Request $request, Training $training)
    {
        $request->validate([
            'nama_pelatihan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'instruktur' => 'nullable|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'kuota' => 'required|integer|min:1',
            'lokasi' => 'nullable|string|max:255',
            // Sesuaikan persis dengan database:
            'metode_pelaksanaan' => 'required|in:OFFLINE,ONLINE,HYBRID',
            'status_pendaftaran' => 'required|in:dibuka,ditutup',
        ]);

        $training->update($request->all());

        return redirect()->route('hrd.trainings.index')->with('success', 'Data pelatihan berhasil diperbarui!');
    }

    public function destroy(Training $training)
    {
        $training->delete();

        return redirect()->route('hrd.trainings.index')->with('success', 'Data pelatihan berhasil dihapus!');
    }
}
