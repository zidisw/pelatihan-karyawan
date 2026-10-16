<x-app-layout>
    <x-slot name="header">
        Katalog Pelatihan
    </x-slot>

    <div class="w-full space-y-6">

        <!-- Header Halaman -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Katalog Pelatihan</h2>
                <p class="text-sm text-slate-500 mt-1 font-medium">Kelola dan pantau program pengembangan kompetensi karyawan di Raja Academy.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('hrd.trainings.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-bold rounded-xl shadow-sm hover:bg-primary-700 transition-colors">
                    <i class="ph-bold ph-plus text-base"></i>
                    Tambah Pelatihan
                </a>
            </div>
        </div>

        <!-- Grid Kartu Katalog Pelatihan -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-12">

            @forelse ($trainings as $training)
                @php
                    $badgeColor = match($training->metode_pelaksanaan) {
                        'ONLINE' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'HYBRID' => 'bg-purple-50 text-purple-700 border-purple-200',
                        default => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    };

                    $kategoriColor = match($training->kategori) {
                        'Sertifikasi' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                        'Technical Skill' => 'bg-sky-50 text-sky-700 border-sky-200',
                        'Soft Skill' => 'bg-amber-50 text-amber-700 border-amber-200',
                        default => 'bg-slate-100 text-slate-600 border-slate-200',
                    };

                    $isClosed = strtolower($training->status_pendaftaran) === 'ditutup';
                    $statusText = $isClosed ? 'DITUTUP' : 'DIBUKA';
                    $statusColor = $isClosed
                        ? 'bg-rose-50 text-rose-600 border-rose-200'
                        : 'bg-emerald-50 text-emerald-600 border-emerald-200';

                    $terisi = $training->employees->count();
                    $kuota = max(1, (int) $training->kuota);
                    $sisaKuota = max(0, (int) $training->kuota - $terisi);
                    $persenTerisi = min(100, round(($terisi / $kuota) * 100));
                @endphp

                <div class="bg-surface rounded-2xl p-6 shadow-sm border border-slate-100 hover:border-primary-200 hover:shadow-md transition-all duration-200 flex flex-col justify-between">

                    <div>
                        <!-- Baris Atas: Badge Status, Kategori, Metode & Kode Program -->
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2.5 py-1 {{ $statusColor }} border text-[10px] font-bold uppercase tracking-wider rounded-lg inline-flex items-center gap-1">
                                    @if($isClosed)
                                        <i class="ph-bold ph-lock-key"></i>
                                    @else
                                        <i class="ph-bold ph-door-open"></i>
                                    @endif
                                    {{ $statusText }}
                                </span>

                                @if($training->kategori)
                                    <span class="px-2.5 py-1 {{ $kategoriColor }} border text-[10px] font-bold uppercase tracking-wider rounded-lg">
                                        {{ $training->kategori }}
                                    </span>
                                @endif

                                <span class="px-2.5 py-1 {{ $badgeColor }} border text-[10px] font-bold uppercase tracking-wider rounded-lg">
                                    {{ $training->metode_pelaksanaan }}
                                </span>
                            </div>

                            <span class="text-xs font-bold text-slate-400 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100">
                                {{ $training->kode_program ?? '#TR-' . str_pad($training->id, 3, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>

                        <!-- Judul & Deskripsi Pelatihan -->
                        <h3 class="text-xl font-extrabold text-slate-800 leading-snug mb-2">
                            <a href="{{ route('hrd.trainings.show', $training->id) }}" class="hover:text-primary-600 transition-colors">
                                {{ $training->nama_pelatihan }}
                            </a>
                        </h3>
                        <p class="text-xs text-slate-500 font-medium line-clamp-2 mb-5 leading-relaxed">
                            {{ $training->deskripsi ?? 'Belum ada deskripsi untuk pelatihan ini.' }}
                        </p>

                        <!-- Kotak Informasi Jadwal, Lokasi, dan Instruktur (Rapi & Tidak Bertabrakan) -->
                        <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-3.5 mb-5">
                            <div class="flex items-start gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-white border border-slate-200/80 flex items-center justify-center text-primary-600 shrink-0">
                                    <i class="ph-fill ph-calendar-blank text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jadwal Pelaksanaan</p>
                                    <p class="text-xs font-bold text-slate-700 mt-0.5">
                                        {{ $training->tanggal_mulai ? \Carbon\Carbon::parse($training->tanggal_mulai)->format('d M Y') : '-' }}
                                        @if($training->tanggal_selesai)
                                            – {{ \Carbon\Carbon::parse($training->tanggal_selesai)->format('d M Y') }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-white border border-slate-200/80 flex items-center justify-center text-emerald-600 shrink-0">
                                    <i class="ph-fill ph-map-pin text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lokasi / Media</p>
                                    <p class="text-xs font-bold text-slate-700 mt-0.5 truncate" title="{{ $training->lokasi ?? '-' }}">
                                        {{ $training->lokasi ?? 'Belum ditentukan' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 min-w-0 sm:col-span-2 pt-2.5 border-t border-slate-200/60">
                                <div class="w-8 h-8 rounded-lg bg-white border border-slate-200/80 flex items-center justify-center text-indigo-600 shrink-0">
                                    <i class="ph-fill ph-chalkboard-teacher text-base"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Instruktur / Lembaga Penyelenggara</p>
                                    <p class="text-xs font-bold text-slate-700 mt-0.5 break-words">
                                        {{ $training->instruktur ?? 'Belum ditentukan' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Bawah: Kapasitas Kuota & Tombol Aksi -->
                    <div>
                        <div class="mb-4">
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="font-semibold text-slate-500">
                                    Kapasitas Peserta: <strong class="text-slate-800">{{ $terisi }} / {{ $training->kuota }}</strong> Terdaftar
                                </span>
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold {{ $sisaKuota > 5 ? 'bg-emerald-50 text-emerald-700' : ($sisaKuota > 0 ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                                    Sisa {{ $sisaKuota }} Kursi
                                </span>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-primary-600 rounded-full transition-all" style="width: {{ $persenTerisi }}%"></div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
                            <a href="{{ route('hrd.trainings.show', $training->id) }}" class="flex-1 py-2.5 px-4 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                                Lihat Detail <i class="ph-bold ph-arrow-right"></i>
                            </a>

                            <a href="{{ route('hrd.trainings.edit', $training->id) }}" class="py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-amber-50 text-slate-600 hover:text-amber-700 border border-slate-200 hover:border-amber-200 font-bold text-xs transition-colors inline-flex items-center justify-center gap-1.5">
                                <i class="ph-bold ph-pencil-simple"></i> Edit Pelatihan
                            </a>
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center p-12 bg-white rounded-2xl border border-slate-100 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 mb-4">
                        <i class="ph-fill ph-graduation-cap text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Pelatihan</h3>
                    <p class="text-xs text-slate-500 max-w-md">Katalog pelatihan masih kosong. Silakan tambahkan program pelatihan baru untuk karyawan Anda.</p>
                </div>
            @endforelse

        </div>

        <div class="mt-6">
            {{ $trainings->links() }}
        </div>
    </div>
</x-app-layout>
