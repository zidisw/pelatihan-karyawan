<x-app-layout>
    <x-slot name="header">Riwayat Pelatihan & Sertifikasi</x-slot>

    <div class="w-full space-y-6 pb-10">

        <!-- Judul Halaman -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Riwayat Pelatihan</h2>
                <p class="text-sm text-slate-500 mt-1 font-medium">
                    Pilih kategori di bawah untuk memantau data <strong>Sertifikasi</strong>, <strong>Technical Skill</strong>, dan <strong>Soft Skill</strong> seluruh karyawan.
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-bold flex items-center gap-2">
                <i class="ph-fill ph-check-circle text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- ========================================================== -->
        <!-- 1. TIGA WIDGET / CARD UTAMA (SERTIFIKASI, TECHNICAL, SOFT) -->
        <!-- ========================================================== -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Widget 1: Sertifikasi -->
            <button type="button" onclick="switchCategory('sertifikasi')" id="card-sertifikasi"
                class="category-card text-left p-6 rounded-2xl border-2 transition-all duration-200 bg-surface shadow-sm hover:shadow-md flex items-center justify-between group">
                <div class="space-y-1">
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                        <i class="ph-fill ph-seal-check"></i> Kategori 1
                    </span>
                    <h3 class="text-xl font-extrabold text-slate-800 pt-1">Sertifikasi</h3>
                    <p class="text-xs text-slate-400 font-medium">POP, POM, POU, K3 & Lisensi Resmi</p>
                </div>
                <div class="text-right">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl font-extrabold ml-auto mb-1 group-hover:scale-105 transition-transform">
                        {{ $certifications->count() }}
                    </div>
                    <span class="text-[11px] font-bold text-slate-400">Data Sertifikat</span>
                </div>
            </button>

            <!-- Widget 2: Technical Skill -->
            <button type="button" onclick="switchCategory('technical')" id="card-technical"
                class="category-card text-left p-6 rounded-2xl border-2 transition-all duration-200 bg-surface shadow-sm hover:shadow-md flex items-center justify-between group">
                <div class="space-y-1">
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-widest text-blue-600 bg-blue-50 px-2.5 py-1 rounded-full">
                        <i class="ph-fill ph-wrench"></i> Kategori 2
                    </span>
                    <h3 class="text-xl font-extrabold text-slate-800 pt-1">Technical Skill</h3>
                    <p class="text-xs text-slate-400 font-medium">Kompetensi Teknis & Operasional</p>
                </div>
                <div class="text-right">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl font-extrabold ml-auto mb-1 group-hover:scale-105 transition-transform">
                        {{ $technicalSkills->count() }}
                    </div>
                    <span class="text-[11px] font-bold text-slate-400">Data Peserta</span>
                </div>
            </button>

            <!-- Widget 3: Soft Skill -->
            <button type="button" onclick="switchCategory('soft')" id="card-soft"
                class="category-card text-left p-6 rounded-2xl border-2 transition-all duration-200 bg-surface shadow-sm hover:shadow-md flex items-center justify-between group">
                <div class="space-y-1">
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-extrabold uppercase tracking-widest text-purple-600 bg-purple-50 px-2.5 py-1 rounded-full">
                        <i class="ph-fill ph-users-three"></i> Kategori 3
                    </span>
                    <h3 class="text-xl font-extrabold text-slate-800 pt-1">Soft Skill</h3>
                    <p class="text-xs text-slate-400 font-medium">Leadership, Komunikasi & Manajemen</p>
                </div>
                <div class="text-right">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl font-extrabold ml-auto mb-1 group-hover:scale-105 transition-transform">
                        {{ $softSkills->count() }}
                    </div>
                    <span class="text-[11px] font-bold text-slate-400">Data Peserta</span>
                </div>
            </button>

        </div>

        <!-- ========================================================== -->
        <!-- 2. CONTAINER UTAMA FILTER & TABEL DINAMIS                  -->
        <!-- ========================================================== -->
        <div class="bg-surface rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 space-y-6">

            <!-- Header Kategori Aktif -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-100">
                <div>
                    <h3 id="activeTableTitle" class="text-xl font-extrabold text-slate-800">Tabel Riwayat Sertifikasi</h3>
                    <p id="activeTableSubtitle" class="text-xs text-slate-400 mt-0.5">Menampilkan daftar sertifikat resmi karyawan beserta masa berlaku dan pengingat kadaluarsa (Time Reminder).</p>
                </div>
                <button type="button" onclick="resetAllFilters()" class="self-start sm:self-auto inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                    <i class="ph-bold ph-arrow-counter-clockwise"></i> Reset Filter
                </button>
            </div>

            <!-- Widget Kecil Filter Departemen (All + Setiap Departemen) -->
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2.5">Filter Departemen:</p>
                <div class="flex flex-wrap items-center gap-2" id="departmentPills">
                    <button type="button" onclick="selectDepartment('ALL')" data-dept="ALL"
                        class="dept-pill px-4 py-2 rounded-xl text-xs font-extrabold border transition-all flex items-center gap-2">
                        <span>All</span>
                        <span id="count-dept-ALL" class="px-1.5 py-0.5 rounded-md text-[10px] bg-white/20">0</span>
                    </button>

                    @foreach($departments as $dept)
                        <button type="button" onclick="selectDepartment('{{ $dept->nama_departemen }}')" data-dept="{{ $dept->nama_departemen }}"
                            class="dept-pill px-4 py-2 rounded-xl text-xs font-bold border transition-all flex items-center gap-2">
                            <span>{{ $dept->nama_departemen }}</span>
                            <span id="count-dept-{{ $dept->nama_departemen }}" class="px-1.5 py-0.5 rounded-md text-[10px] bg-slate-100 text-slate-500">0</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Bar Searching & Filter Dropdown -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 pt-2">

                <!-- Input Searching (Ketik Langsung - Flex Box Rapi Tanpa Bertumpuk) -->
                <div id="searchBoxWrapper" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50/60 focus-within:bg-white focus-within:border-primary-500 transition-colors">
                    <i class="ph-bold ph-magnifying-glass text-slate-400 text-base shrink-0"></i>
                    <input type="text" id="searchInput" oninput="applyFilters()"
                        placeholder="Ketik NIK, Nama, Job Title..."
                        class="w-full bg-transparent border-0 p-0 text-xs font-semibold text-slate-700 focus:ring-0 outline-none placeholder:text-slate-400">
                </div>

                <!-- Dropdown 1: Level Jabatan -->
                <div>
                    <select id="filterLevelJabatan" onchange="applyFilters()"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-xs font-semibold text-slate-700 focus:bg-white focus:border-primary-500 focus:ring-0 outline-none transition-colors cursor-pointer">
                        <option value="">Semua Level Jabatan</option>
                        @foreach($levelJabatanOptions as $lvl)
                            <option value="{{ $lvl }}">{{ $lvl }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown 2: Nama Sertifikasi / Pelatihan -->
                <div>
                    <select id="filterNamaPelatihan" onchange="applyFilters()"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-xs font-semibold text-slate-700 focus:bg-white focus:border-primary-500 focus:ring-0 outline-none transition-colors cursor-pointer">
                        <option value="">Semua Sertifikasi / Pelatihan</option>
                    </select>
                </div>

                <!-- Dropdown 3: Time Reminder (Khusus Sertifikasi) -->
                <div id="filterTimeReminderWrapper">
                    <select id="filterTimeReminder" onchange="applyFilters()"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-50/60 text-xs font-semibold text-slate-700 focus:bg-white focus:border-primary-500 focus:ring-0 outline-none transition-colors cursor-pointer">
                        <option value="">Semua Time Reminder</option>
                        <option value="expired">Sudah Expired (Kadaluarsa)</option>
                        <option value="h_30">Segera Expired (&lt;= 30 Hari)</option>
                        <option value="h_90">Perlu Perhatian (31 - 90 Hari)</option>
                        <option value="aktif">Masih Berlaku (&gt; 90 Hari)</option>
                        <option value="permanen">Permanen</option>
                    </select>
                </div>

            </div>

            <!-- ====================================================== -->
            <!-- TABEL A: SERTIFIKASI                                   -->
            <!-- ====================================================== -->
            <div id="tableWrapperSertifikasi" class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1200px]">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-bold uppercase tracking-widest border-b border-slate-100 bg-slate-50/60">
                            <th class="py-3.5 px-3 text-center w-12">No</th>
                            <th class="py-3.5 px-3" style="white-space: nowrap;">NIK</th>
                            <th class="py-3.5 px-3">Nama</th>
                            <th class="py-3.5 px-3">Department</th>
                            <th class="py-3.5 px-3" style="white-space: nowrap;">Level Jabatan</th>
                            <th class="py-3.5 px-3">Job Title</th>
                            <th class="py-3.5 px-3">Nama Sertifikat/Pelatihan</th>
                            <th class="py-3.5 px-3" style="white-space: nowrap;">Nomor Regist</th>
                            <th class="py-3.5 px-3" style="white-space: nowrap;">Tanggal Sertifikat Terbit</th>
                            <th class="py-3.5 px-3" style="white-space: nowrap;">Masa Berlaku</th>
                            <th class="py-3.5 px-3" style="white-space: nowrap;">Expired</th>
                            <th class="py-3.5 px-3 text-center" style="white-space: nowrap;">Time Reminder</th>
                        </tr>
                    </thead>
                    <tbody id="tbodySertifikasi" class="divide-y divide-slate-100 text-xs">
                        <!-- Diisi secara dinamis oleh script di bawah -->
                    </tbody>
                </table>
            </div>

            <!-- ====================================================== -->
            <!-- TABEL B: TECHNICAL SKILL & SOFT SKILL                  -->
            <!-- ====================================================== -->
            <div id="tableWrapperSkill" class="overflow-x-auto hidden">
                <table class="w-full text-left border-collapse min-w-[950px]">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-bold uppercase tracking-widest border-b border-slate-100 bg-slate-50/60">
                            <th class="py-3.5 px-4 text-center w-12">No</th>
                            <th class="py-3.5 px-4" style="white-space: nowrap;">NIK</th>
                            <th class="py-3.5 px-4">Nama</th>
                            <th class="py-3.5 px-4">Department</th>
                            <th class="py-3.5 px-4" style="white-space: nowrap;">Level Jabatan</th>
                            <th class="py-3.5 px-4">Job Title</th>
                            <th class="py-3.5 px-4">Nama Sertifikat/Pelatihan</th>
                            <th class="py-3.5 px-4" style="white-space: nowrap;">Tanggal Pelatihan</th>
                        </tr>
                    </thead>
                    <tbody id="tbodySkill" class="divide-y divide-slate-100 text-xs">
                        <!-- Diisi secara dinamis oleh script di bawah -->
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <!-- Script Interaktif untuk Pergantian Widget, Filter Departemen, Search, & Dropdown -->
    <script>
        const datasets = {
            sertifikasi: @json($certifications),
            technical: @json($technicalSkills),
            soft: @json($softSkills),
        };

        const departmentsList = @json($departments->pluck('nama_departemen'));

        let activeCategory = 'sertifikasi';
        let activeDepartment = 'ALL';

        function escapeHtml(value) {
            if (value === null || value === undefined) return '-';
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function switchCategory(category) {
            activeCategory = category;

            // Update style pada 3 Widget Utama
            const cards = {
                sertifikasi: document.getElementById('card-sertifikasi'),
                technical: document.getElementById('card-technical'),
                soft: document.getElementById('card-soft'),
            };

            Object.keys(cards).forEach(key => {
                const el = cards[key];
                if (!el) return;
                if (key === category) {
                    el.classList.add('border-primary-600', 'ring-4', 'ring-primary-50');
                    el.classList.remove('border-slate-100');
                } else {
                    el.classList.remove('border-primary-600', 'ring-4', 'ring-primary-50');
                    el.classList.add('border-slate-100');
                }
            });

            // Update Judul Tabel & Visibilitas Filter Time Reminder
            const titleEl = document.getElementById('activeTableTitle');
            const subtitleEl = document.getElementById('activeTableSubtitle');
            const reminderWrapper = document.getElementById('filterTimeReminderWrapper');
            const searchWrapper = document.getElementById('searchBoxWrapper');
            const tableSertifikasi = document.getElementById('tableWrapperSertifikasi');
            const tableSkill = document.getElementById('tableWrapperSkill');

            if (category === 'sertifikasi') {
                titleEl.textContent = 'Tabel Riwayat Sertifikasi';
                subtitleEl.textContent = 'Menampilkan daftar sertifikat resmi karyawan beserta masa berlaku dan pengingat kadaluarsa (Time Reminder).';
                reminderWrapper.classList.remove('hidden');
                searchWrapper.classList.remove('md:col-span-2');
                tableSertifikasi.classList.remove('hidden');
                tableSkill.classList.add('hidden');
            } else if (category === 'technical') {
                titleEl.textContent = 'Tabel Riwayat Technical Skill';
                subtitleEl.textContent = 'Menampilkan daftar karyawan yang mengikuti pelatihan kompetensi teknis & operasional.';
                reminderWrapper.classList.add('hidden');
                searchWrapper.classList.add('md:col-span-2');
                tableSertifikasi.classList.add('hidden');
                tableSkill.classList.remove('hidden');
            } else {
                titleEl.textContent = 'Tabel Riwayat Soft Skill';
                subtitleEl.textContent = 'Menampilkan daftar karyawan yang mengikuti pelatihan kepemimpinan, komunikasi, dan manajerial.';
                reminderWrapper.classList.add('hidden');
                searchWrapper.classList.add('md:col-span-2');
                tableSertifikasi.classList.add('hidden');
                tableSkill.classList.remove('hidden');
            }

            // Reset dropdown Nama Pelatihan & Time Reminder saat berganti kategori
            document.getElementById('filterNamaPelatihan').value = '';
            document.getElementById('filterTimeReminder').value = '';

            populateTrainingDropdown();
            updateDepartmentCounts();
            applyFilters();
        }

        function selectDepartment(dept) {
            activeDepartment = dept;

            document.querySelectorAll('.dept-pill').forEach(btn => {
                const isSelected = btn.getAttribute('data-dept') === dept;
                const badge = btn.querySelector('span:last-child');
                if (isSelected) {
                    btn.className = 'dept-pill px-4 py-2 rounded-xl text-xs font-extrabold border transition-all flex items-center gap-2 bg-primary-600 text-white border-primary-600 shadow-sm';
                    if (badge) badge.className = 'px-1.5 py-0.5 rounded-md text-[10px] bg-white/20 text-white';
                } else {
                    btn.className = 'dept-pill px-4 py-2 rounded-xl text-xs font-bold border transition-all flex items-center gap-2 bg-white text-slate-600 border-slate-200 hover:bg-slate-50';
                    if (badge) badge.className = 'px-1.5 py-0.5 rounded-md text-[10px] bg-slate-100 text-slate-500';
                }
            });

            applyFilters();
        }

        function updateDepartmentCounts() {
            const currentData = datasets[activeCategory] || [];
            const allBadge = document.getElementById('count-dept-ALL');
            if (allBadge) allBadge.textContent = currentData.length;

            departmentsList.forEach(deptName => {
                const count = currentData.filter(item => item.department === deptName).length;
                const el = document.getElementById('count-dept-' + deptName);
                if (el) el.textContent = count;
            });
        }

        function populateTrainingDropdown() {
            const currentData = datasets[activeCategory] || [];
            const select = document.getElementById('filterNamaPelatihan');
            const uniqueNames = [...new Set(currentData.map(item => item.nama_pelatihan).filter(Boolean))].sort();

            select.innerHTML = '<option value="">Semua Sertifikasi / Pelatihan</option>';
            uniqueNames.forEach(name => {
                const opt = document.createElement('option');
                opt.value = name;
                opt.textContent = name;
                select.appendChild(opt);
            });
        }

        function resetAllFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('filterLevelJabatan').value = '';
            document.getElementById('filterNamaPelatihan').value = '';
            document.getElementById('filterTimeReminder').value = '';
            selectDepartment('ALL');
        }

        function applyFilters() {
            const currentData = datasets[activeCategory] || [];
            const search = (document.getElementById('searchInput').value || '').toLowerCase().trim();
            const levelJabatan = document.getElementById('filterLevelJabatan').value;
            const namaPelatihan = document.getElementById('filterNamaPelatihan').value;
            const timeReminder = document.getElementById('filterTimeReminder').value;

            const filtered = currentData.filter(row => {
                if (activeDepartment !== 'ALL' && row.department !== activeDepartment) {
                    return false;
                }
                if (levelJabatan && row.level_jabatan !== levelJabatan) {
                    return false;
                }
                if (namaPelatihan && row.nama_pelatihan !== namaPelatihan) {
                    return false;
                }
                if (activeCategory === 'sertifikasi' && timeReminder && row.reminder_code !== timeReminder) {
                    return false;
                }
                if (search) {
                    const haystack = [
                        row.nik,
                        row.nama,
                        row.department,
                        row.level_jabatan,
                        row.job_title,
                        row.nama_pelatihan,
                        row.nomor_regist || '',
                    ].join(' ').toLowerCase();

                    if (!haystack.includes(search)) {
                        return false;
                    }
                }
                return true;
            });

            if (activeCategory === 'sertifikasi') {
                renderSertifikasiTable(filtered);
            } else {
                renderSkillTable(filtered);
            }
        }

        function renderSertifikasiTable(rows) {
            const tbody = document.getElementById('tbodySertifikasi');
            if (!rows.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="12" class="py-12 text-center text-slate-400 font-medium">
                            Tidak ada data sertifikasi yang sesuai dengan filter pencarian.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = rows.map((row, idx) => `
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-4 px-3 text-center font-bold text-slate-400">${idx + 1}</td>
                    <td class="py-4 px-3 font-bold text-slate-600" style="white-space: nowrap;">${escapeHtml(row.nik)}</td>
                    <td class="py-4 px-3 font-extrabold text-slate-800" style="white-space: nowrap;">${escapeHtml(row.nama)}</td>
                    <td class="py-4 px-3" style="white-space: nowrap;">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-primary-50 text-primary-700 border border-primary-100 uppercase">
                            ${escapeHtml(row.department)}
                        </span>
                    </td>
                    <td class="py-4 px-3 font-semibold text-slate-700" style="white-space: nowrap;">${escapeHtml(row.level_jabatan)}</td>
                    <td class="py-4 px-3 text-slate-600">${escapeHtml(row.job_title)}</td>
                    <td class="py-4 px-3 font-bold text-slate-800">${escapeHtml(row.nama_pelatihan)}</td>
                    <td class="py-4 px-3 font-mono text-[11px] font-semibold text-slate-600" style="white-space: nowrap;">${escapeHtml(row.nomor_regist)}</td>
                    <td class="py-4 px-3 text-slate-600" style="white-space: nowrap;">${escapeHtml(row.tanggal_terbit)}</td>
                    <td class="py-4 px-3 font-semibold text-slate-700" style="white-space: nowrap;">${escapeHtml(row.masa_berlaku)}</td>
                    <td class="py-4 px-3 font-semibold text-slate-700" style="white-space: nowrap;">${escapeHtml(row.expired)}</td>
                    <td class="py-4 px-3 text-center" style="white-space: nowrap;">
                        <span style="white-space: nowrap;" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold border ${row.reminder_badge}">
                            <span class="w-1.5 h-1.5 rounded-full ${row.reminder_dot || 'bg-slate-400'}"></span>
                            <span>${escapeHtml(row.reminder_label)}</span>
                        </span>
                    </td>
                </tr>
            `).join('');
        }

        function renderSkillTable(rows) {
            const tbody = document.getElementById('tbodySkill');
            if (!rows.length) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400 font-medium">
                            Tidak ada data pelatihan pada kategori ini yang sesuai dengan filter pencarian.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = rows.map((row, idx) => `
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="py-4 px-4 text-center font-bold text-slate-400">${idx + 1}</td>
                    <td class="py-4 px-4 font-bold text-slate-600" style="white-space: nowrap;">${escapeHtml(row.nik)}</td>
                    <td class="py-4 px-4 font-extrabold text-slate-800" style="white-space: nowrap;">${escapeHtml(row.nama)}</td>
                    <td class="py-4 px-4" style="white-space: nowrap;">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-extrabold bg-primary-50 text-primary-700 border border-primary-100 uppercase">
                            ${escapeHtml(row.department)}
                        </span>
                    </td>
                    <td class="py-4 px-4 font-semibold text-slate-700" style="white-space: nowrap;">${escapeHtml(row.level_jabatan)}</td>
                    <td class="py-4 px-4 text-slate-600">${escapeHtml(row.job_title)}</td>
                    <td class="py-4 px-4 font-bold text-slate-800">${escapeHtml(row.nama_pelatihan)}</td>
                    <td class="py-4 px-4 font-semibold text-slate-600" style="white-space: nowrap;">
                        <i class="ph-bold ph-calendar-blank text-slate-400 mr-1"></i>${escapeHtml(row.tanggal_pelatihan)}
                    </td>
                </tr>
            `).join('');
        }

        // Inisialisasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', () => {
            selectDepartment('ALL');
            switchCategory('sertifikasi');
        });
    </script>
</x-app-layout>
