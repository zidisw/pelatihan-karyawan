<x-app-layout>
    <x-slot name="header">Daftar Pelatihan Tersedia</x-slot>

    <div class="w-full space-y-6">
        <!-- Page Title & Action Buttons -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Katalog Pelatihan Tersedia</h2>
                <p class="text-sm text-slate-500 mt-1 font-medium">Pilih program pelatihan dari katalog atau ajukan usulan pelatihan baru untuk karyawan departemen Anda.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('department.trainings.history') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    <i class="ph-bold ph-clock-counter-clockwise text-base"></i>
                    Riwayat Pengajuan
                </a>
                <a href="{{ route('department.requests.createNew') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-600/20 transition">
                    <i class="ph-bold ph-plus-circle text-base"></i>
                    + Request Training Baru
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-bold flex items-center gap-2">
                <i class="ph-fill ph-check-circle text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-sm font-bold flex items-center gap-2">
                <i class="ph-fill ph-warning-circle text-lg"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <form method="GET" action="{{ route('department.trainings.index') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                <div class="md:col-span-5">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Cari Pelatihan</label>
                    <div class="relative">
                        <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama pelatihan, kode program, atau instruktur..."
                               class="w-full pl-10 pr-4 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50/70 focus:bg-white focus:border-blue-500 outline-none transition">
                    </div>
                </div>
                <div class="md:col-span-3">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Kategori</label>
                    <select name="kategori" class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50/70 focus:bg-white focus:border-blue-500 outline-none">
                        <option value="">Semua Kategori</option>
                        @foreach(['Sertifikasi', 'Technical Skill', 'Soft Skill'] as $kat)
                            <option value="{{ $kat }}" @selected(request('kategori') === $kat)>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Metode</label>
                    <select name="metode_pelaksanaan" class="w-full px-3.5 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50/70 focus:bg-white focus:border-blue-500 outline-none">
                        <option value="">Semua Metode</option>
                        @foreach(['OFFLINE', 'ONLINE', 'HYBRID'] as $met)
                            <option value="{{ $met }}" @selected(request('metode_pelaksanaan') === $met)>{{ $met }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2 flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2.5 px-4 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'kategori', 'metode_pelaksanaan']))
                        <a href="{{ route('department.trainings.index') }}" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition" title="Reset">
                            <i class="ph-bold ph-arrow-counter-clockwise"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Grid Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($trainings as $training)
                @php
                    $badgeColor = match($training->metode_pelaksanaan) {
                        'ONLINE' => 'bg-blue-50 text-blue-600 border-blue-200',
                        'HYBRID' => 'bg-purple-50 text-purple-600 border-purple-200',
                        default => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                    };
                    $approvedCount = $training->approvedParticipantsCount();
                    $remainingQuota = $training->remainingQuota();
                    $quotaPercent = $training->quotaPercentage();
                @endphp

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col hover:shadow-md transition-all duration-300">
                    <!-- Header Card -->
                    <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                        <div class="flex items-center gap-1.5">
                            @if($training->kode_program)
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[10px] font-extrabold uppercase tracking-wider rounded-md">
                                    {{ $training->kode_program }}
                                </span>
                            @endif
                            @if($training->kategori)
                                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 text-[10px] font-bold uppercase tracking-wider rounded-md">
                                    {{ $training->kategori }}{{ $training->jenis_sertifikasi ? ' • ' . $training->jenis_sertifikasi : '' }}
                                </span>
                            @endif
                        </div>
                        <span class="px-2.5 py-1 {{ $badgeColor }} border text-[10px] font-bold uppercase tracking-wider rounded-md">
                            {{ $training->metode_pelaksanaan }}
                        </span>
                    </div>

                    <h4 class="font-extrabold text-base text-slate-800 mb-2 leading-snug">{{ $training->nama_pelatihan }}</h4>
                    @if($training->deskripsi)
                        <p class="text-xs text-slate-500 line-clamp-2 mb-4">{{ $training->deskripsi }}</p>
                    @endif

                    <!-- Info List -->
                    <div class="space-y-2.5 mb-5 flex-1 text-xs text-slate-600">
                        <div class="flex items-center gap-2.5">
                            <i class="ph-fill ph-user-circle text-slate-400 text-base"></i>
                            <span class="font-semibold">{{ $training->instruktur ?? 'Belum ditentukan' }}</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <i class="ph-fill ph-calendar-blank text-slate-400 text-base"></i>
                            <span class="font-semibold">
                                {{ \Carbon\Carbon::parse($training->tanggal_mulai)->format('d M Y') }} –
                                {{ \Carbon\Carbon::parse($training->tanggal_selesai)->format('d M Y') }}
                            </span>
                        </div>
                        @if($training->batas_pendaftaran)
                            <div class="flex items-center gap-2.5 text-amber-700">
                                <i class="ph-fill ph-alarm text-amber-500 text-base"></i>
                                <span class="font-bold">Batas Daftar: {{ \Carbon\Carbon::parse($training->batas_pendaftaran)->format('d M Y') }}</span>
                            </div>
                        @endif
                        @if($training->lokasi)
                            <div class="flex items-center gap-2.5">
                                <i class="ph-fill ph-map-pin text-slate-400 text-base"></i>
                                <span class="font-medium">{{ $training->lokasi }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Dynamic Quota Box (BR-08) -->
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 mb-4">
                        <div class="grid grid-cols-3 gap-2 text-center mb-2">
                            <div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase">Kuota Total</div>
                                <div class="text-xs font-extrabold text-slate-700">{{ $training->kuota }}</div>
                            </div>
                            <div class="border-x border-slate-200">
                                <div class="text-[10px] font-bold text-slate-400 uppercase">Terisi</div>
                                <div class="text-xs font-extrabold text-blue-600">{{ $approvedCount }}</div>
                            </div>
                            <div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase">Sisa Kuota</div>
                                <div class="text-xs font-extrabold {{ $remainingQuota > 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $remainingQuota }}</div>
                            </div>
                        </div>
                        <div class="w-full h-1.5 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full {{ $remainingQuota > 0 ? 'bg-blue-600' : 'bg-rose-500' }}" style="width: {{ $quotaPercent }}%"></div>
                        </div>
                    </div>

                    <!-- Button -->
                    @if($remainingQuota > 0)
                        <a href="{{ route('department.trainings.request', $training->id) }}"
                           class="w-full inline-flex items-center justify-center gap-2 bg-slate-800 text-white font-bold text-xs py-3 rounded-xl hover:bg-slate-900 transition shadow-sm">
                            <i class="ph-bold ph-users-three text-sm"></i>
                            Ajukan Karyawan (Multi-Peserta)
                        </a>
                    @else
                        <button type="button" disabled
                                class="w-full inline-flex items-center justify-center gap-2 bg-slate-100 text-slate-400 font-bold text-xs py-3 rounded-xl cursor-not-allowed">
                            Kuota Penuh
                        </button>
                    @endif
                </div>
            @empty
                <div class="col-span-full p-12 bg-white rounded-2xl border border-slate-100 text-center space-y-3">
                    <div class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 mx-auto">
                        <i class="ph-fill ph-graduation-cap text-3xl"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">Tidak ada pelatihan di katalog yang sesuai</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto">Anda tetap dapat mengusulkan program pelatihan baru di luar katalog sesuai kebutuhan departemen Anda.</p>
                    <div class="pt-2">
                        <a href="{{ route('department.requests.createNew') }}"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition">
                            <i class="ph-bold ph-plus-circle text-base"></i>
                            + Request Training Baru
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
