<x-app-layout>
    <x-slot name="header">Riwayat Pelatihan & Sertifikasi</x-slot>

    <div class="w-full space-y-6 pb-10">

        <!-- Judul Halaman & Tombol Aksi Cepat (Tambah Sertifikat & Ekspor Laporan) -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Riwayat Pelatihan & Sertifikasi</h2>
                <p class="text-sm text-slate-500 mt-1 font-medium">
                    Pilih kategori di bawah untuk memantau data <strong>Sertifikasi</strong>, <strong>Technical Skill</strong>, dan <strong>Soft Skill</strong> beserta realisasi kehadiran dan sertifikat karyawan.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <button type="button" onclick="openAddCertModalGlobal()"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-primary-600 text-white font-bold text-xs hover:bg-primary-700 shadow-xs transition-all">
                    <i class="ph-bold ph-plus-circle text-sm"></i> + Tambah Sertifikat
                </button>

                <button type="button" onclick="exportParticipantsReport('csv')"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold text-xs hover:bg-emerald-600 hover:text-white transition-all">
                    <i class="ph-bold ph-file-csv text-sm"></i> Export Excel / CSV
                </button>

                <button type="button" onclick="exportParticipantsReport('print')"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-800 text-white font-bold text-xs hover:bg-slate-700 transition-all">
                    <i class="ph-bold ph-printer text-sm"></i> Cetak / PDF
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-bold flex items-center gap-2">
                <i class="ph-fill ph-check-circle text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-bold">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- ========================================================== -->
        <!-- 1. TIGA WIDGET / CARD UTAMA (SERTIFIKASI, TECHNICAL, SOFT) -->
        <!-- ========================================================== -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Widget 1: Sertifikasi -->
            <button type="button" onclick="switchCategory('sertifikasi')" id="card-sertifikasi"
                class="category-card text-left p-6 rounded-2xl border-2 transition-all duration-200 bg-surface shadow-sm hover:shadow-md flex items-center justify-between group">
                <div class="space-y-1">
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                        <i class="ph-fill ph-seal-check"></i> Kategori 1
                    </span>
                    <h3 class="text-xl font-extrabold text-slate-800 pt-1">Sertifikasi</h3>
                    <p class="text-xs text-slate-400 font-medium">POP, POM, POU, K3 & Lisensi Resmi</p>
                </div>
                <div class="text-right">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl font-extrabold ml-auto mb-1 group-hover:scale-105 transition-transform">
                        {{ $certifications->count() }}
                    </div>
                    <span class="text-[11px] font-bold text-slate-400">Data Sertifikat</span>
                </div>
            </button>

            <!-- Widget 2: Technical Skill -->
            <button type="button" onclick="switchCategory('technical')" id="card-technical"
                class="category-card text-left p-6 rounded-2xl border-2 transition-all duration-200 bg-surface shadow-sm hover:shadow-md flex items-center justify-between group">
                <div class="space-y-1">
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-widest text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">
                        <i class="ph-fill ph-wrench"></i> Kategori 2
                    </span>
                    <h3 class="text-xl font-extrabold text-slate-800 pt-1">Technical Skill</h3>
                    <p class="text-xs text-slate-400 font-medium">Kompetensi Teknis & Operasional</p>
                </div>
                <div class="text-right">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl font-extrabold ml-auto mb-1 group-hover:scale-105 transition-transform">
                        {{ $technicalSkills->count() }}
                    </div>
                    <span class="text-[11px] font-bold text-slate-400">Data Peserta</span>
                </div>
            </button>

            <!-- Widget 3: Soft Skill -->
            <button type="button" onclick="switchCategory('soft')" id="card-soft"
                class="category-card text-left p-6 rounded-2xl border-2 transition-all duration-200 bg-surface shadow-sm hover:shadow-md flex items-center justify-between group">
                <div class="space-y-1">
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-widest text-purple-600 bg-purple-50 px-2.5 py-1 rounded-full">
                        <i class="ph-fill ph-users-three"></i> Kategori 3
                    </span>
                    <h3 class="text-xl font-extrabold text-slate-800 pt-1">Soft Skill</h3>
                    <p class="text-xs text-slate-400 font-medium">Leadership, Komunikasi & Manajemen</p>
                </div>
                <div class="text-right">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl font-extrabold ml-auto mb-1 group-hover:scale-105 transition-transform">
                        {{ $softSkills->count() }}
                    </div>
                    <span class="text-[11px] font-bold text-slate-400">Data Peserta</span>
                </div>
            </button>

        </div>

        <!-- ========================================================== -->
        <!-- 2. CONTAINER UTAMA FILTER & TABEL DINAMIS                  -->
        <!-- ========================================================== -->
        <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 space-y-6">

            <!-- Header Kategori Aktif -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-100">
                <div>
                    <h3 id="activeTableTitle" class="text-xl font-extrabold text-slate-800">Tabel Riwayat Sertifikasi</h3>
                    <p id="activeTableSubtitle" class="text-xs text-slate-400 mt-0.5">Menampilkan daftar sertifikat resmi karyawan beserta masa berlaku dan pengingat kadaluarsa (Time Reminder).</p>
                </div>
                <button type="button" onclick="resetAllFilters()" class="self-start sm:self-auto inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                    <i class="ph-bold ph-arrow-counter-clockwise"></i> Reset Filter
                </button>
            </div>

            <!-- Widget Kecil Filter Departemen (All + Setiap Departemen) -->
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2.5">Filter Departemen:</p>
                <div class="flex flex-wrap items-center gap-2" id="departmentPills">
                    <button type="button" onclick="selectDepartment('ALL')" data-dept="ALL"
                        class="dept-pill px-4 py-2 rounded-xl text-xs font-extrabold border transition-all flex items-center gap-2">
                        <span>All</span>
                        <span id="count-dept-ALL" class="px-1.5 py-0.5 rounded-md text-[10px] bg-white/20">0</span>
                    </button>

                    @foreach($departments as $dept)
                        <button type="button" onclick="selectDepartment('{{ $dept->nama_departemen }}')" data-dept="{{ $dept->nama_departemen }}"
                            class="dept-pill px-4 py-2 rounded-xl text-xs font-bold border transition-all flex items-center gap-2">
                            <span>{{ $dept->nama_departemen }}</span>
                            <span id="count-dept-{{ $dept->nama_departemen }}" class="px-1.5 py-0.5 rounded-md text-[10px] bg-slate-100 text-slate-500">0</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Bar Searching & Filter Dropdown -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 pt-2">

                <!-- Input Searching (Ketik Langsung) -->
                <div id="searchBoxWrapper" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/60 focus-within:bg-white focus-within:border-primary-500 transition-colors">
                    <i class="ph-bold ph-magnifying-glass text-slate-400 text-base shrink-0"></i>
                    <input type="text" id="searchInput" oninput="applyFilters()"
                        placeholder="Ketik NIK, Nama, Job Title..."
                        class="w-full bg-transparent border-0 p-0 text-xs font-semibold text-slate-700 focus:ring-0 outline-none placeholder:text-slate-400">
                </div>

                <!-- Dropdown 1: Level Jabatan -->
                <div>
                    <select id="filterLevelJabatan" onchange="applyFilters()"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-xs font-semibold text-slate-700 focus:bg-white focus:border-primary-500 focus:ring-0 outline-none transition-colors cursor-pointer">
                        <option value="">Semua Level Jabatan</option>
                        @foreach($levelJabatanOptions as $lvl)
                            <option value="{{ $lvl }}">{{ $lvl }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown 2: Nama Sertifikasi / Pelatihan -->
                <div>
                    <select id="filterNamaPelatihan" onchange="applyFilters()"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-xs font-semibold text-slate-700 focus:bg-white focus:border-primary-500 focus:ring-0 outline-none transition-colors cursor-pointer">
                        <option value="">Semua Sertifikasi / Pelatihan</option>
                    </select>
                </div>

                <!-- Dropdown 3: Time Reminder (Khusus Sertifikasi) -->
                <div id="filterTimeReminderWrapper">
                    <select id="filterTimeReminder" onchange="applyFilters()"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-xs font-semibold text-slate-700 focus:bg-white focus:border-primary-500 focus:ring-0 outline-none transition-colors cursor-pointer">
                        <option value="">Semua Time Reminder</option>
                        <option value="expired">Sudah Expired (Kadaluarsa)</option>
                        <option value="h_30">Segera Expired (&lt;= 30 Hari)</option>
                        <option value="h_90">Perlu Perhatian (31 - 90 Hari)</option>
                        <option value="aktif">Masih Berlaku (&gt; 90 Hari)</option>
                        <option value="permanen">Permanen</option>
                    </select>
                </div>

            </div>

            <!-- ====================================================== -->
            <!-- TABEL A: SERTIFIKASI (12 KOLOM + AKSI DOKUMEN)         -->
            <!-- ====================================================== -->
            <div id="tableWrapperSertifikasi" class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1320px]">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-bold uppercase tracking-widest border-b border-slate-100 bg-slate-50/60">
                            <th class="py-3.5 px-3 text-center w-12">No</th>
                            <th class="py-3.5 px-3" style="white-space: nowrap;">NIK</th>
                            <th class="py-3.5 px-3">Nama</th>
                            <th class="py-3.5 px-3">Department</th>
                            <th class="py-3.5 px-3" style="white-space: nowrap;">Level Jabatan</th>
                            <th class="py-3.5 px-3">Job Title</th>
                            <th class="py-3.5 px-3">Nama Sertifikat/Pelatihan</th>
                            <th class="py-3.5 px-3" style="white-space: nowrap;">Nomor Regist</th>
                            <th class="py-3.5 px-3" style="white-space: nowrap;">Tanggal Sertifikat Terbit</th>
                            <th class="py-3.5 px-3" style="white-space: nowrap;">Masa Berlaku</th>
                            <th class="py-3.5 px-3" style="white-space: nowrap;">Expired</th>
                            <th class="py-3.5 px-3 text-center" style="white-space: nowrap;">Time Reminder</th>
                            <th class="py-3.5 px-3 text-right" style="white-space: nowrap;">Aksi & Dokumen</th>
                        </tr>
                    </thead>
                    <tbody id="tbodySertifikasi" class="divide-y divide-slate-100 text-xs">
                        <!-- Diisi secara dinamis oleh script di bawah -->
                    </tbody>
                </table>
            </div>

            <!-- ====================================================== -->
            <!-- TABEL B: TECHNICAL SKILL & SOFT SKILL + REALISASI      -->
            <!-- ====================================================== -->
            <div id="tableWrapperSkill" class="overflow-x-auto hidden">
                <table class="w-full text-left border-collapse min-w-[1150px]">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-bold uppercase tracking-widest border-b border-slate-100 bg-slate-50/60">
                            <th class="py-3.5 px-4 text-center w-12">No</th>
                            <th class="py-3.5 px-4" style="white-space: nowrap;">NIK</th>
                            <th class="py-3.5 px-4">Nama</th>
                            <th class="py-3.5 px-4">Department</th>
                            <th class="py-3.5 px-4" style="white-space: nowrap;">Level Jabatan</th>
                            <th class="py-3.5 px-4">Job Title</th>
                            <th class="py-3.5 px-4">Nama Sertifikat/Pelatihan</th>
                            <th class="py-3.5 px-4" style="white-space: nowrap;">Tanggal Pelatihan</th>
                            <th class="py-3.5 px-4" style="white-space: nowrap;">Kehadiran & Status</th>
                            <th class="py-3.5 px-4 text-right" style="white-space: nowrap;">Aksi Realisasi</th>
                        </tr>
                    </thead>
                    <tbody id="tbodySkill" class="divide-y divide-slate-100 text-xs">
                        <!-- Diisi secara dinamis oleh script di bawah -->
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <!-- ========================================================== -->
    <!-- MODAL 1: UPDATE REALISASI & PENYELESAIAN PELATIHAN (BR-09) -->
    <!-- ========================================================== -->
    <div id="modalRealisasi" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 w-full max-w-lg overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Update Realisasi & Evaluasi Pelatihan</h3>
                    <p id="realisasiSubtitle" class="text-xs text-slate-400 mt-0.5">-</p>
                </div>
                <button type="button" onclick="closeRealisasiModal()" class="p-2 rounded-xl text-slate-400 hover:bg-slate-200 hover:text-slate-700">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <form id="formRealisasi" method="POST" class="p-6 space-y-4 text-xs">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Kehadiran Aktual <span class="text-rose-500">*</span></label>
                        <select name="kehadiran" id="realisasiKehadiran" onchange="syncRealisasiBr09()"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                            <option value="belum_dicatat">Belum Dicatat</option>
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="tidak_hadir">Tidak Hadir</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Status Penyelesaian <span class="text-rose-500">*</span></label>
                        <select name="status_peserta" id="realisasiStatus"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                            <option value="disetujui">Disetujui</option>
                            <option value="terjadwal">Terjadwal</option>
                            <option value="sedang_pelatihan">Sedang Pelatihan</option>
                            <option value="selesai">Selesai / Lulus</option>
                            <option value="tidak_hadir">Tidak Hadir</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                </div>

                <div class="p-3 rounded-xl bg-amber-50/70 border border-amber-200/70 text-[11px] text-amber-800 font-medium">
                    <i class="ph-fill ph-info mr-1"></i>
                    <strong>Aturan BR-09:</strong> Peserta yang memilih kehadiran <em>Tidak Hadir</em> otomatis tercatat berstatus <em>Tidak Hadir</em> dan tidak dapat ditandai <em>Selesai</em>.
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Hasil Evaluasi / Nilai / Catatan Pasca-Pelatihan</label>
                    <textarea name="hasil_evaluasi" id="realisasiEvaluasi" rows="3"
                              placeholder="Contoh: Lulus Post-Test dengan skor 88/100, direkomendasikan lanjut sertifikasi."
                              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeRealisasiModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary-600 text-white font-bold hover:bg-primary-700 shadow-xs">
                        Simpan Realisasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- MODAL 2: TAMBAH SERTIFIKAT KARYAWAN (ISSUE #8 & #9)        -->
    <!-- ========================================================== -->
    <div id="modalAddCertGlobal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 w-full max-w-xl overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Tambah / Terbitkan Sertifikat Karyawan</h3>
                    <p class="text-xs text-slate-400">Catat sertifikat resmi beserta masa berlaku dan unggah file sertifikat</p>
                </div>
                <button type="button" onclick="closeAddCertModalGlobal()" class="p-2 rounded-xl text-slate-400 hover:bg-slate-200 hover:text-slate-700">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <form action="{{ route('hrd.certifications.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 text-xs">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Pilih Karyawan <span class="text-rose-500">*</span></label>
                        <select name="employee_id" id="globalCertEmployeeId" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($employees ?? [] as $empOpt)
                                <option value="{{ $empOpt->id }}">{{ $empOpt->nik }} — {{ $empOpt->nama }} ({{ $empOpt->department?->nama_departemen ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Program Pelatihan Terkait</label>
                        <select name="training_id" id="globalCertTrainingId"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                            <option value="">-- Tidak Terkait / Mandiri --</option>
                            @foreach($trainings ?? [] as $trOpt)
                                <option value="{{ $trOpt->id }}">{{ $trOpt->nama_pelatihan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Sertifikat / Lisensi <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_sertifikasi" id="globalCertNama" required
                               placeholder="Contoh: Sertifikasi POP BNSP"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jenis Sertifikasi <span class="text-rose-500">*</span></label>
                        <select name="jenis_sertifikasi" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                            <option value="POP">POP (Pengawas Operasional Pertama)</option>
                            <option value="POM">POM (Pengawas Operasional Madya)</option>
                            <option value="POU">POU (Pengawas Operasional Utama)</option>
                            <option value="K3">K3 / Keselamatan Kerja</option>
                            <option value="Lainnya">Lainnya / Kompetensi Teknis</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nomor Registrasi</label>
                        <input type="text" name="nomor_sertifikat" placeholder="Contoh: REG-2026-001"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Terbit</label>
                        <input type="date" name="tanggal_terbit" value="{{ date('Y-m-d') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Expired</label>
                        <input type="date" name="tanggal_expired" id="globalCertExpired"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 items-center">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Masa Berlaku</label>
                        <input type="text" name="masa_berlaku" placeholder="Misal: 3 Tahun / 5 Tahun"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>

                    <div class="flex items-center gap-2 pt-4">
                        <input type="checkbox" name="is_permanent" id="globalCertPermanent" value="1"
                               onchange="document.getElementById('globalCertExpired').disabled = this.checked"
                               class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        <label for="globalCertPermanent" class="font-bold text-slate-700 cursor-pointer">
                            Berlaku Permanen (Seumur Hidup)
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Unggah Dokumen Sertifikat (PDF / JPG / PNG, Maks 5MB)</label>
                    <input type="file" name="file_sertifikat" accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-600">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeAddCertModalGlobal()" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary-600 text-white font-bold hover:bg-primary-700 shadow-xs">
                        Simpan Sertifikat
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ========================================================== -->
    <!-- MODAL 3: EDIT / PERBARUI SERTIFIKAT (ISSUE #8)             -->
    <!-- ========================================================== -->
    <div id="modalEditCertGlobal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 w-full max-w-xl overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Edit / Perbarui Sertifikat Karyawan</h3>
                    <p id="editCertGlobalSubtitle" class="text-xs text-slate-400">-</p>
                </div>
                <button type="button" onclick="closeEditCertModalGlobal()" class="p-2 rounded-xl text-slate-400 hover:bg-slate-200 hover:text-slate-700">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <form id="formEditCertGlobal" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 text-xs">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Sertifikat / Lisensi <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_sertifikasi" id="editGlobalNama" required
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jenis Sertifikasi <span class="text-rose-500">*</span></label>
                        <select name="jenis_sertifikasi" id="editGlobalJenis" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                            <option value="POP">POP (Pengawas Operasional Pertama)</option>
                            <option value="POM">POM (Pengawas Operasional Madya)</option>
                            <option value="POU">POU (Pengawas Operasional Utama)</option>
                            <option value="K3">K3 / Keselamatan Kerja</option>
                            <option value="Lainnya">Lainnya / Kompetensi Teknis</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nomor Registrasi Sertifikat</label>
                        <input type="text" name="nomor_sertifikat" id="editGlobalNomor"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Program Pelatihan Terkait</label>
                        <select name="training_id" id="editGlobalTrainingId"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                            <option value="">-- Tidak Terkait / Mandiri --</option>
                            @foreach($trainings ?? [] as $trOpt)
                                <option value="{{ $trOpt->id }}">{{ $trOpt->nama_pelatihan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Terbit</label>
                        <input type="date" name="tanggal_terbit" id="editGlobalTerbit"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Masa Berlaku</label>
                        <input type="text" name="masa_berlaku" id="editGlobalMasaBerlaku"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Expired</label>
                        <input type="date" name="tanggal_expired" id="editGlobalExpired"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_permanent" id="editGlobalPermanent" value="1"
                           onchange="document.getElementById('editGlobalExpired').disabled = this.checked"
                           class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                    <label for="editGlobalPermanent" class="font-bold text-slate-700 cursor-pointer">
                        Sertifikat berlaku seumur hidup (Permanen)
                    </label>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Ganti File Sertifikat (Opsional — PDF / JPG / PNG)</label>
                    <input type="file" name="file_sertifikat" accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-600">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeEditCertModalGlobal()" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-500 text-white font-bold hover:bg-amber-600 shadow-xs">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Form Tersembunyi untuk Hapus Sertifikat dari Tabel Dinamis -->
    <form id="formDeleteCertDynamic" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- Script Interaktif untuk Pergantian Widget, Filter, Realisasi, Sertifikat, & Ekspor -->
    <script>
        const datasets = {
            sertifikasi: @json($certifications),
            technical: @json($technicalSkills),
            soft: @json($softSkills),
        };

        const departmentsList = @json($departments->pluck('nama_departemen'));
        const baseExportUrl = "{{ route('hrd.export.participants') }}";

        let activeCategory = 'sertifikasi';
        let activeDepartment = 'ALL';

        function escapeHtml(value) {
            if (value === null || value === undefined) return '-';
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function switchCategory(category) {
            activeCategory = category;

            // Update style pada 3 Widget Utama
            const cards = {
                sertifikasi: document.getElementById('card-sertifikasi'),
                technical: document.getElementById('card-technical'),
                soft: document.getElementById('card-soft'),
            };

            Object.keys(cards).forEach(key => {
                const el = cards[key];
                if (!el) return;
                if (key === category) {
                    el.classList.add('border-primary-600', 'ring-4', 'ring-primary-50');
                    el.classList.remove('border-slate-100');
                } else {
                    el.classList.remove('border-primary-600', 'ring-4', 'ring-primary-50');
                    el.classList.add('border-slate-100');
                }
            });

            // Update Judul Tabel & Visibilitas Filter Time Reminder
            const titleEl = document.getElementById('activeTableTitle');
            const subtitleEl = document.getElementById('activeTableSubtitle');
            const reminderWrapper = document.getElementById('filterTimeReminderWrapper');
            const searchWrapper = document.getElementById('searchBoxWrapper');
            const tableSertifikasi = document.getElementById('tableWrapperSertifikasi');
            const tableSkill = document.getElementById('tableWrapperSkill');

            if (category === 'sertifikasi') {
                titleEl.textContent = 'Tabel Riwayat Sertifikasi';
                subtitleEl.textContent = 'Menampilkan daftar sertifikat resmi karyawan beserta masa berlaku dan pengingat kadaluarsa (Time Reminder).';
                reminderWrapper.classList.remove('hidden');
                searchWrapper.classList.remove('md:col-span-2');
                tableSertifikasi.classList.remove('hidden');
                tableSkill.classList.add('hidden');
            } else if (category === 'technical') {
                titleEl.textContent = 'Tabel Riwayat Technical Skill';
                subtitleEl.textContent = 'Menampilkan daftar karyawan yang mengikuti pelatihan kompetensi teknis & operasional beserta realisasi kehadiran.';
                reminderWrapper.classList.add('hidden');
                searchWrapper.classList.add('md:col-span-2');
                tableSertifikasi.classList.add('hidden');
                tableSkill.classList.remove('hidden');
            } else {
                titleEl.textContent = 'Tabel Riwayat Soft Skill';
                subtitleEl.textContent = 'Menampilkan daftar karyawan yang mengikuti pelatihan kepemimpinan, komunikasi, dan manajerial beserta realisasi kehadiran.';
                reminderWrapper.classList.add('hidden');
                searchWrapper.classList.add('md:col-span-2');
                tableSertifikasi.classList.add('hidden');
                tableSkill.classList.remove('hidden');
            }

            document.getElementById('filterNamaPelatihan').value = '';
            document.getElementById('filterTimeReminder').value = '';

            populateTrainingDropdown();
            updateDepartmentCounts();
            applyFilters();
        }

        function selectDepartment(dept) {
            activeDepartment = dept;

            document.querySelectorAll('.dept-pill').forEach(btn => {
                const isSelected = btn.getAttribute('data-dept') === dept;
                const badge = btn.querySelector('span:last-child');
                if (isSelected) {
                    btn.className = 'dept-pill px-4 py-2 rounded-xl text-xs font-extrabold border transition-all flex items-center gap-2 bg-primary-600 text-white border-primary-600 shadow-sm';
                    if (badge) badge.className = 'px-1.5 py-0.5 rounded-md text-[10px] bg-white/20 text-white';
                } else {
                    btn.className = 'dept-pill px-4 py-2 rounded-xl text-xs font-bold border transition-all flex items-center gap-2 bg-white text-slate-600 border-slate-200 hover:bg-slate-50';
                    if (badge) badge.className = 'px-1.5 py-0.5 rounded-md text-[10px] bg-slate-100 text-slate-500';
                }
            });

            applyFilters();
        }

        function updateDepartmentCounts() {
            const currentData = datasets[activeCategory] || [];
            const allBadge = document.getElementById('count-dept-ALL');
            if (allBadge) allBadge.textContent = currentData.length;

            departmentsList.forEach(deptName => {
                const count = currentData.filter(item => item.department === deptName).length;
                const el = document.getElementById('count-dept-' + deptName);
                if (el) el.textContent = count;
            });
        }

        function populateTrainingDropdown() {
            const currentData = datasets[activeCategory] || [];
            const select = document.getElementById('filterNamaPelatihan');
            const uniqueNames = [...new Set(currentData.map(item => item.nama_pelatihan).filter(Boolean))].sort();

            select.innerHTML = '<option value="">Semua Sertifikasi / Pelatihan</option>';
            uniqueNames.forEach(name => {
                const opt = document.createElement('option');
                opt.value = name;
                opt.textContent = name;
                select.appendChild(opt);
            });
        }

        function resetAllFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterLevelJabatan').value = '';
            document.getElementById('filterNamaPelatihan').value = '';
            document.getElementById('filterTimeReminder').value = '';
            selectDepartment('ALL');
        }

        function applyFilters() {
            const currentData = datasets[activeCategory] || [];
            const search = (document.getElementById('searchInput').value || '').toLowerCase().trim();
            const levelJabatan = document.getElementById('filterLevelJabatan').value;
            const namaPelatihan = document.getElementById('filterNamaPelatihan').value;
            const timeReminder = document.getElementById('filterTimeReminder').value;

            const filtered = currentData.filter(row => {
                if (activeDepartment !== 'ALL' && row.department !== activeDepartment) {
                    return false;
                }
                if (levelJabatan && row.level_jabatan !== levelJabatan) {
                    return false;
                }
                if (namaPelatihan && row.nama_pelatihan !== namaPelatihan) {
                    return false;
                }
                if (activeCategory === 'sertifikasi' && timeReminder && row.reminder_code !== timeReminder) {
                    return false;
                }
                if (search) {
                    const haystack = [
                        row.nik,
                        row.nama,
                        row.department,
                        row.level_jabatan,
                        row.job_title,
                        row.nama_pelatihan,
                        row.nomor_regist || '',
                    ].join(' ').toLowerCase();

                    if (!haystack.includes(search)) {
                        return false;
                    }
                }
                return true;
            });

            if (activeCategory === 'sertifikasi') {
                renderSertifikasiTable(filtered);
            } else {
                renderSkillTable(filtered);
            }
        }

        function renderSertifikasiTable(rows) {
            const tbody = document.getElementById('tbodySertifikasi');
            if (!rows.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="13" class="py-12 text-center text-slate-400 font-medium">
                            Tidak ada data sertifikasi yang sesuai dengan filter pencarian.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = rows.map((row, idx) => `
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-4 px-3 text-center font-bold text-slate-400">${idx + 1}</td>
                    <td class="py-4 px-3 font-bold text-slate-600" style="white-space: nowrap;">${escapeHtml(row.nik)}</td>
                    <td class="py-4 px-3 font-extrabold text-slate-800" style="white-space: nowrap;">${escapeHtml(row.nama)}</td>
                    <td class="py-4 px-3" style="white-space: nowrap;">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-primary-50 text-primary-700 border border-primary-100 uppercase">
                            ${escapeHtml(row.department)}
                        </span>
                    </td>
                    <td class="py-4 px-3 font-semibold text-slate-700" style="white-space: nowrap;">${escapeHtml(row.level_jabatan)}</td>
                    <td class="py-4 px-3 text-slate-600">${escapeHtml(row.job_title)}</td>
                    <td class="py-4 px-3 font-bold text-slate-800">${escapeHtml(row.nama_pelatihan)}</td>
                    <td class="py-4 px-3 font-mono text-[11px] font-semibold text-slate-600" style="white-space: nowrap;">${escapeHtml(row.nomor_regist)}</td>
                    <td class="py-4 px-3 text-slate-600" style="white-space: nowrap;">${escapeHtml(row.tanggal_terbit)}</td>
                    <td class="py-4 px-3 font-semibold text-slate-700" style="white-space: nowrap;">${escapeHtml(row.masa_berlaku)}</td>
                    <td class="py-4 px-3 font-semibold text-slate-700" style="white-space: nowrap;">${escapeHtml(row.expired)}</td>
                    <td class="py-4 px-3 text-center" style="white-space: nowrap;">
                        <span style="white-space: nowrap;" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold border ${row.reminder_badge}">
                            <span class="w-1.5 h-1.5 rounded-full ${row.reminder_dot || 'bg-slate-400'}"></span>
                            <span>${escapeHtml(row.reminder_label)}</span>
                        </span>
                    </td>
                    <td class="py-4 px-3 text-right" style="white-space: nowrap;">
                        <div class="inline-flex items-center justify-end gap-1.5">
                            <a href="${escapeHtml(row.download_url)}?inline=1" target="_blank"
                               class="px-2.5 py-1.5 rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-600 hover:text-white font-bold text-[11px] transition-colors">
                                <i class="ph-bold ph-file-arrow-down"></i> Unduh
                            </a>
                            <button type="button" onclick="openEditCertById(${row.id})"
                                    class="px-2.5 py-1.5 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-500 hover:text-white font-bold text-[11px] transition-colors">
                                <i class="ph-bold ph-pencil-simple"></i> Edit
                            </button>
                            <button type="button" onclick="deleteCertById(${row.id})"
                                    class="px-2.5 py-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-bold text-[11px] transition-colors">
                                <i class="ph-bold ph-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function renderSkillTable(rows) {
            const tbody = document.getElementById('tbodySkill');
            if (!rows.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="10" class="py-12 text-center text-slate-400 font-medium">
                            Tidak ada data pelatihan pada kategori ini yang sesuai dengan filter pencarian.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = rows.map((row, idx) => {
                const statusLabel = (row.status_peserta || 'disetujui').replace(/_/g, ' ').toUpperCase();
                const kehadiranLabel = (row.kehadiran || 'belum_dicatat').replace(/_/g, ' ').toUpperCase();

                let statusBadge = 'bg-blue-50 text-blue-700 border-blue-200';
                if (row.status_peserta === 'selesai' || row.status_peserta === 'lulus') {
                    statusBadge = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                } else if (row.status_peserta === 'tidak_hadir' || row.status_peserta === 'dibatalkan') {
                    statusBadge = 'bg-rose-50 text-rose-700 border-rose-200';
                } else if (row.status_peserta === 'sedang_pelatihan' || row.status_peserta === 'terjadwal') {
                    statusBadge = 'bg-amber-50 text-amber-700 border-amber-200';
                }

                return `
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-4 px-4 text-center font-bold text-slate-400">${idx + 1}</td>
                        <td class="py-4 px-4 font-bold text-slate-600" style="white-space: nowrap;">${escapeHtml(row.nik)}</td>
                        <td class="py-4 px-4 font-extrabold text-slate-800" style="white-space: nowrap;">${escapeHtml(row.nama)}</td>
                        <td class="py-4 px-4" style="white-space: nowrap;">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-primary-50 text-primary-700 border border-primary-100 uppercase">
                                ${escapeHtml(row.department)}
                            </span>
                        </td>
                        <td class="py-4 px-4 font-semibold text-slate-700" style="white-space: nowrap;">${escapeHtml(row.level_jabatan)}</td>
                        <td class="py-4 px-4 text-slate-600">${escapeHtml(row.job_title)}</td>
                        <td class="py-4 px-4 font-bold text-slate-800">${escapeHtml(row.nama_pelatihan)}</td>
                        <td class="py-4 px-4 font-semibold text-slate-600" style="white-space: nowrap;">
                            <i class="ph-bold ph-calendar-blank text-slate-400 mr-1"></i>${escapeHtml(row.tanggal_pelatihan)}
                        </td>
                        <td class="py-4 px-4" style="white-space: nowrap;">
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex self-start px-2.5 py-0.5 rounded-full text-[10px] font-bold border ${statusBadge}">
                                    ${escapeHtml(statusLabel)}
                                </span>
                                <span class="text-[10px] text-slate-500 font-semibold">
                                    Kehadiran: <strong>${escapeHtml(kehadiranLabel)}</strong>
                                </span>
                                ${row.hasil_evaluasi ? `<span class="text-[10px] text-emerald-700 italic truncate max-w-[180px]" title="${escapeHtml(row.hasil_evaluasi)}">Eval: ${escapeHtml(row.hasil_evaluasi)}</span>` : ''}
                            </div>
                        </td>
                        <td class="py-4 px-4 text-right" style="white-space: nowrap;">
                            <div class="inline-flex items-center justify-end gap-1.5">
                                <button type="button" onclick="openRealisasiById(${row.id})"
                                        class="px-2.5 py-1.5 rounded-lg bg-slate-800 text-white hover:bg-slate-700 font-bold text-[11px] transition-colors">
                                    <i class="ph-bold ph-check-square-offset"></i> Realisasi
                                </button>
                                <button type="button" onclick="openAddCertModalGlobal(${row.employee_id || 'null'}, ${row.training_id || 'null'}, '${escapeHtml(row.nama_pelatihan)}')"
                                        class="px-2.5 py-1.5 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 font-bold text-[11px] transition-colors">
                                    + Sertifikat
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function openRealisasiById(participantId) {
            const allSkillRows = [...(datasets.technical || []), ...(datasets.soft || [])];
            const row = allSkillRows.find(r => Number(r.id) === Number(participantId));
            if (!row) return;

            document.getElementById('formRealisasi').action = row.update_status_url;
            document.getElementById('realisasiSubtitle').textContent = `${row.nama} (${row.nik}) — ${row.nama_pelatihan}`;
            document.getElementById('realisasiKehadiran').value = row.kehadiran || 'belum_dicatat';
            document.getElementById('realisasiStatus').value = row.status_peserta === 'lulus' ? 'selesai' : (row.status_peserta || 'disetujui');
            document.getElementById('realisasiEvaluasi').value = row.hasil_evaluasi || '';

            const modal = document.getElementById('modalRealisasi');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function syncRealisasiBr09() {
            const kehadiran = document.getElementById('realisasiKehadiran').value;
            const statusSelect = document.getElementById('realisasiStatus');
            if (kehadiran === 'tidak_hadir') {
                statusSelect.value = 'tidak_hadir';
            } else if (kehadiran === 'hadir' && statusSelect.value === 'tidak_hadir') {
                statusSelect.value = 'selesai';
            }
        }

        function closeRealisasiModal() {
            const modal = document.getElementById('modalRealisasi');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openAddCertModalGlobal(empId = null, trainingId = null, namaPelatihan = '') {
            const empSelect = document.getElementById('globalCertEmployeeId');
            const trSelect = document.getElementById('globalCertTrainingId');
            const namaInput = document.getElementById('globalCertNama');

            if (empSelect) empSelect.value = empId ? String(empId) : '';
            if (trSelect) trSelect.value = trainingId ? String(trainingId) : '';
            if (namaInput && namaPelatihan) namaInput.value = namaPelatihan;

            const modal = document.getElementById('modalAddCertGlobal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeAddCertModalGlobal() {
            const modal = document.getElementById('modalAddCertGlobal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openEditCertById(certId) {
            const cert = (datasets.sertifikasi || []).find(c => Number(c.id) === Number(certId));
            if (!cert) return;

            document.getElementById('formEditCertGlobal').action = cert.update_url;
            document.getElementById('editCertGlobalSubtitle').textContent = `${cert.nama} (${cert.nik}) — ${cert.nama_pelatihan}`;
            document.getElementById('editGlobalNama').value = cert.nama_pelatihan || '';
            document.getElementById('editGlobalJenis').value = cert.jenis_sertifikasi || 'Lainnya';
            document.getElementById('editGlobalNomor').value = cert.nomor_regist !== '-' ? (cert.nomor_regist || '') : '';
            document.getElementById('editGlobalTrainingId').value = cert.training_id ? String(cert.training_id) : '';
            document.getElementById('editGlobalTerbit').value = cert.raw_tanggal_terbit || '';
            document.getElementById('editGlobalMasaBerlaku').value = cert.masa_berlaku !== '-' ? (cert.masa_berlaku || '') : '';
            document.getElementById('editGlobalExpired').value = cert.raw_tanggal_expired || '';

            const permCheckbox = document.getElementById('editGlobalPermanent');
            permCheckbox.checked = !!cert.is_permanent;
            document.getElementById('editGlobalExpired').disabled = !!cert.is_permanent;

            const modal = document.getElementById('modalEditCertGlobal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditCertModalGlobal() {
            const modal = document.getElementById('modalEditCertGlobal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function deleteCertById(certId) {
            const cert = (datasets.sertifikasi || []).find(c => Number(c.id) === Number(certId));
            if (!cert) return;
            if (!confirm(`Hapus sertifikat "${cert.nama_pelatihan}" milik ${cert.nama}?`)) return;

            const form = document.getElementById('formDeleteCertDynamic');
            form.action = cert.destroy_url;
            form.submit();
        }

        function exportParticipantsReport(format) {
            const params = new URLSearchParams();
            params.set('category', activeCategory);
            params.set('format', format);

            if (activeDepartment && activeDepartment !== 'ALL') {
                params.set('department', activeDepartment);
            }
            const levelJabatan = document.getElementById('filterLevelJabatan').value;
            if (levelJabatan) params.set('level_jabatan', levelJabatan);

            const reminder = document.getElementById('filterTimeReminder').value;
            if (activeCategory === 'sertifikasi' && reminder) {
                params.set('reminder', reminder);
            }

            const search = document.getElementById('searchInput').value;
            if (search) params.set('search', search);

            const targetUrl = `${baseExportUrl}?${params.toString()}`;
            if (format === 'print') {
                window.open(targetUrl, '_blank');
            } else {
                window.location.href = targetUrl;
            }
        }

        // Inisialisasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            selectDepartment('ALL');
            switchCategory('sertifikasi');
        });
    </script>
</x-app-layout>
