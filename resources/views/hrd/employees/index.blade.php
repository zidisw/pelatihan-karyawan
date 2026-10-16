<x-app-layout>
    <x-slot name="header">
        Manajemen Data Karyawan
    </x-slot>

    <div class="w-full space-y-6">

        <!-- Header & Tombol Tambah Karyawan -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
            <div>
                <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Data Karyawan</h2>
                <p class="text-sm text-slate-500 mt-1">Monitoring profil, status keaktifan, riwayat pelatihan, dan masa berlaku sertifikat karyawan.</p>
            </div>

            <a href="{{ route('hrd.employees.create') }}" class="inline-flex items-center justify-center gap-2 bg-primary-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-primary-700 transition-colors shadow-[0_4px_12px_rgba(37,99,235,0.2)]">
                <i class="ph-bold ph-user-plus text-lg"></i>
                Tambah Karyawan
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-2xl font-bold flex items-center gap-2">
                <i class="ph-fill ph-check-circle text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @php
            $activeTrainingFilter = request('training_status');
        @endphp

        <!-- 4 Kartu Ringkasan Statistik yang Bisa Diklik untuk Filter (PRD 7.2.A) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5">
            <!-- Kartu 1: Total Karyawan -->
            <a href="{{ route('hrd.employees.index', array_merge(request()->except(['training_status', 'page']))) }}"
               class="group block bg-surface p-5 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-blue-500 relative hover:shadow-md hover:-translate-y-0.5 transition-all {{ !$activeTrainingFilter ? 'ring-2 ring-blue-500/30 bg-blue-50/10' : '' }}">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="ph-fill ph-users text-xl"></i>
                    </div>
                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full uppercase tracking-widest">
                        {{ !$activeTrainingFilter ? 'AKTIF DIFILTER' : 'KLIK FILTER' }}
                    </span>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $totalEmployees ?? $employees->total() }}</h3>
                <p class="text-xs text-slate-500 font-semibold mt-1">Total Seluruh Karyawan</p>
            </a>

            <!-- Kartu 2: Pelatihan Selesai -->
            <a href="{{ route('hrd.employees.index', array_merge(request()->except('page'), ['training_status' => $activeTrainingFilter === 'selesai' ? null : 'selesai'])) }}"
               class="group block bg-surface p-5 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-emerald-500 relative hover:shadow-md hover:-translate-y-0.5 transition-all {{ $activeTrainingFilter === 'selesai' ? 'ring-2 ring-emerald-500 bg-emerald-50/20' : '' }}">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <i class="ph-fill ph-seal-check text-xl"></i>
                    </div>
                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full uppercase tracking-widest">
                        {{ $activeTrainingFilter === 'selesai' ? 'FILTER AKTIF' : 'SELESAI' }}
                    </span>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $completedTrainings ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-semibold mt-1">Partisipasi Pelatihan Selesai</p>
            </a>

            <!-- Kartu 3: Sedang Berjalan -->
            <a href="{{ route('hrd.employees.index', array_merge(request()->except('page'), ['training_status' => $activeTrainingFilter === 'berjalan' ? null : 'berjalan'])) }}"
               class="group block bg-surface p-5 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-amber-500 relative hover:shadow-md hover:-translate-y-0.5 transition-all {{ $activeTrainingFilter === 'berjalan' ? 'ring-2 ring-amber-500 bg-amber-50/20' : '' }}">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                        <i class="ph-fill ph-trend-up text-xl"></i>
                    </div>
                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full uppercase tracking-widest">
                        {{ $activeTrainingFilter === 'berjalan' ? 'FILTER AKTIF' : 'PROGRES' }}
                    </span>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $onProgressTrainings ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-semibold mt-1">Pelatihan Sedang Berjalan</p>
            </a>

            <!-- Kartu 4: Terdaftar / Menunggu -->
            <a href="{{ route('hrd.employees.index', array_merge(request()->except('page'), ['training_status' => $activeTrainingFilter === 'menunggu' ? null : 'menunggu'])) }}"
               class="group block bg-surface p-5 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-rose-500 relative hover:shadow-md hover:-translate-y-0.5 transition-all {{ $activeTrainingFilter === 'menunggu' ? 'ring-2 ring-rose-500 bg-rose-50/20' : '' }}">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-colors">
                        <i class="ph-fill ph-hourglass-high text-xl"></i>
                    </div>
                    <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-full uppercase tracking-widest">
                        {{ $activeTrainingFilter === 'menunggu' ? 'FILTER AKTIF' : 'MENUNGGU' }}
                    </span>
                </div>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $pendingTrainings ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-semibold mt-1">Terdaftar / Menunggu Mulai</p>
            </a>
        </div>

        <!-- Tabel & Panel Filter Lengkap (PRD 7.2.B) -->
        <div class="bg-surface rounded-2xl shadow-sm border border-slate-100 p-6">

            <form action="{{ route('hrd.employees.index') }}" method="GET" class="mb-6 pb-6 border-b border-slate-100 space-y-4">
                <!-- Baris 1: Pencarian Teks & Urutan -->
                <div class="flex flex-col md:flex-row gap-3">
                    <div class="relative flex-1">
                        <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari Nama Karyawan, NIK, Job Title, atau Site/Unit Kerja..."
                               class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 outline-none transition-all">
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <select name="sort" onchange="this.form.submit()" class="px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 bg-slate-50/70 focus:border-primary-500 outline-none">
                            <option value="terbaru" {{ request('sort', 'terbaru') === 'terbaru' ? 'selected' : '' }}>Urutkan: Terbaru</option>
                            <option value="nama_asc" {{ request('sort') === 'nama_asc' ? 'selected' : '' }}>Nama (A - Z)</option>
                            <option value="nama_desc" {{ request('sort') === 'nama_desc' ? 'selected' : '' }}>Nama (Z - A)</option>
                            <option value="pelatihan_terbanyak" {{ request('sort') === 'pelatihan_terbanyak' ? 'selected' : '' }}>Pelatihan Terbanyak</option>
                        </select>

                        <button type="submit" class="inline-flex items-center gap-1.5 bg-slate-800 text-white px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-700 transition-colors">
                            <i class="ph-bold ph-funnel"></i> Terapkan
                        </button>

                        @if(request()->anyFilled(['search', 'department_id', 'level_jabatan', 'training_status', 'cert_status', 'status_aktif']) || (request('sort') && request('sort') !== 'terbaru'))
                            <a href="{{ route('hrd.employees.index') }}" class="inline-flex items-center gap-1 bg-rose-50 text-rose-600 border border-rose-200 px-3.5 py-2.5 rounded-xl text-xs font-bold hover:bg-rose-100 transition-colors">
                                <i class="ph-bold ph-x"></i> Reset
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Baris 2: 5 Dropdown Filter Spesifik -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <!-- 1. Filter Departemen -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Departemen</label>
                        <select name="department_id" onchange="this.form.submit()" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 bg-white focus:border-primary-500 outline-none">
                            <option value="">Semua Departemen</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ (string) request('department_id') === (string) $dept->id ? 'selected' : '' }}>
                                    {{ $dept->kode_departemen ? $dept->kode_departemen . ' - ' : '' }}{{ $dept->nama_departemen }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. Filter Level Jabatan -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Level Jabatan</label>
                        <select name="level_jabatan" onchange="this.form.submit()" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 bg-white focus:border-primary-500 outline-none">
                            <option value="">Semua Level Jabatan</option>
                            @foreach(['Group Leader', 'Head Of Section', 'Head Of Department', 'Penanggung Jawab Operational'] as $lvl)
                                <option value="{{ $lvl }}" {{ request('level_jabatan') === $lvl ? 'selected' : '' }}>{{ $lvl }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 3. Filter Status Pelatihan -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status Pelatihan</label>
                        <select name="training_status" onchange="this.form.submit()" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 bg-white focus:border-primary-500 outline-none">
                            <option value="">Semua Status Pelatihan</option>
                            <option value="selesai" {{ request('training_status') === 'selesai' ? 'selected' : '' }}>Sudah Selesai / Lulus</option>
                            <option value="berjalan" {{ request('training_status') === 'berjalan' ? 'selected' : '' }}>Sedang Berjalan</option>
                            <option value="menunggu" {{ request('training_status') === 'menunggu' ? 'selected' : '' }}>Terdaftar / Menunggu</option>
                            <option value="belum_pernah" {{ request('training_status') === 'belum_pernah' ? 'selected' : '' }}>Belum Pernah Pelatihan</option>
                        </select>
                    </div>

                    <!-- 4. Filter Masa Berlaku Sertifikat -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Masa Berlaku Sertifikat</label>
                        <select name="cert_status" onchange="this.form.submit()" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 bg-white focus:border-primary-500 outline-none">
                            <option value="">Semua Status Sertifikat</option>
                            <option value="aktif" {{ request('cert_status') === 'aktif' ? 'selected' : '' }}>Sertifikat Aktif (&gt; 90 Hari)</option>
                            <option value="segera_expired" {{ request('cert_status') === 'segera_expired' ? 'selected' : '' }}>Segera Expired (&le; 90 Hari)</option>
                            <option value="expired" {{ request('cert_status') === 'expired' ? 'selected' : '' }}>Sudah Expired</option>
                            <option value="belum_ada" {{ request('cert_status') === 'belum_ada' ? 'selected' : '' }}>Belum Punya Sertifikat</option>
                        </select>
                    </div>

                    <!-- 5. Filter Status Keaktifan -->
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status Keaktifan</label>
                        <select name="status_aktif" onchange="this.form.submit()" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 bg-white focus:border-primary-500 outline-none">
                            <option value="">Semua (Aktif & Nonaktif)</option>
                            <option value="aktif" {{ request('status_aktif') === 'aktif' ? 'selected' : '' }}>Karyawan Aktif</option>
                            <option value="nonaktif" {{ request('status_aktif') === 'nonaktif' ? 'selected' : '' }}>Karyawan Nonaktif</option>
                        </select>
                    </div>
                </div>

                <!-- Info hasil filter -->
                <div class="flex items-center justify-between pt-1 text-xs text-slate-500">
                    <span>Menampilkan <strong class="text-slate-700">{{ $employees->firstItem() ?? 0 }}–{{ $employees->lastItem() ?? 0 }}</strong> dari <strong class="text-slate-700">{{ $employees->total() }}</strong> karyawan</span>
                </div>
            </form>

            <!-- Tabel Daftar Karyawan -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[980px]">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="py-3.5 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">NAMA KARYAWAN & NIK</th>
                            <th class="py-3.5 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">DEPARTEMEN & SITE</th>
                            <th class="py-3.5 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">LEVEL & JOB TITLE</th>
                            <th class="py-3.5 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">STATUS PELATIHAN & SERTIFIKAT</th>
                            <th class="py-3.5 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">STATUS KERJA</th>
                            <th class="py-3.5 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">

                        @forelse ($employees as $employee)
                            @php
                                $nameParts = explode(' ', trim($employee->nama));
                                $initials = isset($nameParts[1])
                                    ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                                    : strtoupper(substr($nameParts[0], 0, 2));

                                $completedCount = $employee->trainingParticipants->whereIn('status_peserta', ['selesai', 'lulus'])->count();
                                $ongoingCount = $employee->trainingParticipants->whereIn('status_peserta', ['sedang_pelatihan', 'terjadwal'])->count();
                                $waitingCount = $employee->trainingParticipants->whereIn('status_peserta', ['disetujui', 'terdaftar', 'belum_diajukan'])->count();
                                $totalParticipations = $employee->trainingParticipants->count();

                                $certCount = $employee->certifications->count();
                                $expiredCertCount = $employee->certifications->filter(fn($c) => !$c->is_permanent && $c->tanggal_expired && \Carbon\Carbon::parse($c->tanggal_expired)->isPast())->count();
                                $expiringSoonCount = $employee->certifications->filter(fn($c) => !$c->is_permanent && $c->tanggal_expired && !\Carbon\Carbon::parse($c->tanggal_expired)->isPast() && \Carbon\Carbon::parse($c->tanggal_expired)->diffInDays(now()) <= 90)->count();
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors group">
                                <!-- Kolom 1: Nama, NIK, Jenis Kelamin -->
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 font-bold flex items-center justify-center shrink-0 text-xs">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <a href="{{ route('hrd.employees.show', $employee->id) }}" class="text-sm font-bold text-slate-800 hover:text-primary-600 transition-colors block">
                                                {{ $employee->nama }}
                                            </a>
                                            <div class="flex items-center gap-2 mt-0.5 text-[11px] text-slate-400 font-medium">
                                                <span>NIK: <strong class="text-slate-600">{{ $employee->nik }}</strong></span>
                                                @if($employee->jenis_kelamin)
                                                    <span>&bull;</span>
                                                    <span>{{ $employee->jenis_kelamin }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Kolom 2: Departemen & Site/Unit Kerja -->
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-slate-100 text-slate-700">
                                        {{ $employee->department?->nama_departemen ?? '-' }}
                                    </span>
                                    <p class="text-[11px] text-slate-400 font-medium mt-1">
                                        <i class="ph-bold ph-map-pin text-slate-400 mr-0.5"></i> {{ $employee->site_unit ?? 'Head Office' }}
                                    </p>
                                </td>

                                <!-- Kolom 3: Level Jabatan & Job Title -->
                                <td class="py-4 px-4">
                                    <span class="text-xs font-bold text-slate-800 block">{{ $employee->level_jabatan ?? '-' }}</span>
                                    <span class="text-[11px] font-medium text-slate-500 block mt-0.5">{{ $employee->jabatan ?: '-' }}</span>
                                </td>

                                <!-- Kolom 4: Status Pelatihan & Tombol Pop-up Riwayat Singkat (PRD 7.2.B & 7.2.C) -->
                                <td class="py-4 px-4">
                                    <div class="flex flex-col items-start gap-1.5">
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            @if($totalParticipations > 0)
                                                @if($ongoingCount > 0)
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                        {{ $ongoingCount }} Sedang Berjalan
                                                    </span>
                                                @endif
                                                @if($completedCount > 0)
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        {{ $completedCount }} Selesai
                                                    </span>
                                                @endif
                                                @if($waitingCount > 0)
                                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                                        {{ $waitingCount }} Terdaftar
                                                    </span>
                                                @endif
                                            @else
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-500">
                                                    Belum Ada Pelatihan
                                                </span>
                                            @endif

                                            @if($expiredCertCount > 0)
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200" title="Sertifikat Expired">
                                                    {{ $expiredCertCount }} Sertifikat Exp
                                                </span>
                                            @elseif($expiringSoonCount > 0)
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200" title="Sertifikat Segera Expired">
                                                    {{ $expiringSoonCount }} Segera Exp
                                                </span>
                                            @elseif($certCount > 0)
                                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-teal-50 text-teal-700 border border-teal-200">
                                                    {{ $certCount }} Sertifikat Aktif
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Tombol Buka Pop-up Modal Riwayat Singkat -->
                                        <button type="button"
                                                onclick="openQuickHistoryModal('modal-employee-{{ $employee->id }}')"
                                                class="inline-flex items-center gap-1 text-[11px] font-bold text-primary-600 hover:text-primary-800 hover:underline transition-colors">
                                            <i class="ph-bold ph-clock-counter-clockwise"></i>
                                            Lihat Ringkasan Riwayat ({{ $totalParticipations }})
                                        </button>
                                    </div>
                                </td>

                                <!-- Kolom 5: Status Kontrak & Status Keaktifan -->
                                <td class="py-4 px-4">
                                    @php
                                        $statusClass = match(strtolower($employee->status_karyawan ?? '')) {
                                            'tetap' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                            'kontrak' => 'bg-blue-50 text-blue-600 border-blue-200',
                                            default => 'bg-slate-100 text-slate-600 border-slate-200',
                                        };
                                        $aktifVal = strtolower($employee->status_aktif ?? 'aktif');
                                    @endphp
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold border uppercase {{ $statusClass }}">
                                            {{ $employee->status_karyawan ?? '-' }}
                                        </span>
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold {{ $aktifVal === 'aktif' ? 'text-emerald-600' : 'text-rose-600' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $aktifVal === 'aktif' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                            {{ ucfirst($aktifVal) }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Kolom 6: Aksi Sejajar 1 Baris -->
                                <td class="py-4 px-4 text-right" style="white-space: nowrap;">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <a href="{{ route('hrd.employees.show', $employee->id) }}" class="inline-flex items-center gap-1 text-xs font-bold text-primary-600 hover:text-white bg-primary-50 hover:bg-primary-600 border border-primary-100 px-3 py-1.5 rounded-lg transition-colors">
                                            <i class="ph-bold ph-eye"></i> Detail
                                        </a>
                                        <a href="{{ route('hrd.employees.edit', $employee->id) }}" class="inline-flex items-center gap-1 text-xs font-bold text-amber-600 hover:text-white bg-amber-50 hover:bg-amber-500 border border-amber-200 px-3 py-1.5 rounded-lg transition-colors">
                                            <i class="ph-bold ph-pencil-simple"></i> Edit
                                        </a>

                                        <form action="{{ route('hrd.employees.destroy', $employee->id) }}" method="POST" class="inline-flex m-0" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 text-xs font-bold text-rose-600 hover:text-white bg-rose-50 hover:bg-rose-600 border border-rose-200 px-3 py-1.5 rounded-lg transition-colors">
                                                <i class="ph-bold ph-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 font-medium text-sm">
                                    <i class="ph-fill ph-users text-3xl text-slate-300 mb-2 block"></i>
                                    Tidak ada data karyawan yang sesuai dengan filter pencarian.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <div class="mt-6 border-t border-slate-100 pt-6">
                {{ $employees->links() }}
            </div>

        </div>
    </div>

    <!-- Kumpulan Pop-up Modal Riwayat Pelatihan Singkat (PRD 7.2.C) -->
    @foreach ($employees as $employee)
        <div id="modal-employee-{{ $employee->id }}"
             class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 overflow-y-auto"
             onclick="if(event.target === this) closeQuickHistoryModal('modal-employee-{{ $employee->id }}')">
            <div class="bg-white rounded-3xl shadow-2xl border border-slate-100 w-full max-w-2xl overflow-hidden my-8 animate-fade-in">
                <!-- Modal Header -->
                <div class="px-6 py-5 bg-slate-900 text-white flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-2xl bg-primary-600 text-white font-extrabold flex items-center justify-center text-sm">
                            {{ strtoupper(substr($employee->nama, 0, 2)) }}
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold tracking-tight">{{ $employee->nama }}</h3>
                            <p class="text-xs text-slate-300 mt-0.5">
                                NIK: {{ $employee->nik }} &bull; {{ $employee->department?->nama_departemen ?? '-' }} &bull; {{ $employee->level_jabatan ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <button type="button"
                            onclick="closeQuickHistoryModal('modal-employee-{{ $employee->id }}')"
                            class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                        <i class="ph-bold ph-x text-base"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                    <!-- Quick Info Bar -->
                    <div class="grid grid-cols-3 gap-3">
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-center">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Site / Unit</p>
                            <p class="text-xs font-extrabold text-slate-700 mt-0.5">{{ $employee->site_unit ?? 'Head Office' }}</p>
                        </div>
                        <div class="p-3 rounded-2xl bg-blue-50/60 border border-blue-100 text-center">
                            <p class="text-[10px] font-bold text-blue-600 uppercase">Total Pelatihan</p>
                            <p class="text-sm font-extrabold text-blue-700 mt-0.5">{{ $employee->trainingParticipants->count() }} Program</p>
                        </div>
                        <div class="p-3 rounded-2xl bg-emerald-50/60 border border-emerald-100 text-center">
                            <p class="text-[10px] font-bold text-emerald-600 uppercase">Total Sertifikat</p>
                            <p class="text-sm font-extrabold text-emerald-700 mt-0.5">{{ $employee->certifications->count() }} Sertifikat</p>
                        </div>
                    </div>

                    <!-- Daftar Riwayat Pelatihan -->
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                            <i class="ph-fill ph-graduation-cap text-primary-600 text-sm"></i>
                            Riwayat Partisipasi Pelatihan
                        </h4>
                        <div class="space-y-2">
                            @forelse($employee->trainingParticipants as $part)
                                @php
                                    $trTitle = $part->training?->nama_pelatihan
                                        ?? $part->trainingRequest?->training_need
                                        ?? 'Program Pelatihan';
                                    $trDate = $part->training?->tanggal_mulai
                                        ? \Carbon\Carbon::parse($part->training->tanggal_mulai)->format('d M Y')
                                        : ($part->trainingRequest?->tanggal_mulai ? \Carbon\Carbon::parse($part->trainingRequest->tanggal_mulai)->format('d M Y') : '-');
                                    $stPeserta = $part->status_peserta ?? 'terdaftar';
                                @endphp
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-bold text-slate-800">{{ $trTitle }}</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">
                                            Jadwal: {{ $trDate }}
                                            @if($part->hasil_evaluasi)
                                                &bull; Evaluasi: <strong class="text-slate-600">{{ $part->hasil_evaluasi }}</strong>
                                            @endif
                                        </p>
                                    </div>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase shrink-0
                                        @if(in_array($stPeserta, ['selesai', 'lulus'])) bg-emerald-100 text-emerald-700
                                        @elseif(in_array($stPeserta, ['sedang_pelatihan', 'terjadwal'])) bg-amber-100 text-amber-700
                                        @else bg-blue-100 text-blue-700 @endif">
                                        {{ str_replace('_', ' ', $stPeserta) }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic py-3 text-center bg-slate-50 rounded-xl">Belum ada riwayat pelatihan yang diikuti.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Daftar Sertifikat -->
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                            <i class="ph-fill ph-seal-check text-emerald-600 text-sm"></i>
                            Status Sertifikat Karyawan
                        </h4>
                        <div class="space-y-2">
                            @forelse($employee->certifications as $cert)
                                @php
                                    $isExp = !$cert->is_permanent && $cert->tanggal_expired && \Carbon\Carbon::parse($cert->tanggal_expired)->isPast();
                                    $isSoon = !$cert->is_permanent && $cert->tanggal_expired && !$isExp && \Carbon\Carbon::parse($cert->tanggal_expired)->diffInDays(now()) <= 90;
                                @endphp
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-bold text-slate-800">{{ $cert->nama_sertifikasi }}</p>
                                        <p class="text-[11px] text-slate-400 mt-0.5">
                                            No: {{ $cert->nomor_sertifikat ?? '-' }} &bull; Jenis: {{ $cert->jenis_sertifikasi ?? 'Umum' }}
                                        </p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        @if($cert->is_permanent)
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-700">SEUMUR HIDUP</span>
                                        @elseif($isExp)
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700">EXPIRED ({{ \Carbon\Carbon::parse($cert->tanggal_expired)->format('d M Y') }})</span>
                                        @elseif($isSoon)
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">SEGERA EXP ({{ \Carbon\Carbon::parse($cert->tanggal_expired)->format('d M Y') }})</span>
                                        @elseif($cert->tanggal_expired)
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">AKTIF S/D {{ \Carbon\Carbon::parse($cert->tanggal_expired)->format('d M Y') }}</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-600">TERCATAT</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-400 italic py-3 text-center bg-slate-50 rounded-xl">Belum ada sertifikat resmi yang tercatat.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <button type="button"
                            onclick="closeQuickHistoryModal('modal-employee-{{ $employee->id }}')"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-100 transition-colors">
                        Tutup
                    </button>
                    <a href="{{ route('hrd.employees.show', $employee->id) }}"
                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 transition-colors">
                        Buka Detail Lengkap (3 Tab) <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        function openQuickHistoryModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeQuickHistoryModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
    </script>
</x-app-layout>
