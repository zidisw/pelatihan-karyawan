<x-app-layout>
    <x-slot name="header">
        Dashboard Utama HRD
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="w-full space-y-6">

        <!-- 4 Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Total Karyawan -->
            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-primary-600">
                        <i class="ph-fill ph-users text-xl"></i>
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-primary-600 bg-primary-50 px-2.5 py-1 rounded-full">
                        Aktif
                    </span>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-medium mb-1">Total Karyawan</p>
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $employees ?? 0 }}</h3>
                </div>
            </div>

            <!-- Karyawan Pernah Pelatihan -->
            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                        <i class="ph-fill ph-chalkboard-teacher text-xl"></i>
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-purple-600 bg-purple-50 px-2.5 py-1 rounded-full">
                        Selesai
                    </span>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-medium mb-1">Karyawan Terlatih</p>
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $trainedEmployees ?? 0 }}</h3>
                </div>
            </div>

            <!-- Pelatihan Dibuka -->
            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500">
                        <i class="ph-fill ph-graduation-cap text-xl"></i>
                    </div>
                    <a href="{{ route('hrd.trainings.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-full hover:bg-amber-100">
                        Kelola &rarr;
                    </a>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-medium mb-1">Pelatihan Dibuka</p>
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $activeTrainings ?? 0 }}</h3>
                </div>
            </div>

            <!-- Pengajuan Menunggu -->
            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-500">
                        <i class="ph-fill ph-clipboard-text text-xl"></i>
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-full">
                        Perlu Review
                    </span>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-medium mb-1">Pengajuan Pending</p>
                    <h3 class="text-3xl font-extrabold text-slate-800">{{ $pendingRequestsCount ?? 0 }}</h3>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-10">

            <!-- Chart & Daftar Karyawan (2 Columns) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Chart Partisipasi Per Departemen -->
                <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col h-[400px]">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Distribusi Karyawan Per Departemen</h3>
                            <p class="text-sm text-slate-500">Jumlah karyawan aktif per unit kerja</p>
                        </div>
                        <span class="px-3 py-1 text-xs font-bold bg-primary-50 text-primary-700 rounded-lg">
                            Realtime Data
                        </span>
                    </div>

                    <div class="flex-1 mt-2 relative w-full min-h-[220px]">
                        <canvas id="participationChart"></canvas>
                    </div>
                </div>

                <!-- Karyawan Terbaru -->
                <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Karyawan Terbaru</h3>
                            <p class="text-xs text-slate-400">Data karyawan yang baru ditambahkan ke sistem</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('hrd.employees.index') }}" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-primary-600 bg-primary-50 hover:bg-primary-100 rounded-lg transition-colors">
                                Lihat Semua
                            </a>
                            <a href="{{ route('hrd.employees.create') }}" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors">
                                <i class="ph-bold ph-plus"></i> Tambah Karyawan
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                    <th class="pb-3 px-2">Nama & NIK</th>
                                    <th class="pb-3 px-2">Departemen</th>
                                    <th class="pb-3 px-2">Jabatan</th>
                                    <th class="pb-3 px-2">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($recentEmployees as $emp)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3 px-2">
                                        <div class="font-bold text-slate-800">{{ $emp->nama }}</div>
                                        <div class="text-xs text-slate-400">NIK: {{ $emp->nik }}</div>
                                    </td>
                                    <td class="py-3 px-2 text-xs text-slate-600">
                                        {{ $emp->department->nama_departemen ?? '-' }}
                                    </td>
                                    <td class="py-3 px-2 text-xs text-slate-600">
                                        {{ $emp->jabatan ?? '-' }}
                                    </td>
                                    <td class="py-3 px-2">
                                        <span class="inline-flex px-2 py-0.5 text-[10px] font-bold rounded-full {{ $emp->status_karyawan === 'tetap' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-blue-50 text-blue-700 border border-blue-200' }}">
                                            {{ ucfirst($emp->status_karyawan) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400 text-xs">Belum ada data karyawan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Notifikasi Pengajuan Pelatihan (1 Column) -->
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('participationChart');
            if(ctx) {
                const labels = {!! json_encode($chartLabels ?? []) !!};
                const data = {!! json_encode($chartData ?? []) !!};

                new Chart(ctx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: labels.length ? labels : ['Belum ada departemen'],
                        datasets: [{
                            label: 'Jumlah Karyawan',
                            data: data.length ? data : [0],
                            backgroundColor: '#3b82f6',
                            hoverBackgroundColor: '#2563eb',
                            borderRadius: 8,
                            maxBarThickness: 45
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f1f5f9' },
                                ticks: { precision: 0, font: { family: 'Inter', size: 11 } }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { family: 'Inter', size: 11 } }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
