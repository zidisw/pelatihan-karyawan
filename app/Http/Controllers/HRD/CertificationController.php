<?php

namespace App\Http\Controllers\HRD;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Certification;
use App\Support\SafeFileStorage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CertificationController extends Controller
{
    /**
     * Menyimpan data sertifikat resmi karyawan baru beserta unggahan file (PRD Bab 7.2.D & 7.3.D).
     */
    public function store(Request $request)
    {
        $rules = [
            'employee_id' => 'required|exists:employees,id',
            'training_id' => 'nullable|exists:trainings,id',
            'nama_sertifikasi' => 'required|string|max:255',
            'jenis_sertifikasi' => 'required|string|max:100',
            'nomor_sertifikat' => 'nullable|string|max:150',
            'tanggal_terbit' => 'nullable|date',
            'masa_berlaku' => 'nullable|string|max:100',
            'tanggal_expired' => 'nullable|date',
            'is_permanent' => 'nullable|boolean',
            'file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];

        if ($request->filled('tanggal_terbit') && $request->filled('tanggal_expired')) {
            $rules['tanggal_expired'] = 'nullable|date|after_or_equal:tanggal_terbit';
        }

        $validated = $request->validate($rules);

        $isPermanent = $request->boolean('is_permanent');
        $validated['is_permanent'] = $isPermanent;

        if ($isPermanent) {
            $validated['tanggal_expired'] = null;
            $validated['masa_berlaku'] = 'Permanen';
        } elseif (empty($validated['masa_berlaku']) && !empty($validated['tanggal_terbit']) && !empty($validated['tanggal_expired'])) {
            $years = (int) Carbon::parse($validated['tanggal_terbit'])->diffInYears(Carbon::parse($validated['tanggal_expired']));
            $months = max(1, (int) round(Carbon::parse($validated['tanggal_terbit'])->diffInMonths(Carbon::parse($validated['tanggal_expired']))));
            $validated['masa_berlaku'] = $years > 0 ? $years . ' Tahun' : $months . ' Bulan';
        }

        unset($validated['file_sertifikat']);

        if ($request->hasFile('file_sertifikat') && $request->file('file_sertifikat')->isValid()) {
            $validated['file_sertifikat'] = SafeFileStorage::store($request->file('file_sertifikat'), 'certificates');
        }

        $certification = Certification::create($validated);

        AuditLog::record(
            'Certification',
            $certification->id,
            'CREATE',
            null,
            $certification->toArray(),
            "Menambahkan sertifikat baru: {$certification->nama_sertifikasi}"
        );

        return redirect()->back()->with('success', "Sertifikat \"{$certification->nama_sertifikasi}\" berhasil ditambahkan!");
    }

    /**
     * Memperbarui data sertifikat karyawan atau mengganti file sertifikat (pembaruan masa berlaku).
     */
    public function update(Request $request, Certification $certification)
    {
        $rules = [
            'training_id' => 'nullable|exists:trainings,id',
            'nama_sertifikasi' => 'required|string|max:255',
            'jenis_sertifikasi' => 'required|string|max:100',
            'nomor_sertifikat' => 'nullable|string|max:150',
            'tanggal_terbit' => 'nullable|date',
            'masa_berlaku' => 'nullable|string|max:100',
            'tanggal_expired' => 'nullable|date',
            'is_permanent' => 'nullable|boolean',
            'file_sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ];

        if ($request->filled('tanggal_terbit') && $request->filled('tanggal_expired')) {
            $rules['tanggal_expired'] = 'nullable|date|after_or_equal:tanggal_terbit';
        }

        $validated = $request->validate($rules);

        $oldData = $certification->toArray();

        $isPermanent = $request->boolean('is_permanent');
        $validated['is_permanent'] = $isPermanent;

        if ($isPermanent) {
            $validated['tanggal_expired'] = null;
            $validated['masa_berlaku'] = 'Permanen';
        } elseif (empty($validated['masa_berlaku']) && !empty($validated['tanggal_terbit']) && !empty($validated['tanggal_expired'])) {
            $years = (int) Carbon::parse($validated['tanggal_terbit'])->diffInYears(Carbon::parse($validated['tanggal_expired']));
            $months = max(1, (int) round(Carbon::parse($validated['tanggal_terbit'])->diffInMonths(Carbon::parse($validated['tanggal_expired']))));
            $validated['masa_berlaku'] = $years > 0 ? $years . ' Tahun' : $months . ' Bulan';
        }

        unset($validated['file_sertifikat']);

        if ($request->hasFile('file_sertifikat') && $request->file('file_sertifikat')->isValid()) {
            if ($certification->file_sertifikat) {
                SafeFileStorage::delete($certification->file_sertifikat);
            }
            $validated['file_sertifikat'] = SafeFileStorage::store($request->file('file_sertifikat'), 'certificates');
        }

        $certification->update($validated);

        AuditLog::record(
            'Certification',
            $certification->id,
            'UPDATE',
            $oldData,
            $certification->fresh()->toArray(),
            "Memperbarui sertifikat: {$certification->nama_sertifikasi}"
        );

        return redirect()->back()->with('success', "Data sertifikat \"{$certification->nama_sertifikasi}\" berhasil diperbarui!");
    }

    /**
     * Menghapus data sertifikat karyawan beserta file fisiknya.
     */
    public function destroy(Certification $certification)
    {
        $oldData = $certification->toArray();
        $nama = $certification->nama_sertifikasi;

        if ($certification->file_sertifikat) {
            SafeFileStorage::delete($certification->file_sertifikat);
        }

        AuditLog::record(
            'Certification',
            $certification->id,
            'DELETE',
            $oldData,
            null,
            "Menghapus sertifikat: {$nama}"
        );

        $certification->delete();

        return redirect()->back()->with('success', "Sertifikat \"{$nama}\" berhasil dihapus.");
    }

    /**
     * Kontrol akses aman untuk melihat / mengunduh dokumen sertifikat (PRD Bab 14 & BR-03):
     * - Admin HRD & Superadmin: dapat mengakses seluruh sertifikat karyawan.
     * - User Departemen: hanya dapat mengakses sertifikat karyawan dari departemennya sendiri.
     */
    public function download(Request $request, Certification $certification)
    {
        $user = auth()->user();
        $certification->load(['employee.department', 'training']);

        if (in_array($user->role, ['departemen', 'department'], true)) {
            $empDeptId = $certification->employee?->department_id;
            if (!$user->department_id || (int) $empDeptId !== (int) $user->department_id) {
                abort(403, 'Akses ditolak: Anda hanya diizinkan melihat atau mengunduh sertifikat karyawan dari departemen Anda sendiri.');
            }
        } elseif (!in_array($user->role, ['hrd', 'superadmin'], true)) {
            abort(403, 'Akses ditolak.');
        }

        if ($certification->file_sertifikat) {
            $extension = pathinfo($certification->file_sertifikat, PATHINFO_EXTENSION);
            $safeName = 'Sertifikat_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $certification->employee?->nik ?? 'EMP')
                . '_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $certification->nama_sertifikasi)
                . ($extension ? ".{$extension}" : '');

            $fileResponse = SafeFileStorage::response(
                $certification->file_sertifikat,
                $safeName,
                $request->boolean('inline')
            );

            if ($fileResponse !== null) {
                return $fileResponse;
            }
        }

        // Fallback ringkasan sertifikat siap cetak apabila file fisik dari data seeder belum tersedia
        return response()->view('hrd.exports.certificate_preview', [
            'certification' => $certification,
        ]);
    }
}
