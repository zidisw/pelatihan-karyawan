<x-app-layout>
    <x-slot name="header">
        Katalog Pelatihan
    </x-slot>

    <div class="w-full space-y-6">

        <!-- Header Halaman -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Katalog Pelatihan</h2>
                <p class="text-sm text-slate-500 mt-1 font-medium">Kelola program pengembangan kompetensi, kuota riil peserta, dokumen silabus/TOR, dan status pelaksanaan di Raja Academy.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('hrd.trainings.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white text-sm font-bold rounded-xl shadow-[0_4px_12px_rgba(37,99,235,0.2)] hover:bg-primary-700 transition-colors">
                    <i class="ph-bold ph-plus text-base"></i>
                    Tambah Program Pelatihan
                </a>
            </div>
        </div>

        <!-- Notifikasi Flash Message -->
        @if(session('success'))
            <div class="p-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-2xl font-bold flex items-center gap-2.5">
                <i class="ph-fill ph-check-circle text-lg shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('warning'))
            <div class="p-4 text-sm text-amber-800 bg-amber-50 border border-amber-200 rounded-2xl font-bold flex items-center gap-2.5">
                <i class="ph-fill ph-warning-circle text-lg shrink-0 text-amber-600"></i>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        <!-- 4 Kartu Ringkasan Katalog Interaktif (Klik untuk Filter) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('hrd.trainings.index') }}"
               class="bg-surface p-5 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-blue-500 hover:shadow-md transition-all block {{ !request()->anyFilled(['kategori', 'status_pendaftaran', 'status_program']) ? 'ring-2 ring-blue-500/30 bg-blue-50/10' : '' }}">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="ph-fill ph-books text-xl"></i>
                    </div>
                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-full uppercase">TOTAL PROGRAM</span>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $totalCatalog ?? $trainings->total() }}</h3>
                <p class="text-xs text-slate-500 font-semibold mt-0.5">Seluruh Katalog Pelatihan</p>
            </a>

            <a href="{{ route('hrd.trainings.index', ['status_pendaftaran' => request('status_pendaftaran') === 'dibuka' ? null : 'dibuka']) }}"
               class="bg-surface p-5 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-emerald-500 hover:shadow-md transition-all block {{ request('status_pendaftaran') === 'dibuka' ? 'ring-2 ring-emerald-500 bg-emerald-50/20' : '' }}">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="ph-fill ph-door-open text-xl"></i>
                    </div>
                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2.5 py-0.5 rounded-full uppercase">DIBUKA</span>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $openRegistrationCount ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-semibold mt-0.5">Pendaftaran Sedang Dibuka</p>
            </a>

            <a href="{{ route('hrd.trainings.index', ['kategori' => request('kategori') === 'Sertifikasi' ? null : 'Sertifikasi']) }}"
               class="bg-surface p-5 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-indigo-500 hover:shadow-md transition-all block {{ request('kategori') === 'Sertifikasi' ? 'ring-2 ring-indigo-500 bg-indigo-50/20' : '' }}">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <i class="ph-fill ph-seal-check text-xl"></i>
                    </div>
                    <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2.5 py-0.5 rounded-full uppercase">SERTIFIKASI</span>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $certificationProgramCount ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-semibold mt-0.5">Program Sertifikasi (POP/POM/POU/K3)</p>
            </a>

            <a href="{{ route('hrd.trainings.index', ['status_program' => request('status_program') === 'dibatalkan' ? null : 'dibatalkan']) }}"
               class="bg-surface p-5 rounded-2xl shadow-sm border border-slate-100 border-l-4 border-l-slate-400 hover:shadow-md transition-all block {{ request('status_program') === 'dibatalkan' ? 'ring-2 ring-slate-500 bg-slate-50' : '' }}">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                        <i class="ph-fill ph-archive-box text-xl"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-600 bg-slate-100 px-2.5 py-0.5 rounded-full uppercase">ARSIP / BATAL</span>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-800">{{ $archivedOrCancelledCount ?? 0 }}</h3>
                <p class="text-xs text-slate-500 font-semibold mt-0.5">Program Diarsipkan / Dibatalkan</p>
            </a>
        </div>

        <!-- Panel Pencarian & Filter Katalog (PRD 7.4) -->
        <div class="bg-surface rounded-2xl p-5 shadow-sm border border-slate-100">
            <form action="{{ route('hrd.trainings.index') }}" method="GET" class="space-y-4">
                <div class="flex flex-col md:flex-row gap-3">
                    <div class="relative flex-1">
                        <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari Nama Pelatihan, Kode Program (TRG-...), Instruktur, atau Jenis Sertifikasi..."
                               class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/10 outline-none transition-all">
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <button type="submit" class="inline-flex items-center gap-1.5 bg-slate-800 text-white px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-700 transition-colors">
                            <i class="ph-bold ph-funnel"></i> Cari & Filter
                        </button>
                        @if(request()->anyFilled(['search', 'kategori', 'metode_pelaksanaan', 'status_pendaftaran', 'status_program']))
                            <a href="{{ route('hrd.trainings.index') }}" class="inline-flex items-center gap-1 bg-rose-50 text-rose-600 border border-rose-200 px-3.5 py-2.5 rounded-xl text-xs font-bold hover:bg-rose-100 transition-colors">
                                <i class="ph-bold ph-x"></i> Reset
                            </a>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Kategori Program</label>
                        <select name="kategori" onchange="this.form.submit()" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 bg-white focus:border-primary-500 outline-none">
                            <option value="">Semua Kategori</option>
                            <option value="Sertifikasi" {{ request('kategori') === 'Sertifikasi' ? 'selected' : '' }}>Sertifikasi</option>
                            <option value="Technical Skill" {{ request('kategori') === 'Technical Skill' ? 'selected' : '' }}>Technical Skill</option>
                            <option value="Soft Skill" {{ request('kategori') === 'Soft Skill' ? 'selected' : '' }}>Soft Skill</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Metode Pelaksanaan</label>
                        <select name="metode_pelaksanaan" onchange="this.form.submit()" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 bg-white focus:border-primary-500 outline-none">
                            <option value="">Semua Metode</option>
                            <option value="OFFLINE" {{ request('metode_pelaksanaan') === 'OFFLINE' ? 'selected' : '' }}>OFFLINE (Tatap Muka)</option>
                            <option value="ONLINE" {{ request('metode_pelaksanaan') === 'ONLINE' ? 'selected' : '' }}>ONLINE (Daring)</option>
                            <option value="HYBRID" {{ request('metode_pelaksanaan') === 'HYBRID' ? 'selected' : '' }}>HYBRID (Campuran)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status Pendaftaran</label>
                        <select name="status_pendaftaran" onchange="this.form.submit()" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 bg-white focus:border-primary-500 outline-none">
                            <option value="">Semua Status Pendaftaran</option>
                            <option value="dibuka" {{ request('status_pendaftaran') === 'dibuka' ? 'selected' : '' }}>Pendaftaran Dibuka</option>
                            <option value="ditutup" {{ request('status_pendaftaran') === 'ditutup' ? 'selected' : '' }}>Pendaftaran Ditutup</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status Program (Siklus)</label>
                        <select name="status_program" onchange="this.form.submit()" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 bg-white focus:border-primary-500 outline-none">
                            <option value="">Semua Status Program</option>
                            <option value="akan_datang" {{ request('status_program') === 'akan_datang' ? 'selected' : '' }}>Aktif / Akan Datang</option>
                            <option value="draft" {{ request('status_program') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="berjalan" {{ request('status_program') === 'berjalan' ? 'selected' : '' }}>Sedang Berjalan</option>
                            <option value="selesai" {{ request('status_program') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="dibatalkan" {{ request('status_program') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan / Arsip</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Grid Kartu Katalog Pelatihan -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pb-8">

            @forelse ($trainings as $training)
                @php
                    $badgeColor = match($training->metode_pelaksanaan) {
                        'ONLINE' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'HYBRID' => 'bg-purple-50 text-purple-700 border-purple-200',
                        default => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    };

                    $kategoriColor = match($training->kategori) {
                        'Sertifikasi' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                        'Technical Skill' => 'bg-sky-50 text-sky-700 border-sky-200',
                        'Soft Skill' => 'bg-amber-50 text-amber-700 border-amber-200',
                        default => 'bg-slate-100 text-slate-600 border-slate-200',
                    };

                    $progStatus = strtolower($training->status_program ?? 'akan_datang');
                    $progStatusBadge = match($progStatus) {
                        'draft' => ['label' => 'DRAFT', 'class' => 'bg-slate-100 text-slate-600 border-slate-200'],
                        'berjalan' => ['label' => 'BERJALAN', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                        'selesai' => ['label' => 'SELESAI', 'class' => 'bg-teal-50 text-teal-700 border-teal-200'],
                        'dibatalkan' => ['label' => 'DIARSIPKAN / BATAL', 'class' => 'bg-rose-100 text-rose-800 border-rose-300'],
                        default => ['label' => 'PROGRAM AKTIF', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                    };

                    // BR-08: Hitung Kuota Riil dari Peserta yang Disetujui
                    $terisi = $training->approvedParticipantsCount();
                    $kuota = max(1, (int) $training->kuota);
                    $sisaKuota = $training->remainingQuota();
                    $persenTerisi = $training->quotaPercentage();

                    $isClosed = strtolower($training->status_pendaftaran) === 'ditutup' || $sisaKuota <= 0 || in_array($progStatus, ['dibatalkan', 'arsip', 'selesai']);
                    $statusText = $isClosed ? 'PENDAFTARAN DITUTUP' : 'PENDAFTARAN DIBUKA';
                    $statusColor = $isClosed
                        ? 'bg-rose-50 text-rose-600 border-rose-200'
                        : 'bg-emerald-50 text-emerald-600 border-emerald-200';

                    $hasTransactions = $training->hasTransactions();
                @endphp

                <div class="bg-surface rounded-2xl p-6 shadow-sm border border-slate-100 hover:border-primary-200 hover:shadow-md transition-all duration-200 flex flex-col justify-between {{ in_array($progStatus, ['arsip', 'dibatalkan']) ? 'opacity-80 bg-slate-50/40' : '' }}">

                    <div>
                        <!-- Baris Atas: Badge Status Pendaftaran, Status Program, Kategori, Metode & Kode Program -->
                        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="px-2.5 py-1 {{ $statusColor }} border text-[10px] font-bold uppercase tracking-wider rounded-lg inline-flex items-center gap-1">
                                    <i class="ph-bold {{ $isClosed ? 'ph-lock-key' : 'ph-door-open' }}"></i>
                                    {{ $statusText }}
                                </span>

                                <span class="px-2.5 py-1 {{ $progStatusBadge['class'] }} border text-[10px] font-bold uppercase tracking-wider rounded-lg">
                                    {{ $progStatusBadge['label'] }}
                                </span>

                                @if($training->kategori)
                                    <span class="px-2.5 py-1 {{ $kategoriColor }} border text-[10px] font-bold uppercase tracking-wider rounded-lg">
                                        {{ $training->kategori }}
                                        @if($training->jenis_sertifikasi)
                                            ({{ $training->jenis_sertifikasi }})
                                        @endif
                                    </span>
                                @endif

                                <span class="px-2.5 py-1 {{ $badgeColor }} border text-[10px] font-bold uppercase tracking-wider rounded-lg">
                                    {{ $training->metode_pelaksanaan }}
                                </span>
                            </div>

                            <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                                {{ $training->kode_program ?? '#TR-' . str_pad($training->id, 3, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>

                        <!-- Judul & Deskripsi Pelatihan -->
                        <h3 class="text-xl font-extrabold text-slate-800 leading-snug mb-2">
                            <a href="{{ route('hrd.trainings.show', $training->id) }}" class="hover:text-primary-600 transition-colors">
                                {{ $training->nama_pelatihan }}
                            </a>
                        </h3>
                        <p class="text-xs text-slate-500 font-medium line-clamp-2 mb-5 leading-relaxed">
                            {{ $training->deskripsi ?? 'Belum ada deskripsi untuk pelatihan ini.' }}
                        </p>

                        <!-- Kotak Informasi Jadwal, Batas Pendaftaran, Lokasi, Instruktur, & Biaya -->
                        <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-3.5 mb-5">
                            <div class="flex items-start gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-white border border-slate-200/80 flex items-center justify-center text-primary-600 shrink-0">
                                    <i class="ph-fill ph-calendar-blank text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jadwal Pelaksanaan</p>
                                    <p class="text-xs font-bold text-slate-700 mt-0.5">
                                        {{ $training->tanggal_mulai ? \Carbon\Carbon::parse($training->tanggal_mulai)->format('d M Y') : '-' }}
                                        @if($training->tanggal_selesai)
                                            – {{ \Carbon\Carbon::parse($training->tanggal_selesai)->format('d M Y') }}
                                        @endif
                                    </p>
                                    @if($training->batas_pendaftaran)
                                        <p class="text-[10px] font-semibold text-amber-700 mt-0.5">
                                            Batas Daftar: {{ \Carbon\Carbon::parse($training->batas_pendaftaran)->format('d M Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-start gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-white border border-slate-200/80 flex items-center justify-center text-emerald-600 shrink-0">
                                    <i class="ph-fill ph-map-pin text-base"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lokasi / Media</p>
                                    <p class="text-xs font-bold text-slate-700 mt-0.5 truncate" title="{{ $training->lokasi ?? '-' }}">
                                        {{ $training->lokasi ?? 'Belum ditentukan' }}
                                    </p>
                                    @if($training->estimasi_biaya !== null)
                                        <p class="text-[10px] font-semibold text-slate-500 mt-0.5">
                                            Est. Biaya: <strong class="text-slate-700">Rp {{ number_format((float) $training->estimasi_biaya, 0, ',', '.') }}</strong>
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-start justify-between gap-3 min-w-0 sm:col-span-2 pt-2.5 border-t border-slate-200/60">
                                <div class="flex items-start gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg bg-white border border-slate-200/80 flex items-center justify-center text-indigo-600 shrink-0">
                                        <i class="ph-fill ph-chalkboard-teacher text-base"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Instruktur / Lembaga Penyelenggara</p>
                                        <p class="text-xs font-bold text-slate-700 mt-0.5 break-words">
                                            {{ $training->instruktur ?? 'Belum ditentukan' }}
                                        </p>
                                    </div>
                                </div>

                                @if($training->documents->count() > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-[10px] font-bold text-primary-600 shrink-0" title="Dokumen Silabus / Brosur / TOR Tersedia">
                                        <i class="ph-bold ph-paperclip"></i> {{ $training->documents->count() }} Dokumen
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Bawah: Kapasitas Kuota Dinamis (BR-08) & Tombol Aksi + Proteksi BR-11 -->
                    <div>
                        <div class="mb-4">
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <span class="font-semibold text-slate-500">
                                    Peserta Disetujui: <strong class="text-slate-800">{{ $terisi }} / {{ $training->kuota }}</strong> Kursi
                                </span>
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-bold {{ $sisaKuota > 5 ? 'bg-emerald-50 text-emerald-700' : ($sisaKuota > 0 ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                                    {{ $sisaKuota > 0 ? 'Sisa ' . $sisaKuota . ' Kursi' : 'Kuota Penuh' }}
                                </span>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full {{ $sisaKuota <= 0 ? 'bg-rose-500' : 'bg-primary-600' }} rounded-full transition-all" style="width: {{ $persenTerisi }}%"></div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center gap-2">
                            <a href="{{ route('hrd.trainings.show', $training->id) }}" class="flex-1 py-2.5 px-4 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-bold text-xs transition-colors flex items-center justify-center gap-1.5 shadow-xs">
                                <i class="ph-bold ph-eye"></i> Detail
                            </a>

                            <a href="{{ route('hrd.trainings.edit', $training->id) }}" class="py-2.5 px-3.5 rounded-xl bg-amber-50 hover:bg-amber-500 text-amber-700 hover:text-white border border-amber-200 font-bold text-xs transition-colors inline-flex items-center justify-center gap-1">
                                <i class="ph-bold ph-pencil-simple"></i> Edit
                            </a>

                            @if($hasTransactions)
                                <!-- BR-11: Jika sudah ada peserta/pengajuan, tampilkan tombol Arsipkan / Aktifkan (Bukan Hapus Permanen) -->
                                <form action="{{ route('hrd.trainings.archive', $training->id) }}" method="POST" class="inline-flex m-0">
                                    @csrf
                                    @method('PATCH')
                                    @if($progStatus === 'dibatalkan')
                                        <input type="hidden" name="status_program" value="akan_datang">
                                        <button type="submit" class="py-2.5 px-3.5 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 font-bold text-xs transition-colors inline-flex items-center gap-1" title="Aktifkan kembali program ini">
                                            <i class="ph-bold ph-arrow-counter-clockwise"></i> Aktifkan
                                        </button>
                                    @else
                                        <input type="hidden" name="status_program" value="dibatalkan">
                                        <button type="submit" onclick="return confirm('Program ini memiliki riwayat peserta/pengajuan. Arsipkan/batalkan program dan tutup pendaftaran?');" class="py-2.5 px-3.5 rounded-xl bg-slate-100 hover:bg-slate-700 text-slate-700 hover:text-white border border-slate-200 font-bold text-xs transition-colors inline-flex items-center gap-1" title="Arsipkan program (BR-11)">
                                            <i class="ph-bold ph-archive-box"></i> Arsipkan
                                        </button>
                                    @endif
                                </form>
                            @else
                                <!-- Jika belum ada transaksi sama sekali, boleh dihapus atau diarsipkan -->
                                <form action="{{ route('hrd.trainings.destroy', $training->id) }}" method="POST" class="inline-flex m-0" onsubmit="return confirm('Hapus program pelatihan ini dari katalog?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="py-2.5 px-3.5 rounded-xl bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200 font-bold text-xs transition-colors inline-flex items-center gap-1">
                                        <i class="ph-bold ph-trash"></i> Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center p-12 bg-white rounded-2xl border border-slate-100 text-center">
                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 mb-4">
                        <i class="ph-fill ph-graduation-cap text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Tidak Ada Program Pelatihan Ditemukan</h3>
                    <p class="text-xs text-slate-500 max-w-md">Belum ada program pelatihan yang sesuai dengan filter pencarian Anda.</p>
                </div>
            @endforelse

        </div>

        <div class="mt-4">
            {{ $trainings->links() }}
        </div>
    </div>
</x-app-layout>
