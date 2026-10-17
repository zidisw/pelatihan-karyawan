<x-app-layout>
    <x-slot name="header">
        Detail Profil & Kompetensi Karyawan
    </x-slot>

    <div class="w-full space-y-6">

        <!-- Top Navigation & Action Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('hrd.employees.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-primary-600 transition-colors">
                <i class="ph-bold ph-arrow-left text-base"></i> Kembali ke Daftar Karyawan
            </a>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('hrd.employees.edit', $employee->id) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 font-bold text-xs shadow-xs hover:bg-amber-500 hover:text-white transition-all">
                    <i class="ph-bold ph-pencil-simple text-sm"></i> Edit Data Karyawan
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-bold flex items-center gap-2">
                <i class="ph-fill ph-check-circle text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @php
            $nameParts = explode(' ', trim($employee->nama));
            $initials = isset($nameParts[1])
                ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                : strtoupper(substr($nameParts[0], 0, 2));
            $isAktif = strtolower($employee->status_aktif ?? 'aktif') === 'aktif';
            $requestsList = $allRequests ?? $employee->trainingRequests;
        @endphp

        <!-- Hero Profile Card -->
        <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="w-full h-24 bg-gradient-to-r from-slate-900 via-primary-900 to-indigo-900 absolute top-0 left-0"></div>

            <div class="relative pt-8 flex flex-col lg:flex-row lg:items-end justify-between gap-6">
                <div class="flex flex-col sm:flex-row sm:items-end gap-5">
                    <div class="relative inline-block shrink-0">
                        <div class="w-24 h-24 rounded-3xl bg-white p-1.5 shadow-lg">
                            <div class="w-full h-full rounded-2xl bg-primary-600 text-white font-extrabold text-2xl flex items-center justify-center">
                                {{ $initials }}
                            </div>
                        </div>
                        <span class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full {{ $isAktif ? 'bg-emerald-500' : 'bg-rose-500' }} border-2 border-white"
                              title="Status: {{ ucfirst($employee->status_aktif ?? 'aktif') }}"></span>
                    </div>

                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ $employee->nama }}</h2>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $isAktif ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                {{ strtoupper($employee->status_aktif ?? 'AKTIF') }}
                            </span>
                        </div>
                        <p class="text-xs font-semibold text-slate-500 mt-1">
                            NIK: <strong class="text-slate-700">{{ $employee->nik }}</strong>
                            &bull; {{ $employee->department?->nama_departemen ?? 'Tanpa Departemen' }}
                            &bull; {{ $employee->level_jabatan ?? '-' }}
                            @if($employee->jabatan)
                                ({{ $employee->jabatan }})
                            @endif
                        </p>
                        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5">
                            <i class="ph-bold ph-map-pin text-primary-600"></i>
                            <span>Penempatan: <strong class="text-slate-600">{{ $employee->site_unit ?? 'Head Office' }}</strong></span>
                        </p>
                    </div>
                </div>

                <!-- 4 Statistik Ringkas -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 shrink-0">
                    <div class="px-4 py-3 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Pelatihan</p>
                        <p class="text-xl font-extrabold text-slate-800 mt-0.5">{{ $totalTrainings }}</p>
                    </div>
                    <div class="px-4 py-3 rounded-2xl bg-emerald-50/60 border border-emerald-100 text-center">
                        <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Selesai / Lulus</p>
                        <p class="text-xl font-extrabold text-emerald-700 mt-0.5">{{ $completedTrainings }}</p>
                    </div>
                    <div class="px-4 py-3 rounded-2xl bg-amber-50/60 border border-amber-100 text-center">
                        <p class="text-[10px] font-bold text-amber-600 uppercase tracking-wider">Sedang Berjalan</p>
                        <p class="text-xl font-extrabold text-amber-700 mt-0.5">{{ $inProgressTrainings }}</p>
                    </div>
                    <div class="px-4 py-3 rounded-2xl bg-blue-50/60 border border-blue-100 text-center">
                        <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">Total Sertifikat</p>
                        <p class="text-xl font-extrabold text-blue-700 mt-0.5">{{ $employee->certifications->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Navigasi 3 Tab (PRD 7.2.D) -->
            <div class="mt-8 pt-4 border-t border-slate-100 flex flex-wrap gap-2" role="tablist">
                <button type="button"
                        id="btn-tab-biodata"
                        onclick="switchEmployeeTab('biodata')"
                        class="emp-tab-btn inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-extrabold transition-all bg-primary-600 text-white shadow-xs">
                    <i class="ph-bold ph-identification-card text-base"></i>
                    1. Data Diri
                </button>
                <button type="button"
                        id="btn-tab-pelatihan"
                        onclick="switchEmployeeTab('pelatihan')"
                        class="emp-tab-btn inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-extrabold transition-all bg-slate-100 text-slate-600 hover:bg-slate-200">
                    <i class="ph-bold ph-graduation-cap text-base"></i>
                    2. Riwayat Pelatihan
                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-white/20 font-extrabold">{{ $totalTrainings }}</span>
                </button>
                <button type="button"
                        id="btn-tab-sertifikat"
                        onclick="switchEmployeeTab('sertifikat')"
                        class="emp-tab-btn inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-extrabold transition-all bg-slate-100 text-slate-600 hover:bg-slate-200">
                    <i class="ph-bold ph-seal-check text-base"></i>
                    3. Sertifikat Resmi
                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-white/20 font-extrabold">{{ $employee->certifications->count() }}</span>
                </button>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- TAB 1: DATA DIRI & KEPEGAWAIAN                                    -->
        <!-- ================================================================= -->
        <div id="panel-tab-biodata" class="emp-tab-panel grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Kartu Informasi Pribadi & Kontak -->
            <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="ph-fill ph-user-circle text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-800">Informasi Pribadi & Kontak</h3>
                        <p class="text-xs text-slate-400">Identitas dasar dan saluran komunikasi karyawan</p>
                    </div>
                </div>

                <div class="space-y-4 text-xs">
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                        <span class="text-slate-400 font-semibold">Nama Lengkap</span>
                        <span class="font-bold text-slate-800">{{ $employee->nama }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                        <span class="text-slate-400 font-semibold">Nomor Induk Karyawan (NIK)</span>
                        <span class="font-bold text-slate-800">{{ $employee->nik }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                        <span class="text-slate-400 font-semibold">Jenis Kelamin</span>
                        <span class="font-bold text-slate-800">{{ $employee->jenis_kelamin ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                        <span class="text-slate-400 font-semibold">Alamat Email</span>
                        <span class="font-bold text-slate-800">{{ $employee->email ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2.5">
                        <span class="text-slate-400 font-semibold">Nomor Telepon / WhatsApp</span>
                        <span class="font-bold text-slate-800">{{ $employee->nomor_hp ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Kartu Informasi Pekerjaan & Penempatan -->
            <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i class="ph-fill ph-briefcase text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-800">Informasi Pekerjaan & Penempatan</h3>
                        <p class="text-xs text-slate-400">Departemen, jabatan, lokasi kerja, dan masa bakti</p>
                    </div>
                </div>

                <div class="space-y-4 text-xs">
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                        <span class="text-slate-400 font-semibold">Departemen</span>
                        <span class="font-bold text-slate-800">
                            {{ $employee->department?->kode_departemen ? $employee->department->kode_departemen . ' — ' : '' }}{{ $employee->department?->nama_departemen ?? '-' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                        <span class="text-slate-400 font-semibold">Level Jabatan</span>
                        <span class="px-2.5 py-1 rounded-lg bg-primary-50 text-primary-700 font-bold">{{ $employee->level_jabatan ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                        <span class="text-slate-400 font-semibold">Job Title / Spesifikasi Jabatan</span>
                        <span class="font-bold text-slate-800">{{ $employee->jabatan ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                        <span class="text-slate-400 font-semibold">Site / Unit Kerja</span>
                        <span class="font-bold text-slate-800">{{ $employee->site_unit ?? 'Head Office' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-50">
                        <span class="text-slate-400 font-semibold">Status Keaktifan</span>
                        <span class="px-2.5 py-0.5 rounded-md font-bold uppercase {{ $isAktif ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ $employee->status_aktif ?? 'aktif' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between py-2.5">
                        <span class="text-slate-400 font-semibold">Tanggal Bergabung & Masa Kerja</span>
                        <span class="font-bold text-slate-800">
                            @if($employee->tanggal_join)
                                {{ \Carbon\Carbon::parse($employee->tanggal_join)->format('d M Y') }}
                                <span class="text-slate-400 font-medium">({{ \Carbon\Carbon::parse($employee->tanggal_join)->diffForHumans(null, true) }})</span>
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- TAB 2: RIWAYAT PELATIHAN & USULAN PENGAJUAN                       -->
        <!-- ================================================================= -->
        <div id="panel-tab-pelatihan" class="emp-tab-panel hidden space-y-6">
            <!-- Sub-bagian 2A: Riwayat Partisipasi Pelatihan -->
            <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Riwayat Partisipasi Pelatihan</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Program pelatihan (dari Katalog maupun Training Baru) yang diikuti oleh karyawan ini</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-extrabold bg-blue-50 text-blue-700">
                        {{ $employee->trainingParticipants->count() }} Pelatihan
                    </span>
                </div>

                <div class="space-y-4">
                    @forelse($employee->trainingParticipants as $part)
                        @php
                            $training = $part->training;
                            $reqRef = $part->trainingRequest;
                            $namaProgram = $training?->nama_pelatihan ?? $reqRef?->training_need ?? 'Program Pelatihan';
                            $metode = $training?->metode_pelaksanaan ?? $reqRef?->jenis_pelaksanaan ?? 'Offline';
                            $instruktur = $training?->instruktur ?? $reqRef?->nama_penyelenggara ?? '-';
                            $tglMulai = $training?->tanggal_mulai ?? $reqRef?->tanggal_mulai;
                            $tglSelesai = $training?->tanggal_selesai ?? $reqRef?->tanggal_selesai;
                            $status = $part->status_peserta ?? 'terdaftar';
                        @endphp
                        <div class="p-5 rounded-2xl bg-slate-50/70 border border-slate-100 hover:border-primary-200 transition-all space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-primary-100 text-primary-700">
                                            {{ $metode }}
                                        </span>
                                        @if($training)
                                            <span class="text-xs font-semibold text-slate-400">{{ $training->kode_pelatihan ?? '#TR-' . str_pad($training->id, 3, '0', STR_PAD_LEFT) }}</span>
                                        @elseif($reqRef)
                                            <span class="text-xs font-semibold text-purple-600">Training Baru ({{ $reqRef->nomor_request ?? '#REQ-' . $reqRef->id }})</span>
                                        @endif
                                    </div>
                                    <h4 class="text-base font-extrabold text-slate-800 mt-1">
                                        @if($training)
                                            <a href="{{ route('hrd.trainings.show', $training->id) }}" class="hover:text-primary-600 transition-colors">
                                                {{ $namaProgram }}
                                            </a>
                                        @else
                                            {{ $namaProgram }}
                                        @endif
                                    </h4>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                        @if(in_array($status, ['selesai', 'lulus'])) bg-emerald-100 text-emerald-800
                                        @elseif(in_array($status, ['sedang_pelatihan', 'terjadwal'])) bg-amber-100 text-amber-800
                                        @elseif(in_array($status, ['disetujui', 'terdaftar'])) bg-blue-100 text-blue-800
                                        @else bg-slate-200 text-slate-700 @endif">
                                        {{ str_replace('_', ' ', $status) }}
                                    </span>
                                </div>
                            </div>

                            @if($training?->deskripsi)
                                <p class="text-xs text-slate-500 line-clamp-2">{{ $training->deskripsi }}</p>
                            @endif

                            <div class="pt-3 border-t border-slate-200/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500">
                                <div class="flex flex-wrap gap-4">
                                    <span><i class="ph-bold ph-chalkboard-teacher mr-1 text-slate-400"></i>Penyelenggara/Instruktur: <strong class="text-slate-700">{{ $instruktur }}</strong></span>
                                    <span>
                                        <i class="ph-bold ph-calendar mr-1 text-slate-400"></i>
                                        @if($tglMulai)
                                            {{ \Carbon\Carbon::parse($tglMulai)->format('d M Y') }}
                                            @if($tglSelesai) - {{ \Carbon\Carbon::parse($tglSelesai)->format('d M Y') }} @endif
                                        @else
                                            Jadwal menyusul
                                        @endif
                                    </span>
                                    @if($part->kehadiran)
                                        <span><i class="ph-bold ph-check-square-offset mr-1 text-emerald-600"></i>Kehadiran: <strong class="text-slate-700">{{ ucfirst($part->kehadiran) }}</strong></span>
                                    @endif
                                    @if($part->hasil_evaluasi)
                                        <span><i class="ph-bold ph-chart-bar mr-1 text-blue-600"></i>Evaluasi: <strong class="text-slate-700">{{ $part->hasil_evaluasi }}</strong></span>
                                    @endif
                                </div>

                                <!-- Form Update Realisasi (Kehadiran, Status, & Evaluasi - Issue #9) + Pintasan Sertifikat -->
                                <div class="flex flex-wrap items-center gap-2">
                                    <form action="{{ route('hrd.participants.updateStatus', $part->id) }}" method="POST" class="flex flex-wrap items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="kehadiran" title="Kehadiran Aktual" class="text-xs font-semibold py-1.5 px-2.5 bg-white border border-slate-200 rounded-lg text-slate-700 shadow-xs focus:ring-1 focus:ring-primary-500">
                                            <option value="belum_dicatat" {{ ($part->kehadiran ?? 'belum_dicatat') === 'belum_dicatat' ? 'selected' : '' }}>Kehadiran: Belum Dicatat</option>
                                            <option value="hadir" {{ $part->kehadiran === 'hadir' ? 'selected' : '' }}>Kehadiran: Hadir</option>
                                            <option value="izin" {{ $part->kehadiran === 'izin' ? 'selected' : '' }}>Kehadiran: Izin</option>
                                            <option value="tidak_hadir" {{ $part->kehadiran === 'tidak_hadir' ? 'selected' : '' }}>Kehadiran: Tidak Hadir</option>
                                        </select>

                                        <select name="status_peserta" title="Status Penyelesaian" class="text-xs font-semibold py-1.5 px-2.5 bg-white border border-slate-200 rounded-lg text-slate-700 shadow-xs focus:ring-1 focus:ring-primary-500">
                                            <option value="disetujui" {{ $status === 'disetujui' ? 'selected' : '' }}>Status: Disetujui</option>
                                            <option value="terjadwal" {{ $status === 'terjadwal' ? 'selected' : '' }}>Status: Terjadwal</option>
                                            <option value="sedang_pelatihan" {{ $status === 'sedang_pelatihan' ? 'selected' : '' }}>Status: Sedang Pelatihan</option>
                                            <option value="selesai" {{ in_array($status, ['selesai', 'lulus']) ? 'selected' : '' }}>Status: Selesai / Lulus</option>
                                            <option value="tidak_hadir" {{ $status === 'tidak_hadir' ? 'selected' : '' }}>Status: Tidak Hadir</option>
                                        </select>

                                        <input type="text" name="hasil_evaluasi" value="{{ $part->hasil_evaluasi }}"
                                               placeholder="Catatan / Skor Evaluasi..."
                                               class="text-xs font-semibold py-1.5 px-2.5 bg-white border border-slate-200 rounded-lg text-slate-700 w-44 focus:ring-1 focus:ring-primary-500 outline-none">

                                        <button type="submit" class="px-3 py-1.5 text-xs font-bold bg-slate-800 text-white rounded-lg hover:bg-slate-700 transition">
                                            Simpan Realisasi
                                        </button>
                                    </form>

                                    <button type="button"
                                            onclick="openAddCertModal({{ $training ? $training->id : 'null' }}, '{{ addslashes($namaProgram) }}')"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                                        <i class="ph-bold ph-plus-circle"></i> + Sertifikat
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center text-slate-400">
                            <i class="ph-fill ph-book-open text-3xl mb-2 text-slate-300 block"></i>
                            <p class="text-xs font-semibold">Karyawan ini belum terdaftar di program pelatihan manapun.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Sub-bagian 2B: Riwayat Usulan & Pengajuan Pelatihan -->
            <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Usulan & Pengajuan Pelatihan</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Daftar permintaan pelatihan yang diusulkan untuk karyawan ini</p>
                    </div>
                    <a href="{{ route('hrd.requests.index') }}" class="text-xs font-bold text-primary-600 hover:underline">
                        Modul Persetujuan &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                <th class="pb-3 px-3">No. Request & Program</th>
                                <th class="pb-3 px-3">Jenis Pengajuan</th>
                                <th class="pb-3 px-3">Diusulkan Oleh</th>
                                <th class="pb-3 px-3">Status</th>
                                <th class="pb-3 px-3">Catatan Review</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($requestsList as $req)
                                @php
                                    $judulReq = $req->training?->nama_pelatihan ?? $req->training_need ?? 'Pengajuan Pelatihan';
                                    $isBaru = ($req->jenis_request ?? '') === 'training_baru' || !$req->training_id;
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3.5 px-3">
                                        <div class="font-bold text-slate-800">{{ $judulReq }}</div>
                                        <div class="text-[11px] text-slate-400">
                                            {{ $req->nomor_request ?? '#REQ-' . str_pad($req->id, 4, '0', STR_PAD_LEFT) }} &bull; {{ $req->created_at?->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-3">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $isBaru ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                            {{ $isBaru ? 'Training Baru' : 'Dari Katalog' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-3 font-semibold text-slate-600">
                                        {{ $req->diajukanOleh?->name ?? 'Admin Departemen' }}
                                    </td>
                                    <td class="py-3.5 px-3">
                                        @if(in_array($req->status, ['pending', 'menunggu_review']))
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                MENUNGGU REVIEW
                                            </span>
                                        @elseif(in_array($req->status, ['approved', 'disetujui', 'partially_approved']))
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                {{ $req->status === 'partially_approved' ? 'DISETUJUI SEBAGIAN' : 'DISETUJUI' }}
                                            </span>
                                        @else
                                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                DITOLAK / REVISI
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-3 text-slate-500 italic">
                                        {{ $req->catatan_admin ?? $req->alasan_penolakan ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 text-xs">
                                        Belum ada data pengajuan pelatihan untuk karyawan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- TAB 3: SERTIFIKAT RESMI & TIME REMINDER (ISSUE #8)                -->
        <!-- ================================================================= -->
        <div id="panel-tab-sertifikat" class="emp-tab-panel hidden">
            <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="ph-fill ph-seal-check text-emerald-600 text-xl"></i>
                            Daftar Sertifikat Resmi Karyawan
                        </h3>
                        <p class="text-xs text-slate-400 mt-0.5">Rekam jejak sertifikasi kompetensi beserta status masa berlaku (Time Reminder) & dokumen resmi</p>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="px-3 py-1.5 rounded-xl text-xs font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-100">
                            Total: {{ $employee->certifications->count() }} Sertifikat
                        </span>
                        <button type="button"
                                onclick="openAddCertModal()"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary-600 text-white font-bold text-xs hover:bg-primary-700 shadow-xs transition-all">
                            <i class="ph-bold ph-plus-circle text-sm"></i> + Tambah Sertifikat
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[850px] text-xs">
                        <thead>
                            <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                <th class="py-3.5 px-4">Nama & Jenis Sertifikasi</th>
                                <th class="py-3.5 px-4">Nomor Registrasi</th>
                                <th class="py-3.5 px-4">Pelatihan Terkait</th>
                                <th class="py-3.5 px-4">Tanggal Terbit</th>
                                <th class="py-3.5 px-4">Masa Berlaku & Expired</th>
                                <th class="py-3.5 px-4">Status Masa Berlaku</th>
                                <th class="py-3.5 px-4 text-right">Dokumen & Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($employee->certifications as $cert)
                                @php
                                    $expDate = $cert->tanggal_expired ? \Carbon\Carbon::parse($cert->tanggal_expired) : null;
                                    $isPast = !$cert->is_permanent && $expDate && $expDate->isPast();
                                    $daysLeft = (!$cert->is_permanent && $expDate && !$isPast) ? (int) now()->diffInDays($expDate) : null;
                                    $isExpiringSoon = $daysLeft !== null && $daysLeft <= 90;
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-4 px-4">
                                        <p class="font-extrabold text-slate-800 text-sm">{{ $cert->nama_sertifikasi }}</p>
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                                            {{ $cert->jenis_sertifikasi ?? 'Sertifikasi Kompetensi' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 font-bold text-slate-700 font-mono">
                                        {{ $cert->nomor_sertifikat ?? '-' }}
                                    </td>
                                    <td class="py-4 px-4 text-slate-600 font-medium">
                                        {{ $cert->training?->nama_pelatihan ?? '-' }}
                                    </td>
                                    <td class="py-4 px-4 font-semibold text-slate-600">
                                        {{ $cert->tanggal_terbit ? \Carbon\Carbon::parse($cert->tanggal_terbit)->format('d M Y') : '-' }}
                                    </td>
                                    <td class="py-4 px-4 font-semibold text-slate-700">
                                        @if($cert->is_permanent)
                                            <span class="text-teal-600 font-bold">Seumur Hidup (Permanen)</span>
                                        @elseif($expDate)
                                            <div>{{ $expDate->format('d M Y') }}</div>
                                            @if($cert->masa_berlaku)
                                                <span class="text-[10px] text-slate-400">Masa Berlaku: {{ $cert->masa_berlaku }}</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="py-4 px-4">
                                        @if($cert->is_permanent)
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200">
                                                PERMANEN
                                            </span>
                                        @elseif($isPast)
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                SUDAH EXPIRED
                                            </span>
                                        @elseif($isExpiringSoon)
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                SEGERA EXPIRED ({{ $daysLeft }} HARI LAGI)
                                            </span>
                                        @elseif($expDate)
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                AKTIF ({{ $daysLeft }} HARI LAGI)
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                                                TERCATAT
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <div class="inline-flex items-center justify-end gap-1.5">
                                            <a href="{{ route('certifications.download', ['certification' => $cert->id, 'inline' => 1]) }}"
                                               target="_blank"
                                               title="Lihat / Unduh Dokumen Sertifikat"
                                               class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-600 hover:text-white font-bold text-[11px] transition-colors">
                                                <i class="ph-bold ph-file-arrow-down"></i> Unduh
                                            </a>

                                            <button type="button"
                                                    onclick="openEditCertModal({{ json_encode([
                                                        'id' => $cert->id,
                                                        'update_url' => route('hrd.certifications.update', $cert->id),
                                                        'training_id' => $cert->training_id,
                                                        'nama_sertifikasi' => $cert->nama_sertifikasi,
                                                        'jenis_sertifikasi' => $cert->jenis_sertifikasi ?? 'Lainnya',
                                                        'nomor_sertifikat' => $cert->nomor_sertifikat,
                                                        'tanggal_terbit' => $cert->tanggal_terbit,
                                                        'masa_berlaku' => $cert->masa_berlaku,
                                                        'tanggal_expired' => $cert->tanggal_expired,
                                                        'is_permanent' => (bool) $cert->is_permanent,
                                                    ]) }})"
                                                    title="Edit / Perbarui Sertifikat"
                                                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-500 hover:text-white font-bold text-[11px] transition-colors">
                                                <i class="ph-bold ph-pencil-simple"></i> Edit
                                            </button>

                                            <form action="{{ route('hrd.certifications.destroy', $cert->id) }}" method="POST" class="inline m-0" onsubmit="return confirm('Hapus sertifikat ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus Sertifikat" class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-bold text-[11px] transition-colors">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-10 text-center text-slate-400">
                                        <i class="ph-fill ph-certificate text-3xl text-slate-300 mb-2 block"></i>
                                        Belum ada riwayat sertifikat resmi untuk karyawan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- ================================================================= -->
    <!-- MODAL TAMBAH SERTIFIKAT KARYAWAN (ISSUE #8)                       -->
    <!-- ================================================================= -->
    <div id="modalAddCert" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 w-full max-w-xl overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Tambah Sertifikat Resmi Karyawan</h3>
                    <p class="text-xs text-slate-400">Karyawan: <strong>{{ $employee->nama }} ({{ $employee->nik }})</strong></p>
                </div>
                <button type="button" onclick="closeAddCertModal()" class="p-2 rounded-xl text-slate-400 hover:bg-slate-200 hover:text-slate-700">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <form action="{{ route('hrd.certifications.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 text-xs">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Sertifikat / Lisensi <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_sertifikasi" id="addCertNama" required
                               placeholder="Contoh: Sertifikasi POP BNSP"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jenis Sertifikasi <span class="text-rose-500">*</span></label>
                        <select name="jenis_sertifikasi" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
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
                        <input type="text" name="nomor_sertifikat" placeholder="Contoh: REG-BNSP-2026-001"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Program Pelatihan Terkait (Opsional)</label>
                        <select name="training_id" id="addCertTrainingId" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                            <option value="">-- Tidak Terkait / Mandiri --</option>
                            @foreach($allTrainings ?? $availableTrainings as $trOpt)
                                <option value="{{ $trOpt->id }}">{{ $trOpt->nama_pelatihan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Terbit</label>
                        <input type="date" name="tanggal_terbit" value="{{ date('Y-m-d') }}"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Masa Berlaku</label>
                        <input type="text" name="masa_berlaku" placeholder="Misal: 3 Tahun / 5 Tahun"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Expired</label>
                        <input type="date" name="tanggal_expired" id="addCertExpired"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_permanent" id="addCertPermanent" value="1"
                           onchange="document.getElementById('addCertExpired').disabled = this.checked"
                           class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                    <label for="addCertPermanent" class="font-bold text-slate-700 cursor-pointer">
                        Sertifikat berlaku seumur hidup (Permanen — Tanpa Tanggal Kedaluwarsa)
                    </label>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Unggah Dokumen Sertifikat (PDF / JPG / PNG, Maks 5MB)</label>
                    <input type="file" name="file_sertifikat" accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-600">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeAddCertModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary-600 text-white font-bold hover:bg-primary-700 shadow-xs">
                        Simpan Sertifikat
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================================================================= -->
    <!-- MODAL EDIT / PERBARUI SERTIFIKAT KARYAWAN (ISSUE #8)              -->
    <!-- ================================================================= -->
    <div id="modalEditCert" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 w-full max-w-xl overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Edit / Perbarui Sertifikat Karyawan</h3>
                    <p class="text-xs text-slate-400">Perbarui nomor registrasi, masa berlaku, atau unggah dokumen sertifikat baru</p>
                </div>
                <button type="button" onclick="closeEditCertModal()" class="p-2 rounded-xl text-slate-400 hover:bg-slate-200 hover:text-slate-700">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <form id="formEditCert" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 text-xs">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Nama Sertifikat / Lisensi <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_sertifikasi" id="editCertNama" required
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Jenis Sertifikasi <span class="text-rose-500">*</span></label>
                        <select name="jenis_sertifikasi" id="editCertJenis" required class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
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
                        <input type="text" name="nomor_sertifikat" id="editCertNomor"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Program Pelatihan Terkait</label>
                        <select name="training_id" id="editCertTrainingId" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                            <option value="">-- Tidak Terkait / Mandiri --</option>
                            @foreach($allTrainings ?? $availableTrainings as $trOpt)
                                <option value="{{ $trOpt->id }}">{{ $trOpt->nama_pelatihan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Terbit</label>
                        <input type="date" name="tanggal_terbit" id="editCertTerbit"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Masa Berlaku</label>
                        <input type="text" name="masa_berlaku" id="editCertMasaBerlaku"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Tanggal Expired</label>
                        <input type="date" name="tanggal_expired" id="editCertExpired"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 font-semibold text-slate-800 focus:border-primary-500 outline-none">
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_permanent" id="editCertPermanent" value="1"
                           onchange="document.getElementById('editCertExpired').disabled = this.checked"
                           class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                    <label for="editCertPermanent" class="font-bold text-slate-700 cursor-pointer">
                        Sertifikat berlaku seumur hidup (Permanen)
                    </label>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 mb-1">Ganti File Sertifikat (Opsional — PDF / JPG / PNG)</label>
                    <input type="file" name="file_sertifikat" accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-600">
                </div>

                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeEditCertModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 text-slate-600 font-bold hover:bg-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-500 text-white font-bold hover:bg-amber-600 shadow-xs">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function switchEmployeeTab(tabName) {
            const tabs = ['biodata', 'pelatihan', 'sertifikat'];
            tabs.forEach((t) => {
                const panel = document.getElementById('panel-tab-' + t);
                const btn = document.getElementById('btn-tab-' + t);
                if (t === tabName) {
                    panel.classList.remove('hidden');
                    btn.classList.remove('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
                    btn.classList.add('bg-primary-600', 'text-white', 'shadow-xs');
                } else {
                    panel.classList.add('hidden');
                    btn.classList.remove('bg-primary-600', 'text-white', 'shadow-xs');
                    btn.classList.add('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
                }
            });
        }

        function openAddCertModal(trainingId = null, namaProgram = '') {
            const modal = document.getElementById('modalAddCert');
            const namaInput = document.getElementById('addCertNama');
            const trainingSelect = document.getElementById('addCertTrainingId');

            if (namaProgram && namaInput) {
                namaInput.value = namaProgram;
            }
            if (trainingSelect) {
                trainingSelect.value = trainingId ? String(trainingId) : '';
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeAddCertModal() {
            const modal = document.getElementById('modalAddCert');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openEditCertModal(cert) {
            const modal = document.getElementById('modalEditCert');
            const form = document.getElementById('formEditCert');
            form.action = cert.update_url;

            document.getElementById('editCertNama').value = cert.nama_sertifikasi || '';
            document.getElementById('editCertJenis').value = cert.jenis_sertifikasi || 'Lainnya';
            document.getElementById('editCertNomor').value = cert.nomor_sertifikat || '';
            document.getElementById('editCertTrainingId').value = cert.training_id ? String(cert.training_id) : '';
            document.getElementById('editCertTerbit').value = cert.tanggal_terbit || '';
            document.getElementById('editCertMasaBerlaku').value = cert.masa_berlaku || '';
            document.getElementById('editCertExpired').value = cert.tanggal_expired || '';

            const permCheckbox = document.getElementById('editCertPermanent');
            permCheckbox.checked = !!cert.is_permanent;
            document.getElementById('editCertExpired').disabled = !!cert.is_permanent;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeEditCertModal() {
            const modal = document.getElementById('modalEditCert');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
</x-app-layout>
