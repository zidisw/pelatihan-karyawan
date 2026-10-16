<x-app-layout>
    <x-slot name="header">Form Request Training Baru</x-slot>

    @php
        $oldSelected = array_map('intval', (array) old('employee_ids', []));
        $employeeListJson = $employees->map(function ($emp) {
            return [
                'id' => (int) $emp->id,
                'nik' => $emp->nik,
                'nama' => $emp->nama,
                'jabatan' => $emp->jabatan,
                'level_jabatan' => $emp->level_jabatan ?? '-',
            ];
        })->values();
    @endphp

    <div class="w-full max-w-4xl mx-auto space-y-6"
         x-data="{
            search: '',
            pelaksana: '{{ old('pelaksana', 'Eksternal') }}',
            metode: '{{ old('metode_pelaksanaan', 'Offline') }}',
            employees: {{ Js::from($employeeListJson) }},
            selectedIds: {{ Js::from($oldSelected) }},
            get filteredEmployees() {
                if (!this.search.trim()) return this.employees;
                const q = this.search.toLowerCase();
                return this.employees.filter(e =>
                    e.nama.toLowerCase().includes(q) ||
                    e.nik.toLowerCase().includes(q) ||
                    e.jabatan.toLowerCase().includes(q) ||
                    e.level_jabatan.toLowerCase().includes(q)
                );
            },
            get selectedEmployeesList() {
                return this.employees.filter(e => this.selectedIds.includes(e.id));
            },
            isSelected(id) {
                return this.selectedIds.includes(id);
            },
            toggleEmployee(id) {
                const idx = this.selectedIds.indexOf(id);
                if (idx > -1) {
                    this.selectedIds.splice(idx, 1);
                } else {
                    this.selectedIds.push(id);
                }
            }
         }">

        <!-- Top Back & Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('department.trainings.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition mb-2">
                    <i class="ph-bold ph-arrow-left"></i> Kembali ke Katalog Pelatihan
                </a>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Form + Request Training Baru</h2>
                <p class="text-xs text-slate-500 mt-1 font-medium">Ajukan usulan pelatihan baru di luar katalog berdasarkan analisis kebutuhan (*Training Need Analysis*) departemen Anda.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl p-6 sm:p-8 shadow-sm border border-slate-100">
            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 text-rose-700 rounded-xl text-xs font-bold border border-rose-200 flex items-center gap-2">
                    <i class="ph-fill ph-warning-circle text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-rose-50 text-rose-700 rounded-xl text-xs font-bold border border-rose-200">
                    <div class="font-extrabold mb-1">Mohon periksa kembali input Anda:</div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('department.requests.storeNew') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Hidden inputs for selected employee_ids -->
                <template x-for="id in selectedIds" :key="'emp-hidden-' + id">
                    <input type="hidden" name="employee_ids[]" :value="id">
                </template>

                <!-- Row 1: Field 1 (Department Pengaju) & Field 2 (Periode Pengajuan) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- 1. Department Pengaju -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                            1. Department Pengaju <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-700 text-xs font-extrabold">
                            <i class="ph-fill ph-buildings text-blue-600 text-base"></i>
                            <span>{{ $user->department->nama_departemen ?? 'Departemen User' }}</span>
                            <span class="ml-auto px-2 py-0.5 bg-blue-50 text-blue-700 rounded text-[10px] font-bold">Otomatis</span>
                        </div>
                    </div>

                    <!-- 2. Periode Pengajuan -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                            2. Periode Pengajuan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="periode_pengajuan"
                               value="{{ old('periode_pengajuan', now()->translatedFormat('M Y')) }}"
                               placeholder="Contoh: Okt 2026 / Kuartal IV 2026"
                               required
                               class="w-full px-4 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 outline-none transition">
                        <p class="text-[11px] text-slate-400 mt-1">Bulan & tahun rencana pelaksanaan pelatihan (misal: Okt 2026).</p>
                    </div>
                </div>

                <!-- 3. Scope / Program -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                        3. Scope / Program Pelatihan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="scope_program"
                           value="{{ old('scope_program') }}"
                           placeholder="Contoh: Sertifikasi Operator Alat Berat / Preventive Maintenance Hydraulic System"
                           required
                           class="w-full px-4 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 outline-none transition">
                </div>

                <!-- 4. Kondisi Karyawan Saat Ini -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                        4. Kondisi Karyawan Saat Ini <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="kondisi_saat_ini" rows="3" required
                              placeholder="Jelaskan kondisi aktual kompetensi karyawan saat ini atau kendala teknis yang dihadapi di lapangan..."
                              class="w-full px-4 py-3 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 outline-none transition">{{ old('kondisi_saat_ini') }}</textarea>
                </div>

                <!-- 5. Training Need -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                        5. Training Need (Kebutuhan Pelatihan) <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="training_need" rows="3" required
                              placeholder="Jelaskan materi atau pelatihan spesifik apa yang sesuai untuk mengatasi kondisi pekerja tersebut..."
                              class="w-full px-4 py-3 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 outline-none transition">{{ old('training_need') }}</textarea>
                </div>

                <!-- 6. Target / KPI Setelah Mengikuti Pelatihan -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                        6. Target / KPI Setelah Mengikuti Pelatihan <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="target_kpi" rows="3" required
                              placeholder="Contoh: Menurunkan downtime alat sebesar 20%, lulus uji kompetensi resmi dengan nilai minimal 85..."
                              class="w-full px-4 py-3 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 outline-none transition">{{ old('target_kpi') }}</textarea>
                </div>

                <!-- Row: 7. Estimasi Biaya, 8. Pelaksana, 10. Pelaksanaan -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- 7. Estimasi Biaya -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                            7. Estimasi Biaya (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-xs font-extrabold text-slate-400">Rp</span>
                            <input type="number" name="estimasi_biaya" min="0" step="1000"
                                   value="{{ old('estimasi_biaya', 0) }}"
                                   required
                                   class="w-full pl-10 pr-4 py-2.5 text-xs font-bold rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 outline-none transition">
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Isi 0 jika pelatihan internal tanpa biaya.</p>
                    </div>

                    <!-- 8. Pelaksana (Option Tombol: Internal / Eksternal) -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                            8. Pelaksana <span class="text-rose-500">*</span>
                        </label>
                        <input type="hidden" name="pelaksana" :value="pelaksana">
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" @click="pelaksana = 'Internal'"
                                    :class="pelaksana === 'Internal'
                                        ? 'bg-blue-600 text-white border-blue-600 shadow-sm'
                                        : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100'"
                                    class="py-2.5 px-3 rounded-xl border text-xs font-extrabold transition flex items-center justify-center gap-1.5">
                                <i class="ph-bold ph-buildings"></i> Internal
                            </button>
                            <button type="button" @click="pelaksana = 'Eksternal'"
                                    :class="pelaksana === 'Eksternal'
                                        ? 'bg-blue-600 text-white border-blue-600 shadow-sm'
                                        : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100'"
                                    class="py-2.5 px-3 rounded-xl border text-xs font-extrabold transition flex items-center justify-center gap-1.5">
                                <i class="ph-bold ph-globe-hemisphere-west"></i> Eksternal
                            </button>
                        </div>
                    </div>

                    <!-- 10. Pelaksanaan (Option Tombol: Online / Offline) -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                            10. Metode Pelaksanaan <span class="text-rose-500">*</span>
                        </label>
                        <input type="hidden" name="metode_pelaksanaan" :value="metode">
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" @click="metode = 'Online'"
                                    :class="metode === 'Online'
                                        ? 'bg-indigo-600 text-white border-indigo-600 shadow-sm'
                                        : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100'"
                                    class="py-2.5 px-3 rounded-xl border text-xs font-extrabold transition flex items-center justify-center gap-1.5">
                                <i class="ph-bold ph-laptop"></i> Online
                            </button>
                            <button type="button" @click="metode = 'Offline'"
                                    :class="metode === 'Offline'
                                        ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm'
                                        : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100'"
                                    class="py-2.5 px-3 rounded-xl border text-xs font-extrabold transition flex items-center justify-center gap-1.5">
                                <i class="ph-bold ph-map-pin"></i> Offline
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 9. Identitas Peserta yang Didaftarkan (Nama & Jabatan) -->
                <div class="space-y-3 pt-2 border-t border-slate-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                9. Identitas Peserta yang Didaftarkan (Nama & Jabatan) <span class="text-rose-500">*</span>
                            </label>
                            <p class="text-xs text-slate-500 mt-0.5">Pilih satu atau lebih karyawan dari departemen Anda yang akan mengikuti pelatihan ini.</p>
                        </div>
                        <span class="px-3 py-1 rounded-xl text-xs font-extrabold"
                              :class="selectedIds.length > 0 ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-slate-100 text-slate-600'">
                            Total Peserta Dipilih: <span x-text="selectedIds.length"></span> Orang
                        </span>
                    </div>

                    <!-- Preview Chips Peserta Terpilih (Nama & Jabatan) -->
                    <div x-show="selectedEmployeesList.length > 0" class="p-3.5 bg-blue-50/60 border border-blue-100 rounded-xl">
                        <div class="text-[10px] font-extrabold text-blue-700 uppercase tracking-wider mb-2">Daftar Identitas Peserta Terpilih (Nama & Jabatan):</div>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="emp in selectedEmployeesList" :key="'chip-' + emp.id">
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-blue-200 rounded-lg text-xs font-bold text-slate-700 shadow-sm">
                                    <span x-text="emp.nama" class="text-blue-700 font-extrabold"></span>
                                    <span class="text-slate-400">•</span>
                                    <span x-text="emp.jabatan" class="text-slate-600"></span>
                                    <button type="button" @click="toggleEmployee(emp.id)" class="text-slate-400 hover:text-rose-600">
                                        <i class="ph-bold ph-x"></i>
                                    </button>
                                </span>
                            </template>
                        </div>
                    </div>

                    <!-- Search Bar Peserta -->
                    <div class="relative">
                        <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" x-model="search"
                               placeholder="Cari nama karyawan, NIK, atau jabatan..."
                               class="w-full pl-10 pr-4 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 outline-none transition">
                    </div>

                    <!-- Employee Checkboxes List -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden max-h-64 overflow-y-auto divide-y divide-slate-100">
                        <template x-for="emp in filteredEmployees" :key="emp.id">
                            <div @click="toggleEmployee(emp.id)"
                                 class="flex items-center justify-between p-3.5 transition cursor-pointer select-none"
                                 :class="isSelected(emp.id) ? 'bg-blue-50/70 hover:bg-blue-50' : 'hover:bg-slate-50'">
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-md border flex items-center justify-center transition"
                                         :class="isSelected(emp.id) ? 'bg-blue-600 border-blue-600 text-white' : 'border-slate-300 bg-white'">
                                        <i class="ph-bold ph-check text-xs" x-show="isSelected(emp.id)"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-extrabold text-slate-800" x-text="emp.nama"></div>
                                        <div class="text-[11px] font-medium text-slate-500">
                                            Jabatan: <span class="font-bold text-slate-700" x-text="emp.jabatan"></span>
                                            • Level: <span x-text="emp.level_jabatan"></span>
                                            • NIK: <span x-text="emp.nik"></span>
                                        </div>
                                    </div>
                                </div>
                                <span x-show="isSelected(emp.id)" class="px-2.5 py-1 rounded-lg bg-blue-100 text-blue-700 text-[10px] font-bold uppercase">
                                    Terpilih
                                </span>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('department.trainings.index') }}"
                       class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                        Batal
                    </a>
                    <button type="submit"
                            :disabled="selectedIds.length === 0"
                            :class="selectedIds.length === 0 ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/20'"
                            class="px-6 py-2.5 rounded-xl text-xs font-bold text-white transition inline-flex items-center gap-2">
                        <i class="ph-bold ph-paper-plane-tilt"></i>
                        <span>Kirim Usulan Training Baru (<span x-text="selectedIds.length"></span> Peserta)</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
