<x-app-layout>
    <x-slot name="header">
        Dashboard Departemen {{ $department->nama_departemen ?? '' }}
    </x-slot>

    <div class="w-full space-y-6">

        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 md:p-8 text-white shadow-lg shadow-blue-600/10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 text-white text-xs font-semibold backdrop-blur-sm mb-3">
                    <i class="ph-fill ph-buildings text-sm"></i> Departemen {{ $department->nama_departemen ?? 'User Portal' }}
                </span>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Halo, {{ $user->name }}!</h2>
                <p class="text-white/85 text-sm mt-1 max-w-xl">Pantau perkembangan kompetensi karyawan divisi Anda, pilih pelatihan dari katalog, atau ajukan usulan training baru ke tim HRGA.</p>
            </div>
            <div class="flex flex-wrap gap-2.5">
                <a href="{{ route('department.trainings.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-blue-700 font-bold text-xs shadow hover:bg-slate-50 transition-all">
                    <i class="ph-bold ph-books text-base"></i> Pilih dari Katalog
                </a>
                <a href="{{ route('department.requests.createNew') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs shadow transition-all">
                    <i class="ph-bold ph-plus-circle text-base"></i> + Request Training Baru
                </a>
                <a href="{{ route('department.trainings.history') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-xs backdrop-blur-sm transition-all border border-white/20">
                    <i class="ph-bold ph-clock-counter-clockwise text-base"></i> Riwayat Pengajuan
                </a>
            </div>
        </div>

        <!-- 4 Stat Cards Klikable dengan Satuan Metrik Jelas (PRD 8.1) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            <!-- Karyawan Departemen -->
            <a href="{{ route('department.employees.index') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:border-blue-200 hover:shadow-md transition-all group block">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-xs">
                        <i class="ph-fill ph-users text-2xl"></i>
                    </div>
                    <span class="text-xs font-bold text-blue-600 bg-blue-50 group-hover:bg-blue-100 px-2.5 py-1 rounded-full transition-colors">Lihat Data &rarr;</span>
                </div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Karyawan Divisi</p>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $departmentEmployeesCount }}</h3>
                    <span class="text-xs font-bold text-slate-400">Orang</span>
                </div>
                <p class="text-xs text-slate-400 mt-2 font-medium">Terdaftar di {{ $department->nama_departemen ?? 'Departemen ini' }}</p>
            </a>

            <!-- Pelatihan Tersedia -->
            <a href="{{ route('department.trainings.index') }}" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:border-indigo-200 hover:shadow-md transition-all group block">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-xs">
                        <i class="ph-fill ph-graduation-cap text-2xl"></i>
                    </div>
                    <span class="text-xs font-bold text-indigo-600 bg-indigo-50 group-hover:bg-indigo-100 px-2.5 py-1 rounded-full transition-colors">Buka Katalog &rarr;</span>
                </div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Pelatihan Dibuka</p>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $availableTrainingsCount }}</h3>
                    <span class="text-xs font-bold text-slate-400">Program</span>
                </div>
                <p class="text-xs text-slate-400 mt-2 font-medium">Tersedia untuk diajukan saat ini</p>
            </a>

            <!-- Pengajuan Menunggu -->
            <a href="{{ route('department.trainings.history', ['status' => 'pending_group']) }}" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:border-amber-200 hover:shadow-md transition-all group block">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 shadow-xs">
                        <i class="ph-fill ph-hourglass-medium text-2xl"></i>
                    </div>
                    <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full">Proses HRGA</span>
                </div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Menunggu Review</p>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $pendingRequestsCount }}</h3>
                    <span class="text-xs font-bold text-slate-400">Pengajuan</span>
                </div>
                <p class="text-xs text-slate-400 mt-2 font-medium">Sedang ditinjau oleh Admin HRGA</p>
            </a>

            <!-- Pengajuan Disetujui -->
            <a href="{{ route('department.trainings.history', ['status' => 'approved']) }}" class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:border-emerald-200 hover:shadow-md transition-all group block">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-xs">
                        <i class="ph-fill ph-check-circle text-2xl"></i>
                    </div>
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">Disetujui</span>
                </div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Total Disetujui</p>
                <div class="flex items-baseline gap-1.5 mt-1">
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $approvedRequestsCount }}</h3>
                    <span class="text-xs font-bold text-slate-400">Pengajuan</span>
                </div>
                <p class="text-xs text-slate-400 mt-2 font-medium">Termasuk disetujui penuh & sebagian</p>
            </a>

        </div>

        <!-- 2 Columns: Riwayat Pengajuan & Pelatihan Tersedia -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Riwayat Pengajuan Terbaru (2 Columns) -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-800">Pengajuan Terakhir Divisi Anda</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Daftar pengajuan terbaru lengkap dengan NIK peserta dan tanggal pengajuan</p>
                    </div>
                    <a href="{{ route('department.trainings.history') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 bg-blue-50 px-3.5 py-2 rounded-xl hover:bg-blue-100 transition-colors">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                <th class="pb-3 px-3">No. Request & Tanggal</th>
                                <th class="pb-3 px-3">Peserta & NIK</th>
                                <th class="pb-3 px-3">Pelatihan / Scope</th>
                                <th class="pb-3 px-3 text-center">Status</th>
                                <th class="pb-3 px-3">Catatan HRGA</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentRequests as $req)
                                @php
                                    $participants = $req->participants;
                                    $partCount = $participants->count() ?: ($req->employee ? 1 : 0);
                                    $firstPartEmp = $participants->first()?->employee ?? $req->employee;
                                    $firstPartName = $participants->first()?->nama_peserta ?? $firstPartEmp?->nama ?? 'Peserta';
                                    $firstPartNik = $firstPartEmp?->nik ?? '-';

                                    $statusBadge = match($req->status) {
                                        'approved', 'disetujui' => ['label' => 'Disetujui', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500'],
                                        'partially_approved' => ['label' => 'Disetujui Sebagian', 'class' => 'bg-blue-50 text-blue-700 border-blue-200', 'dot' => 'bg-blue-500'],
                                        'rejected', 'ditolak' => ['label' => 'Ditolak', 'class' => 'bg-rose-50 text-rose-700 border-rose-200', 'dot' => 'bg-rose-500'],
                                        'cancelled' => ['label' => 'Dibatalkan', 'class' => 'bg-slate-100 text-slate-600 border-slate-200', 'dot' => 'bg-slate-400'],
                                        'pending' => ['label' => 'Pending', 'class' => 'bg-amber-50 text-amber-700 border-amber-200', 'dot' => 'bg-amber-500'],
                                        default => ['label' => 'Menunggu Review', 'class' => 'bg-amber-50 text-amber-700 border-amber-200', 'dot' => 'bg-amber-500'],
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3.5 px-3 align-top">
                                        <div class="text-xs font-extrabold text-slate-800">{{ $req->nomor_request ?? ('#REQ-' . $req->id) }}</div>
                                        <div class="text-[11px] text-slate-500 font-medium mt-0.5">{{ $req->created_at->format('d M Y, H:i') }}</div>
                                    </td>
                                    <td class="py-3.5 px-3 align-top">
                                        <div class="text-xs font-bold text-slate-800">{{ $firstPartName }}</div>
                                        <div class="text-[11px] text-slate-400">NIK: {{ $firstPartNik }}</div>
                                        @if($partCount > 1)
                                            <span class="inline-block mt-1 px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-[10px] font-bold">
                                                +{{ $partCount - 1 }} peserta lainnya (Total {{ $partCount }} orang)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-3 align-top">
                                        <div class="flex items-center gap-1.5 mb-0.5">
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold uppercase {{ $req->jenis_request === 'training_baru' ? 'bg-indigo-50 text-indigo-700' : 'bg-blue-50 text-blue-700' }}">
                                                {{ $req->jenis_request === 'training_baru' ? 'Training Baru' : 'Katalog' }}
                                            </span>
                                        </div>
                                        <div class="font-bold text-slate-700 text-xs">{{ $req->training?->nama_pelatihan ?? $req->scope_program ?? $req->training_need ?? '-' }}</div>
                                    </td>
                                    <td class="py-3.5 px-3 align-top text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase border {{ $statusBadge['class'] }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $statusBadge['dot'] }} mr-1.5"></span>
                                            {{ $statusBadge['label'] }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-3 align-top text-xs text-slate-500 italic max-w-[160px]">
                                        {{ $req->catatan_admin ? '"' . $req->catatan_admin . '"' : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-10 text-center text-slate-400 text-xs font-medium">
                                        Belum ada riwayat pengajuan pelatihan dari divisi Anda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pelatihan Siap Diajukan (1 Column) dengan Sisa Kuota & Batas Pendaftaran (PRD 8.1) -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-lg font-extrabold text-slate-800">Pelatihan Tersedia</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Info sisa kuota & batas pendaftaran</p>
                        </div>
                        <a href="{{ route('department.trainings.index') }}" class="text-xs font-bold text-blue-600 hover:underline">
                            Semua &rarr;
                        </a>
                    </div>

                    <div class="space-y-3.5">
                        @forelse($availableTrainings as $training)
                            @php
                                $remainingQuota = $training->remainingQuota();
                            @endphp
                            <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-100 hover:border-blue-200 transition-colors space-y-2.5">
                                <div class="flex justify-between items-start gap-2">
                                    <div>
                                        @if($training->kode_program)
                                            <span class="text-[10px] font-extrabold text-slate-500 uppercase">{{ $training->kode_program }}</span>
                                        @endif
                                        <h4 class="text-xs font-extrabold text-slate-800 leading-snug">{{ $training->nama_pelatihan }}</h4>
                                    </div>
                                    <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded uppercase shrink-0">
                                        {{ $training->metode_pelaksanaan }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-2 text-[11px] text-slate-600 bg-white p-2.5 rounded-lg border border-slate-100">
                                    <div>
                                        <span class="text-[10px] text-slate-400 font-bold block uppercase">Sisa Kuota</span>
                                        <span class="font-extrabold {{ $remainingQuota > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $remainingQuota }} / {{ $training->kuota }} Kursi
                                        </span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-slate-400 font-bold block uppercase">Batas Daftar</span>
                                        <span class="font-extrabold text-amber-700">
                                            {{ $training->batas_pendaftaran ? \Carbon\Carbon::parse($training->batas_pendaftaran)->format('d M Y') : (\Carbon\Carbon::parse($training->tanggal_mulai)->format('d M Y')) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="pt-1 flex items-center justify-between gap-2">
                                    <a href="{{ route('department.trainings.show', $training->id) }}"
                                       class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 text-[11px] font-bold transition">
                                        Detail Katalog
                                    </a>
                                    <a href="{{ route('department.trainings.request', $training->id) }}"
                                       class="px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold transition flex items-center gap-1">
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
                    <a href="{{ route('department.trainings.index') }}" class="block text-center text-xs font-bold text-blue-600 hover:text-blue-700 py-2">
                        Buka Katalog Pelatihan Selengkapnya &rarr;
                    </a>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
