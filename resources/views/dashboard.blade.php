<x-app-layout>
    <x-slot name="header">
        Dashboard Overview Admin Portal
    </x-slot>

    <div class="w-full space-y-6">

        <!-- Bagian Pertama: 2 Card / Widget Utama (Bisa Diklik) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Widget 1: Total Karyawan (Klik -> Page Data Karyawan) -->
            <a href="{{ route('hrd.employees.index') }}" class="group bg-surface p-6 md:p-7 rounded-2xl shadow-sm border border-slate-100 hover:border-primary-300 hover:shadow-md hover:-translate-y-0.5 transition-all block">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-primary-600 group-hover:bg-primary-600 group-hover:text-white transition-colors shadow-sm">
                        <i class="ph-fill ph-users text-2xl"></i>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-primary-600 bg-primary-50 group-hover:bg-primary-600 group-hover:text-white px-3 py-1.5 rounded-full transition-colors">
                        Lihat Data Karyawan
                        <i class="ph-bold ph-arrow-right"></i>
                    </span>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-semibold mb-1">Total Karyawan</p>
                    <h3 class="text-4xl font-extrabold text-slate-800 group-hover:text-primary-600 transition-colors">{{ $employees ?? 0 }}</h3>
                    <p class="text-xs text-slate-400 mt-2 font-medium">Klik untuk membuka halaman manajemen seluruh data karyawan</p>
                </div>
            </a>

            <!-- Widget 2: Riwayat Pelatihan (Klik -> Page Riwayat Pelatihan) -->
            <a href="{{ route('hrd.participants.index') }}" class="group bg-surface p-6 md:p-7 rounded-2xl shadow-sm border border-slate-100 hover:border-emerald-300 hover:shadow-md hover:-translate-y-0.5 transition-all block">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors shadow-sm">
                        <i class="ph-fill ph-users-three text-2xl"></i>
                    </div>
                    <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 group-hover:bg-emerald-600 group-hover:text-white px-3 py-1.5 rounded-full transition-colors">
                        Buka Riwayat Pelatihan
                        <i class="ph-bold ph-arrow-right"></i>
                    </span>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-semibold mb-1">Riwayat Pelatihan</p>
                    <h3 class="text-4xl font-extrabold text-slate-800 group-hover:text-emerald-600 transition-colors">{{ $trainingHistoryCount ?? 0 }}</h3>
                    <p class="text-xs text-slate-400 mt-2 font-medium">
                        Klik untuk memantau Sertifikasi ({{ $totalCertifications ?? 0 }}) serta Technical & Soft Skill ({{ $totalParticipantRecords ?? 0 }})
                    </p>
                </div>
            </a>

        </div>

        <!-- Bagian Bawah Widget: Katalog Pelatihan Tersedia (Kiri) & Pengajuan Menunggu Persetujuan (Kanan) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-10">

            <!-- Kolom Kiri (2 Kolom): List Katalog Pelatihan yang Tersedia -->
            <div class="lg:col-span-2 bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between">
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Katalog Pelatihan yang Tersedia</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Daftar program pelatihan aktif dan terjadwal di Raja Academy</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('hrd.trainings.index') }}" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-primary-600 bg-primary-50 hover:bg-primary-100 rounded-lg transition-colors">
                                Kelola Katalog
                            </a>
                            <a href="{{ route('hrd.trainings.create') }}" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors">
                                <i class="ph-bold ph-plus"></i> Tambah Pelatihan
                            </a>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($availableTrainings as $training)
                        <div class="p-4 rounded-2xl bg-slate-50/70 border border-slate-100 hover:border-primary-200 transition-all">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                                <div class="space-y-1.5">
                                    <div class="flex flex-wrap items-center gap-2">
                                        @php
                                            $kategoriClass = match($training->kategori) {
                                                'Sertifikasi' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'Technical Skill' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'Soft Skill' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                default => 'bg-slate-100 text-slate-600 border-slate-200',
                                            };
                                        @endphp
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border uppercase {{ $kategoriClass }}">
                                            {{ $training->kategori ?? 'Program' }}
                                        </span>
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 uppercase">
                                            {{ $training->metode_pelaksanaan }}
                                        </span>
                                        @if($training->kode_program)
                                            <span class="text-[11px] font-bold text-slate-400">{{ $training->kode_program }}</span>
                                        @endif
                                    </div>

                                    <h4 class="text-base font-extrabold text-slate-800">
                                        <a href="{{ route('hrd.trainings.show', $training->id) }}" class="hover:text-primary-600 transition-colors">
                                            {{ $training->nama_pelatihan }}
                                        </a>
                                    </h4>
                                    <p class="text-xs text-slate-500 line-clamp-2">{{ $training->deskripsi }}</p>
                                </div>

                                <div class="flex sm:flex-col items-end justify-between shrink-0 gap-2">
                                    @if($training->status_pendaftaran === 'dibuka')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-600 border border-emerald-200">
                                            Pendaftaran Dibuka
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-500 border border-slate-200">
                                            {{ ucfirst(str_replace('_', ' ', $training->status_pendaftaran)) }}
                                        </span>
                                    @endif

                                    <a href="{{ route('hrd.trainings.show', $training->id) }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 inline-flex items-center gap-1">
                                        Detail &rarr;
                                    </a>
                                </div>
                            </div>

                            <div class="mt-3 pt-3 border-t border-slate-200/60 flex flex-wrap items-center justify-between gap-3 text-xs text-slate-500">
                                <div class="flex flex-wrap items-center gap-4">
                                    <span>
                                        <i class="ph-bold ph-calendar mr-1 text-slate-400"></i>
                                        {{ \Carbon\Carbon::parse($training->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($training->tanggal_selesai)->format('d M Y') }}
                                    </span>
                                    <span>
                                        <i class="ph-bold ph-chalkboard-teacher mr-1 text-slate-400"></i>
                                        {{ $training->instruktur ?? '-' }}
                                    </span>
                                </div>
                                <span class="font-semibold text-slate-600">
                                    <i class="ph-bold ph-users mr-1 text-slate-400"></i>
                                    Terdaftar: {{ $training->participants_count ?? 0 }} / {{ $training->kuota }} Kuota
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="py-12 text-center text-slate-400">
                            <i class="ph-fill ph-graduation-cap text-3xl text-slate-300 mb-2"></i>
                            <p class="text-xs font-semibold">Belum ada katalog pelatihan yang tersedia.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 mt-6">
                    <a href="{{ route('hrd.trainings.index') }}" class="block text-center text-xs font-bold text-primary-600 hover:text-primary-700 py-2">
                        Lihat Seluruh Katalog Pelatihan &rarr;
                    </a>
                </div>
            </div>

            <!-- Kolom Kanan (1 Kolom): Pengajuan Menunggu Persetujuan -->
            <div class="bg-surface rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 leading-tight">Pengajuan Menunggu<br>Persetujuan</h3>
                            <p class="text-xs text-slate-400 mt-1">Usulan dari Admin Departemen</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-rose-500 text-white text-xs font-extrabold shadow-sm">
                            {{ $pendingRequestsCount ?? 0 }}
                        </span>
                    </div>

                    <div class="space-y-4">
                        @forelse($recentPendingRequests as $req)
                        <div class="p-3.5 rounded-xl bg-slate-50/70 border border-slate-100 space-y-2">
                            <div class="flex justify-between items-start">
                                <span class="text-[10px] font-bold text-primary-600 bg-primary-50 px-2 py-0.5 rounded uppercase">
                                    {{ $req->department?->nama_departemen ?? $req->employee?->department?->nama_departemen ?? 'Divisi' }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-medium">{{ $req->created_at->diffForHumans() }}</span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800">{{ $req->employee?->nama ?? 'N/A' }}</h4>
                            <p class="text-xs text-slate-500 line-clamp-2">
                                Mengajukan pelatihan: <strong class="text-slate-700">{{ $req->training?->nama_pelatihan ?? $req->training_need ?? $req->scope_program ?? '-' }}</strong>
                            </p>
                            <div class="pt-2 flex justify-end">
                                <a href="{{ route('hrd.requests.index') }}" class="px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white text-xs font-semibold rounded-lg transition-colors inline-flex items-center gap-1">
                                    Tinjau & Verifikasi &rarr;
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="py-12 text-center text-slate-400">
                            <i class="ph-fill ph-check-circle text-3xl text-emerald-500 mb-2"></i>
                            <p class="text-xs font-semibold">Tidak ada pengajuan yang tertunda.</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Semua pengajuan telah diverifikasi.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 mt-6">
                    <a href="{{ route('hrd.requests.index') }}" class="block text-center text-xs font-bold text-primary-600 hover:text-primary-700 py-2">
                        Buka Seluruh Pengajuan Pelatihan &rarr;
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
