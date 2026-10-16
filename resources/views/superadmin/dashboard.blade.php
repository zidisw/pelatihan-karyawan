<x-app-layout>
    <x-slot name="header">
        Dashboard Superadmin
    </x-slot>

    <div class="w-full space-y-6">

        <!-- Welcome Banner (Proporsional & Rapi) -->
        <div class="bg-gradient-to-r from-primary-600 to-indigo-700 rounded-2xl p-6 md:p-7 text-white shadow-sm flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 text-white text-[11px] font-bold uppercase tracking-wider backdrop-blur-sm mb-2.5">
                    <i class="ph-fill ph-shield-check text-sm text-emerald-300"></i> Hak Akses Tertinggi (Superadmin)
                </span>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Selamat Datang, {{ auth()->user()->name }}!</h2>
                <p class="text-white/85 text-xs md:text-sm mt-1 max-w-2xl">
                    Kelola seluruh pengguna sistem, master departemen, katalog pelatihan, serta pantau riwayat sertifikasi dan kompetensi karyawan.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2.5 shrink-0">
                <a href="{{ route('superadmin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-primary-700 font-bold text-xs md:text-sm shadow-sm hover:bg-slate-50 transition-all">
                    <i class="ph-bold ph-user-plus text-base"></i> Tambah User
                </a>
                <a href="{{ route('superadmin.departments.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/15 hover:bg-white/25 text-white font-bold text-xs md:text-sm backdrop-blur-sm transition-all border border-white/20">
                    <i class="ph-bold ph-buildings text-base"></i> Tambah Departemen
                </a>
            </div>
        </div>

        <!-- 4 Kartu Statistik Utama (Seragam, Rapi, & Klikable Penuh) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            <!-- Card 1: Total Pengguna -->
            <a href="{{ route('superadmin.users.index') }}" class="group bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-blue-500 hover:border-primary-300 hover:shadow-md hover:-translate-y-0.5 transition-all flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="ph-fill ph-users text-2xl"></i>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            Kelola <i class="ph-bold ph-arrow-right"></i>
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Total Pengguna</p>
                    <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalUsers }}</h3>
                </div>
                <div class="flex flex-wrap items-center gap-1.5 mt-4 pt-3 border-t border-slate-100 text-[11px]">
                    <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-700 font-bold">{{ $usersByRole['superadmin'] }} Super</span>
                    <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-bold">{{ $usersByRole['hrd'] }} HRD</span>
                    <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 font-bold">{{ $usersByRole['departemen'] }} Dept</span>
                </div>
            </a>

            <!-- Card 2: Master Departemen -->
            <a href="{{ route('superadmin.departments.index') }}" class="group bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-indigo-500 hover:border-indigo-300 hover:shadow-md hover:-translate-y-0.5 transition-all flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <i class="ph-fill ph-buildings text-2xl"></i>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            Kelola <i class="ph-bold ph-arrow-right"></i>
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Master Departemen</p>
                    <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalDepartments }}</h3>
                </div>
                <p class="text-xs text-slate-400 mt-4 pt-3 border-t border-slate-100 font-medium">
                    Unit kerja resmi terdaftar di sistem
                </p>
            </a>

            <!-- Card 3: Total Karyawan -->
            <a href="{{ route('hrd.employees.index') }}" class="group bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-emerald-500 hover:border-emerald-300 hover:shadow-md hover:-translate-y-0.5 transition-all flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <i class="ph-fill ph-identification-badge text-2xl"></i>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            Buka Data <i class="ph-bold ph-arrow-right"></i>
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Total Karyawan</p>
                    <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalEmployees }}</h3>
                </div>
                <p class="text-xs text-slate-400 mt-4 pt-3 border-t border-slate-100 font-medium">
                    Karyawan terdaftar di seluruh departemen
                </p>
            </a>

            <!-- Card 4: Katalog Pelatihan -->
            <a href="{{ route('hrd.trainings.index') }}" class="group bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-amber-500 hover:border-amber-300 hover:shadow-md hover:-translate-y-0.5 transition-all flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                            <i class="ph-fill ph-graduation-cap text-2xl"></i>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                            Buka Katalog <i class="ph-bold ph-arrow-right"></i>
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Katalog Pelatihan</p>
                    <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalTrainings }}</h3>
                </div>
                <p class="text-xs text-slate-400 mt-4 pt-3 border-t border-slate-100 font-medium">
                    Program pelatihan di katalog Raja Academy
                </p>
            </a>

        </div>

        <!-- 2 Kolom Bawah: Pengguna Sistem Terbaru & Master Departemen -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-10">

            <!-- Pengguna Sistem Terbaru (2 Kolom) -->
            <div class="lg:col-span-2 bg-surface rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Pengguna Sistem Terbaru</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Daftar akun yang memiliki akses ke sistem Raja Academy</p>
                    </div>
                    <a href="{{ route('superadmin.users.index') }}" class="text-xs font-bold text-primary-600 hover:text-primary-700 bg-primary-50 px-3 py-1.5 rounded-lg hover:bg-primary-100 transition-colors">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                <th class="pb-3 px-3">Nama & Email</th>
                                <th class="pb-3 px-3">Role</th>
                                <th class="pb-3 px-3">Departemen</th>
                                <th class="pb-3 px-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentUsers as $user)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-3.5 px-3">
                                    <div class="font-bold text-slate-800">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $user->email }}</div>
                                </td>
                                <td class="py-3.5 px-3">
                                    @if($user->role === 'superadmin')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                            Superadmin
                                        </span>
                                    @elseif($user->role === 'hrd')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            HRD
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Departemen
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-3 text-xs font-semibold text-slate-600">
                                    {{ $user->department->nama_departemen ?? 'Semua / Pusat' }}
                                </td>
                                <td class="py-3.5 px-3 text-right">
                                    <a href="{{ route('superadmin.users.edit', $user->id) }}" class="inline-flex items-center text-xs font-bold text-slate-600 hover:text-primary-600 px-2.5 py-1.5 rounded-lg bg-slate-50 hover:bg-primary-50 border border-slate-200/80 transition-colors">
                                        <i class="ph-bold ph-pencil-simple mr-1"></i> Edit
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400 text-xs">Belum ada akun pengguna.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Master Departemen (1 Kolom) -->
            <div class="bg-surface rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Master Departemen</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Ringkasan unit kerja</p>
                        </div>
                        <a href="{{ route('superadmin.departments.create') }}" class="text-xs font-bold text-primary-600 bg-primary-50 px-2.5 py-1 rounded-lg hover:bg-primary-100 transition-colors">
                            + Baru
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($departments as $dept)
                        <div class="p-3.5 rounded-xl bg-slate-50/70 border border-slate-100 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-100 flex items-center justify-center font-bold text-sm shrink-0">
                                    <i class="ph-fill ph-buildings text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="text-sm font-bold text-slate-800 truncate">{{ $dept->nama_departemen }}</h4>
                                    <p class="text-[11px] text-slate-400 truncate">{{ $dept->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-white text-slate-600 shadow-sm border border-slate-100 shrink-0">
                                {{ $dept->employees_count }} Karyawan
                            </span>
                        </div>
                        @empty
                        <p class="text-xs text-slate-400 text-center py-4">Belum ada data departemen.</p>
                        @endforelse
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 mt-4">
                    <a href="{{ route('superadmin.departments.index') }}" class="block text-center text-xs font-bold text-primary-600 hover:text-primary-700 py-2">
                        Buka Manajemen Departemen Selengkapnya &rarr;
                    </a>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
