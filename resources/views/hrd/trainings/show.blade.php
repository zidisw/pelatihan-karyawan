<x-app-layout>
    <x-slot name="header">
        Detail Informasi Pelatihan
    </x-slot>

    <div class="w-full space-y-6">

        <!-- Header & Navigasi -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('hrd.trainings.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-primary-600 transition-colors">
                <i class="ph-bold ph-arrow-left text-base"></i> Kembali ke Katalog Pelatihan
            </a>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('hrd.trainings.edit', $training->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-xs shadow-sm hover:bg-slate-50 transition-all">
                    <i class="ph-bold ph-pencil-simple text-sm"></i> Edit Pelatihan
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Kolom Kiri: Info Utama & Daftar Peserta (2 Kolom) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Card Detail Program -->
                <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                    <div class="flex items-center gap-2.5 mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-primary-50 text-primary-700 border border-primary-100">
                            {{ $training->metode_pelaksanaan }}
                        </span>
                        <span class="text-xs font-semibold text-slate-400">#TR-{{ str_pad($training->id, 3, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 leading-tight mb-3">
                        {{ $training->nama_pelatihan }}
                    </h1>
                    <p class="text-slate-600 text-sm leading-relaxed mb-6 whitespace-pre-line">
                        {{ $training->deskripsi ?? 'Tidak ada deskripsi rinci untuk pelatihan ini.' }}
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-xl bg-white flex items-center justify-center text-primary-600 shadow-sm shrink-0">
                                <i class="ph-fill ph-chalkboard-teacher text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Instruktur / Trainer</p>
                                <p class="text-sm font-bold text-slate-800">{{ $training->instruktur ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-xl bg-white flex items-center justify-center text-indigo-600 shadow-sm shrink-0">
                                <i class="ph-fill ph-map-pin text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Lokasi / Tautan</p>
                                <p class="text-sm font-bold text-slate-800 truncate max-w-[200px]">{{ $training->lokasi ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Peserta Terdaftar -->
                <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Daftar Peserta Terdaftar</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Karyawan yang terdaftar mengikuti sesi pelatihan ini</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-primary-50 text-primary-700">
                            {{ $training->employees->count() }} Peserta
                        </span>
                    </div>

                    @if ($training->employees->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                        <th class="pb-3 px-3">Nama Karyawan</th>
                                        <th class="pb-3 px-3">Departemen</th>
                                        <th class="pb-3 px-3">Status Peserta</th>
                                        <th class="pb-3 px-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach ($training->employees as $peserta)
                                        @php
                                            $pStatus = $peserta->pivot->status_peserta ?? 'disetujui';
                                        @endphp
                                        <tr class="hover:bg-slate-50/70 transition-colors">
                                            <td class="py-3 px-3">
                                                <a href="{{ route('hrd.employees.show', $peserta->id) }}" class="font-bold text-slate-800 hover:text-primary-600 transition-colors">
                                                    {{ $peserta->nama }}
                                                </a>
                                                <div class="text-[11px] text-slate-400">NIK: {{ $peserta->nik }}</div>
                                            </td>
                                            <td class="py-3 px-3 font-semibold text-slate-600">
                                                {{ $peserta->department->nama_departemen ?? '-' }}
                                            </td>
                                            <td class="py-3 px-3">
                                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                                    @if($pStatus === 'selesai') bg-emerald-50 text-emerald-700 border border-emerald-200
                                                    @elseif($pStatus === 'sedang_pelatihan') bg-amber-50 text-amber-700 border border-amber-200
                                                    @elseif($pStatus === 'disetujui') bg-blue-50 text-blue-700 border border-blue-200
                                                    @else bg-slate-100 text-slate-600 border border-slate-200 @endif">
                                                    {{ str_replace('_', ' ', $pStatus) }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-3 text-right">
                                                <a href="{{ route('hrd.employees.show', $peserta->id) }}" class="inline-flex items-center text-xs font-semibold text-primary-600 hover:underline">
                                                    Profil &rarr;
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10 text-slate-400">
                            <i class="ph-fill ph-users-three text-3xl mb-2 text-slate-300"></i>
                            <p class="text-xs font-semibold">Belum ada peserta yang terdaftar di pelatihan ini.</p>
                        </div>
                    @endif
                </div>

                <!-- Card Riwayat Permintaan / Usulan Pelatihan Ini -->
                @if($training->requests && $training->requests->count() > 0)
                <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <h3 class="text-base font-bold text-slate-800">Riwayat Usulan Departemen untuk Pelatihan Ini</h3>
                        <a href="{{ route('hrd.requests.index') }}" class="text-xs font-bold text-primary-600 hover:underline">
                            Verifikasi &rarr;
                        </a>
                    </div>
                    <div class="space-y-3">
                        @foreach($training->requests as $req)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-slate-800">{{ $req->employee->nama ?? 'N/A' }}</span>
                                <span class="text-slate-400 ml-1">({{ $req->employee->department->nama_departemen ?? 'Divisi' }})</span>
                                <div class="text-[11px] text-slate-400 mt-0.5">Diusulkan oleh: {{ $req->diajukanOleh->name ?? 'Admin' }}</div>
                            </div>
                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                @if($req->status === 'approved') bg-emerald-50 text-emerald-700
                                @elseif($req->status === 'pending') bg-amber-50 text-amber-700
                                @else bg-rose-50 text-rose-700 @endif">
                                {{ $req->status }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            <!-- Kolom Kanan: Jadwal, Kuota & Status Pendaftaran -->
            <div class="space-y-6">

                <!-- Status Pendaftaran -->
                <div class="bg-surface rounded-3xl p-6 shadow-sm border border-slate-100 text-center">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Status Pendaftaran</p>
                    <div class="px-4 py-2.5 rounded-2xl {{ $training->status_pendaftaran === 'dibuka' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }} font-extrabold text-sm uppercase tracking-wide inline-flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full {{ $training->status_pendaftaran === 'dibuka' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                        {{ $training->status_pendaftaran }}
                    </div>
                </div>

                <!-- Informasi Kuota -->
                @php
                    $terdaftar = $training->employees->count();
                    $kuota = $training->kuota;
                    $sisa = max(0, $kuota - $terdaftar);
                    $persen = $kuota > 0 ? min(100, round(($terdaftar / $kuota) * 100)) : 0;
                @endphp
                <div class="bg-surface rounded-3xl p-6 shadow-sm border border-slate-100">
                    <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center justify-between">
                        <span>Kapasitas Peserta</span>
                        <span class="text-xs font-semibold text-primary-600">{{ $persen }}% Terisi</span>
                    </h3>

                    <!-- Progress Bar -->
                    <div class="w-full bg-slate-100 rounded-full h-2.5 mb-4 overflow-hidden">
                        <div class="bg-primary-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ $persen }}%"></div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 text-center text-xs">
                        <div class="p-2.5 rounded-xl bg-slate-50">
                            <p class="text-[10px] text-slate-400 font-bold uppercase">Kuota</p>
                            <p class="font-extrabold text-slate-800 mt-0.5">{{ $kuota }}</p>
                        </div>
                        <div class="p-2.5 rounded-xl bg-blue-50">
                            <p class="text-[10px] text-blue-500 font-bold uppercase">Terdaftar</p>
                            <p class="font-extrabold text-blue-700 mt-0.5">{{ $terdaftar }}</p>
                        </div>
                        <div class="p-2.5 rounded-xl {{ $sisa > 0 ? 'bg-emerald-50' : 'bg-rose-50' }}">
                            <p class="text-[10px] {{ $sisa > 0 ? 'text-emerald-500' : 'text-rose-500' }} font-bold uppercase">Sisa</p>
                            <p class="font-extrabold {{ $sisa > 0 ? 'text-emerald-700' : 'text-rose-700' }} mt-0.5">{{ $sisa }}</p>
                        </div>
                    </div>
                </div>

                <!-- Informasi Jadwal -->
                <div class="bg-surface rounded-3xl p-6 shadow-sm border border-slate-100">
                    <h3 class="text-sm font-bold text-slate-800 mb-4">Informasi Jadwal Pelaksanaan</h3>
                    <div class="space-y-3.5 text-xs">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">Tanggal Mulai</span>
                            <span class="font-bold text-slate-700">
                                {{ $training->tanggal_mulai ? \Carbon\Carbon::parse($training->tanggal_mulai)->format('d M Y') : '-' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between pb-3 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">Tanggal Selesai</span>
                            <span class="font-bold text-slate-700">
                                {{ $training->tanggal_selesai ? \Carbon\Carbon::parse($training->tanggal_selesai)->format('d M Y') : '-' }}
                            </span>
                        </div>
                        @if($training->tanggal_mulai && $training->tanggal_selesai)
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 font-medium">Durasi Pelatihan</span>
                            <span class="font-bold text-primary-600">
                                {{ \Carbon\Carbon::parse($training->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($training->tanggal_selesai)) + 1 }} Hari
                            </span>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
