<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Training;
use App\Models\TrainingParticipant;
use Illuminate\Http\Request;

class TrainingParticipantController extends Controller
{
    // Menampilkan daftar seluruh peserta pelatihan
    // public function index()
    // {
    //     // Mengambil data peserta beserta relasi karyawan dan pelatihannya
    //     $participants = TrainingParticipant::with(['employee', 'training'])
    //         ->latest()
    //         ->get();

    //     return view('hrd.participants.index', compact('participants'));
    // }


    public function index(Request $request)
{
    $trainingId = $request->training_id;

    $trainings = Training::withCount('employees')
        ->orderBy('nama_pelatihan')
        ->get();

    $participants = TrainingParticipant::with([
            'employee',
            'training'
        ])
        ->when($trainingId, function ($query) use ($trainingId) {
            $query->where('training_id', $trainingId);
        })
        ->latest()
        ->paginate(15)
        ->withQueryString();

    return view('hrd.participants.index', compact(
        'participants',
        'trainings',
        'trainingId'
    ));
}

    // Memperbarui status progres peserta
    public function updateStatus(Request $request, TrainingParticipant $participant)
    {
        $request->validate([
            // Validasi ini sudah disesuaikan dengan isi migration kamu!
            'status_peserta' => 'required|in:belum_diajukan,diajukan,disetujui,sedang_pelatihan,selesai',
        ]);

        $participant->update([
            'status_peserta' => $request->status_peserta,
        ]);

        return redirect()->route('hrd.participants.index')
            ->with('success', 'Status peserta pelatihan berhasil diperbarui!');
    }
}
