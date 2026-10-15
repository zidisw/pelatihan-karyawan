<x-app-layout>
    <x-slot name="header">
        Profil & Sertifikasi Karyawan
    </x-slot>

    <div class="w-full space-y-6">

        <!-- Top Navigation -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('department.employees.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-primary-600 transition-colors">
                <i class="ph-bold ph-arrow-left text-base"></i> Kembali ke Data Karyawan Departemen
            </a>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 font-bold text-xs border border-slate-200">
                <i class="ph-bold ph-eye text-sm text-primary-600"></i> Mode Lihat Saja (Read-Only)
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Kolom Kiri: Profil Identitas & Sertifikasi Karyawan -->
            <div class="space-y-6">

                <!-- Profile Card -->
                <div class="bg-surface rounded-3xl p-6 shadow-sm border border-slate-100 text-center relative overflow-hidden">
                    <div class="w-full h-20 bg-gradient-to-r from-primary-500 to-indigo-600 absolute top-0 left-0"></div>

                    @php
                        $nameParts = explode(' ', $employee->nama);
                        $initials = isset($nameParts[1])
                            ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                            : strtoupper(substr($nameParts[0], 0, 2));
                    @endphp

                    <div class="relative mt-8 mb-4 inline-block">
                        <div class="w-24 h-24 rounded-full bg-white p-1 shadow-md mx-auto">
                            <div class="w-full h-full rounded-full bg-primary-100 text-primary-700 font-extrabold text-2xl flex items-center justify-center">
                                {{ $initials }}
                            </div>
                        </div>
                        <span class="absolute bottom-1 right-1 w-5 h-5 rounded-full {{ $employee->status_karyawan === 'tetap' ? 'bg-emerald-500' : 'bg-blue-500' }} border-2 border-white" title="Status: {{ ucfirst($employee->status_karyawan) }}"></span>
                    </div>

                    <h2 class="text-xl font-extrabold text-slate-800">{{ $employee->nama }}</h2>
                    <p class="text-xs font-semibold text-slate-400 mt-0.5">NIK: {{ $employee->nik }}</p>

                    <div class="mt-3 flex justify-center gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-primary-50 text-primary-700 border border-primary-100">
                            {{ $employee->department->nama_departemen ?? 'Tanpa Departemen' }}
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $employee->status_karyawan === 'tetap' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-blue-50 text-blue-700 border border-blue-100' }}">
                            {{ strtoupper($employee->status_karyawan) }}
                        </span>
                    </div>

                    <!-- Detail Kontak & Pekerjaan -->
                    <div class="mt-6 pt-6 border-t border-slate-100 text-left space-y-3.5 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 font-medium flex items-center gap-1.5">
                                <i class="ph-bold ph-medal"></i> Level Jabatan
                            </span>
                            <span class="font-bold text-slate-700">{{ $employee->level_jabatan ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 font-medium flex items-center gap-1.5">
                                <i class="ph-bold ph-briefcase"></i> Job Title
                            </span>
                            <span class="font-bold text-slate-700">{{ $employee->jabatan ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 font-medium flex items-center gap-1.5">
                                <i class="ph-bold ph-map-pin"></i> Site / Unit
                            </span>
                            <span class="font-bold text-slate-700">{{ $employee->site_unit ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 font-medium flex items-center gap-1.5">
                                <i class="ph-bold ph-envelope"></i> Email
                            </span>
                            <span class="font-semibold text-slate-700 truncate max-w-[180px]">{{ $employee->email ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 font-medium flex items-center gap-1.5">
                                <i class="ph-bold ph-phone"></i> No. HP
                            </span>
                            <span class="font-semibold text-slate-700">{{ $employee->nomor_hp ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-400 font-medium flex items-center gap-1.5">
                                <i class="ph-bold ph-calendar"></i> Tanggal Join
                            </span>
                            <span class="font-bold text-slate-700">
                                {{ $employee->tanggal_join ? \Carbon\Carbon::parse($employee->tanggal_join)->format('d M Y') : '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Card Sertifikasi Karyawan -->
                <div class="bg-surface rounded-3xl p-6 shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <i class="ph-fill ph-seal-check text-emerald-500 text-lg"></i> Sertifikasi Resmi
                        </h3>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600">
                            {{ $employee->certifications->count() }}
                        </span>
                    </div>

                    <div class="space-y-3">
                        @forelse($employee->certifications as $cert)
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                            <div class="flex items-center justify-between gap-2">
                                <h4 class="font-bold text-slate-800">{{ $cert->nama_sertifikasi }}</h4>
                                @if($cert->jenis_sertifikasi)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100">
                                        {{ $cert->jenis_sertifikasi }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-slate-400 text-[11px] mt-0.5">No: {{ $cert->nomor_sertifikat ?? '-' }}</p>
                            <div class="flex items-center justify-between text-[11px] text-slate-500 mt-2 pt-2 border-t border-slate-200/60">
                                <span>Terbit: {{ $cert->tanggal_terbit ? \Carbon\Carbon::parse($cert->tanggal_terbit)->format('d M Y') : '-' }}</span>
                                @if($cert->tanggal_expired && !$cert->is_permanent)
                                    <span class="{{ \Carbon\Carbon::parse($cert->tanggal_expired)->isPast() ? 'text-rose-600 font-bold' : 'text-emerald-600 font-semibold' }}">
                                        Exp: {{ \Carbon\Carbon::parse($cert->tanggal_expired)->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-slate-400 font-semibold">Permanen</span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-slate-400 text-center py-4">Belum ada riwayat sertifikasi resmi.</p>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Kolom Kanan: Rekam Jejak Pelatihan & Status Kompetensi (Read-Only) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- 4 Stat Badges Pelatihan Karyawan -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-surface p-4 rounded-2xl shadow-sm border border-slate-100 text-center">
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pelatihan</p>
                        <h4 class="text-2xl font-extrabold text-slate-800 mt-1">{{ $totalTrainings }}</h4>
                    </div>
                    <div class="bg-surface p-4 rounded-2xl shadow-sm border border-slate-100 text-center">
                        <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider">Selesai / Lulus</p>
                        <h4 class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $completedTrainings }}</h4>
                    </div>
                    <div class="bg-surface p-4 rounded-2xl shadow-sm border border-slate-100 text-center">
                        <p class="text-[11px] font-bold text-amber-600 uppercase tracking-wider">Sedang / Terjadwal</p>
                        <h4 class="text-2xl font-extrabold text-amber-600 mt-1">{{ $inProgressTrainings }}</h4>
                    </div>
                    <div class="bg-surface p-4 rounded-2xl shadow-sm border border-slate-100 text-center">
                        <p class="text-[11px] font-bold text-blue-600 uppercase tracking-wider">Usulan Pending</p>
                        <h4 class="text-2xl font-extrabold text-blue-600 mt-1">{{ $pendingRequests }}</h4>
                    </div>
                </div>

                <!-- Bagian 1: Pelatihan yang Diikuti -->
                <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Riwayat Partisipasi Pelatihan</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Program pelatihan yang telah atau sedang dijalani oleh karyawan ini</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($employee->trainings as $training)
                        <div class="p-5 rounded-2xl bg-slate-50/70 border border-slate-100 space-y-3">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-primary-100 text-primary-700">
                                            {{ $training->metode_pelaksanaan }}
                                        </span>
                                        <span class="text-xs font-semibold text-slate-400">{{ $training->kode_program ?? '#TR-' . str_pad($training->id, 3, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <h4 class="text-base font-extrabold text-slate-800 mt-1">
                                        {{ $training->nama_pelatihan }}
                                    </h4>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    @php
                                        $status = $training->pivot->status_peserta ?? 'terjadwal';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                        @if(in_array($status, ['selesai', 'lulus'])) bg-emerald-100 text-emerald-800
                                        @elseif(in_array($status, ['sedang_pelatihan', 'terjadwal'])) bg-amber-100 text-amber-800
                                        @elseif($status === 'disetujui') bg-blue-100 text-blue-800
                                        @else bg-slate-200 text-slate-700 @endif">
                                        {{ str_replace('_', ' ', $status) }}
                                    </span>
                                </div>
                            </div>

                            <p class="text-xs text-slate-500 line-clamp-2">{{ $training->deskripsi }}</p>

                            <div class="pt-3 border-t border-slate-200/60 flex flex-wrap gap-4 text-xs text-slate-500">
                                <span><i class="ph-bold ph-chalkboard-teacher mr-1 text-slate-400"></i>Instruktur: <strong>{{ $training->instruktur ?? '-' }}</strong></span>
                                <span><i class="ph-bold ph-calendar mr-1 text-slate-400"></i>{{ \Carbon\Carbon::parse($training->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($training->tanggal_selesai)->format('d M Y') }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="py-10 text-center text-slate-400">
                            <i class="ph-fill ph-book-open text-3xl mb-2 text-slate-300"></i>
                            <p class="text-xs font-semibold">Karyawan ini belum terdaftar di program pelatihan manapun.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Bagian 2: Pengajuan Pelatihan Terkait Karyawan Ini -->
                <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Riwayat Usulan Pelatihan</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Daftar permintaan pelatihan yang pernah diajukan untuk karyawan ini</p>
                        </div>
                        <a href="{{ route('department.trainings.history') }}" class="text-xs font-bold text-primary-600 hover:underline">
                            Riwayat Pengajuan &rarr;
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                    <th class="pb-3 px-3">Program Pelatihan</th>
                                    <th class="pb-3 px-3">Diusulkan Oleh</th>
                                    <th class="pb-3 px-3">Status</th>
                                    <th class="pb-3 px-3">Catatan HRD</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($employee->trainingRequests as $req)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3 px-3">
                                        <div class="font-bold text-slate-800">{{ $req->training?->nama_pelatihan ?? $req->training_need ?? $req->scope_program ?? '-' }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $req->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="py-3 px-3 font-semibold text-slate-600">
                                        {{ $req->diajukanOleh->name ?? 'Admin' }}
                                    </td>
                                    <td class="py-3 px-3">
                                        @if(in_array($req->status, ['pending', 'menunggu_review']))
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                PENDING
                                            </span>
                                        @elseif(in_array($req->status, ['approved', 'partially_approved']))
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                APPROVED
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                {{ strtoupper($req->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-slate-500 italic">
                                        {{ $req->catatan_admin ?? '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400 text-xs">
                                        Belum ada data pengajuan pelatihan untuk karyawan ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
