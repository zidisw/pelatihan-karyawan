<x-app-layout>
    <x-slot name="header">
        Dashboard Superadmin
    </x-slot>

    <div class="w-full space-y-6">

        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-primary-600 to-indigo-700 rounded-3xl p-6 md:p-8 text-white shadow-lg shadow-primary-600/10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 text-white text-xs font-semibold backdrop-blur-sm mb-3">
                    <i class="ph-fill ph-shield-check text-sm"></i> Hak Akses Tertinggi
                </span>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Selamat Datang, {{ auth()->user()->name }}!</h2>
                <p class="text-white/80 text-sm mt-1 max-w-xl">Kelola seluruh pengguna sistem, hak akses departemen, dan pantau infrastruktur data pelatihan karyawan.</p>
            </div>
            <div class="flex flex-wrap gap-2.5">
                <a href="{{ route('superadmin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white text-primary-700 font-bold text-sm shadow hover:bg-slate-50 transition-all">
                    <i class="ph-bold ph-user-plus text-base"></i> Tambah User
                </a>
                <a href="{{ route('superadmin.departments.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-sm backdrop-blur-sm transition-all border border-white/20">
                    <i class="ph-bold ph-plus text-base"></i> Tambah Departemen
                </a>
            </div>
        </div>

        <!-- 4 Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            <!-- Total Users -->
            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-primary-600 shadow-sm">
                        <i class="ph-fill ph-users text-2xl"></i>
                    </div>
                    <span class="text-xs font-bold text-primary-600 bg-primary-50 px-2.5 py-1 rounded-full">Akun Aktif</span>
                </div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Total Pengguna</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalUsers }}</h3>
                <div class="flex items-center gap-1.5 mt-3 text-[11px] text-slate-500">
                    <span class="font-bold text-purple-600">{{ $usersByRole['superadmin'] }} Super</span> •
                    <span class="font-bold text-blue-600">{{ $usersByRole['hrd'] }} HRD</span> •
                    <span class="font-bold text-emerald-600">{{ $usersByRole['departemen'] }} Dept</span>
                </div>
            </div>

            <!-- Total Departments -->
            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm">
                        <i class="ph-fill ph-buildings text-2xl"></i>
                    </div>
                    <a href="{{ route('superadmin.departments.index') }}" class="text-xs font-bold text-indigo-600 hover:underline">Kelola &rarr;</a>
                </div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Departemen</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalDepartments }}</h3>
                <p class="text-xs text-slate-400 mt-3 font-medium">Unit kerja terdaftar</p>
            </div>

            <!-- Total Employees -->
            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm">
                        <i class="ph-fill ph-identification-badge text-2xl"></i>
                    </div>
                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">Karyawan</span>
                </div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Total Karyawan</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalEmployees }}</h3>
                <p class="text-xs text-slate-400 mt-3 font-medium">Di semua departemen</p>
            </div>

            <!-- Total Trainings -->
            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600 shadow-sm">
                        <i class="ph-fill ph-graduation-cap text-2xl"></i>
                    </div>
                    <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2.5 py-1 rounded-full">Program</span>
                </div>
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Program Pelatihan</p>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalTrainings }}</h3>
                <p class="text-xs text-slate-400 mt-3 font-medium">Katalog pelatihan aktif</p>
            </div>

        </div>

        <!-- 2 Columns: Recent Users & Department Breakdown -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Recent Users (2 Columns) -->
            <div class="lg:col-span-2 bg-surface rounded-2xl p-6 shadow-sm border border-slate-100">
                <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Pengguna Sistem Terbaru</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Daftar akun yang memiliki akses ke sistem</p>
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
                                <td class="py-3 px-3">
                                    <div class="font-bold text-slate-800">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $user->email }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    @if($user->role === 'superadmin')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                            Superadmin
                                        </span>
                                    @elseif($user->role === 'hrd')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                            HRD
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Departemen
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-xs text-slate-600">
                                    {{ $user->department->nama_departemen ?? 'Semua / Pusat' }}
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <a href="{{ route('superadmin.users.edit', $user->id) }}" class="inline-flex items-center text-xs font-semibold text-slate-500 hover:text-primary-600 px-2 py-1 rounded hover:bg-slate-100">
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

            <!-- Departments Overview (1 Column) -->
            <div class="bg-surface rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Master Departemen</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Ringkasan unit kerja</p>
                        </div>
                        <a href="{{ route('superadmin.departments.create') }}" class="text-xs font-bold text-primary-600 hover:underline">
                            + Baru
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($departments as $dept)
                        <div class="p-3.5 rounded-xl bg-slate-50/70 border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-sm">
                                    <i class="ph-fill ph-building text-base"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-800">{{ $dept->nama_departemen }}</h4>
                                    <p class="text-[11px] text-slate-400 line-clamp-1">{{ $dept->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold bg-white text-slate-600 shadow-sm border border-slate-100">
                                {{ $dept->employees_count }} karyawan
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
