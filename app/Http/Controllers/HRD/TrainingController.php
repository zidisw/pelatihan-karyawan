<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Training;
use App\Models\TrainingDocument;
use App\Support\SafeFileStorage;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $validParticipantStatuses = ['disetujui', 'terjadwal', 'sedang_pelatihan', 'selesai', 'lulus'];

        $query = Training::with(['employees', 'participants', 'documents', 'requests'])
            ->withCount([
                'participants as approved_participants_count' => function ($q) use ($validParticipantStatuses) {
                    $q->whereIn('status_peserta', $validParticipantStatuses);
                },
                'documents',
                'requests',
            ]);

        // 1. Filter Pencarian (Nama, Kode Program, Instruktur, Lokasi, Jenis Sertifikasi)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_pelatihan', 'like', "%{$search}%")
                  ->orWhere('kode_program', 'like', "%{$search}%")
                  ->orWhere('instruktur', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('jenis_sertifikasi', 'like', "%{$search}%");
            });
        }

        // 2. Filter Kategori Pelatihan (Sertifikasi, Technical Skill, Soft Skill)
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // 3. Filter Metode Pelaksanaan (OFFLINE, ONLINE, HYBRID)
        if ($request->filled('metode_pelaksanaan')) {
            $query->where('metode_pelaksanaan', $request->metode_pelaksanaan);
        }

        // 4. Filter Status Pendaftaran (dibuka, ditutup)
        if ($request->filled('status_pendaftaran')) {
            $query->where('status_pendaftaran', $request->status_pendaftaran);
        }

        // 5. Filter Status Program (draft, aktif, berjalan, selesai, dibatalkan, arsip)
        if ($request->filled('status_program')) {
            $query->where('status_program', $request->status_program);
        }

        $trainings = $query->latest()->paginate(10)->withQueryString();

        // Statistik Ringkasan Katalog Pelatihan
        $totalCatalog = Training::count();
        $openRegistrationCount = Training::where('status_pendaftaran', 'dibuka')
            ->where('status_program', '!=', 'dibatalkan')
            ->count();
        $certificationProgramCount = Training::where('kategori', 'Sertifikasi')->count();
        $archivedOrCancelledCount = Training::where('status_program', 'dibatalkan')->count();

        return view('hrd.trainings.index', compact(
            'trainings',
            'totalCatalog',
            'openRegistrationCount',
            'certificationProgramCount',
            'archivedOrCancelledCount'
        ));
    }

    public function create()
    {
        $departments = Department::orderBy('nama_departemen')->get();
        $suggestedCode = 'TRG-' . date('Y') . '-' . str_pad((string) (Training::count() + 1), 3, '0', STR_PAD_LEFT);

        return view('hrd.trainings.create', compact('departments', 'suggestedCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_program' => 'nullable|string|max:50|unique:trainings,kode_program',
            'nama_pelatihan' => 'required|string|max:255',
            'kategori' => 'required|in:Sertifikasi,Technical Skill,Soft Skill,Lainnya',
            'jenis_sertifikasi' => 'nullable|string|max:100',
            'deskripsi' => 'required|string',
            'instruktur' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'batas_pendaftaran' => 'nullable|date|before_or_equal:tanggal_mulai',
            'kuota' => 'required|integer|min:1',
            'lokasi' => 'required|string|max:255',
            'metode_pelaksanaan' => 'required|in:OFFLINE,ONLINE,HYBRID',
            'status_pendaftaran' => 'required|in:belum_dibuka,dibuka,ditutup',
            'status_program' => 'required|in:draft,akan_datang,berjalan,selesai,dibatalkan',
            'persyaratan_peserta' => 'nullable|string',
            'target_departemen' => 'nullable|array',
            'target_departemen.*' => 'integer|exists:departments,id',
            'estimasi_biaya' => 'nullable|numeric|min:0',
            'catatan_internal' => 'nullable|string',
            'dokumen_pendukung' => 'nullable|array',
            'dokumen_pendukung.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        // Auto-generate kode_program jika dikosongkan
        if (empty($validated['kode_program'])) {
            $nextNumber = Training::count() + 1;
            $code = 'TRG-' . date('Y') . '-' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
            while (Training::where('kode_program', $code)->exists()) {
                $nextNumber++;
                $code = 'TRG-' . date('Y') . '-' . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
            }
            $validated['kode_program'] = $code;
        }

        // Jika kategori bukan Sertifikasi, kosongkan jenis_sertifikasi
        if ($validated['kategori'] !== 'Sertifikasi') {
            $validated['jenis_sertifikasi'] = null;
        }

        // Jika status program dibatalkan / selesai, otomatis tutup pendaftaran
        if (in_array($validated['status_program'], ['dibatalkan', 'selesai'])) {
            $validated['status_pendaftaran'] = 'ditutup';
        }

        unset($validated['dokumen_pendukung']);

        $training = Training::create($validated);

        // Simpan Dokumen Pendukung (Silabus / Brosur / TOR) jika diunggah
        $this->handleSupportingDocumentsUpload($request, $training);

        return redirect()->route('hrd.trainings.index')->with('success', 'Program pelatihan berhasil ditambahkan ke katalog!');
    }

    public function edit(Training $training)
    {
        $training->load(['documents', 'participants', 'requests']);
        $departments = Department::orderBy('nama_departemen')->get();

        return view('hrd.trainings.edit', compact('training', 'departments'));
    }

    public function show(Training $training)
    {
        $training->load([
            'employees.department',
            'participants.employee.department',
            'requests.employee.department',
            'requests.department',
            'requests.diajukanOleh',
            'requests.participants.employee',
            'documents.uploader',
        ]);

        $departments = Department::orderBy('nama_departemen')->get();

        return view('hrd.trainings.show', compact('training', 'departments'));
    }

    public function update(Request $request, Training $training)
    {
        $validated = $request->validate([
            'kode_program' => 'nullable|string|max:50|unique:trainings,kode_program,' . $training->id,
            'nama_pelatihan' => 'required|string|max:255',
            'kategori' => 'required|in:Sertifikasi,Technical Skill,Soft Skill,Lainnya',
            'jenis_sertifikasi' => 'nullable|string|max:100',
            'deskripsi' => 'required|string',
            'instruktur' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'batas_pendaftaran' => 'nullable|date|before_or_equal:tanggal_mulai',
            'kuota' => 'required|integer|min:1',
            'lokasi' => 'required|string|max:255',
            'metode_pelaksanaan' => 'required|in:OFFLINE,ONLINE,HYBRID',
            'status_pendaftaran' => 'required|in:belum_dibuka,dibuka,ditutup',
            'status_program' => 'required|in:draft,akan_datang,berjalan,selesai,dibatalkan',
            'persyaratan_peserta' => 'nullable|string',
            'target_departemen' => 'nullable|array',
            'target_departemen.*' => 'integer|exists:departments,id',
            'estimasi_biaya' => 'nullable|numeric|min:0',
            'catatan_internal' => 'nullable|string',
            'dokumen_pendukung' => 'nullable|array',
            'dokumen_pendukung.*' => 'file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        if (empty($validated['kode_program'])) {
            $validated['kode_program'] = $training->kode_program
                ?? ('TRG-' . date('Y') . '-' . str_pad((string) $training->id, 3, '0', STR_PAD_LEFT));
        }

        if ($validated['kategori'] !== 'Sertifikasi') {
            $validated['jenis_sertifikasi'] = null;
        }

        // Jika target_departemen tidak dicentang sama sekali, simpan null (berarti terbuka untuk semua departemen)
        if (!$request->has('target_departemen')) {
            $validated['target_departemen'] = null;
        }

        // BR-08 & BR-11: Tutup pendaftaran otomatis jika kuota penuh atau program dibatalkan/selesai
        $approvedCount = $training->approvedParticipantsCount();
        if (in_array($validated['status_program'], ['dibatalkan', 'selesai']) || $approvedCount >= (int) $validated['kuota']) {
            $validated['status_pendaftaran'] = 'ditutup';
        }

        unset($validated['dokumen_pendukung']);

        $training->update($validated);

        // Simpan Dokumen Pendukung baru jika ada yang diunggah
        $this->handleSupportingDocumentsUpload($request, $training);

        return redirect()->route('hrd.trainings.show', $training->id)->with('success', 'Data program pelatihan berhasil diperbarui!');
    }

    /**
     * Mengubah status program menjadi Dibatalkan (Arsip) / Akan Datang (Aktif) / Selesai (BR-11).
     */
    public function archive(Request $request, Training $training)
    {
        $request->validate([
            'status_program' => 'nullable|in:draft,akan_datang,berjalan,selesai,dibatalkan',
        ]);

        $newStatus = $request->input('status_program', 'dibatalkan');
        $updateData = ['status_program' => $newStatus];

        if (in_array($newStatus, ['dibatalkan', 'selesai'])) {
            $updateData['status_pendaftaran'] = 'ditutup';
        }

        $training->update($updateData);

        $label = match ($newStatus) {
            'dibatalkan' => 'dibatalkan / diarsipkan',
            'selesai' => 'ditandai selesai',
            'berjalan' => 'ditandai sedang berjalan',
            default => 'diaktifkan kembali',
        };

        return redirect()->back()->with('success', "Program pelatihan \"{$training->nama_pelatihan}\" berhasil {$label}.");
    }

    /**
     * Menghapus dokumen pendukung individual dari katalog pelatihan.
     */
    public function destroyDocument(TrainingDocument $document)
    {
        if ($document->file_path) {
            SafeFileStorage::delete($document->file_path);
        }

        $document->delete();

        return redirect()->back()->with('success', 'Dokumen pendukung berhasil dihapus.');
    }

    /**
     * Proteksi Hapus Program Pelatihan sesuai Business Rule BR-11:
     * Jika sudah memiliki peserta atau pengajuan, cegah DELETE permanen dan alihkan ke status Dibatalkan/Arsip.
     */
    public function destroy(Training $training)
    {
        if ($training->hasTransactions()) {
            $training->update([
                'status_program' => 'dibatalkan',
                'status_pendaftaran' => 'ditutup',
            ]);

            return redirect()->route('hrd.trainings.index')->with(
                'warning',
                "Sesuai aturan BR-11, pelatihan \"{$training->nama_pelatihan}\" sudah memiliki riwayat peserta/pengajuan sehingga tidak dapat dihapus permanen. Status program telah otomatis diubah menjadi DIBATALKAN / ARSIP dan pendaftaran ditutup."
            );
        }

        // Hapus file dokumen pendukung jika belum ada transaksi peserta/pengajuan
        foreach ($training->documents as $doc) {
            if ($doc->file_path) {
                SafeFileStorage::delete($doc->file_path);
            }
            $doc->delete();
        }

        $training->delete();

        return redirect()->route('hrd.trainings.index')->with('success', 'Program pelatihan (tanpa transaksi) berhasil dihapus dari katalog.');
    }

    /**
     * Helper untuk menyimpan file Dokumen Pendukung (Silabus / Brosur / TOR) ke tabel training_documents.
     */
    protected function handleSupportingDocumentsUpload(Request $request, Training $training): void
    {
        if (!$request->hasFile('dokumen_pendukung')) {
            return;
        }

        foreach ($request->file('dokumen_pendukung') as $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }

            $storedPath = SafeFileStorage::store($file, 'training-documents');

            TrainingDocument::create([
                'training_id' => $training->id,
                'jenis_dokumen' => 'silabus',
                'nama_file' => $file->getClientOriginalName(),
                'file_path' => $storedPath,
                'mime_type' => $file->getClientMimeType(),
                'ukuran_file' => $file->getSize(),
                'uploaded_by' => auth()->id(),
            ]);
        }
    }
}
