<?php

namespace Database\Seeders;

use App\Models\Certification;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Models\TrainingRequest;
use App\Models\TrainingRequestParticipant;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $hrgaDept = Department::where('nama_departemen', 'HRGA')->first();
        $tcDept   = Department::where('nama_departemen', 'TC')->first();
        $civilDept = Department::where('nama_departemen', 'CIVIL')->first();
        $plmDept  = Department::where('nama_departemen', 'PLM')->first();
        $medDept  = Department::where('nama_departemen', 'MED')->first();
        $logDept  = Department::where('nama_departemen', 'LOGISTIK')->first();
        $modDept  = Department::where('nama_departemen', 'MOD')->first();
        $hseDept  = Department::where('nama_departemen', 'HSE')->first();

        $adminHr  = User::where('email', 'hrd@pelatihan.com')->first();
        $adminIt  = User::where('email', 'admin.it@pelatihan.com')->first();
        $adminEng = User::where('email', 'admin.engineering@pelatihan.com')->first();
        $adminHse = User::where('email', 'admin.hse@pelatihan.com')->first();

        // 1. Data Karyawan
        $emp1 = Employee::updateOrCreate(['nik' => 'EMP-001'], [
            'nama' => 'Budi Santoso',
            'jenis_kelamin' => 'Laki-laki',
            'department_id' => $tcDept?->id,
            'level_jabatan' => 'Head Of Section',
            'jabatan' => 'Senior Technical Instructor',
            'site_unit' => 'Head Office',
            'status_karyawan' => 'tetap',
            'status_aktif' => 'aktif',
            'email' => 'budi.santoso@perusahaan.com',
            'nomor_hp' => '081234567890',
            'tanggal_join' => '2023-02-15',
        ]);

        $emp2 = Employee::updateOrCreate(['nik' => 'EMP-002'], [
            'nama' => 'Siti Rahmawati',
            'jenis_kelamin' => 'Perempuan',
            'department_id' => $tcDept?->id,
            'level_jabatan' => 'Group Leader',
            'jabatan' => 'System & Training Specialist',
            'site_unit' => 'Head Office',
            'status_karyawan' => 'kontrak',
            'status_aktif' => 'aktif',
            'email' => 'siti.rahmawati@perusahaan.com',
            'nomor_hp' => '081298765432',
            'tanggal_join' => '2024-01-10',
        ]);

        $emp3 = Employee::updateOrCreate(['nik' => 'EMP-003'], [
            'nama' => 'Dedi Maulana',
            'jenis_kelamin' => 'Laki-laki',
            'department_id' => $plmDept?->id,
            'level_jabatan' => 'Head Of Section',
            'jabatan' => 'Mechanical Maintenance Engineer',
            'site_unit' => 'Site Kalimantan Timur',
            'status_karyawan' => 'tetap',
            'status_aktif' => 'aktif',
            'email' => 'dedi.maulana@perusahaan.com',
            'nomor_hp' => '082155667788',
            'tanggal_join' => '2022-08-01',
        ]);

        $emp4 = Employee::updateOrCreate(['nik' => 'EMP-004'], [
            'nama' => 'Maya Anggraini',
            'jenis_kelamin' => 'Perempuan',
            'department_id' => $hrgaDept?->id,
            'level_jabatan' => 'Head Of Department',
            'jabatan' => 'People Development Officer',
            'site_unit' => 'Head Office',
            'status_karyawan' => 'tetap',
            'status_aktif' => 'aktif',
            'email' => 'maya.anggraini@perusahaan.com',
            'nomor_hp' => '085711223344',
            'tanggal_join' => '2023-06-20',
        ]);

        $emp5 = Employee::updateOrCreate(['nik' => 'EMP-005'], [
            'nama' => 'Rian Pratama',
            'jenis_kelamin' => 'Laki-laki',
            'department_id' => $plmDept?->id,
            'level_jabatan' => 'Group Leader',
            'jabatan' => 'Heavy Equipment Electrical Specialist',
            'site_unit' => 'Site Kalimantan Timur',
            'status_karyawan' => 'kontrak',
            'status_aktif' => 'aktif',
            'email' => 'rian.pratama@perusahaan.com',
            'nomor_hp' => '087899887766',
            'tanggal_join' => '2024-03-01',
        ]);

        $emp6 = Employee::updateOrCreate(['nik' => 'EMP-006'], [
            'nama' => 'Andi Wijaya',
            'jenis_kelamin' => 'Laki-laki',
            'department_id' => $hseDept?->id,
            'level_jabatan' => 'Penanggung Jawab Operational',
            'jabatan' => 'HSE Supervisor',
            'site_unit' => 'Site Kalimantan Selatan',
            'status_karyawan' => 'tetap',
            'status_aktif' => 'aktif',
            'email' => 'andi.wijaya@perusahaan.com',
            'nomor_hp' => '081344556677',
            'tanggal_join' => '2021-11-10',
        ]);

        $emp7 = Employee::updateOrCreate(['nik' => 'EMP-007'], [
            'nama' => 'Hendra Kusuma',
            'jenis_kelamin' => 'Laki-laki',
            'department_id' => $modDept?->id,
            'level_jabatan' => 'Group Leader',
            'jabatan' => 'Mine Operation Foreman',
            'site_unit' => 'Site Kalimantan Selatan',
            'status_karyawan' => 'tetap',
            'status_aktif' => 'aktif',
            'email' => 'hendra.kusuma@perusahaan.com',
            'nomor_hp' => '081377889900',
            'tanggal_join' => '2020-05-12',
        ]);

        $emp8 = Employee::updateOrCreate(['nik' => 'EMP-008'], [
            'nama' => 'Fajar Nugroho',
            'jenis_kelamin' => 'Laki-laki',
            'department_id' => $logDept?->id,
            'level_jabatan' => 'Head Of Section',
            'jabatan' => 'Warehouse & Supply Chain Coordinator',
            'site_unit' => 'Site Kalimantan Timur',
            'status_karyawan' => 'tetap',
            'status_aktif' => 'aktif',
            'email' => 'fajar.nugroho@perusahaan.com',
            'nomor_hp' => '081355667799',
            'tanggal_join' => '2022-11-03',
        ]);

        // 2. Data Program Pelatihan (Katalog)
        $tr1 = Training::updateOrCreate(['nama_pelatihan' => 'Sertifikasi Pengawas Operasional Pertama (POP)'], [
            'kode_program' => 'TRN-POP-001',
            'kategori' => 'Sertifikasi',
            'jenis_sertifikasi' => 'POP',
            'deskripsi' => 'Pelatihan dan uji kompetensi Pengawas Operasional Pertama (POP) pertambangan sesuai standar keselamatan dan operasional.',
            'instruktur' => 'Budi Hermawan, S.T., M.T. (LSP Energi & Pertambangan)',
            'tanggal_mulai' => now()->addDays(5)->toDateString(),
            'tanggal_selesai' => now()->addDays(12)->toDateString(),
            'batas_pendaftaran' => now()->addDays(3)->toDateString(),
            'kuota' => 25,
            'lokasi' => 'Ruang Training Gedung A, Lt. 3 / Hybrid Zoom',
            'metode_pelaksanaan' => 'HYBRID',
            'status_pendaftaran' => 'dibuka',
            'status_program' => 'akan_datang',
            'persyaratan_peserta' => 'Supervisor / Foreman dengan pengalaman minimal 2 tahun di operasional site.',
            'target_departemen' => ['MOD', 'PLM', 'HSE', 'CIVIL'],
            'estimasi_biaya' => 6500000,
        ]);

        $tr2 = Training::updateOrCreate(['nama_pelatihan' => 'Preventive Maintenance & Diagnostic Alat Berat'], [
            'kode_program' => 'TRN-TEC-002',
            'kategori' => 'Technical Skill',
            'jenis_sertifikasi' => null,
            'deskripsi' => 'Pelatihan teknis pemeliharaan berkala, troubleshooting sistem hidrolik dan elektrikal alat berat untuk menurunkan downtime.',
            'instruktur' => 'Ir. Faisal Akbar, Senior Plant Specialist',
            'tanggal_mulai' => now()->subDays(15)->toDateString(),
            'tanggal_selesai' => now()->subDays(10)->toDateString(),
            'batas_pendaftaran' => now()->subDays(18)->toDateString(),
            'kuota' => 15,
            'lokasi' => 'Workshop Utama PLM',
            'metode_pelaksanaan' => 'OFFLINE',
            'status_pendaftaran' => 'ditutup',
            'status_program' => 'selesai',
            'persyaratan_peserta' => 'Teknisi & Engineer departemen PLM dan TC.',
            'target_departemen' => ['PLM', 'TC'],
            'estimasi_biaya' => 4000000,
        ]);

        $tr3 = Training::updateOrCreate(['nama_pelatihan' => 'Pelatihan K3 Umum & Keselamatan Kerja Industri'], [
            'kode_program' => 'TRN-K3-003',
            'kategori' => 'Sertifikasi',
            'jenis_sertifikasi' => 'K3',
            'deskripsi' => 'Standar operasional prosedur Keselamatan dan Kesehatan Kerja (K3), manajemen risiko kecelakaan kerja, penanganan bahan B3, dan kepatuhan regulasi.',
            'instruktur' => 'Ir. Hendra Saputra, Ahli K3 Utama',
            'tanggal_mulai' => now()->addDays(10)->toDateString(),
            'tanggal_selesai' => now()->addDays(14)->toDateString(),
            'batas_pendaftaran' => now()->addDays(7)->toDateString(),
            'kuota' => 20,
            'lokasi' => 'Training Center Lantai Dasar',
            'metode_pelaksanaan' => 'OFFLINE',
            'status_pendaftaran' => 'dibuka',
            'status_program' => 'akan_datang',
            'persyaratan_peserta' => 'Terbuka untuk seluruh departemen operasional dan teknik.',
            'target_departemen' => ['HSE', 'PLM', 'MOD', 'CIVIL', 'LOGISTIK'],
            'estimasi_biaya' => 5000000,
        ]);

        $tr4 = Training::updateOrCreate(['nama_pelatihan' => 'Leadership & Effective Team Communication'], [
            'kode_program' => 'TRN-SFT-004',
            'kategori' => 'Soft Skill',
            'jenis_sertifikasi' => null,
            'deskripsi' => 'Pengembangan kepemimpinan lapangan, komunikasi efektif lintas divisi, problem solving, dan manajemen kinerja tim.',
            'instruktur' => 'Anita Wijaya, M.Psi., Leadership Coach',
            'tanggal_mulai' => now()->addDays(7)->toDateString(),
            'tanggal_selesai' => now()->addDays(9)->toDateString(),
            'batas_pendaftaran' => now()->addDays(5)->toDateString(),
            'kuota' => 30,
            'lokasi' => 'Online Video Conference (Zoom)',
            'metode_pelaksanaan' => 'ONLINE',
            'status_pendaftaran' => 'dibuka',
            'status_program' => 'akan_datang',
            'persyaratan_peserta' => 'Leader, Supervisor, dan Staf Senior.',
            'target_departemen' => ['HRGA', 'TC', 'PLM', 'MOD', 'HSE', 'LOGISTIK', 'CIVIL', 'MED'],
            'estimasi_biaya' => 2500000,
        ]);

        // 3. Data Pengajuan Pelatihan (Training Requests) + Detail Peserta (TrainingRequestParticipant)
        $req1 = TrainingRequest::updateOrCreate([
            'nomor_request' => 'REQ-202610-0001',
        ], [
            'department_id' => $plmDept?->id,
            'employee_id' => $emp3->id,
            'training_id' => $tr3->id,
            'diajukan_oleh' => $adminEng?->id,
            'jenis_request' => 'katalog',
            'status' => 'pending',
            'catatan_admin' => 'Wajib untuk pemenuhan standar audit K3 workshop kuartal ini.',
        ]);

        TrainingRequestParticipant::updateOrCreate([
            'training_request_id' => $req1->id,
            'employee_id' => $emp3->id,
        ], [
            'nama_peserta' => $emp3->nama,
            'jabatan_peserta' => $emp3->jabatan,
            'status_peserta' => 'menunggu_review',
        ]);

        $req2 = TrainingRequest::updateOrCreate([
            'nomor_request' => 'REQ-202610-0002',
        ], [
            'department_id' => $tcDept?->id,
            'employee_id' => $emp2->id,
            'training_id' => $tr1->id,
            'diajukan_oleh' => $adminIt?->id,
            'jenis_request' => 'katalog',
            'status' => 'approved',
            'catatan_admin' => 'Disetujui. Memenuhi persyaratan kompetensi instruktur.',
            'reviewed_by' => $adminHr?->id,
            'reviewed_at' => now()->subDays(1),
        ]);

        TrainingRequestParticipant::updateOrCreate([
            'training_request_id' => $req2->id,
            'employee_id' => $emp2->id,
        ], [
            'nama_peserta' => $emp2->nama,
            'jabatan_peserta' => $emp2->jabatan,
            'status_peserta' => 'approved',
            'catatan_keputusan' => 'Disetujui oleh HRGA.',
            'reviewed_by' => $adminHr?->id,
            'reviewed_at' => now()->subDays(1),
        ]);

        $req3 = TrainingRequest::updateOrCreate([
            'nomor_request' => 'REQ-202610-0003',
        ], [
            'department_id' => $plmDept?->id,
            'employee_id' => $emp5->id,
            'training_id' => null,
            'diajukan_oleh' => $adminEng?->id,
            'jenis_request' => 'training_baru',
            'periode_pengajuan' => 'Okt 2026',
            'scope_program' => 'High Voltage Electrical Safety & Troubleshooting',
            'kondisi_saat_ini' => 'Teknisi kelistrikan alat berat membutuhkan pembaruan kompetensi sistem kontrol tegangan tinggi.',
            'training_need' => 'Pelatihan Sertifikasi Kelistrikan Tegangan Tinggi & Sistem Kontrol Alat Berat',
            'target_kpi' => 'Menurunkan waktu perbaikan electrical breakdown sebesar 30% dan zero accident.',
            'estimasi_biaya' => 8500000,
            'pelaksana' => 'Eksternal',
            'metode_pelaksanaan' => 'Offline',
            'status' => 'pending',
            'catatan_admin' => 'Diusulkan sebagai program training baru kuartal IV.',
        ]);

        TrainingRequestParticipant::updateOrCreate([
            'training_request_id' => $req3->id,
            'employee_id' => $emp5->id,
        ], [
            'nama_peserta' => $emp5->nama,
            'jabatan_peserta' => $emp5->jabatan,
            'status_peserta' => 'menunggu_review',
        ]);

        // 4. Data Peserta Pelatihan (Training Participants / Realisasi Technical Skill, Soft Skill, & Sertifikasi)
        TrainingParticipant::updateOrCreate([
            'employee_id' => $emp1->id,
            'training_id' => $tr2->id,
        ], [
            'status_peserta' => 'selesai',
            'kehadiran' => 'hadir',
            'hasil_evaluasi' => 'Lulus dengan nilai 92 (Sangat Baik)',
        ]);

        TrainingParticipant::updateOrCreate([
            'employee_id' => $emp3->id,
            'training_id' => $tr2->id,
        ], [
            'status_peserta' => 'selesai',
            'kehadiran' => 'hadir',
            'hasil_evaluasi' => 'Lulus dengan nilai 89 (Sangat Baik)',
        ]);

        TrainingParticipant::updateOrCreate([
            'employee_id' => $emp5->id,
            'training_id' => $tr2->id,
        ], [
            'status_peserta' => 'selesai',
            'kehadiran' => 'hadir',
            'hasil_evaluasi' => 'Lulus dengan nilai 86 (Baik)',
        ]);

        TrainingParticipant::updateOrCreate([
            'employee_id' => $emp2->id,
            'training_id' => $tr1->id,
        ], [
            'training_request_id' => $req2->id,
            'status_peserta' => 'sedang_pelatihan',
            'kehadiran' => 'hadir',
        ]);

        TrainingParticipant::updateOrCreate([
            'employee_id' => $emp4->id,
            'training_id' => $tr4->id,
        ], [
            'status_peserta' => 'selesai',
            'kehadiran' => 'hadir',
            'hasil_evaluasi' => 'Lulus dengan nilai 94 (Sangat Baik)',
        ]);

        TrainingParticipant::updateOrCreate([
            'employee_id' => $emp8->id,
            'training_id' => $tr4->id,
        ], [
            'status_peserta' => 'selesai',
            'kehadiran' => 'hadir',
            'hasil_evaluasi' => 'Lulus dengan nilai 88 (Baik)',
        ]);

        // 5. Data Sertifikasi Karyawan
        Certification::updateOrCreate([
            'employee_id' => $emp1->id,
            'nama_sertifikasi' => 'Sertifikat Pengawas Operasional Pertama (POP)',
        ], [
            'training_id' => $tr1->id,
            'jenis_sertifikasi' => 'POP',
            'nomor_sertifikat' => 'CERT-POP-2025-0089',
            'tanggal_terbit' => '2025-02-01',
            'masa_berlaku' => '3 Tahun',
            'tanggal_expired' => '2028-02-01',
            'is_permanent' => false,
        ]);

        Certification::updateOrCreate([
            'employee_id' => $emp3->id,
            'nama_sertifikasi' => 'Sertifikat Ahli K3 Umum Kemenaker RI',
        ], [
            'training_id' => $tr3->id,
            'jenis_sertifikasi' => 'K3',
            'nomor_sertifikat' => 'K3-REG-2024-5541',
            'tanggal_terbit' => '2024-05-15',
            'masa_berlaku' => '3 Tahun',
            'tanggal_expired' => now()->addDays(25)->toDateString(), // Segera kedaluwarsa (<= 30 hari)
            'is_permanent' => false,
        ]);

        Certification::updateOrCreate([
            'employee_id' => $emp6->id,
            'nama_sertifikasi' => 'Sertifikat Pengawas Operasional Madya (POM)',
        ], [
            'jenis_sertifikasi' => 'POM',
            'nomor_sertifikat' => 'CERT-POM-2024-1120',
            'tanggal_terbit' => '2024-01-10',
            'masa_berlaku' => '3 Tahun',
            'tanggal_expired' => now()->addDays(60)->toDateString(), // Perlu perhatian (31-90 hari)
            'is_permanent' => false,
        ]);

        Certification::updateOrCreate([
            'employee_id' => $emp7->id,
            'nama_sertifikasi' => 'Sertifikat Pengawas Operasional Pertama (POP)',
        ], [
            'training_id' => $tr1->id,
            'jenis_sertifikasi' => 'POP',
            'nomor_sertifikat' => 'CERT-POP-2021-0032',
            'tanggal_terbit' => '2021-04-10',
            'masa_berlaku' => '3 Tahun',
            'tanggal_expired' => now()->subDays(12)->toDateString(), // Sudah Expired
            'is_permanent' => false,
        ]);
    }
}

