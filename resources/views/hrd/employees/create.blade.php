<x-app-layout>
    <x-slot name="header">
        Tambah Karyawan Baru
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
    </style>

    <div class="w-full space-y-6">

        <div class="mb-10">
            <nav class="flex items-center text-sm text-slate-500 mb-3 font-semibold">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 hover:text-primary-600 transition duration-200">
                    <i class="ph-fill ph-squares-four text-lg"></i> Dashboard
                </a>
                <i class="ph-bold ph-caret-right text-slate-300 mx-2 text-xs"></i>
                <a href="{{ route('hrd.employees.index') }}" class="flex items-center gap-2 hover:text-primary-600 transition duration-200">
                    <i class="ph-fill ph-users text-lg"></i> Data Karyawan
                </a>
                <i class="ph-bold ph-caret-right text-slate-300 mx-2 text-xs"></i>
                <span class="text-slate-800">Tambah Karyawan</span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-4xl font-extrabold text-slate-800 tracking-tight mb-2">
                        Tambah Karyawan Baru
                    </h2>
                    <p class="text-sm text-slate-500 font-medium">Lengkapi formulir data diri, pekerjaan, serta histori sertifikat/pelatihan yang pernah diikuti.</p>
                </div>
            </div>
        </div>

        <div class="bg-surface rounded-[32px] p-8 md:p-12 shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-slate-100">

            <form action="{{ route('hrd.employees.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                @csrf

                <!-- ========================================================= -->
                <!-- BAGIAN 1: INFORMASI PRIBADI                               -->
                <!-- ========================================================= -->
                <div>
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-inner">
                            <i class="ph-fill ph-identification-card text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-extrabold text-slate-800">Informasi Pribadi</h3>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Data Diri Dasar</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                        <div>
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" class="form-input" required />
                            @error('nama')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">NIK</label>
                            <input type="text" name="nik" value="{{ old('nik') }}" placeholder="Contoh: 20240010023" class="form-input" required />
                            @error('nik')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Jenis Kelamin</label>
                            <div class="relative group">
                                <select name="jenis_kelamin" class="form-input appearance-none cursor-pointer">
                                    <option value="" {{ old('jenis_kelamin') == '' ? 'selected' : '' }}>Pilih jenis kelamin</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center px-2 text-slate-400 group-hover:text-slate-600 transition duration-200">
                                    <i class="ph-bold ph-caret-down"></i>
                                </div>
                            </div>
                            @error('jenis_kelamin')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="ph-fill ph-envelope-simple text-lg"></i>
                                </div>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@perusahaan.com" class="form-input pl-11" />
                            </div>
                            @error('email')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Nomor HP</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400">
                                    <i class="ph-fill ph-phone text-lg"></i>
                                </div>
                                <input type="tel" name="nomor_hp" value="{{ old('nomor_hp') }}" placeholder="+62 8..." class="form-input pl-11" />
                            </div>
                            @error('nomor_hp')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="h-px w-full bg-gradient-to-r from-transparent via-slate-200 to-transparent my-14"></div>

                <!-- ========================================================= -->
                <!-- BAGIAN 2: INFORMASI PEKERJAAN                             -->
                <!-- ========================================================= -->
                <div>
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shadow-inner">
                            <i class="ph-fill ph-briefcase text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-extrabold text-slate-800">Informasi Pekerjaan</h3>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Posisi & Penempatan</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-12 gap-y-10">
                        <div>
                            <label class="form-label">Departemen</label>
                            <div class="relative group">
                                <select name="department_id" class="form-input appearance-none cursor-pointer" required>
                                    <option value="" disabled {{ old('department_id') == '' ? 'selected' : '' }}>Pilih departemen</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->nama_departemen }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center px-2 text-slate-400 group-hover:text-slate-600 transition duration-200">
                                    <i class="ph-bold ph-caret-down"></i>
                                </div>
                            </div>
                            @error('department_id')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Level Jabatan</label>
                            <div class="relative group">
                                @php
                                    $levelOptions = [
                                        'Group Leader',
                                        'Head Of Section',
                                        'Head Of Department',
                                        'Penanggung Jawab Operational',
                                    ];
                                @endphp
                                <select name="level_jabatan" class="form-input appearance-none cursor-pointer" required>
                                    <option value="" disabled {{ old('level_jabatan') == '' ? 'selected' : '' }}>Pilih level jabatan</option>
                                    @foreach ($levelOptions as $lvl)
                                        <option value="{{ $lvl }}" {{ old('level_jabatan') == $lvl ? 'selected' : '' }}>
                                            {{ $lvl }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center px-2 text-slate-400 group-hover:text-slate-600 transition duration-200">
                                    <i class="ph-bold ph-caret-down"></i>
                                </div>
                            </div>
                            @error('level_jabatan')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Job Title / Jabatan</label>
                            <input type="text" name="jabatan" value="{{ old('jabatan') }}" placeholder="Contoh: Senior HSE Specialist" class="form-input" />
                            @error('jabatan')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Site / Unit Kerja</label>
                            <input type="text" name="site_unit" value="{{ old('site_unit', 'Head Office') }}" placeholder="Contoh: Head Office / Site Operasional" class="form-input" />
                            @error('site_unit')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Status Keaktifan</label>
                            <div class="relative group">
                                <select name="status_aktif" class="form-input appearance-none cursor-pointer" required>
                                    <option value="aktif" {{ old('status_aktif', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif Bekerja</option>
                                    <option value="nonaktif" {{ old('status_aktif') == 'nonaktif' ? 'selected' : '' }}>Nonaktif / Resign</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center px-2 text-slate-400 group-hover:text-slate-600 transition duration-200">
                                    <i class="ph-bold ph-caret-down"></i>
                                </div>
                            </div>
                            @error('status_aktif')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label">Tanggal Bergabung</label>
                            <input type="date" name="tanggal_join" value="{{ old('tanggal_join') }}" class="form-input text-slate-600 uppercase text-xs tracking-wide" />
                            @error('tanggal_join')
                                <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="h-px w-full bg-gradient-to-r from-transparent via-slate-200 to-transparent my-14"></div>

                <!-- ========================================================= -->
                <!-- BAGIAN 3: RIWAYAT SERTIFIKAT / PELATIHAN YANG PERNAH DIIKUTI -->
                <!-- ========================================================= -->
                <div>
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-inner shrink-0">
                                <i class="ph-fill ph-seal-check text-xl"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="text-xl font-extrabold text-slate-800">Riwayat Sertifikat / Pelatihan yang Pernah Diikuti</h3>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 uppercase tracking-wider">Opsional</span>
                                </div>
                                <p class="text-xs text-slate-400 font-medium mt-0.5">
                                    Tambahkan sertifikat atau pelatihan yang sudah pernah diikuti agar karyawan baru langsung memiliki histori kompetensi.
                                </p>
                            </div>
                        </div>

                        <button type="button"
                                onclick="addTrainingHistoryRow()"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 font-bold text-xs transition-all shrink-0 shadow-xs">
                            <i class="ph-bold ph-plus-circle text-base"></i>
                            <span>Tambah Sertifikat / Pelatihan</span>
                        </button>
                    </div>

                    <!-- Datalist saran nama pelatihan yang sudah ada di katalog -->
                    <datalist id="existing-trainings-list">
                        @foreach($existingTrainings ?? [] as $trName)
                            <option value="{{ $trName }}"></option>
                        @endforeach
                    </datalist>

                    <!-- Empty State ketika belum ada baris riwayat ditambahkan -->
                    <div id="empty-history-state" class="p-8 rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50/50 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-white border border-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3 shadow-xs">
                            <i class="ph-fill ph-graduation-cap text-2xl text-primary-500"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-700">Belum ada riwayat sertifikat / pelatihan yang ditambahkan</p>
                        <p class="text-xs text-slate-400 mt-1 max-w-md mx-auto">
                            Jika karyawan ini sudah pernah mengikuti sertifikasi (POP, POM, POU, K3, dll.) atau pelatihan Technical/Soft Skill sebelumnya, klik tombol di bawah untuk mencatat historinya.
                        </p>
                        <button type="button"
                                onclick="addTrainingHistoryRow()"
                                class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white hover:bg-primary-50 text-primary-600 border border-slate-200 hover:border-primary-200 text-xs font-bold transition-colors shadow-2xs">
                            <i class="ph-bold ph-plus"></i>
                            Tambah Baris Riwayat Pertama
                        </button>
                    </div>

                    <!-- Container daftar kartu input riwayat -->
                    <div id="training-history-container" class="space-y-6 mt-4"></div>
                </div>

                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-4 mt-12 pt-8 border-t border-slate-100">
                    <a href="{{ route('hrd.employees.index') }}" class="w-full sm:w-auto">
                        <button type="button" class="w-full px-8 py-3.5 rounded-2xl font-semibold text-slate-600 bg-white border-2 border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition duration-200 hover:shadow-sm">
                            Batal
                        </button>
                    </a>
                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 rounded-2xl font-semibold text-white bg-primary-600 hover:bg-primary-700 transition duration-200 shadow-[0_8px_24px_rgba(37,99,235,0.25)] hover:shadow-[0_12px_32px_rgba(37,99,235,0.35)] flex items-center justify-center gap-2">
                        <i class="ph-bold ph-check"></i>
                        <span>Simpan Karyawan</span>
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        let historyRowIndex = 0;

        function updateEmptyHistoryState() {
            const container = document.getElementById('training-history-container');
            const emptyState = document.getElementById('empty-history-state');
            if (!container || !emptyState) return;

            if (container.children.length > 0) {
                emptyState.classList.add('hidden');
            } else {
                emptyState.classList.remove('hidden');
            }
        }

        function addTrainingHistoryRow(prefill = {}) {
            const container = document.getElementById('training-history-container');
            const idx = historyRowIndex++;

            const kategori = prefill.kategori || 'Sertifikasi';
            const namaPelatihan = prefill.nama_pelatihan || '';
            const tanggalPelatihan = prefill.tanggal_pelatihan || '';
            const penyelenggara = prefill.penyelenggara || '';
            const jenisSertifikasi = prefill.jenis_sertifikasi || 'K3';
            const nomorSertifikat = prefill.nomor_sertifikat || '';
            const masaBerlaku = prefill.masa_berlaku || '';
            const isPermanent = Boolean(Number(prefill.is_permanent || 0));
            const tanggalExpired = prefill.tanggal_expired || '';
            const hasilEvaluasi = prefill.hasil_evaluasi || '';

            const rowEl = document.createElement('div');
            rowEl.id = `history-row-${idx}`;
            rowEl.className = 'p-6 rounded-3xl bg-slate-50/70 border-2 border-slate-100 space-y-5 relative transition-all hover:border-slate-200';

            rowEl.innerHTML = `
                <div class="flex items-center justify-between gap-3 pb-3 border-b border-slate-200/70">
                    <div class="flex items-center gap-2.5">
                        <span class="w-7 h-7 rounded-xl bg-primary-600 text-white text-xs font-extrabold flex items-center justify-center">
                            #${container.children.length + 1}
                        </span>
                        <h4 class="text-sm font-extrabold text-slate-800">Data Riwayat Sertifikat / Pelatihan</h4>
                    </div>
                    <button type="button"
                            onclick="removeTrainingHistoryRow('history-row-${idx}')"
                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white text-xs font-bold transition-colors">
                        <i class="ph-bold ph-trash"></i> Hapus
                    </button>
                </div>

                <!-- Baris Utama: Kategori, Nama Sertifikat/Pelatihan, Tanggal Pelatihan, Penyelenggara -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div>
                        <label class="form-label">Kategori Kompetensi</label>
                        <select name="histories[${idx}][kategori]"
                                onchange="toggleHistoryCategoryFields(${idx}, this.value)"
                                class="form-input bg-white cursor-pointer">
                            <option value="Sertifikasi" ${kategori === 'Sertifikasi' ? 'selected' : ''}>Sertifikasi Resmi</option>
                            <option value="Technical Skill" ${kategori === 'Technical Skill' ? 'selected' : ''}>Technical Skill</option>
                            <option value="Soft Skill" ${kategori === 'Soft Skill' ? 'selected' : ''}>Soft Skill</option>
                        </select>
                    </div>

                    <div class="lg:col-span-2">
                        <label class="form-label" id="label-nama-pelatihan-${idx}">
                            ${kategori === 'Sertifikasi' ? 'Nama Sertifikat / Pelatihan' : 'Nama Pelatihan yang Pernah Diikuti'}
                        </label>
                        <input type="text"
                               name="histories[${idx}][nama_pelatihan]"
                               value="${namaPelatihan.replace(/"/g, '&quot;')}"
                               list="existing-trainings-list"
                               placeholder="Contoh: Sertifikasi POP / Basic Safety / Leadership"
                               class="form-input bg-white"
                               required />
                    </div>

                    <div>
                        <label class="form-label" id="label-tanggal-pelatihan-${idx}">
                            ${kategori === 'Sertifikasi' ? 'Tanggal Sertifikat Terbit' : 'Tanggal Pelatihan'}
                        </label>
                        <input type="date"
                               name="histories[${idx}][tanggal_pelatihan]"
                               value="${tanggalPelatihan}"
                               class="form-input bg-white text-slate-600 text-xs" />
                    </div>
                </div>

                <!-- Field Tambahan Khusus Kategori Sertifikasi -->
                <div id="cert-fields-${idx}" class="${kategori === 'Sertifikasi' ? '' : 'hidden'} pt-2 border-t border-slate-200/60">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                        <div>
                            <label class="form-label">Jenis Sertifikasi</label>
                            <select name="histories[${idx}][jenis_sertifikasi]" class="form-input bg-white cursor-pointer">
                                <option value="POP" ${jenisSertifikasi === 'POP' ? 'selected' : ''}>POP (Pengawas Operasional Pertama)</option>
                                <option value="POM" ${jenisSertifikasi === 'POM' ? 'selected' : ''}>POM (Pengawas Operasional Madya)</option>
                                <option value="POU" ${jenisSertifikasi === 'POU' ? 'selected' : ''}>POU (Pengawas Operasional Utama)</option>
                                <option value="K3" ${jenisSertifikasi === 'K3' ? 'selected' : ''}>K3 / Keselamatan Kerja</option>
                                <option value="Lainnya" ${jenisSertifikasi === 'Lainnya' ? 'selected' : ''}>Lainnya</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Nomor Registrasi / Sertifikat</label>
                            <input type="text"
                                   name="histories[${idx}][nomor_sertifikat]"
                                   value="${nomorSertifikat.replace(/"/g, '&quot;')}"
                                   placeholder="Contoh: REG-K3-2025-001"
                                   class="form-input bg-white" />
                        </div>

                        <div>
                            <label class="form-label">Masa Berlaku</label>
                            <input type="text"
                                   id="masa-berlaku-${idx}"
                                   name="histories[${idx}][masa_berlaku]"
                                   value="${masaBerlaku.replace(/"/g, '&quot;')}"
                                   placeholder="Contoh: 3 Tahun / 5 Tahun"
                                   class="form-input bg-white" />
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Tanggal Expired</label>
                                <label class="inline-flex items-center gap-1.5 text-[11px] font-bold text-primary-600 cursor-pointer">
                                    <input type="checkbox"
                                           id="is-permanent-${idx}"
                                           name="histories[${idx}][is_permanent]"
                                           value="1"
                                           ${isPermanent ? 'checked' : ''}
                                           onchange="toggleHistoryPermanent(${idx}, this.checked)"
                                           class="rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                    <span>Permanen</span>
                                </label>
                            </div>
                            <input type="date"
                                   id="tanggal-expired-${idx}"
                                   name="histories[${idx}][tanggal_expired]"
                                   value="${tanggalExpired}"
                                   ${isPermanent ? 'disabled' : ''}
                                   class="form-input bg-white text-slate-600 text-xs ${isPermanent ? 'opacity-50 cursor-not-allowed' : ''}" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-4">
                        <div>
                            <label class="form-label">Penyelenggara / Lembaga Penerbit</label>
                            <input type="text"
                                   name="histories[${idx}][penyelenggara]"
                                   value="${penyelenggara.replace(/"/g, '&quot;')}"
                                   placeholder="Contoh: BNSP / Kementerian ESDM / Kemnaker"
                                   class="form-input bg-white" />
                        </div>
                        <div>
                            <label class="form-label">Upload File Sertifikat (PDF / JPG / PNG, Maks 5MB)</label>
                            <input type="file"
                                   name="histories[${idx}][file_sertifikat]"
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="form-input bg-white text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" />
                        </div>
                    </div>
                </div>

                <!-- Field Tambahan Khusus Technical Skill & Soft Skill -->
                <div id="skill-fields-${idx}" class="${kategori !== 'Sertifikasi' ? '' : 'hidden'} pt-2 border-t border-slate-200/60">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label">Penyelenggara / Instruktur Pelatihan</label>
                            <input type="text"
                                   name="histories[${idx}][penyelenggara]"
                                   value="${penyelenggara.replace(/"/g, '&quot;')}"
                                   ${kategori === 'Sertifikasi' ? 'disabled' : ''}
                                   id="skill-penyelenggara-${idx}"
                                   placeholder="Contoh: Internal Training Center / Vendor Eksternal"
                                   class="form-input bg-white" />
                        </div>
                        <div>
                            <label class="form-label">Hasil Evaluasi / Catatan</label>
                            <input type="text"
                                   name="histories[${idx}][hasil_evaluasi]"
                                   value="${hasilEvaluasi.replace(/"/g, '&quot;')}"
                                   placeholder="Contoh: Telah mengikuti & lulus dengan baik"
                                   class="form-input bg-white" />
                        </div>
                    </div>
                </div>
            `;

            container.appendChild(rowEl);
            updateEmptyHistoryState();
        }

        function removeTrainingHistoryRow(rowId) {
            const el = document.getElementById(rowId);
            if (el) {
                el.remove();
            }
            updateEmptyHistoryState();
        }

        function toggleHistoryCategoryFields(idx, kategori) {
            const certFields = document.getElementById(`cert-fields-${idx}`);
            const skillFields = document.getElementById(`skill-fields-${idx}`);
            const skillPenyelenggara = document.getElementById(`skill-penyelenggara-${idx}`);
            const labelNama = document.getElementById(`label-nama-pelatihan-${idx}`);
            const labelTanggal = document.getElementById(`label-tanggal-pelatihan-${idx}`);

            if (kategori === 'Sertifikasi') {
                certFields?.classList.remove('hidden');
                skillFields?.classList.add('hidden');
                if (skillPenyelenggara) skillPenyelenggara.disabled = true;
                if (labelNama) labelNama.textContent = 'Nama Sertifikat / Pelatihan';
                if (labelTanggal) labelTanggal.textContent = 'Tanggal Sertifikat Terbit';
            } else {
                certFields?.classList.add('hidden');
                skillFields?.classList.remove('hidden');
                if (skillPenyelenggara) skillPenyelenggara.disabled = false;
                if (labelNama) labelNama.textContent = 'Nama Pelatihan yang Pernah Diikuti';
                if (labelTanggal) labelTanggal.textContent = 'Tanggal Pelatihan';
            }
        }

        function toggleHistoryPermanent(idx, checked) {
            const expInput = document.getElementById(`tanggal-expired-${idx}`);
            const masaInput = document.getElementById(`masa-berlaku-${idx}`);
            if (!expInput) return;

            if (checked) {
                expInput.value = '';
                expInput.disabled = true;
                expInput.classList.add('opacity-50', 'cursor-not-allowed');
                if (masaInput && !masaInput.value) {
                    masaInput.value = 'Permanen';
                }
            } else {
                expInput.disabled = false;
                expInput.classList.remove('opacity-50', 'cursor-not-allowed');
                if (masaInput && masaInput.value === 'Permanen') {
                    masaInput.value = '';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const oldHistories = @json(old('histories', []));
            if (Array.isArray(oldHistories) && oldHistories.length > 0) {
                oldHistories.forEach(item => addTrainingHistoryRow(item));
            } else if (oldHistories && typeof oldHistories === 'object') {
                Object.values(oldHistories).forEach(item => addTrainingHistoryRow(item));
            }
        });
    </script>
</x-app-layout>
