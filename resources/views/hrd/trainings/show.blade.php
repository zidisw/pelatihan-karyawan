<x-app-layout>
    <x-slot name="header">
        Detail Program Katalog Pelatihan
    </x-slot>

    <div class="w-full space-y-6">

        <!-- Header & Navigasi + Aksi BR-11 -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <a href="{{ route('hrd.trainings.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-primary-600 transition-colors">
                <i class="ph-bold ph-arrow-left text-base"></i> Kembali ke Katalog Pelatihan
            </a>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('hrd.trainings.edit', $training->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-700 font-bold text-xs shadow-xs hover:bg-amber-500 hover:text-white transition-all">
                    <i class="ph-bold ph-pencil-simple text-sm"></i> Edit Program
                </a>

                <!-- Form Cepat Ubah Status Siklus Program (Aktif / Berjalan / Selesai / Dibatalkan-Arsip) -->
                <form action="{{ route('hrd.trainings.archive', $training->id) }}" method="POST" class="inline-flex items-center gap-1.5 m-0">
                    @csrf
                    @method('PATCH')
                    <select name="status_program" onchange="this.form.submit()" class="px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 font-bold text-xs shadow-xs focus:border-primary-500 outline-none cursor-pointer">
                        <option value="akan_datang" {{ ($training->status_program ?? 'akan_datang') === 'akan_datang' ? 'selected' : '' }}>Status: AKTIF / AKAN DATANG</option>
                        <option value="draft" {{ $training->status_program === 'draft' ? 'selected' : '' }}>Status: DRAFT</option>
                        <option value="berjalan" {{ $training->status_program === 'berjalan' ? 'selected' : '' }}>Status: BERJALAN</option>
                        <option value="selesai" {{ $training->status_program === 'selesai' ? 'selected' : '' }}>Status: SELESAI</option>
                        <option value="dibatalkan" {{ $training->status_program === 'dibatalkan' ? 'selected' : '' }}>Status: DIBATALKAN / ARSIP</option>
                    </select>
                </form>

                @if(!$training->hasTransactions())
                    <form action="{{ route('hrd.trainings.destroy', $training->id) }}" method="POST" class="inline-flex m-0" onsubmit="return confirm('Hapus program pelatihan ini secara permanen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 font-bold text-xs hover:bg-rose-600 hover:text-white transition-all">
                            <i class="ph-bold ph-trash"></i> Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-2xl font-bold flex items-center gap-2">
                <i class="ph-fill ph-check-circle text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="p-4 text-sm text-amber-800 bg-amber-50 border border-amber-200 rounded-2xl font-bold flex items-center gap-2">
                <i class="ph-fill ph-warning-circle text-lg text-amber-600"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @php
            $terdaftarDisetujui = $training->approvedParticipantsCount();
            $kuotaTotal = max(1, (int) $training->kuota);
            $sisaKuota = $training->remainingQuota();
            $persenKuota = $training->quotaPercentage();

            $targetDeptIds = is_array($training->target_departemen) ? $training->target_departemen : [];
            $targetDeptNames = !empty($targetDeptIds) && isset($departments)
                ? $departments->whereIn('id', $targetDeptIds)->pluck('nama_departemen')->toArray()
                : [];
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Kolom Kiri (2/3): Detail Lengkap, Persyaratan, Dokumen Pendukung, & Daftar Peserta -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Card Detail Utama Program -->
                <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 space-y-6">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="px-3 py-1 rounded-lg text-xs font-extrabold bg-slate-100 text-slate-700 border border-slate-200">
                                {{ $training->kode_program ?? '#TR-' . str_pad($training->id, 3, '0', STR_PAD_LEFT) }}
                            </span>
                            @if($training->kategori)
                                <span class="px-3 py-1 rounded-lg text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    {{ $training->kategori }}
                                    @if($training->jenis_sertifikasi)
                                        &bull; {{ $training->jenis_sertifikasi }}
                                    @endif
                                </span>
                            @endif
                            <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-primary-50 text-primary-700 border border-primary-100">
                                {{ $training->metode_pelaksanaan }}
                            </span>
                            <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-slate-100 text-slate-600">
                                Siklus: {{ strtoupper($training->status_program ?? 'AKTIF') }}
                            </span>
                        </div>

                        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 leading-tight">
                            {{ $training->nama_pelatihan }}
                        </h1>
                    </div>

                    <div>
                        <h3 class="text-xs font-extrabold text-slate-400 uppercase tracking-wider mb-2">Deskripsi & Cakupan Materi</h3>
                        <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">
                            {{ $training->deskripsi ?? 'Tidak ada deskripsi rinci untuk pelatihan ini.' }}
                        </p>
                    </div>

                    @if($training->persyaratan_peserta)
                        <div class="p-4 rounded-2xl bg-amber-50/50 border border-amber-200/70">
                            <h3 class="text-xs font-extrabold text-amber-800 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                                <i class="ph-fill ph-check-circle text-sm"></i> Persyaratan & Kualifikasi Peserta
                            </h3>
                            <p class="text-xs text-slate-700 leading-relaxed whitespace-pre-line font-medium">{{ $training->persyaratan_peserta }}</p>
                        </div>
                    @endif

                    <!-- Grid Instruktur, Lokasi, Estimasi Biaya, & Target Departemen -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100">
                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-primary-600 shadow-xs shrink-0">
                                <i class="ph-fill ph-chalkboard-teacher text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Instruktur / Lembaga Penyelenggara</p>
                                <p class="text-xs font-bold text-slate-800 mt-0.5">{{ $training->instruktur ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-indigo-600 shadow-xs shrink-0">
                                <i class="ph-fill ph-map-pin text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Lokasi / Tautan Pelaksanaan</p>
                                <p class="text-xs font-bold text-slate-800 mt-0.5">{{ $training->lokasi ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5 pt-3 border-t border-slate-200/60">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-emerald-600 shadow-xs shrink-0">
                                <i class="ph-fill ph-currency-circle-dollar text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Estimasi Biaya</p>
                                <p class="text-xs font-extrabold text-slate-800 mt-0.5">
                                    {{ $training->estimasi_biaya !== null ? 'Rp ' . number_format((float) $training->estimasi_biaya, 0, ',', '.') : 'Tidak ditentukan' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5 pt-3 border-t border-slate-200/60">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-purple-600 shadow-xs shrink-0">
                                <i class="ph-fill ph-buildings text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Target Departemen</p>
                                @if(!empty($targetDeptNames))
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($targetDeptNames as $dName)
                                            <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-700 border border-purple-200 text-[10px] font-bold">{{ $dName }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-xs font-bold text-slate-700 mt-0.5">Terbuka untuk Semua Departemen</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($training->catatan_internal)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs">
                            <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">Catatan Internal HRD</p>
                            <p class="text-slate-600 font-medium whitespace-pre-line">{{ $training->catatan_internal }}</p>
                        </div>
                    @endif
                </div>

                <!-- Card Dokumen Pendukung (Silabus / Brosur / TOR) -->
                <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                        <div>
                            <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                                <i class="ph-fill ph-folder-open text-primary-600 text-lg"></i>
                                Dokumen Pendukung (Silabus / Brosur / TOR)
                            </h3>
                            <p class="text-xs text-slate-400 mt-0.5">Lampiran materi acuan dan pedoman pelaksanaan program pelatihan</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                            {{ $training->documents->count() }} File
                        </span>
                    </div>

                    @if($training->documents->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($training->documents as $doc)
                                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/70 flex items-center justify-between gap-3 text-xs">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-primary-600 shrink-0">
                                            <i class="ph-fill ph-file-pdf text-xl"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="font-bold text-slate-800 hover:text-primary-600 truncate block">
                                                {{ $doc->nama_file }}
                                            </a>
                                            <p class="text-[11px] text-slate-400 mt-0.5">
                                                {{ $doc->ukuran_file ? number_format($doc->ukuran_file / 1024, 1) . ' KB' : 'Dokumen' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg bg-primary-50 text-primary-600 hover:bg-primary-600 hover:text-white font-bold text-[11px] transition-colors">
                                            Unduh
                                        </a>
                                        <form action="{{ route('hrd.trainings.documents.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1.5 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white font-bold text-[11px] transition-colors">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-6 text-center text-slate-400 text-xs bg-slate-50/60 rounded-2xl border border-dashed border-slate-200">
                            Belum ada dokumen pendukung (Silabus / Brosur / TOR) yang diunggah. Anda dapat menambahkannya melalui menu <strong>Edit Program</strong>.
                        </div>
                    @endif
                </div>

                <!-- Card Peserta Terdaftar -->
                <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                    <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Daftar Peserta Terdaftar</h3>
                            <p class="text-xs text-slate-400 mt-0.5">Karyawan yang terdaftar mengikuti sesi pelatihan ini</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-primary-50 text-primary-700">
                            {{ $training->employees->count() }} Peserta
                        </span>
                    </div>

                    @if ($training->employees->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                                        <th class="pb-3 px-3">Nama Karyawan & NIK</th>
                                        <th class="pb-3 px-3">Departemen</th>
                                        <th class="pb-3 px-3">Status Peserta</th>
                                        <th class="pb-3 px-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach ($training->employees as $peserta)
                                        @php
                                            $pStatus = $peserta->pivot->status_peserta ?? 'disetujui';
                                        @endphp
                                        <tr class="hover:bg-slate-50/70 transition-colors">
                                            <td class="py-3 px-3">
                                                <a href="{{ route('hrd.employees.show', $peserta->id) }}" class="font-bold text-slate-800 hover:text-primary-600 transition-colors">
                                                    {{ $peserta->nama }}
                                                </a>
                                                <div class="text-[11px] text-slate-400">NIK: {{ $peserta->nik }}</div>
                                            </td>
                                            <td class="py-3 px-3 font-semibold text-slate-600">
                                                {{ $peserta->department->nama_departemen ?? '-' }}
                                            </td>
                                            <td class="py-3 px-3">
                                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                                    @if(in_array($pStatus, ['selesai', 'lulus'])) bg-emerald-50 text-emerald-700 border border-emerald-200
                                                    @elseif(in_array($pStatus, ['sedang_pelatihan', 'terjadwal'])) bg-amber-50 text-amber-700 border border-amber-200
                                                    @elseif($pStatus === 'disetujui') bg-blue-50 text-blue-700 border border-blue-200
                                                    @else bg-slate-100 text-slate-600 border border-slate-200 @endif">
                                                    {{ str_replace('_', ' ', $pStatus) }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-3 text-right">
                                                <a href="{{ route('hrd.employees.show', $peserta->id) }}" class="inline-flex items-center text-xs font-bold text-primary-600 hover:underline">
                                                    Profil &rarr;
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-10 text-slate-400">
                            <i class="ph-fill ph-users-three text-3xl mb-2 text-slate-300 block"></i>
                            <p class="text-xs font-semibold">Belum ada peserta yang terdaftar di pelatihan ini.</p>
                        </div>
                    @endif
                </div>

                <!-- Card Riwayat Permintaan / Usulan Pelatihan Ini -->
                @if($training->requests && $training->requests->count() > 0)
                    <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100">
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                            <h3 class="text-base font-bold text-slate-800">Riwayat Usulan Departemen untuk Pelatihan Ini</h3>
                            <a href="{{ route('hrd.requests.index') }}" class="text-xs font-bold text-primary-600 hover:underline">
                                Modul Persetujuan &rarr;
                            </a>
                        </div>
                        <div class="space-y-3">
                            @foreach($training->requests as $req)
                                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                                    <div>
                                        <span class="font-bold text-slate-800">
                                            {{ $req->nomor_request ?? '#REQ-' . str_pad($req->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <span class="text-slate-500 ml-1">
                                            &bull; {{ $req->department?->nama_departemen ?? $req->employee?->department?->nama_departemen ?? 'Departemen' }}
                                        </span>
                                        <div class="text-[11px] text-slate-400 mt-0.5">
                                            Diusulkan oleh: {{ $req->diajukanOleh->name ?? 'Admin Departemen' }}
                                            @if($req->participants && $req->participants->count() > 0)
                                                ({{ $req->participants->count() }} Peserta)
                                            @endif
                                        </div>
                                    </div>
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                        @if(in_array($req->status, ['approved', 'disetujui'])) bg-emerald-50 text-emerald-700
                                        @elseif(in_array($req->status, ['pending', 'menunggu_review'])) bg-amber-50 text-amber-700
                                        @else bg-rose-50 text-rose-700 @endif">
                                        {{ $req->status }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- Kolom Kanan (1/3): Status Pendaftaran, Kuota Dinamis (BR-08), & Jadwal -->
            <div class="space-y-6">

                <!-- Status Pendaftaran & Siklus Program -->
                <div class="bg-surface rounded-3xl p-6 shadow-sm border border-slate-100 text-center space-y-3">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2">Status Pendaftaran</p>
                        @php
                            $isPendaftaranBuka = $training->status_pendaftaran === 'dibuka' && $sisaKuota > 0 && !in_array($training->status_program, ['dibatalkan', 'arsip', 'selesai']);
                        @endphp
                        <div class="px-4 py-2 rounded-2xl {{ $isPendaftaranBuka ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }} font-extrabold text-xs uppercase tracking-wide inline-flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full {{ $isPendaftaranBuka ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                            {{ $isPendaftaranBuka ? 'DIBUKA' : 'DITUTUP' }}
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Status Siklus Program</p>
                        <span class="text-xs font-extrabold text-slate-700 uppercase">{{ $training->status_program ?? 'AKTIF' }}</span>
                    </div>
                </div>

                <!-- Perhitungan Kuota Dinamis (BR-08) -->
                <div class="bg-surface rounded-3xl p-6 shadow-sm border border-slate-100">
                    <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center justify-between">
                        <span>Perhitungan Kuota (BR-08)</span>
                        <span class="text-xs font-extrabold text-primary-600">{{ $persenKuota }}% Terisi</span>
                    </h3>

                    <!-- Progress Bar -->
                    <div class="w-full bg-slate-100 rounded-full h-2.5 mb-4 overflow-hidden">
                        <div class="{{ $sisaKuota <= 0 ? 'bg-rose-500' : 'bg-primary-600' }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $persenKuota }}%"></div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 text-center text-xs">
                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
                            <p class="text-[10px] text-slate-400 font-bold uppercase">Kuota Total</p>
                            <p class="font-extrabold text-slate-800 text-base mt-0.5">{{ $kuotaTotal }}</p>
                        </div>
                        <div class="p-2.5 rounded-xl bg-blue-50 border border-blue-100">
                            <p class="text-[10px] text-blue-600 font-bold uppercase">Disetujui</p>
                            <p class="font-extrabold text-blue-700 text-base mt-0.5">{{ $terdaftarDisetujui }}</p>
                        </div>
                        <div class="p-2.5 rounded-xl {{ $sisaKuota > 0 ? 'bg-emerald-50 border border-emerald-100' : 'bg-rose-50 border border-rose-100' }}">
                            <p class="text-[10px] {{ $sisaKuota > 0 ? 'text-emerald-600' : 'text-rose-600' }} font-bold uppercase">Sisa Kuota</p>
                            <p class="font-extrabold {{ $sisaKuota > 0 ? 'text-emerald-700' : 'text-rose-700' }} text-base mt-0.5">{{ $sisaKuota }}</p>
                        </div>
                    </div>
                </div>

                <!-- Informasi Jadwal & Batas Pendaftaran -->
                <div class="bg-surface rounded-3xl p-6 shadow-sm border border-slate-100">
                    <h3 class="text-sm font-bold text-slate-800 mb-4">Jadwal & Batas Pendaftaran</h3>
                    <div class="space-y-3.5 text-xs">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">Batas Pendaftaran</span>
                            <span class="font-bold text-amber-700">
                                {{ $training->batas_pendaftaran ? \Carbon\Carbon::parse($training->batas_pendaftaran)->format('d M Y') : 'Sebelum Mulai' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between pb-3 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">Tanggal Mulai</span>
                            <span class="font-bold text-slate-700">
                                {{ $training->tanggal_mulai ? \Carbon\Carbon::parse($training->tanggal_mulai)->format('d M Y') : '-' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between pb-3 border-b border-slate-50">
                            <span class="text-slate-400 font-medium">Tanggal Selesai</span>
                            <span class="font-bold text-slate-700">
                                {{ $training->tanggal_selesai ? \Carbon\Carbon::parse($training->tanggal_selesai)->format('d M Y') : '-' }}
                            </span>
                        </div>
                        @if($training->tanggal_mulai && $training->tanggal_selesai)
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400 font-medium">Durasi Pelatihan</span>
                                <span class="font-bold text-primary-600">
                                    {{ \Carbon\Carbon::parse($training->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($training->tanggal_selesai)) + 1 }} Hari
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>
