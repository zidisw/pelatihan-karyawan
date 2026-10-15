<x-app-layout>
    <x-slot name="header">
        Dashboard Departemen {{ $department->nama_departemen ?? '' }}
    </x-slot>

    <div class="w-full space-y-6">

        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-3xl p-6 md:p-8 text-white shadow-lg shadow-emerald-600/10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 text-white text-xs font-semibold backdrop-blur-sm mb-3">
                    <i class="ph-fill ph-buildings text-sm"></i> {{ $department->nama_departemen ?? 'Admin Departemen' }}
                </span>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Halo, {{ $user->name }}!</h2>
                <p class="text-white/80 text-sm mt-1 max-w-xl">Pantau perkembangan karyawan divisi Anda dan ajukan pelatihan peningkatan kompetensi ke tim HRD.</p>
            </div>
            <div class="flex flex-wrap gap-2.5">
                <a href="{{ route('department.trainings.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-emerald-800 font-bold text-sm shadow hover:bg-slate-50 transition-all">
                    <i class="ph-bold ph-plus-circle text-base"></i> Ajukan Pelatihan
                </a>
                <a href="{{ route('department.trainings.history') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-sm backdrop-blur-sm transition-all border border-white/20">
                    <i class="ph-bold ph-clock-counter-clockwise text-base"></i> Riwayat Pengajuan
                </a>
            </div>
        </div>

        <!-- 4 Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            <!-- Karyawan Departemen -->
            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm">
                        <i class="ph-fill ph-users text-2xl"></i>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">Divisi Saya</span>
                </div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Karyawan Divisi</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $departmentEmployeesCount }}</h3>
                <p class="text-xs text-slate-400 mt-3 font-medium">Di {{ $department->nama_departemen ?? 'Departemen ini' }}</p>
            </div>

            <!-- Pelatihan Tersedia -->
            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-primary-600 shadow-sm">
                        <i class="ph-fill ph-graduation-cap text-2xl"></i>
                    </div>
                    <a href="{{ route('department.trainings.index') }}" class="text-xs font-bold text-primary-600 hover:underline">Katalog &rarr;</a>
                </div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Pelatihan Dibuka</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $availableTrainingsCount }}</h3>
                <p class="text-xs text-slate-400 mt-3 font-medium">Dapat diajukan saat ini</p>
            </div>

            <!-- Pengajuan Menunggu -->
            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 shadow-sm">
                        <i class="ph-fill ph-hourglass-medium text-2xl"></i>
                    </div>
                    <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full">Proses HRD</span>
                </div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Menunggu Review</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $pendingRequestsCount }}</h3>
                <p class="text-xs text-slate-400 mt-3 font-medium">Pengajuan belum disetujui</p>
            </div>

            <!-- Pengajuan Disetujui -->
            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600 shadow-sm">
                        <i class="ph-fill ph-check-circle text-2xl"></i>
                    </div>
                    <span class="text-xs font-bold text-teal-700 bg-teal-50 px-2.5 py-1 rounded-full">Disetujui</span>
                </div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Total Disetujui</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $approvedRequestsCount }}</h3>
                <p class="text-xs text-slate-400 mt-3 font-medium">Siap mengikuti pelatihan</p>
            </div>

        </div>

        <!-- 2 Columns: Riwayat Pengajuan & Pelatihan Tersedia -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Riwayat Pengajuan Terbaru (2 Columns) -->
            <div class="lg:col-span-2 bg-surface rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Pengajuan Terakhir Anda</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Status verifikasi usulan pelatihan oleh tim HRD</p>
                    </div>
                    <a href="{{ route('department.trainings.history') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 bg-primary-50 px-3 py-1.5 rounded-lg hover:bg-primary-100 transition-colors">
                        Lihat Riwayat &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                <th class="pb-3 px-3">Nama Karyawan</th>
                                <th class="pb-3 px-3">Pelatihan</th>
                                <th class="pb-3 px-3">Status</th>
                                <th class="pb-3 px-3">Catatan HRD</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentRequests as $req)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3 px-3">
                                    <div class="font-bold text-slate-800">{{ $req->employee?->nama ?? 'N/A' }}</div>
                                    <div class="text-xs text-slate-400">NIK: {{ $req->employee?->nik ?? '-' }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="font-semibold text-slate-700 text-xs">{{ $req->training?->nama_pelatihan ?? $req->training_need ?? $req->scope_program ?? 'N/A' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $req->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    @if(in_array($req->status, ['pending', 'menunggu_review']))
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span> Menunggu
                                        </span>
                                    @elseif($req->status === 'approved')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span> Disetujui
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span> Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-xs text-slate-500 italic">
                                    {{ $req->catatan_admin ? '"'.$req->catatan_admin.'"' : '-' }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-400 text-xs">
                                    Belum ada riwayat pengajuan pelatihan dari divisi Anda.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pelatihan Siap Diajukan (1 Column) -->
            <div class="bg-surface rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Pelatihan Tersedia</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Program dibuka untuk pendaftaran</p>
                        </div>
                        <a href="{{ route('department.trainings.index') }}" class="text-xs font-bold text-primary-600 hover:underline">
                            Semua &rarr;
                        </a>
                    </div>

                    <div class="space-y-3.5">
                        @forelse($availableTrainings as $training)
                        <div class="p-3.5 rounded-xl bg-slate-50/70 border border-slate-100 hover:border-emerald-200 transition-colors">
                            <div class="flex justify-between items-start">
                                <h4 class="text-sm font-bold text-slate-800 leading-snug">{{ $training->nama_pelatihan }}</h4>
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded uppercase">
                                    {{ $training->metode_pelaksanaan }}
                                </span>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-slate-400 mt-2">
                                <span><i class="ph-bold ph-calendar mr-1"></i>{{ \Carbon\Carbon::parse($training->tanggal_mulai)->format('d M') }}</span>
                                <span><i class="ph-bold ph-users mr-1"></i>Kuota: {{ $training->kuota }}</span>
                            </div>
                            <div class="mt-3 pt-2.5 border-t border-slate-100 flex justify-end">
                                <a href="{{ route('department.trainings.request', $training->id) }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                                    Ajukan Karyawan &rarr;
                                </a>
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-slate-400 text-center py-6">Tidak ada pelatihan yang dibuka saat ini.</p>
                        @endforelse
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 mt-4">
                    <a href="{{ route('department.trainings.index') }}" class="block text-center text-xs font-bold text-emerald-600 hover:text-emerald-700 py-2">
                        Buka Katalog Pelatihan Selengkapnya &rarr;
                    </a>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
