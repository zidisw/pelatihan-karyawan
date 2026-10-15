<x-app-layout>
    <x-slot name="header">
        Manajemen Data Karyawan
    </x-slot>

    <div class="w-full space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Data Karyawan</h2>
                <p class="text-sm text-slate-500 mt-1">Monitoring status pelatihan dan riwayat kompetensi karyawan.</p>
            </div>

            <a href="{{ route('hrd.employees.create') }}">
                <button class="flex items-center justify-center gap-2 bg-primary-600 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-primary-700 transition-colors shadow-[0_4px_12px_rgba(37,99,235,0.2)]">
                    <i class="ph-bold ph-user-plus text-lg"></i>
                    Tambah Karyawan
                </button>
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-emerald-700 bg-emerald-100 rounded-xl font-medium">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-blue-500 relative hover:shadow-md transition-shadow">
                <div class="absolute top-5 right-5 text-[10px] font-bold text-slate-400 uppercase tracking-widest">TOTAL</div>
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 mb-4">
                    <i class="ph-fill ph-users text-xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $employees->count() ?? 0 }}</h3>
                <p class="text-sm text-slate-500 font-medium mt-1">Total Karyawan</p>
            </div>
            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-emerald-500 relative hover:shadow-md transition-shadow">
                <div class="absolute top-5 right-5 text-[10px] font-bold text-emerald-500 uppercase tracking-widest">SELESAI</div>
                <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 mb-4">
                    <i class="ph-fill ph-seal-check text-xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $completedTrainings ?? 0 }}</h3>
                <p class="text-sm text-slate-500 font-medium mt-1">Pelatihan Selesai</p>
            </div>
            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-amber-500 relative hover:shadow-md transition-shadow">
                <div class="absolute top-5 right-5 text-[10px] font-bold text-amber-500 uppercase tracking-widest">PROGRES</div>
                <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-600 mb-4">
                    <i class="ph-fill ph-trend-up text-xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $onProgressTrainings ?? 0 }}</h3>
                <p class="text-sm text-slate-500 font-medium mt-1">Sedang Berjalan</p>
            </div>
            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-rose-500 relative hover:shadow-md transition-shadow">
                <div class="absolute top-5 right-5 text-[10px] font-bold text-rose-500 uppercase tracking-widest">PENDING</div>
                <div class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center text-rose-600 mb-4">
                    <i class="ph-fill ph-hourglass-high text-xl"></i>
                </div>
                <h3 class="text-3xl font-bold text-slate-800">{{ $pendingTrainings ?? 0 }}</h3>
                <p class="text-sm text-slate-500 font-medium mt-1">Belum Mulai</p>
            </div>
        </div>

        <div class="bg-surface rounded-2xl shadow-sm border border-slate-100 p-6">

            <div class="mb-6 border-b border-slate-100 pb-6 flex justify-between items-end">
                <form action="{{ route('hrd.employees.index') }}" method="GET" class="flex gap-3 w-full max-w-md">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIK atau Nama..." class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg text-sm focus:border-primary-500 focus:ring-0 outline-none transition-colors">
                    <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-700 transition-colors">Cari</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest w-1/4">NAMA KARYAWAN & NIK</th>
                            <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">DEPARTEMEN</th>
                            <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">LEVEL JABATAN</th>
                            <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">JOB TITLE</th>
                            <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">STATUS</th>
                            <th class="py-4 px-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">AKSI</th>
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
                                            <a href="{{ route('hrd.employees.show', $employee->id) }}" class="text-sm font-bold text-slate-800 hover:text-primary-600 transition-colors">
                                                {{ $employee->nama }}
                                            </a>
                                            <p class="text-[11px] text-slate-400 mt-0.5">NIK: {{ $employee->nik }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-xs font-bold text-slate-600">
                                        {{ $employee->department ? $employee->department->nama_departemen : '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-xs font-bold text-slate-700">{{ $employee->level_jabatan ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-xs font-medium text-slate-500">{{ $employee->jabatan ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    @php
                                        $statusClass = match(strtolower($employee->status_karyawan)) {
                                            'tetap' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'kontrak' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            default => 'bg-slate-100 text-slate-600 border-slate-200',
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold border uppercase {{ $statusClass }}">
                                        {{ $employee->status_karyawan }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right space-x-1.5">
                                    <a href="{{ route('hrd.employees.show', $employee->id) }}" class="inline-block text-xs font-bold text-primary-600 hover:text-primary-700 bg-primary-50 px-3 py-1.5 rounded-lg transition-colors">Detail</a>
                                    <a href="{{ route('hrd.employees.edit', $employee->id) }}" class="inline-block text-xs font-bold text-amber-500 hover:text-amber-600 bg-amber-50 px-3 py-1.5 rounded-lg transition-colors">Edit</a>

                                    <form action="{{ route('hrd.employees.destroy', $employee->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus karyawan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-600 bg-rose-50 px-3 py-1.5 rounded-lg transition-colors">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 font-medium text-sm">
                                    Belum ada data karyawan. Silahkan tambah data baru.
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
