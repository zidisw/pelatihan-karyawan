<x-app-layout>
    <x-slot name="header">
        Profil & Sertifikasi Karyawan
    </x-slot>

    <div class="w-full space-y-6">

        <!-- Top Navigation -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('department.employees.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-primary-600 transition-colors">
                <i class="ph-bold ph-arrow-left text-base"></i> Kembali ke Data Karyawan Departemen
            </a>
            <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs border border-slate-200">
                <i class="ph-bold ph-eye text-sm text-primary-600"></i> Mode Lihat Saja (Read-Only)
            </span>
        </div>

        @php
            $nameParts = explode(' ', trim($employee->nama));
            $initials = isset($nameParts[1])
                ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                : strtoupper(substr($nameParts[0], 0, 2));
            $isAktif = strtolower($employee->status_aktif ?? 'aktif') === 'aktif';
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
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $employee->status_karyawan === 'tetap' ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                KARYAWAN {{ strtoupper($employee->status_karyawan ?? 'TETAP') }}
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

            <!-- Navigasi 3 Tab -->
            <div class="mt-8 pt-4 border-t border-slate-100 flex flex-wrap gap-2" role="tablist">
                <button type="button"
                        id="btn-tab-biodata"
                        onclick="switchDeptEmpTab('biodata')"
                        class=" inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-extrabold transition-all bg-primary-600 text-white shadow-xs">
                    <i class="ph-bold ph-identification-card text-base"></i>
                    1. Data Diri
                </button>
                <button type="button"
                        id="btn-tab-pelatihan"
                        onclick="switchDeptEmpTab('pelatihan')"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-extrabold transition-all bg-slate-100 text-slate-600 hover:bg-slate-200">
                    <i class="ph-bold ph-graduation-cap text-base"></i>
                    2. Riwayat Pelatihan ({{ $totalTrainings }})
                </button>
                <button type="button"
                        id="btn-tab-sertifikat"
                        onclick="switchDeptEmpTab('sertifikat')"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-extrabold transition-all bg-slate-100 text-slate-600 hover:bg-slate-200">
                    <i class="ph-bold ph-seal-check text-base"></i>
                    3. Sertifikat Resmi ({{ $employee->certifications->count() }})
                </button>
            </div>
        </div>

        <!-- TAB 1: DATA DIRI -->
        <div id="panel-tab-biodata" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                <h3 class="text-base font-extrabold text-slate-800 mb-5 pb-3 border-b border-slate-100">Informasi Pribadi & Kontak</h3>
                <div class="space-y-4 text-xs">
                    <div class="flex items-center justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-400 font-semibold">Nama Lengkap</span>
                        <span class="font-bold text-slate-800">{{ $employee->nama }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-400 font-semibold">NIK</span>
                        <span class="font-bold text-slate-800">{{ $employee->nik }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-400 font-semibold">Jenis Kelamin</span>
                        <span class="font-bold text-slate-800">{{ $employee->jenis_kelamin ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-400 font-semibold">Email</span>
                        <span class="font-bold text-slate-800">{{ $employee->email ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-slate-400 font-semibold">No. HP</span>
                        <span class="font-bold text-slate-800">{{ $employee->nomor_hp ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                <h3 class="text-base font-extrabold text-slate-800 mb-5 pb-3 border-b border-slate-100">Informasi Pekerjaan & Penempatan</h3>
                <div class="space-y-4 text-xs">
                    <div class="flex items-center justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-400 font-semibold">Departemen</span>
                        <span class="font-bold text-slate-800">{{ $employee->department?->nama_departemen ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-400 font-semibold">Level Jabatan</span>
                        <span class="font-bold text-slate-800">{{ $employee->level_jabatan ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-400 font-semibold">Job Title</span>
                        <span class="font-bold text-slate-800">{{ $employee->jabatan ?? '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-slate-50">
                        <span class="text-slate-400 font-semibold">Site / Unit Kerja</span>
                        <span class="font-bold text-slate-800">{{ $employee->site_unit ?? 'Head Office' }}</span>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <span class="text-slate-400 font-semibold">Tanggal Bergabung</span>
                        <span class="font-bold text-slate-800">
                            {{ $employee->tanggal_join ? \Carbon\Carbon::parse($employee->tanggal_join)->format('d M Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: RIWAYAT PELATIHAN -->
        <div id="panel-tab-pelatihan" class="hidden space-y-6">
            <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                <h3 class="text-lg font-bold text-slate-800 mb-5 pb-3 border-b border-slate-100">Riwayat Partisipasi Pelatihan</h3>
                <div class="space-y-4">
                    @forelse($employee->trainings as $training)
                        @php
                            $status = $training->pivot->status_peserta ?? 'terjadwal';
                        @endphp
                        <div class="p-5 rounded-2xl bg-slate-50/70 border border-slate-100 space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <h4 class="text-base font-extrabold text-slate-800">{{ $training->nama_pelatihan }}</h4>
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                                    @if(in_array($status, ['selesai', 'lulus'])) bg-emerald-100 text-emerald-800
                                    @elseif(in_array($status, ['sedang_pelatihan', 'terjadwal'])) bg-amber-100 text-amber-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ str_replace('_', ' ', $status) }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500">
                                Jadwal: {{ \Carbon\Carbon::parse($training->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($training->tanggal_selesai)->format('d M Y') }}
                                &bull; Metode: {{ $training->metode_pelaksanaan }}
                            </p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-6">Belum ada riwayat partisipasi pelatihan.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- TAB 3: SERTIFIKAT RESMI -->
        <div id="panel-tab-sertifikat" class="hidden">
            <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                <h3 class="text-lg font-bold text-slate-800 mb-5 pb-3 border-b border-slate-100">Daftar Sertifikat Resmi Karyawan</h3>
                <div class="space-y-3">
                    @forelse($employee->certifications as $cert)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between gap-4 text-xs">
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">{{ $cert->nama_sertifikasi }}</h4>
                                <p class="text-slate-400 mt-0.5">No: {{ $cert->nomor_sertifikat ?? '-' }} &bull; Jenis: {{ $cert->jenis_sertifikasi ?? 'Umum' }}</p>
                            </div>
                            <div class="text-right">
                                @if($cert->tanggal_expired && !$cert->is_permanent)
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ \Carbon\Carbon::parse($cert->tanggal_expired)->isPast() ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                                        Exp: {{ \Carbon\Carbon::parse($cert->tanggal_expired)->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-teal-100 text-teal-700">Permanen</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-6">Belum ada riwayat sertifikasi resmi.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <script>
        function switchDeptEmpTab(tabName) {
            ['biodata', 'pelatihan', 'sertifikat'].forEach((t) => {
                const panel = document.getElementById('panel-tab-' + t);
                const btn = document.getElementById('btn-tab-' + t);
                if (t === tabName) {
                    panel.classList.remove('hidden');
                    btn.classList.remove('bg-slate-100', 'text-slate-600');
                    btn.classList.add('bg-primary-600', 'text-white');
                } else {
                    panel.classList.add('hidden');
                    btn.classList.remove('bg-primary-600', 'text-white');
                    btn.classList.add('bg-slate-100', 'text-slate-600');
                }
            });
        }
    </script>
</x-app-layout>
