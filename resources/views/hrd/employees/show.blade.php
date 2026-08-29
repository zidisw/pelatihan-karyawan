<x-app-layout>

    <div class="w-full space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Data Karyawan</h2>
                <p class="text-sm text-slate-500 mt-1">Monitoring status pelatihan dan riwayat kompetensi karyawan.</p>
            </div>

            {{-- <a href="{{ route('hrd.employees.edit', $employee->id) }}">
                <button
                    class="flex items-center justify-center gap-2 bg-primary-600 text-white px-5 py-2.5 rounded-xl font-medium hover:bg-primary-700 transition-colors shadow-[0_4px_12px_rgba(37,99,235,0.2)]">
                    <i class="ph-bold ph-user-plus text-lg"></i>
                    Edit Karyawan
                </button>
            </a> --}}
        </div>
        <div class="w-full space-y-6">
            <!-- Scrollable Page Content -->
            <div class="flex-1 overflow-y-auto px-4 md:px-6 py-2 relative">
                <div class="w-full space-y-6">
                    <!-- 2-Column Layout -->
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pb-20">

                        <!-- Left Column: Profile Card -->
                        <div class="lg:col-span-4 flex flex-col gap-6">

                            <!-- Main Profile Card -->
                            <div
                                class="bg-surface rounded-[32px] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex flex-col items-center relative">
                                <!-- Avatar -->
                                <div class="relative w-36 h-36 mb-6">
                                    <div
                                        class="w-full h-full rounded-[32px] bg-slate-100 overflow-hidden border-4 border-white shadow-md">
                                        <img src=""
                                            alt="Andi Saputra" class="w-full h-full object-cover"
                                            onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0iI2NiZDVlMSI+PHBhdGggZD0iTTEyIDJhNSA1IDAgMSAwIDAgMTAgNSA1IDAgMCAwIDAtMTB6bS03IDEyYS41LjUgMCAwIDAtLjUuNXYuNUE3LjUgNy41IDAgMCAwIDEyIDIyaDcuNWE3LjUgNy41IDAgMCAwIDcuNS03LjVWMjJhLjUuNSAwIDAgMC0uNS0uNWgtMTR6Ii8+PC9zdmc+'">
                                    </div>
                                    <!-- Verified Badge -->
                                    <div
                                        class="absolute -bottom-2 -right-2 w-8 h-8 bg-primary-600 rounded-full border-4 border-white flex items-center justify-center text-white shadow-sm">
                                        <i class="ph-fill ph-seal-check text-lg"></i>
                                    </div>
                                </div>

                                <!-- Name & ID -->
                                <h3 class="text-2xl font-bold text-slate-800 text-center">{{ $employee->nama }}</h3>
                                <p class="text-sm font-semibold text-primary-600 mt-1 mb-4">NIK: {{ $employee->nik }}
                                </p>

                                <!-- Tags -->
                                <div class="flex gap-2 mb-8">
                                    <span
                                        class="px-4 py-1.5 bg-blue-50 text-blue-600 border border-blue-100 rounded-full text-[11px] font-bold tracking-wide uppercase">{{ $employee->department->nama_departemen ?? 'Departemen tidak tersedia' }}</span>
                                    <span
                                        class="px-4 py-1.5 bg-purple-50 text-purple-600 border border-purple-100 rounded-full text-[11px] font-bold tracking-wide uppercase">{{ $employee->status_karyawan ?? 'Status tidak tersedia' }}</span>
                                </div>

                                <!-- Contact Info List -->
                                <div class="w-full space-y-6 border-t border-slate-100 pt-8">
                                    <div class="flex items-start gap-5">
                                        <div class="text-slate-400 mt-0.5"><i class="ph ph-briefcase text-xl"></i></div>
                                        <div>
                                            <p
                                                class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                                                JABATAN</p>
                                            <p class="text-sm font-bold text-slate-700">
                                                {{ $employee->jabatan ?? 'Jabatan tidak tersedia' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-5">
                                        <div class="text-primary-500 mt-0.5"><i
                                                class="ph ph-envelope-simple text-xl"></i>
                                        </div>
                                        <div>
                                            <p
                                                class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                                                EMAIL</p>
                                            <p class="text-sm font-bold text-slate-700">
                                                {{ $employee->email ?? 'Email tidak tersedia' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-5">
                                        <div class="text-primary-500 mt-0.5"><i class="ph ph-phone text-xl"></i></div>
                                        <div>
                                            <p
                                                class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                                                TELEPON</p>
                                            <p class="text-sm font-bold text-slate-700">
                                                {{ $employee->nomor_hp ?? 'Nomor telepon tidak tersedia' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Secondary Card: Join Date -->
                            <div
                                class="bg-surface rounded-[24px] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex items-center gap-5">
                                <div
                                    class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500">
                                    <i class="ph ph-calendar-blank text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tgl
                                        Bergabung</p>
                                    <p class="text-base font-bold text-slate-800">{{ $employee->tanggal_join }}</p>
                                    {{-- <p class="text-base font-bold text-slate-800">{{ $employee->tanggal_join ? $employee->tanggal_join->format('d M Y') : 'Tanggal join tidak tersedia' }}</p> --}}
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Stats & History -->
                        <div class="lg:col-span-8 flex flex-col gap-8">
                            <!-- Training History Section -->
                            <div
                                class="bg-surface rounded-[32px] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center gap-3">
                                        <i class="ph ph-scroll text-2xl text-primary-600"></i>
                                        <h3 class="text-lg font-bold text-slate-800">Riwayat Pelatihan</h3>
                                    </div>
                                    <a href="#"
                                        class="text-sm font-bold text-primary-600 hover:text-primary-700" @disabled(true)>Lihat
                                        Semua</a>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse min-w-[600px]">
                                        <thead>
                                            <tr class="border-b border-slate-100">
                                                <th
                                                    class="pb-4 pt-2 px-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest w-[40%]">
                                                    NAMA PELATIHAN</th>
                                                <th
                                                    class="pb-4 pt-2 px-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                                                    TANGGAL</th>
                                                <th
                                                    class="pb-4 pt-2 px-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                                                    STATUS</th>
                                                <th
                                                    class="pb-4 pt-2 px-2 text-[11px] font-bold text-slate-400 uppercase tracking-widest text-right">
                                                    SKOR/LINK</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50">
                                            @forelse($employee->trainings as $training)
                                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                                    <td class="py-5 px-2">
                                                        <p class="text-sm font-bold text-slate-800">
                                                            {{ $training->nama_pelatihan }}
                                                        </p>
                                                        <p class="text-[11px] text-slate-400 mt-1">
                                                            {{ $training->penyelenggara ?? 'Internal Training' }}
                                                        </p>
                                                    </td>

                                                    <td class="py-5 px-2">
                                                        <span class="text-sm font-medium text-slate-600">
                                                            {{ \Carbon\Carbon::parse($training->tanggal_mulai)->format('d M Y') }}
                                                        </span>
                                                    </td>

                                                    <td class="py-5 px-2">
                                                        @php
                                                            $today = now()->toDateString();
                                                        @endphp

                                                        @if ($training->tanggal_selesai < $today)
                                                            <div class="flex items-center gap-2">
                                                                <span
                                                                    class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                                <span class="text-xs font-bold text-emerald-600">
                                                                    Selesai
                                                                </span>
                                                            </div>
                                                        @elseif($training->tanggal_mulai <= $today && $training->tanggal_selesai >= $today)
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-bold">
                                                                <span
                                                                    class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span>
                                                                In Progress
                                                            </span>
                                                        @else
                                                            <span
                                                                class="inline-flex items-center px-2.5 py-1 rounded-full bg-amber-50 text-amber-600 text-[10px] font-bold">
                                                                <span
                                                                    class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                                                Pending
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td class="py-5 px-2 text-right">
                                                        <div class="flex items-center justify-end gap-3">

                                                            @if ($training->pivot->status_peserta)
                                                                <span class="text-sm font-bold text-slate-800">
                                                                    {{ $training->pivot->status_peserta }}
                                                                </span>
                                                            @endif

                                                            @if ($training->sertifikat_url)
                                                                <a href="{{ $training->sertifikat_url }}"
                                                                    target="_blank"
                                                                    class="p-1.5 text-primary-600 hover:bg-primary-50 rounded-lg transition-colors">
                                                                    <i class="ph ph-file-text text-lg"></i>
                                                                </a>
                                                            @endif

                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="py-8 text-center text-slate-400">
                                                        Belum ada riwayat pelatihan.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Certifications Section -->
                            <div>
                                <div class="flex items-center gap-3 mb-5 px-2">
                                    <i class="ph ph-medal text-2xl text-primary-600"></i>
                                    <h3 class="text-lg font-bold text-slate-800">Daftar Sertifikasi</h3>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <!-- Cert Card 1 (Active) -->
                                    <div
                                        class="bg-surface rounded-[24px] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 relative overflow-hidden flex flex-col justify-between">
                                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-600"></div>
                                        <div class="flex items-start justify-between mb-5 pl-3">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                                    <i class="ph ph-shield-check text-2xl"></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-bold text-slate-800 leading-tight mb-1">
                                                        Ahli K3
                                                        Umum</h4>
                                                    <p class="text-[11px] text-slate-500">Kemnaker RI</p>
                                                </div>
                                            </div>
                                            <span
                                                class="px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase rounded-md tracking-wider border border-emerald-100">Aktif</span>
                                        </div>
                                        <div class="flex items-end justify-between mt-auto pl-3">
                                            <div>
                                                <p class="text-[11px] text-slate-400 font-semibold mb-1">Berlaku
                                                    hingga:
                                                </p>
                                                <p class="text-sm font-bold text-slate-700">20 Jan 2026</p>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <button
                                                    class="flex items-center gap-1.5 text-[11px] font-bold text-slate-500 hover:text-slate-800 transition-colors">
                                                    <i class="ph ph-eye text-sm"></i> Lihat File
                                                </button>
                                                <button
                                                    class="text-[11px] font-bold text-primary-600 hover:text-primary-700 flex items-center gap-1 uppercase tracking-wide">
                                                    Detail <i class="ph ph-arrow-square-out text-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cert Card 2 (Expired) -->
                                    <div
                                        class="bg-surface rounded-[24px] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 relative overflow-hidden flex flex-col justify-between">
                                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-red-500"></div>
                                        <div class="flex items-start justify-between mb-5 pl-3">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center shrink-0">
                                                    <i class="ph ph-fire text-2xl"></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-bold text-slate-800 leading-tight mb-1">
                                                        Fire
                                                        Safety Expert</h4>
                                                    <p class="text-[11px] text-slate-500">NFPA Association</p>
                                                </div>
                                            </div>
                                            <span
                                                class="px-2.5 py-1 bg-red-50 text-red-600 text-[10px] font-bold uppercase rounded-md tracking-wider border border-red-100">Kadaluarsa</span>
                                        </div>
                                        <div class="flex items-end justify-between mt-auto pl-3">
                                            <div>
                                                <p class="text-[11px] text-slate-400 font-semibold mb-1">Berakhir:</p>
                                                <p class="text-sm font-bold text-red-500">15 Des 2023</p>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <button
                                                    class="flex items-center gap-1.5 text-[11px] font-bold text-slate-500 hover:text-slate-800 transition-colors">
                                                    <i class="ph ph-eye text-sm"></i> Lihat File
                                                </button>
                                                <button
                                                    class="px-3 py-1.5 bg-blue-100 text-primary-700 text-[10px] font-bold rounded-lg uppercase tracking-wider hover:bg-blue-200 transition-colors">
                                                    Perbarui
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <!-- Floating Action Button (Edit Profile) -->
        <a href="{{ route('hrd.employees.edit', $employee->id) }}">
            <button
                class="fixed bottom-8 right-8 w-14 h-14 bg-primary-600 text-white rounded-full shadow-[0_4px_20px_rgba(37,99,235,0.4)] flex items-center justify-center hover:bg-primary-700 hover:scale-105 transition-all z-20 focus:outline-none">
                <i class="ph-fill ph-pencil-simple text-2xl"></i>
            </button>
        </a>
        </div>
    </div>
</x-app-layout>
