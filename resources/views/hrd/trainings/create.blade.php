<x-app-layout>
    <x-slot name="header">
        Tambah Program Pelatihan Baru
    </x-slot>

    <style>
        .form-input {
            width: 100%;
            padding: 0.875rem 1.25rem;
            border-radius: 1rem;
            border: 2px solid #eef2f7;
            background-color: rgba(241, 245, 249, 0.6);
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
            outline: none;
            transition: all .15s ease-in-out;
        }

        .form-input.pl-11 {
            padding-left: 2.75rem;
        }

        .form-input:focus {
            background-color: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 8px 28px rgba(37, 99, 235, 0.08);
        }

        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 800;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            margin-bottom: 0.625rem;
            margin-left: 0.25rem;
        }

        textarea.form-input {
            min-height: 110px;
            resize: vertical;
        }
    </style>

    <div class="w-full space-y-6">

        <div class="mb-8">
            <nav class="flex items-center text-sm text-slate-500 mb-3 font-semibold">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 hover:text-primary-600 transition duration-200">
                    <i class="ph-fill ph-squares-four text-lg"></i> Dashboard
                </a>
                <i class="ph-bold ph-caret-right text-slate-300 mx-2 text-xs"></i>
                <a href="{{ route('hrd.trainings.index') }}" class="flex items-center gap-2 hover:text-primary-600 transition duration-200">
                    <i class="ph-fill ph-graduation-cap text-lg"></i> Katalog Pelatihan
                </a>
                <i class="ph-bold ph-caret-right text-slate-300 mx-2 text-xs"></i>
                <span class="text-slate-800">Tambah Program Pelatihan</span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 tracking-tight mb-1">
                        Tambah Program Pelatihan Baru
                    </h2>
                    <p class="text-sm text-slate-500 font-medium">Lengkapi informasi program, kategori sertifikasi, kuota, target departemen, dan dokumen pendukung (Silabus/Brosur/TOR).</p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-bold">
                <p class="mb-1.5 flex items-center gap-1.5 text-sm"><i class="ph-fill ph-warning-circle text-lg"></i> Mohon periksa kembali isian formulir:</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-[32px] p-8 md:p-12 shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-slate-100">

            <form action="{{ route('hrd.trainings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                @csrf

                <!-- BAGIAN 1: INFORMASI DASAR & KATEGORI -->
                <div>
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-inner">
                            <i class="ph-fill ph-info text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-extrabold text-slate-800">1. Informasi Dasar & Klasifikasi Program</h3>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Identitas, Kategori Kompetensi & Sertifikasi</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-7">
                        <div>
                            <label class="form-label">Kode Program</label>
                            <input type="text" name="kode_program" value="{{ old('kode_program', $suggestedCode ?? '') }}" placeholder="Contoh: TRG-2026-001" class="form-input" />
                            <p class="text-[11px] text-slate-400 mt-1 ml-1">*Otomatis dibuat jika dikosongkan.</p>
                            @error('kode_program')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="form-label">Nama Program Pelatihan <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_pelatihan" value="{{ old('nama_pelatihan') }}" placeholder="Contoh: Diklat & Sertifikasi Pengawas Operasional Pertama (POP)" class="form-input" required />
                            @error('nama_pelatihan')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Kategori Pelatihan <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <select name="kategori" id="select-kategori" onchange="toggleJenisSertifikasi()" class="form-input appearance-none cursor-pointer" required>
                                    <option value="" disabled {{ old('kategori') == '' ? 'selected' : '' }}>Pilih kategori</option>
                                    <option value="Sertifikasi" {{ old('kategori') == 'Sertifikasi' ? 'selected' : '' }}>Sertifikasi</option>
                                    <option value="Technical Skill" {{ old('kategori') == 'Technical Skill' ? 'selected' : '' }}>Technical Skill</option>
                                    <option value="Soft Skill" {{ old('kategori') == 'Soft Skill' ? 'selected' : '' }}>Soft Skill</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center px-2 text-slate-400">
                                    <i class="ph-bold ph-caret-down"></i>
                                </div>
                            </div>
                            @error('kategori')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div id="wrapper-jenis-sertifikasi">
                            <label class="form-label">Jenis Sertifikasi (Khusus Sertifikasi)</label>
                            <div class="relative group">
                                <select name="jenis_sertifikasi" id="select-jenis-sertifikasi" class="form-input appearance-none cursor-pointer">
                                    <option value="" {{ old('jenis_sertifikasi') == '' ? 'selected' : '' }}>-- Pilih Jenis Sertifikasi --</option>
                                    <option value="POP" {{ old('jenis_sertifikasi') == 'POP' ? 'selected' : '' }}>POP (Pengawas Operasional Pertama)</option>
                                    <option value="POM" {{ old('jenis_sertifikasi') == 'POM' ? 'selected' : '' }}>POM (Pengawas Operasional Madya)</option>
                                    <option value="POU" {{ old('jenis_sertifikasi') == 'POU' ? 'selected' : '' }}>POU (Pengawas Operasional Utama)</option>
                                    <option value="K3" {{ old('jenis_sertifikasi') == 'K3' ? 'selected' : '' }}>K3 / Keselamatan Pertambangan</option>
                                    <option value="Kompetensi Teknis" {{ old('jenis_sertifikasi') == 'Kompetensi Teknis' ? 'selected' : '' }}>Sertifikasi Kompetensi Teknis Lainnya</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center px-2 text-slate-400">
                                    <i class="ph-bold ph-caret-down"></i>
                                </div>
                            </div>
                            @error('jenis_sertifikasi')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Metode Pelaksanaan <span class="text-rose-500">*</span></label>
                            <div class="relative group">
                                <select name="metode_pelaksanaan" class="form-input appearance-none cursor-pointer" required>
                                    <option value="" disabled {{ old('metode_pelaksanaan') == '' ? 'selected' : '' }}>Pilih metode</option>
                                    <option value="OFFLINE" {{ old('metode_pelaksanaan') == 'OFFLINE' ? 'selected' : '' }}>OFFLINE (Tatap Muka)</option>
                                    <option value="ONLINE" {{ old('metode_pelaksanaan') == 'ONLINE' ? 'selected' : '' }}>ONLINE (Daring)</option>
                                    <option value="HYBRID" {{ old('metode_pelaksanaan') == 'HYBRID' ? 'selected' : '' }}>HYBRID (Campuran)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center px-2 text-slate-400">
                                    <i class="ph-bold ph-caret-down"></i>
                                </div>
                            </div>
                            @error('metode_pelaksanaan')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label class="form-label">Deskripsi & Cakupan Materi <span class="text-rose-500">*</span></label>
                            <textarea name="deskripsi" placeholder="Tuliskan tujuan pembelajaran, silabus singkat, dan manfaat pelatihan ini..." class="form-input" required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label class="form-label">Persyaratan Peserta</label>
                            <textarea name="persyaratan_peserta" placeholder="Contoh: Minimal menjabat sebagai Group Leader, pengalaman kerja minimal 2 tahun di operasional tambang..." class="form-input">{{ old('persyaratan_peserta') }}</textarea>
                            @error('persyaratan_peserta')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="h-px w-full bg-gradient-to-r from-transparent via-slate-200 to-transparent my-8"></div>

                <!-- BAGIAN 2: JADWAL, KUOTA, BIAYA & PENYELENGGARA -->
                <div>
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shadow-inner">
                            <i class="ph-fill ph-calendar-check text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-extrabold text-slate-800">2. Jadwal, Kuota, Anggaran & Penyelenggara</h3>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Waktu Pelaksanaan, Batas Pendaftaran, dan Kapasitas</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-7">
                        <div>
                            <label class="form-label">Tanggal Mulai Pelatihan <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="form-input text-slate-600 uppercase text-xs tracking-wide" required />
                            @error('tanggal_mulai')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Tanggal Selesai Pelatihan <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="form-input text-slate-600 uppercase text-xs tracking-wide" required />
                            @error('tanggal_selesai')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Batas Akhir Pendaftaran</label>
                            <input type="date" name="batas_pendaftaran" value="{{ old('batas_pendaftaran') }}" class="form-input text-slate-600 uppercase text-xs tracking-wide" />
                            <p class="text-[11px] text-slate-400 mt-1 ml-1">*Maksimal sebelum atau sama dengan tanggal mulai.</p>
                            @error('batas_pendaftaran')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Kuota Total Peserta <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="ph-fill ph-users text-lg"></i>
                                </div>
                                <input type="number" name="kuota" value="{{ old('kuota', 25) }}" placeholder="Contoh: 25" min="1" class="form-input pl-11" required />
                            </div>
                            @error('kuota')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Estimasi Biaya (Rp)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 font-bold text-xs">
                                    Rp
                                </div>
                                <input type="number" step="0.01" min="0" name="estimasi_biaya" value="{{ old('estimasi_biaya', 0) }}" placeholder="Contoh: 3500000" class="form-input pl-11" />
                            </div>
                            @error('estimasi_biaya')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label">Instruktur / Lembaga Penyelenggara <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="ph-fill ph-user-circle text-lg"></i>
                                </div>
                                <input type="text" name="instruktur" value="{{ old('instruktur') }}" placeholder="Contoh: LSP Energi & Pertambangan" class="form-input pl-11" required />
                            </div>
                            @error('instruktur')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="md:col-span-3">
                            <label class="form-label">Lokasi Pelaksanaan / Link Meeting <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="ph-fill ph-map-pin text-lg"></i>
                                </div>
                                <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Balikpapan Training Center atau Link Zoom Meeting" class="form-input pl-11" required />
                            </div>
                            @error('lokasi')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="h-px w-full bg-gradient-to-r from-transparent via-slate-200 to-transparent my-8"></div>

                <!-- BAGIAN 3: TARGET DEPARTEMEN, STATUS & DOKUMEN PENDUKUNG -->
                <div>
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner">
                            <i class="ph-fill ph-folder-open text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-extrabold text-slate-800">3. Target Departemen, Status & Dokumen Pendukung</h3>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Pengaturan Sasaran Peserta, Siklus Program, dan Lampiran Silabus/TOR</p>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <!-- Target Departemen -->
                        <div>
                            <label class="form-label">Target Departemen Peserta (Opsional)</label>
                            <p class="text-xs text-slate-400 mb-3 ml-1">Centang departemen yang menjadi sasaran program ini. Jika dikosongkan, program terbuka untuk <strong>Semua Departemen</strong>.</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                @foreach($departments as $dept)
                                    <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50/60 hover:bg-blue-50/40 hover:border-primary-300 cursor-pointer transition-all text-xs font-semibold text-slate-700">
                                        <input type="checkbox" name="target_departemen[]" value="{{ $dept->id }}"
                                               {{ in_array($dept->id, old('target_departemen', [])) ? 'checked' : '' }}
                                               class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                        <span>{{ $dept->kode_departemen ? $dept->kode_departemen . ' - ' : '' }}{{ $dept->nama_departemen }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Pemisahan Status Pendaftaran vs Status Program -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="form-label">Status Pendaftaran <span class="text-rose-500">*</span></label>
                                <div class="relative group">
                                    <select name="status_pendaftaran" class="form-input appearance-none cursor-pointer" required>
                                        <option value="dibuka" {{ old('status_pendaftaran', 'dibuka') == 'dibuka' ? 'selected' : '' }}>DIBUKA (Departemen Bisa Mengajukan Peserta)</option>
                                        <option value="ditutup" {{ old('status_pendaftaran') == 'ditutup' ? 'selected' : '' }}>DITUTUP (Pendaftaran Ditutup)</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center px-2 text-slate-400">
                                        <i class="ph-bold ph-caret-down"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Status Siklus Program <span class="text-rose-500">*</span></label>
                                <div class="relative group">
                                    <select name="status_program" class="form-input appearance-none cursor-pointer" required>
                                        <option value="akan_datang" {{ old('status_program', 'akan_datang') == 'akan_datang' ? 'selected' : '' }}>AKTIF / AKAN DATANG (Tampil di Katalog)</option>
                                        <option value="draft" {{ old('status_program') == 'draft' ? 'selected' : '' }}>DRAFT (Persiapan Internal)</option>
                                        <option value="berjalan" {{ old('status_program') == 'berjalan' ? 'selected' : '' }}>BERJALAN (Pelatihan Sedang Berlangsung)</option>
                                        <option value="selesai" {{ old('status_program') == 'selesai' ? 'selected' : '' }}>SELESAI (Pelatihan Telah Berakhir)</option>
                                        <option value="dibatalkan" {{ old('status_program') == 'dibatalkan' ? 'selected' : '' }}>DIBATALKAN / ARSIP</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center px-2 text-slate-400">
                                        <i class="ph-bold ph-caret-down"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Dokumen Pendukung (Silabus / Brosur / TOR) -->
                        <div>
                            <label class="form-label">Upload Dokumen Pendukung (Silabus / Brosur / TOR)</label>
                            <div class="p-5 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50/50 hover:border-primary-400 transition-colors">
                                <input type="file" name="dokumen_pendukung[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                       class="block w-full text-xs text-slate-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-primary-600 file:text-white hover:file:bg-primary-700 cursor-pointer" />
                                <p class="text-[11px] text-slate-400 mt-2">
                                    *Dapat memilih lebih dari 1 file sekaligus (Format: PDF, DOC, DOCX, JPG, PNG — Maks. 5 MB per file).
                                </p>
                            </div>
                            @error('dokumen_pendukung.*')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Catatan Internal HRD -->
                        <div>
                            <label class="form-label">Catatan Internal HRD (Opsional)</label>
                            <textarea name="catatan_internal" placeholder="Catatan khusus internal HRD (vendor PIC, nomor kontrak, atau informasi logistik)..." class="form-input">{{ old('catatan_internal') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Tombol Submit & Batal -->
                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-4 mt-10 pt-8 border-t border-slate-100">
                    <a href="{{ route('hrd.trainings.index') }}" class="w-full sm:w-auto">
                        <button type="button" class="w-full px-8 py-3.5 rounded-2xl font-semibold text-slate-600 bg-white border-2 border-slate-200 hover:bg-slate-50 transition duration-200">
                            Batal
                        </button>
                    </a>
                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl font-semibold text-white bg-primary-600 hover:bg-primary-700 transition duration-200 shadow-[0_8px_24px_rgba(37,99,235,0.25)] flex items-center justify-center gap-2">
                        <i class="ph-bold ph-check"></i>
                        <span>Simpan Program Pelatihan</span>
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function toggleJenisSertifikasi() {
            const kategori = document.getElementById('select-kategori').value;
            const wrapper = document.getElementById('wrapper-jenis-sertifikasi');
            const selectCert = document.getElementById('select-jenis-sertifikasi');
            if (kategori === 'Sertifikasi') {
                wrapper.classList.remove('opacity-50');
                selectCert.disabled = false;
            } else {
                wrapper.classList.add('opacity-50');
                selectCert.value = '';
                selectCert.disabled = true;
            }
        }
        document.addEventListener('DOMContentLoaded', toggleJenisSertifikasi);
    </script>
</x-app-layout>
