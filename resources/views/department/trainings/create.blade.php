<x-app-layout>
    <x-slot name="header">Form Pengajuan Pelatihan Katalog</x-slot>

    @php
        $approvedCount = $training->approvedParticipantsCount();
        $remainingQuota = $training->remainingQuota();
        $existingIds = $existingEmployeeIds ?? [];
        $oldSelected = array_map('intval', (array) old('employee_ids', []));

        $employeeListJson = $employees->map(function ($emp) use ($existingIds) {
            return [
                'id' => (int) $emp->id,
                'nik' => $emp->nik,
                'nama' => $emp->nama,
                'jabatan' => $emp->jabatan,
                'level_jabatan' => $emp->level_jabatan ?? '-',
                'departemen' => $emp->department->nama_departemen ?? '-',
                'is_existing' => in_array((int) $emp->id, $existingIds),
            ];
        })->values();
    @endphp

    <div class="w-full max-w-4xl mx-auto space-y-6"
         x-data="{
            search: '',
            maxQuota: {{ $remainingQuota }},
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
            isSelected(id) {
                return this.selectedIds.includes(id);
            },
            toggleEmployee(emp) {
                if (emp.is_existing) return;
                const idx = this.selectedIds.indexOf(emp.id);
                if (idx > -1) {
                    this.selectedIds.splice(idx, 1);
                } else {
                    if (this.selectedIds.length >= this.maxQuota) {
                        alert('Jumlah peserta yang dipilih sudah mencapai batas Sisa Kuota (' + this.maxQuota + ' kursi).');
                        return;
                    }
                    this.selectedIds.push(emp.id);
                }
            },
            selectAllAvailable() {
                const available = this.filteredEmployees.filter(e => !e.is_existing);
                for (const emp of available) {
                    if (!this.selectedIds.includes(emp.id)) {
                        if (this.selectedIds.length < this.maxQuota) {
                            this.selectedIds.push(emp.id);
                        } else {
                            break;
                        }
                    }
                }
            },
            clearSelection() {
                this.selectedIds = [];
            }
         }">

        <!-- Top Back & Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('department.trainings.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 transition mb-2">
                    <i class="ph-bold ph-arrow-left"></i> Kembali ke Katalog Pelatihan
                </a>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Form Pengajuan Peserta Pelatihan</h2>
                <p class="text-xs text-slate-500 mt-1 font-medium">Pilih satu atau beberapa karyawan sekaligus (Multi-Peserta) dari departemen Anda.</p>
            </div>
        </div>

        <!-- Ringkasan Program Pelatihan -->
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-md">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                <div class="flex flex-wrap items-center gap-2">
                    @if($training->kode_program)
                        <span class="px-2.5 py-1 bg-white/20 backdrop-blur-sm rounded-md text-[10px] font-extrabold uppercase tracking-wider">
                            {{ $training->kode_program }}
                        </span>
                    @endif
                    <span class="px-2.5 py-1 bg-white/20 backdrop-blur-sm rounded-md text-[10px] font-bold uppercase tracking-wider">
                        {{ $training->kategori ?? 'Program Pelatihan' }}{{ $training->jenis_sertifikasi ? ' • ' . $training->jenis_sertifikasi : '' }}
                    </span>
                    <span class="px-2.5 py-1 bg-emerald-400/20 border border-emerald-300/30 text-emerald-100 rounded-md text-[10px] font-bold uppercase tracking-wider">
                        {{ $training->metode_pelaksanaan }}
                    </span>
                </div>
                @if($training->batas_pendaftaran)
                    <span class="text-xs font-bold bg-amber-400/20 border border-amber-300/30 text-amber-100 px-3 py-1 rounded-lg">
                        <i class="ph-fill ph-alarm mr-1"></i> Batas Daftar: {{ \Carbon\Carbon::parse($training->batas_pendaftaran)->format('d M Y') }}
                    </span>
                @endif
            </div>

            <h3 class="text-xl font-extrabold mb-2">{{ $training->nama_pelatihan }}</h3>
            @if($training->deskripsi)
                <p class="text-xs text-blue-100 mb-4 leading-relaxed">{{ $training->deskripsi }}</p>
            @endif

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-4 border-t border-white/15 text-xs">
                <div class="bg-white/10 rounded-xl p-3">
                    <div class="text-[10px] uppercase text-blue-200 font-bold">Jadwal Pelaksanaan</div>
                    <div class="font-extrabold mt-0.5">
                        {{ \Carbon\Carbon::parse($training->tanggal_mulai)->format('d M') }} – {{ \Carbon\Carbon::parse($training->tanggal_selesai)->format('d M Y') }}
                    </div>
                </div>
                <div class="bg-white/10 rounded-xl p-3">
                    <div class="text-[10px] uppercase text-blue-200 font-bold">Kuota Total</div>
                    <div class="font-extrabold mt-0.5">{{ $training->kuota }} Kursi</div>
                </div>
                <div class="bg-white/10 rounded-xl p-3">
                    <div class="text-[10px] uppercase text-blue-200 font-bold">Peserta Disetujui</div>
                    <div class="font-extrabold mt-0.5">{{ $approvedCount }} Orang</div>
                </div>
                <div class="bg-white/15 border border-white/25 rounded-xl p-3">
                    <div class="text-[10px] uppercase text-emerald-200 font-bold">Sisa Kuota Tersedia</div>
                    <div class="font-extrabold text-sm text-emerald-300 mt-0.5">{{ $remainingQuota }} Kursi</div>
                </div>
            </div>

            @if($training->persyaratan_peserta)
                <div class="mt-4 p-3 bg-white/10 rounded-xl text-xs text-blue-50">
                    <span class="font-extrabold uppercase text-[10px] tracking-wider block text-blue-200 mb-0.5">Persyaratan Peserta:</span>
                    {{ $training->persyaratan_peserta }}
                </div>
            @endif
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
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('department.trainings.store', $training->id) }}" method="POST" class="space-y-6">
                @csrf

                <!-- Hidden inputs for selected employee_ids -->
                <template x-for="id in selectedIds" :key="'hidden-' + id">
                    <input type="hidden" name="employee_ids[]" :value="id">
                </template>

                <!-- Multi-Select Employee Section -->
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                Pilih Peserta Karyawan (Multi-Peserta) <span class="text-rose-500">*</span>
                            </label>
                            <p class="text-xs text-slate-500 mt-0.5">Centang satu atau lebih karyawan dari departemen Anda untuk diajukan.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1.5 rounded-xl text-xs font-extrabold"
                                  :class="selectedIds.length > 0 ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'bg-slate-100 text-slate-600'">
                                Dipilih: <span x-text="selectedIds.length"></span> / <span x-text="maxQuota"></span> Kursi
                            </span>
                            <button type="button" @click="selectAllAvailable()"
                                    class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                                Pilih Semua
                            </button>
                            <button type="button" @click="clearSelection()" x-show="selectedIds.length > 0"
                                    class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl text-xs font-bold transition">
                                Reset
                            </button>
                        </div>
                    </div>

                    <!-- Search Bar Karyawan -->
                    <div class="relative">
                        <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" x-model="search"
                               placeholder="Cari nama karyawan, NIK, level jabatan, atau job title..."
                               class="w-full pl-10 pr-4 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 outline-none transition">
                    </div>

                    <!-- Employee Checkboxes List -->
                    <div class="border border-slate-200 rounded-2xl overflow-hidden max-h-80 overflow-y-auto divide-y divide-slate-100">
                        <template x-for="emp in filteredEmployees" :key="emp.id">
                            <div @click="toggleEmployee(emp)"
                                 class="flex items-center justify-between p-3.5 transition select-none"
                                 :class="{
                                    'bg-slate-50/80 opacity-60 cursor-not-allowed': emp.is_existing,
                                    'bg-blue-50/60 hover:bg-blue-50 cursor-pointer': !emp.is_existing && isSelected(emp.id),
                                    'hover:bg-slate-50 cursor-pointer': !emp.is_existing && !isSelected(emp.id)
                                 }">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-5 h-5 rounded-md border flex items-center justify-center transition"
                                         :class="{
                                            'bg-slate-200 border-slate-300 text-slate-400': emp.is_existing,
                                            'bg-blue-600 border-blue-600 text-white': !emp.is_existing && isSelected(emp.id),
                                            'border-slate-300 bg-white': !emp.is_existing && !isSelected(emp.id)
                                         }">
                                        <i class="ph-bold ph-check text-xs" x-show="isSelected(emp.id) || emp.is_existing"></i>
                                    </div>
                                    <div>
                                        <div class="text-xs font-extrabold text-slate-800" x-text="emp.nama"></div>
                                        <div class="text-[11px] font-medium text-slate-500">
                                            NIK: <span class="font-bold text-slate-700" x-text="emp.nik"></span>
                                            • <span x-text="emp.jabatan"></span>
                                            (<span x-text="emp.level_jabatan"></span>)
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <template x-if="emp.is_existing">
                                        <span class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-bold uppercase">
                                            Sudah Diajukan / Terdaftar
                                        </span>
                                    </template>
                                    <template x-if="!emp.is_existing && isSelected(emp.id)">
                                        <span class="px-2.5 py-1 rounded-lg bg-blue-100 text-blue-700 text-[10px] font-bold uppercase">
                                            Terpilih
                                        </span>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <div x-show="filteredEmployees.length === 0" class="p-8 text-center text-xs text-slate-400 font-semibold">
                            Tidak ada data karyawan yang cocok dengan pencarian Anda.
                        </div>
                    </div>
                </div>

                <!-- Alasan / Catatan Pengajuan -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alasan / Catatan Pengajuan (Opsional)
                    </label>
                    <textarea name="alasan_pengajuan" rows="3"
                              placeholder="Tuliskan alasan pengajuan atau harapan kompetensi bagi peserta yang didaftarkan..."
                              class="w-full px-4 py-3 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 outline-none transition">{{ old('alasan_pengajuan') }}</textarea>
                </div>

                <!-- Footer Action -->
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
                        <span>Kirim Pengajuan (<span x-text="selectedIds.length"></span> Peserta)</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
