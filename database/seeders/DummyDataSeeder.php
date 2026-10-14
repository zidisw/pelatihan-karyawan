<?php

namespace Database\Seeders;

use App\Models\Certification;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Training;
use App\Models\TrainingParticipant;
use App\Models\TrainingRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $itDept = Department::where('nama_departemen', 'Teknologi Informasi')->first();
        $engDept = Department::where('nama_departemen', 'Engineering')->first();
        $hrDept = Department::where('nama_departemen', 'Human Resources')->first();

        $adminIt = User::where('email', 'admin.it@pelatihan.com')->first();
        $adminEng = User::where('email', 'admin.engineering@pelatihan.com')->first();

        // 1. Data Karyawan
        $emp1 = Employee::updateOrCreate(['nik' => 'EMP-001'], [
            'nama' => 'Budi Santoso',
            'department_id' => $itDept?->id,
            'jabatan' => 'Senior Backend Developer',
            'status_karyawan' => 'tetap',
            'email' => 'budi.santoso@perusahaan.com',
            'nomor_hp' => '081234567890',
            'tanggal_join' => '2023-02-15',
        ]);

        $emp2 = Employee::updateOrCreate(['nik' => 'EMP-002'], [
            'nama' => 'Siti Rahmawati',
            'department_id' => $itDept?->id,
            'jabatan' => 'Frontend Web Engineer',
            'status_karyawan' => 'kontrak',
            'email' => 'siti.rahmawati@perusahaan.com',
            'nomor_hp' => '081298765432',
            'tanggal_join' => '2024-01-10',
        ]);

        $emp3 = Employee::updateOrCreate(['nik' => 'EMP-003'], [
            'nama' => 'Dedi Maulana',
            'department_id' => $engDept?->id,
            'jabatan' => 'Mechanical Maintenance Engineer',
            'status_karyawan' => 'tetap',
            'email' => 'dedi.maulana@perusahaan.com',
            'nomor_hp' => '082155667788',
            'tanggal_join' => '2022-08-01',
        ]);

        $emp4 = Employee::updateOrCreate(['nik' => 'EMP-004'], [
            'nama' => 'Maya Anggraini',
            'department_id' => $hrDept?->id,
            'jabatan' => 'Talent Acquisition & People Development',
            'status_karyawan' => 'tetap',
            'email' => 'maya.anggraini@perusahaan.com',
            'nomor_hp' => '085711223344',
            'tanggal_join' => '2023-06-20',
        ]);

        $emp5 = Employee::updateOrCreate(['nik' => 'EMP-005'], [
            'nama' => 'Rian Pratama',
            'department_id' => $engDept?->id,
            'jabatan' => 'Electrical Specialist',
            'status_karyawan' => 'kontrak',
            'email' => 'rian.pratama@perusahaan.com',
            'nomor_hp' => '087899887766',
            'tanggal_join' => '2024-03-01',
        ]);

        // 2. Data Program Pelatihan
        $tr1 = Training::updateOrCreate(['nama_pelatihan' => 'Sertifikasi Cloud Computing & DevOps'], [
            'deskripsi' => "Pelatihan intensif arsitektur cloud AWS/GCP, otomatisasi CI/CD pipeline, Docker containerization, dan implementasi infrastruktur modern untuk meningkatkan keandalan sistem.",
            'instruktur' => 'Budi Hermawan, S.Kom, AWS Solution Architect',
            'tanggal_mulai' => now()->addDays(5)->toDateString(),
            'tanggal_selesai' => now()->addDays(12)->toDateString(),
            'kuota' => 25,
            'lokasi' => 'Ruang Training Gedung A, Lt. 3 / Hybrid Zoom',
            'metode_pelaksanaan' => 'HYBRID',
            'status_pendaftaran' => 'dibuka',
        ]);

        $tr2 = Training::updateOrCreate(['nama_pelatihan' => 'Cybersecurity & Data Privacy Protection'], [
            'deskripsi' => "Fundamental keamanan siber, identifikasi kerentanan aplikasi web OWASP Top 10, perlindungan data pribadi sesuai UU PDP, dan simulasi penanganan insiden siber.",
            'instruktur' => 'Dr. Faisal Akbar, CISSP, CEH',
            'tanggal_mulai' => now()->subDays(15)->toDateString(),
            'tanggal_selesai' => now()->subDays(10)->toDateString(),
            'kuota' => 15,
            'lokasi' => 'Lab Komputer 2, Gedung Utama',
            'metode_pelaksanaan' => 'OFFLINE',
            'status_pendaftaran' => 'ditutup',
        ]);

        $tr3 = Training::updateOrCreate(['nama_pelatihan' => 'Pelatihan K3 & Keselamatan Kerja Industri'], [
            'deskripsi' => "Standar operasional prosedur Keselamatan dan Kesehatan Kerja (K3), manajemen risiko kecelakaan kerja, penanganan bahan B3, dan sertifikasi kepatuhan regulasi industri.",
            'instruktur' => 'Ir. Hendra Saputra, Ahli K3 Utama',
            'tanggal_mulai' => now()->addDays(10)->toDateString(),
            'tanggal_selesai' => now()->addDays(14)->toDateString(),
            'kuota' => 20,
            'lokasi' => 'Workshop Engineering Lantai Dasar',
            'metode_pelaksanaan' => 'OFFLINE',
            'status_pendaftaran' => 'dibuka',
        ]);

        $tr4 = Training::updateOrCreate(['nama_pelatihan' => 'Strategic HR & Performance Management'], [
            'deskripsi' => "Penyusunan KPI berbasis OKR, evaluasi kinerja karyawan 360 derajat, retensi talenta terbaik, serta strategi pengembangan karir karyawan internal.",
            'instruktur' => 'Anita Wijaya, M.Psi., HR Consultant',
            'tanggal_mulai' => now()->addDays(7)->toDateString(),
            'tanggal_selesai' => now()->addDays(9)->toDateString(),
            'kuota' => 30,
            'lokasi' => 'Online Video Conference (Zoom)',
            'metode_pelaksanaan' => 'ONLINE',
            'status_pendaftaran' => 'dibuka',
        ]);

        // 3. Data Pengajuan Pelatihan (Training Requests)
        TrainingRequest::updateOrCreate([
            'employee_id' => $emp3->id,
            'training_id' => $tr3->id,
        ], [
            'diajukan_oleh' => $adminEng?->id,
            'status' => 'pending',
            'catatan_admin' => 'Wajib untuk pemenuhan standar audit K3 pabrik kuartal ini.',
        ]);

        TrainingRequest::updateOrCreate([
            'employee_id' => $emp2->id,
            'training_id' => $tr1->id,
        ], [
            'diajukan_oleh' => $adminIt?->id,
            'status' => 'approved',
            'catatan_admin' => 'Disetujui. Membantu transisi frontend ke arsitektur container.',
        ]);

        TrainingRequest::updateOrCreate([
            'employee_id' => $emp5->id,
            'training_id' => $tr3->id,
        ], [
            'diajukan_oleh' => $adminEng?->id,
            'status' => 'pending',
            'catatan_admin' => 'Diusulkan untuk sertifikasi kelistrikan tegangan tinggi.',
        ]);

        // 4. Data Peserta Pelatihan (Training Participants)
        TrainingParticipant::updateOrCreate([
            'employee_id' => $emp1->id,
            'training_id' => $tr2->id,
        ], [
            'status_peserta' => 'selesai',
        ]);

        TrainingParticipant::updateOrCreate([
            'employee_id' => $emp2->id,
            'training_id' => $tr1->id,
        ], [
            'status_peserta' => 'sedang_pelatihan',
        ]);

        // 5. Data Sertifikasi Karyawan
        Certification::updateOrCreate([
            'employee_id' => $emp1->id,
            'nama_sertifikasi' => 'Certified Information Security Practitioner (CISP)',
        ], [
            'nomor_sertifikat' => 'CERT-IT-2025-0089',
            'tanggal_terbit' => '2025-02-01',
            'tanggal_expired' => '2028-02-01',
        ]);

        Certification::updateOrCreate([
            'employee_id' => $emp3->id,
            'nama_sertifikasi' => 'Sertifikat Ahli K3 Umum Kemenaker RI',
        ], [
            'nomor_sertifikat' => 'K3-REG-2024-5541',
            'tanggal_terbit' => '2024-05-15',
            'tanggal_expired' => '2027-05-15',
        ]);
    }
}
