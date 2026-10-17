<x-app-layout>
    <x-slot name="header">
        Data Karyawan Departemen {{ $department->nama_departemen ?? '' }}
    </x-slot>

    <div class="w-full space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Data Karyawan & Sertifikasi</h2>
                <p class="text-sm text-slate-500 mt-1">
                    Daftar karyawan, riwayat pelatihan, dan masa berlaku sertifikat di departemen <strong>{{ $department->nama_departemen ?? '-' }}</strong> (Mode Lihat Saja / Read-Only).
                </p>
            </div>

            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">
                <i class="ph-bold ph-eye text-base text-primary-600"></i>
                Akses Read-Only Departemen
            </span>
        </div>

        <!-- 4 Kartu Ringkasan Departemen -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-blue-500 relative hover:shadow-md transition-shadow">
                <div class="absolute top-5 right-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">TOTAL</div>
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mb-4">
                    <i class="ph-fill ph-users text-xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $totalEmployees ?? 0 }}</h3>
                <p class="text-sm text-slate-500 font-medium mt-1">Total Karyawan Divisi</p>
            </div>

            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-emerald-500 relative hover:shadow-md transition-shadow">
                <div class="absolute top-5 right-5 text-[10px] font-bold text-emerald-500 uppercase tracking-widest">AKTIF</div>
                <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 mb-4">
                    <i class="ph-fill ph-user-check text-xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $activeEmployees ?? 0 }}</h3>
                <p class="text-sm text-slate-500 font-medium mt-1">Karyawan Aktif</p>
            </div>

            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-indigo-500 relative hover:shadow-md transition-shadow">
                <div class="absolute top-5 right-5 text-[10px] font-bold text-indigo-500 uppercase tracking-widest">SERTIFIKAT</div>
                <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 mb-4">
                    <i class="ph-fill ph-seal-check text-xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $totalCertifications ?? 0 }}</h3>
                <p class="text-sm text-slate-500 font-medium mt-1">Total Sertifikat Dimiliki</p>
            </div>

            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-rose-500 relative hover:shadow-md transition-shadow">
                <div class="absolute top-5 right-5 text-[10px] font-bold text-rose-500 uppercase tracking-widest">PERHATIAN</div>
                <div class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 mb-4">
                    <i class="ph-fill ph-warning-circle text-xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $expiringCertifications ?? 0 }}</h3>
                <p class="text-sm text-slate-500 font-medium mt-1">Sertifikat Segera/Sudah Habis</p>
            </div>
        </div>

        <div class="bg-surface rounded-2xl shadow-sm border border-slate-100 p-6">

            <div class="mb-6 border-b border-slate-100 pb-6 flex justify-between items-end">
                <form action="{{ route('department.employees.index') }}" method="GET" class="flex gap-3 w-full max-w-md">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIK, Nama, atau Jabatan..." class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg text-sm focus:border-primary-500 focus:ring-0 outline-none transition-colors">
                    <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-700 transition-colors">Cari</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest w-1/3">NAMA KARYAWAN & NIK</th>
                            <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">JABATAN & SITE</th>
                            <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">PELATIHAN</th>
                            <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-center">SERTIFIKAT</th>
                            <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">STATUS</th>
                            <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">DETAIL</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">

                        @forelse ($employees as $employee)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-4">
                                        @php
                                            $nameParts = explode(' ', $employee->nama);
                                            $initials = isset($nameParts[1])
                                                ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[1], 0, 1))
                                                : strtoupper(substr($nameParts[0], 0, 2));
                                        @endphp
                                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 font-bold flex items-center justify-center shrink-0 text-sm">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <a href="{{ route('department.employees.show', $employee->id) }}" class="text-sm font-bold text-slate-800 hover:text-primary-600 transition-colors">
                                                {{ $employee->nama }}
                                            </a>
                                            <p class="text-[11px] text-slate-400 mt-0.5">NIK: {{ $employee->nik }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="text-xs font-bold text-slate-700">{{ $employee->level_jabatan ?? '-' }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $employee->jabatan ?? '-' }}</div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-600">
                                        {{ $employee->trainings->count() }} Program
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600">
                                        {{ $employee->certifications->count() }} Sertifikat
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    @php
                                        $aktifVal = strtolower($employee->status_aktif ?? 'aktif');
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold border uppercase {{ $aktifVal === 'aktif' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $aktifVal === 'aktif' ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                        {{ $aktifVal }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <a href="{{ route('department.employees.show', $employee->id) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary-600 hover:text-primary-700 bg-primary-50 px-3 py-1.5 rounded-lg transition-colors">
                                        <i class="ph-bold ph-eye"></i> Lihat Profil & Sertifikat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400 font-medium text-sm">
                                    Belum ada data karyawan yang terdaftar di departemen Anda.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <div class="mt-6 border-t border-slate-100 pt-6">
                {{ $employees->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
