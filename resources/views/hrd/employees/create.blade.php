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
                    <p class="text-sm text-slate-500 font-medium">Lengkapi formulir di bawah ini untuk menyimpan data karyawan ke sistem.</p>
                </div>
            </div>
        </div>

        <div class="bg-surface rounded-[32px] p-8 md:p-12 shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-slate-100">

        <form action="{{ route('hrd.employees.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
                @csrf

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
                            <label class="form-label">Status Kontrak Karyawan</label>
                            <div class="relative group">
                                <select name="status_karyawan" class="form-input appearance-none cursor-pointer" required>
                                    <option value="" disabled {{ old('status_karyawan') == '' ? 'selected' : '' }}>Pilih status kontrak</option>
                                    <option value="tetap" {{ old('status_karyawan', 'tetap') == 'tetap' ? 'selected' : '' }}>Karyawan Tetap</option>
                                    <option value="kontrak" {{ old('status_karyawan') == 'kontrak' ? 'selected' : '' }}>Karyawan Kontrak</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center px-2 text-slate-400 group-hover:text-slate-600 transition duration-200">
                                    <i class="ph-bold ph-caret-down"></i>
                                </div>
                            </div>
                            @error('status_karyawan')
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
</x-app-layout>
