{{-- <x-app-layout>

</x-app-layout> --}}

<x-app-layout>
    <x-slot name="header">
        Dashboard Utama {{ Auth::user()->role }}
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="w-full space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100">
                <a href="{{ route('hrd.employees.index') }}">

                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="ph-fill ph-user text-xl"></i>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-medium mb-1">Total Karyawan</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ $employees ?? 0 }}</h3>
                </div>
                </a>

            </div>

            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100">
                <a href="{{ route('hrd.participants.index') }}">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-600">
                        <i class="ph-fill ph-chalkboard-teacher text-xl"></i>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-medium mb-1">Karyawan Pelatihan</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ $trainedEmployees ?? 0 }}</h3>
                </div>
                </a>
            </div>

            {{-- <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-500">
                        <i class="ph-fill ph-timer text-xl"></i>
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-full">
                        Stabil
                    </span>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-medium mb-1">Total Jam Pelatihan</p>
                    <h3 class="text-3xl font-bold text-slate-800">1.250</h3>
                </div>
            </div> --}}

            <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                        <i class="ph-fill ph-seal-check text-xl"></i>
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">
                        +20%
                    </span>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-medium mb-1">Tersertifikasi</p>
                    <h3 class="text-3xl font-bold text-slate-800">320</h3>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-10">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100 flex flex-col h-[400px]">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            {{-- <h3 class="text-lg font-bold text-slate-800">Partisipasi Per Departemen</h3> --}}
                            <p class="text-sm text-slate-500">Statistik keikutsertaan pelatihan 2024</p>
                        </div>
                        <div class="flex bg-slate-100 p-1 rounded-lg">
                            <button class="px-4 py-1.5 text-sm font-medium text-slate-500 rounded-md hover:bg-white hover:shadow-sm transition-all">Bulanan</button>
                            <button class="px-4 py-1.5 text-sm font-medium bg-primary-600 text-white rounded-md shadow-sm">Tahunan</button>
                        </div>
                    </div>

                    <div class="flex-1 mt-4 relative w-full min-h-[200px]">
                        <canvas id="participationChart"></canvas>
                    </div>
                </div>

                <div class="bg-surface p-6 rounded-2xl shadow-sm border border-slate-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-bold text-slate-800">Cari Karyawan</h3>
                        <div class="flex gap-3">
                            <button class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50">
                                <i class="ph-bold ph-faders"></i> Filter
                            </button>
                            <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors">
                                <i class="ph-bold ph-plus"></i> Tambah Baru
                            </a>
                        </div>
                    </div>
                    <div class="mt-4 h-24 bg-slate-50 rounded-lg border border-dashed border-slate-200 flex items-center justify-center text-slate-400 text-sm">
                        Daftar karyawan akan muncul di sini...
                    </div>
                </div>

            </div>

            <div class="bg-surface rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-800 leading-tight">Notifikasi Request<br>Pelatihan</h3>
                    <span class="w-6 h-6 rounded-full bg-red-600 text-white text-xs font-bold flex items-center justify-center">3</span>
                </div>

                <div class="flex-1 relative space-y-6 before:absolute before:inset-0 before:ml-1.5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-slate-100" style="--tw-translate-x: 0; left: 11px;">

                    <div class="relative flex items-start gap-4 -ml-[11px]">
                        <div class="absolute left-0 w-3 h-3 bg-primary-500 rounded-full border-4 border-surface shadow-sm z-10 top-1"></div>
                        <div class="pl-6 w-full">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-[10px] font-bold text-primary-600 uppercase tracking-wider">New Request</span>
                                <span class="text-[10px] text-slate-400">10 menit lalu</span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800 mb-1">Dedi Maulana - HSE</h4>
                            <p class="text-xs text-slate-500 leading-relaxed mb-3">Mengajukan pelatihan Sertifikasi Ahli K3 Kimia untuk periode November.</p>
                            <div class="flex gap-2">
                                <button class="px-4 py-1.5 bg-primary-600 text-white text-xs font-semibold rounded-md hover:bg-primary-700 transition-colors">Tinjau</button>
                                <button class="px-4 py-1.5 bg-white border border-slate-200 text-slate-600 text-xs font-semibold rounded-md hover:bg-slate-50 transition-colors">Abaikan</button>
                            </div>
                        </div>
                    </div>

                    <div class="relative flex items-start gap-4 pt-4 border-t border-slate-100 -ml-[11px]">
                        <div class="absolute left-0 w-3 h-3 bg-slate-300 rounded-full border-4 border-surface shadow-sm z-10 top-5"></div>
                        <div class="pl-6 w-full">
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Pending</span>
                                <span class="text-[10px] text-slate-400">2 jam lalu</span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800 mb-1">Siska Dewi - HRGA</h4>
                            <p class="text-xs text-slate-500 leading-relaxed mb-3">Pengajuan perpanjangan lisensi HR Management Professional.</p>
                        </div>
                    </div>

                    <div class="relative flex items-start gap-4 pt-4 border-t border-slate-100 opacity-50 -ml-[11px]">
                        <div class="absolute left-0 w-3 h-3 bg-slate-300 rounded-full border-4 border-surface shadow-sm z-10 top-5"></div>
                        <div class="pl-6 w-full">
                            <h4 class="text-sm font-bold text-slate-800 mb-1">Budi Santoso - Logistik</h4>
                            <p class="text-xs text-slate-500 line-clamp-1">Request training safety riding...</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('participationChart');
            if(ctx) {
                new Chart(ctx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ['HRGA', 'HSE', 'LOGISTIK', 'PLM', 'MED', 'MOD'],
                        datasets: [{
                            label: 'Partisipasi',
                            data: [60, 80, 40, 90, 50, 30],
                            backgroundColor: '#3b82f6',
                            hoverBackgroundColor: '#2563eb',
                            borderRadius: 6,
                            barThickness: 32,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                padding: 12,
                                titleFont: { family: 'Inter', size: 13 },
                                bodyFont: { family: 'Inter', size: 14, weight: 'bold' },
                                displayColors: false,
                                callbacks: {
                                    label: function (context) { return context.parsed.y + ' Peserta'; }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: '#f1f5f9', drawBorder: false },
                                border: { display: false },
                                ticks: { font: { family: 'Inter', size: 12 }, color: '#64748b', stepSize: 20 }
                            },
                            x: {
                                grid: { display: false, drawBorder: false },
                                border: { display: false },
                                ticks: { font: { family: 'Inter', size: 12, weight: '600' }, color: '#64748b' }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
