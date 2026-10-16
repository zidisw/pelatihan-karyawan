<x-app-layout>
    <x-slot name="header">Detail Katalog Pelatihan</x-slot>

    @php
        $approvedCount = $training->approvedParticipantsCount();
        $remainingQuota = $training->remainingQuota();
        $quotaPercent = $training->quotaPercentage();
        $isOpen = $training->status_pendaftaran === 'dibuka'
            && !in_array($training->status_program, ['dibatalkan', 'selesai'])
            && $remainingQuota > 0;
    @endphp

    <div class="w-full max-w-5xl mx-auto space-y-6">
        <!-- Top Navigation & CTA -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('department.trainings.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition mb-1.5">
                    <i class="ph-bold ph-arrow-left"></i> Kembali ke Katalog Pelatihan
                </a>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Detail Program Pelatihan</h2>
            </div>

            <div class="flex items-center gap-3">
                @if($isOpen)
                    <a href="{{ route('department.trainings.request', $training->id) }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-600/20 transition">
                        <i class="ph-bold ph-users-three text-base"></i>
                        Ajukan Karyawan Departemen
                    </a>
                @else
                    <span class="px-4 py-2.5 bg-slate-100 text-slate-500 text-xs font-bold rounded-xl">
                        Pendaftaran Ditutup / Kuota Penuh
                    </span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Columns: Main Details & Documents -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-6">
                    <!-- Badges -->
                    <div class="flex flex-wrap items-center gap-2">
                        @if($training->kode_program)
                            <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 text-[10px] font-extrabold uppercase tracking-wider">
                                {{ $training->kode_program }}
                            </span>
                        @endif
                        @if($training->kategori)
                            <span class="px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-200 text-[10px] font-extrabold uppercase tracking-wider">
                                {{ $training->kategori }}{{ $training->jenis_sertifikasi ? ' • ' . $training->jenis_sertifikasi : '' }}
                            </span>
                        @endif
                        <span class="px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 border border-blue-200 text-[10px] font-extrabold uppercase tracking-wider">
                            {{ $training->metode_pelaksanaan }}
                        </span>
                        <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider {{ $training->status_pendaftaran === 'dibuka' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                            Pendaftaran {{ str_replace('_', ' ', $training->status_pendaftaran) }}
                        </span>
                    </div>

                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800">{{ $training->nama_pelatihan }}</h3>
                        <p class="text-xs text-slate-500 mt-2 leading-relaxed whitespace-pre-line">
                            {{ $training->deskripsi ?: 'Tidak ada deskripsi tambahan untuk program pelatihan ini.' }}
                        </p>
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-100 text-xs">
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Instruktur / Lembaga</span>
                            <span class="font-extrabold text-slate-800 mt-0.5 block">{{ $training->instruktur ?? 'Belum ditentukan' }}</span>
                        </div>
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Lokasi / Tempat Pelaksanaan</span>
                            <span class="font-extrabold text-slate-800 mt-0.5 block">{{ $training->lokasi ?? '-' }}</span>
                        </div>
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Jadwal Pelaksanaan</span>
                            <span class="font-extrabold text-slate-800 mt-0.5 block">
                                {{ \Carbon\Carbon::parse($training->tanggal_mulai)->format('d M Y') }} – {{ \Carbon\Carbon::parse($training->tanggal_selesai)->format('d M Y') }}
                            </span>
                        </div>
                        <div class="p-3.5 bg-amber-50/70 rounded-xl border border-amber-200/70">
                            <span class="text-[10px] font-bold text-amber-700 uppercase block">Batas Akhir Pendaftaran</span>
                            <span class="font-extrabold text-amber-900 mt-0.5 block">
                                {{ $training->batas_pendaftaran ? \Carbon\Carbon::parse($training->batas_pendaftaran)->format('d M Y') : (\Carbon\Carbon::parse($training->tanggal_mulai)->format('d M Y')) }}
                            </span>
                        </div>
                    </div>

                    @if($training->persyaratan_peserta)
                        <div class="p-4 bg-blue-50/50 border border-blue-100 rounded-xl text-xs">
                            <div class="text-[10px] font-extrabold text-blue-700 uppercase tracking-wider mb-1">Persyaratan Peserta:</div>
                            <p class="font-medium text-slate-700 whitespace-pre-line">{{ $training->persyaratan_peserta }}</p>
                        </div>
                    @endif

                    <div>
                        <div class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-2">Target Departemen Peserta:</div>
                        @if(!empty($targetDepartmentNames))
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($targetDepartmentNames as $deptName)
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold">{{ $deptName }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold">Terbuka untuk Seluruh Departemen</span>
                        @endif
                    </div>
                </div>

                <!-- Dokumen Pendukung (Silabus / Brosur / TOR) -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-extrabold text-slate-800">Dokumen Pendukung Program (Silabus / Brosur / TOR)</h4>
                            <p class="text-xs text-slate-400">Unduh materi atau panduan resmi sebelum mendaftarkan karyawan Anda</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold">
                            {{ $training->documents->count() }} Dokumen
                        </span>
                    </div>

                    @if($training->documents->isNotEmpty())
                        <div class="divide-y divide-slate-100 border border-slate-200 rounded-xl overflow-hidden">
                            @foreach($training->documents as $doc)
                                <div class="p-3.5 flex items-center justify-between gap-3 bg-white hover:bg-slate-50 transition">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                            <i class="ph-fill ph-file-text text-lg"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-xs font-extrabold text-slate-800 truncate">{{ $doc->nama_file }}</div>
                                            <div class="text-[10px] font-bold text-slate-400 uppercase">
                                                {{ strtoupper($doc->jenis_dokumen) }}
                                                @if($doc->ukuran_file) • {{ number_format($doc->ukuran_file / 1024, 1) }} KB @endif
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                       class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 text-xs font-bold transition shrink-0 inline-flex items-center gap-1">
                                        <i class="ph-bold ph-download-simple"></i> Lihat / Unduh
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-6 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200 text-xs text-slate-400 font-semibold">
                            Belum ada dokumen silabus/brosur/TOR yang dilampirkan pada program ini.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Dynamic Quota & Department Participants -->
            <div class="space-y-6">
                <!-- Panel Kuota Dinamis (BR-08) -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
                    <h4 class="text-sm font-extrabold text-slate-800">Informasi Kuota Kursi</h4>
                    <div class="grid grid-cols-3 gap-2 text-center bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase">Kuota Total</div>
                            <div class="text-base font-extrabold text-slate-800 mt-0.5">{{ $training->kuota }}</div>
                        </div>
                        <div class="border-x border-slate-200">
                            <div class="text-[10px] font-bold text-slate-400 uppercase">Terisi</div>
                            <div class="text-base font-extrabold text-blue-600 mt-0.5">{{ $approvedCount }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase">Sisa Kuota</div>
                            <div class="text-base font-extrabold {{ $remainingQuota > 0 ? 'text-emerald-600' : 'text-rose-600' }} mt-0.5">{{ $remainingQuota }}</div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[11px] font-bold text-slate-500 mb-1">
                            <span>Keterisian Kuota</span>
                            <span>{{ $quotaPercent }}%</span>
                        </div>
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full {{ $remainingQuota > 0 ? 'bg-blue-600' : 'bg-rose-500' }}" style="width: {{ $quotaPercent }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Peserta dari Departemen Anda -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-4">
                    <div>
                        <h4 class="text-sm font-extrabold text-slate-800">Peserta Divisi Anda</h4>
                        <p class="text-xs text-slate-400">Karyawan departemen Anda yang terdaftar di program ini</p>
                    </div>

                    @if($departmentParticipants->isNotEmpty())
                        <div class="divide-y divide-slate-100 border border-slate-100 rounded-xl overflow-hidden">
                            @foreach($departmentParticipants as $part)
                                <div class="p-3 flex items-center justify-between gap-2 text-xs">
                                    <div>
                                        <div class="font-extrabold text-slate-800">{{ $part->employee?->nama ?? '-' }}</div>
                                        <div class="text-[11px] text-slate-400">NIK: {{ $part->employee?->nik ?? '-' }}</div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[10px] font-bold uppercase">
                                        {{ str_replace('_', ' ', $part->status_peserta) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-5 text-center bg-slate-50 rounded-xl text-xs text-slate-400 font-medium">
                            Belum ada karyawan divisi Anda yang terdaftar sebagai peserta aktif.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
