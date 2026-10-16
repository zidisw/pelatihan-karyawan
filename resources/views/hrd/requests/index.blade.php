<x-app-layout>
    <x-slot name="header">Persetujuan Pengajuan Pelatihan</x-slot>

    <div class="w-full space-y-6" x-data="{ activeModalId: null }">
        <!-- Page Title & Export Buttons (Issue #10) -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Persetujuan & Review Request Training</h2>
                <p class="text-xs text-slate-500 mt-1 font-medium">Tinjau pengajuan pelatihan katalog maupun usulan training baru, serta berikan keputusan keseluruhan atau per peserta (Disetujui Sebagian).</p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('hrd.export.requests', array_merge(request()->query(), ['format' => 'csv'])) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold text-xs hover:bg-emerald-600 hover:text-white transition-all">
                    <i class="ph-bold ph-file-csv text-base"></i> Export Excel / CSV
                </a>

                <a href="{{ route('hrd.export.requests', array_merge(request()->query(), ['format' => 'print'])) }}"
                   target="_blank"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-slate-800 text-white font-bold text-xs hover:bg-slate-700 transition-all">
                    <i class="ph-bold ph-printer text-base"></i> Cetak / PDF
                </a>
            </div>
        </div>

        <!-- Flash Notifications -->
        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-xs font-bold flex items-center gap-2">
                <i class="ph-fill ph-check-circle text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-bold flex items-center gap-2">
                <i class="ph-fill ph-warning-circle text-lg"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-xs font-bold">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- 4 Kartu Ringkasan Klikable -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('hrd.requests.index') }}"
               class="p-5 rounded-2xl border transition flex items-center justify-between {{ !request('status') ? 'bg-blue-600 text-white border-blue-600 shadow-md shadow-blue-600/15' : 'bg-white text-slate-800 border-slate-100 hover:border-blue-200 shadow-sm' }}">
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-wider {{ !request('status') ? 'text-blue-100' : 'text-slate-400' }}">Total Pengajuan</div>
                    <div class="text-2xl font-extrabold mt-1">{{ $totalCount ?? $requests->count() }}</div>
                </div>
                <div class="w-11 h-11 rounded-xl flex items-center justify-center {{ !request('status') ? 'bg-white/20 text-white' : 'bg-blue-50 text-blue-600' }}">
                    <i class="ph-fill ph-tray text-xl"></i>
                </div>
            </a>

            <a href="{{ route('hrd.requests.index', ['status' => 'waiting']) }}"
               class="p-5 rounded-2xl border transition flex items-center justify-between {{ request('status') === 'waiting' ? 'bg-amber-500 text-white border-amber-500 shadow-md shadow-amber-500/15' : 'bg-white text-slate-800 border-slate-100 hover:border-amber-200 shadow-sm' }}">
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-wider {{ request('status') === 'waiting' ? 'text-amber-100' : 'text-slate-400' }}">Menunggu / Pending</div>
                    <div class="text-2xl font-extrabold mt-1">{{ $waitingCount ?? 0 }}</div>
                </div>
                <div class="w-11 h-11 rounded-xl flex items-center justify-center {{ request('status') === 'waiting' ? 'bg-white/20 text-white' : 'bg-amber-50 text-amber-600' }}">
                    <i class="ph-fill ph-hourglass-high text-xl"></i>
                </div>
            </a>

            <a href="{{ route('hrd.requests.index', ['status' => 'approved_group']) }}"
               class="p-5 rounded-2xl border transition flex items-center justify-between {{ request('status') === 'approved_group' ? 'bg-emerald-600 text-white border-emerald-600 shadow-md shadow-emerald-600/15' : 'bg-white text-slate-800 border-slate-100 hover:border-emerald-200 shadow-sm' }}">
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-wider {{ request('status') === 'approved_group' ? 'text-emerald-100' : 'text-slate-400' }}">Disetujui (Penuh/Sebagian)</div>
                    <div class="text-2xl font-extrabold mt-1">{{ $approvedCount ?? 0 }}</div>
                </div>
                <div class="w-11 h-11 rounded-xl flex items-center justify-center {{ request('status') === 'approved_group' ? 'bg-white/20 text-white' : 'bg-emerald-50 text-emerald-600' }}">
                    <i class="ph-fill ph-check-circle text-xl"></i>
                </div>
            </a>

            <a href="{{ route('hrd.requests.index', ['status' => 'rejected']) }}"
               class="p-5 rounded-2xl border transition flex items-center justify-between {{ request('status') === 'rejected' ? 'bg-rose-600 text-white border-rose-600 shadow-md shadow-rose-600/15' : 'bg-white text-slate-800 border-slate-100 hover:border-rose-200 shadow-sm' }}">
                <div>
                    <div class="text-[11px] font-bold uppercase tracking-wider {{ request('status') === 'rejected' ? 'text-rose-100' : 'text-slate-400' }}">Ditolak</div>
                    <div class="text-2xl font-extrabold mt-1">{{ $rejectedCount ?? 0 }}</div>
                </div>
                <div class="w-11 h-11 rounded-xl flex items-center justify-center {{ request('status') === 'rejected' ? 'bg-white/20 text-white' : 'bg-rose-50 text-rose-600' }}">
                    <i class="ph-fill ph-x-circle text-xl"></i>
                </div>
            </a>
        </div>

        <!-- Search & Filter Bar -->
        <form method="GET" action="{{ route('hrd.requests.index') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                <div class="md:col-span-4">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Cari Pengajuan / Peserta</label>
                    <div class="relative">
                        <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="No. Request, nama pelatihan, scope, atau nama peserta..."
                               class="w-full pl-10 pr-4 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 outline-none">
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Jenis Request</label>
                    <select name="jenis_request" class="w-full px-3 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50">
                        <option value="">Semua Jenis</option>
                        <option value="katalog" @selected(request('jenis_request') === 'katalog')>Dari Katalog</option>
                        <option value="training_baru" @selected(request('jenis_request') === 'training_baru')>Training Baru</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Departemen</label>
                    <select name="department_id" class="w-full px-3 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50">
                        <option value="">Semua Dept</option>
                        @foreach($departments ?? [] as $dept)
                            <option value="{{ $dept->id }}" @selected((string) request('department_id') === (string) $dept->id)>{{ $dept->nama_departemen }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status Keputusan</label>
                    <select name="status" class="w-full px-3 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50">
                        <option value="">Semua Status</option>
                        <option value="waiting" @selected(request('status') === 'waiting')>Menunggu / Pending</option>
                        <option value="menunggu_review" @selected(request('status') === 'menunggu_review')>Menunggu Review</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="approved" @selected(request('status') === 'approved')>Disetujui Seluruhnya</option>
                        <option value="partially_approved" @selected(request('status') === 'partially_approved')>Disetujui Sebagian</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
                    </select>
                </div>

                <div class="md:col-span-2 flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2.5 px-4 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'jenis_request', 'department_id', 'status']))
                        <a href="{{ route('hrd.requests.index') }}" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition" title="Reset">
                            <i class="ph-bold ph-arrow-counter-clockwise"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Main Table Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-bold uppercase tracking-widest border-b border-slate-100">
                            <th class="pb-3.5 px-4">No. Request & Waktu</th>
                            <th class="pb-3.5 px-4">Departemen & Pengaju</th>
                            <th class="pb-3.5 px-4">Program / Usulan Training</th>
                            <th class="pb-3.5 px-4">Daftar Peserta</th>
                            <th class="pb-3.5 px-4 text-center">Status Keputusan</th>
                            <th class="pb-3.5 px-4 text-center">Aksi HRGA</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($requests as $req)
                            @php
                                $deptName = $req->department?->nama_departemen
                                    ?? $req->employee?->department?->nama_departemen
                                    ?? $req->diajukanOleh?->department?->nama_departemen
                                    ?? '-';

                                $participants = $req->participants;
                                $partCount = $participants->count() ?: ($req->employee ? 1 : 0);

                                $statusMeta = match($req->status) {
                                    'approved' => ['label' => 'Disetujui', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                    'partially_approved' => ['label' => 'Disetujui Sebagian', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                    'rejected' => ['label' => 'Ditolak', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'],
                                    'pending' => ['label' => 'Pending', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                    default => ['label' => 'Menunggu Review', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-4 align-top">
                                    <div class="text-xs font-extrabold text-slate-800">{{ $req->nomor_request ?? ('#REQ-' . $req->id) }}</div>
                                    <div class="text-[11px] text-slate-400 font-medium mt-0.5">{{ $req->created_at->format('d M Y, H:i') }}</div>
                                    @if($req->periode_pengajuan)
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-bold">
                                            Periode: {{ $req->periode_pengajuan }}
                                        </span>
                                    @endif
                                </td>

                                <td class="py-4 px-4 align-top">
                                    <span class="inline-block px-2.5 py-0.5 rounded-md bg-blue-50 text-blue-700 text-[10px] font-extrabold uppercase tracking-wider mb-1">
                                        {{ $deptName }}
                                    </span>
                                    <div class="text-xs font-bold text-slate-700">{{ $req->diajukanOleh->name ?? 'User Departemen' }}</div>
                                </td>

                                <td class="py-4 px-4 align-top max-w-xs">
                                    <div class="flex flex-wrap items-center gap-1.5 mb-1">
                                        @if($req->jenis_request === 'training_baru')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase">
                                                + Training Baru
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase">
                                                Katalog
                                            </span>
                                        @endif
                                        @if($req->pelaksana)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                                                {{ $req->pelaksana }}
                                            </span>
                                        @endif
                                        @if($req->metode_pelaksanaan)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 uppercase">
                                                {{ $req->metode_pelaksanaan }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs font-extrabold text-slate-800">
                                        {{ $req->training?->nama_pelatihan ?? $req->scope_program ?? $req->training_need ?? 'Usulan Training Baru' }}
                                    </div>
                                    @if($req->jenis_request === 'training_baru' && $req->estimasi_biaya !== null)
                                        <div class="text-[11px] font-bold text-slate-500 mt-0.5">
                                            Est. Biaya: Rp {{ number_format((float) $req->estimasi_biaya, 0, ',', '.') }}
                                        </div>
                                    @elseif($req->training)
                                        <div class="text-[11px] font-bold text-slate-500 mt-0.5">
                                            Sisa Kuota Katalog: <span class="text-emerald-600">{{ $req->training->remainingQuota() }}</span> / {{ $req->training->kuota }} Kursi
                                        </div>
                                    @endif
                                </td>

                                <td class="py-4 px-4 align-top">
                                    <div class="text-xs font-extrabold text-slate-800 mb-1">{{ $partCount }} Peserta:</div>
                                    <div class="space-y-1">
                                        @if($participants->isNotEmpty())
                                            @foreach($participants->take(3) as $part)
                                                @php
                                                    $dotClass = match($part->status_peserta) {
                                                        'approved' => 'bg-emerald-500',
                                                        'rejected' => 'bg-rose-500',
                                                        default => 'bg-amber-400',
                                                    };
                                                @endphp
                                                <div class="flex items-center gap-1.5 text-[11px] text-slate-600 font-semibold">
                                                    <span class="w-2 h-2 rounded-full {{ $dotClass }}"></span>
                                                    <span>{{ $part->nama_peserta ?? $part->employee?->nama ?? '-' }}</span>
                                                </div>
                                            @endforeach
                                            @if($participants->count() > 3)
                                                <div class="text-[10px] font-bold text-blue-600">+{{ $participants->count() - 3 }} peserta lainnya</div>
                                            @endif
                                        @elseif($req->employee)
                                            <div class="text-[11px] text-slate-700 font-semibold">{{ $req->employee->nama }} ({{ $req->employee->nik }})</div>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-4 px-4 align-top text-center">
                                    <span class="inline-block px-3 py-1 rounded-lg border text-[10px] font-extrabold uppercase tracking-wider {{ $statusMeta['class'] }}">
                                        {{ $statusMeta['label'] }}
                                    </span>
                                    @if($req->catatan_admin)
                                        <p class="text-[11px] text-slate-500 italic mt-1 max-w-[180px] mx-auto line-clamp-2">"{{ $req->catatan_admin }}"</p>
                                    @endif
                                    @if($req->reviewer)
                                        <div class="text-[10px] text-slate-400 mt-1">Oleh: {{ $req->reviewer->name }}</div>
                                    @endif
                                </td>

                                <td class="py-4 px-4 align-top text-center">
                                    <button type="button"
                                            @click="activeModalId = {{ $req->id }}"
                                            class="px-3.5 py-2 rounded-xl text-xs font-bold transition inline-flex items-center gap-1.5 {{ in_array($req->status, ['menunggu_review', 'pending']) ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-sm' : 'bg-slate-100 hover:bg-slate-200 text-slate-700' }}">
                                        <i class="ph-bold {{ in_array($req->status, ['menunggu_review', 'pending']) ? 'ph-gavel' : 'ph-eye' }}"></i>
                                        <span>{{ in_array($req->status, ['menunggu_review', 'pending']) ? 'Tinjau & Putuskan' : 'Detail / Ubah' }}</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 text-xs font-semibold">
                                    Belum ada data pengajuan pelatihan yang sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Review & Keputusan Per Peserta -->
        @foreach ($requests as $req)
            @php
                $participants = $req->participants;
                $initialDecisions = [];
                $initialNotes = [];
                foreach ($participants as $p) {
                    $initialDecisions[$p->id] = in_array($p->status_peserta, ['approved', 'rejected', 'pending'])
                        ? $p->status_peserta
                        : 'approved';
                    $initialNotes[$p->id] = $p->catatan_keputusan ?? '';
                }
                $defaultMode = ($req->status === 'partially_approved' || $participants->count() > 1) ? 'per_participant' : 'bulk';
                $defaultBulkStatus = in_array($req->status, ['approved', 'rejected', 'pending']) ? $req->status : 'approved';
            @endphp

            <div x-show="activeModalId === {{ $req->id }}"
                 x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4"
                 @keydown.escape.window="activeModalId = null"
                 x-data="{
                    decisionMode: '{{ $defaultMode }}',
                    bulkStatus: '{{ $defaultBulkStatus }}',
                    catatanAdmin: {{ Js::from($req->catatan_admin ?? '') }},
                    decisions: {{ Js::from($initialDecisions) }},
                    notes: {{ Js::from($initialNotes) }},
                    get computedOverallStatus() {
                        if (this.decisionMode === 'bulk') return this.bulkStatus;
                        const vals = Object.values(this.decisions);
                        if (vals.length === 0) return this.bulkStatus;
                        const app = vals.filter(v => v === 'approved').length;
                        const rej = vals.filter(v => v === 'rejected').length;
                        const pen = vals.filter(v => v === 'pending').length;
                        if (app === vals.length) return 'approved';
                        if (rej === vals.length) return 'rejected';
                        if (pen === vals.length) return 'pending';
                        if (app > 0) return 'partially_approved';
                        return 'pending';
                    },
                    get needsNote() {
                        if (this.decisionMode === 'bulk') {
                            return this.bulkStatus === 'rejected' || this.bulkStatus === 'pending';
                        }
                        const vals = Object.values(this.decisions);
                        return vals.includes('rejected') || vals.includes('pending');
                    },
                    setAllParticipants(status) {
                        Object.keys(this.decisions).forEach(k => {
                            this.decisions[k] = status;
                        });
                    }
                 }">
                <div @click.outside="activeModalId = null"
                     class="bg-white rounded-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-100 p-6 sm:p-7 space-y-6">

                    <!-- Modal Header -->
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2.5 py-1 rounded bg-blue-50 text-blue-700 text-[10px] font-extrabold uppercase">
                                    {{ $req->nomor_request ?? ('#REQ-' . $req->id) }}
                                </span>
                                <span class="px-2.5 py-1 rounded {{ $req->jenis_request === 'training_baru' ? 'bg-indigo-50 text-indigo-700' : 'bg-emerald-50 text-emerald-700' }} text-[10px] font-extrabold uppercase">
                                    {{ $req->jenis_request === 'training_baru' ? 'Usulan Training Baru (Di Luar Katalog)' : 'Pengajuan dari Katalog' }}
                                </span>
                            </div>
                            <h3 class="text-lg font-extrabold text-slate-800 mt-2">
                                {{ $req->training?->nama_pelatihan ?? $req->scope_program ?? $req->training_need ?? 'Review Pengajuan Pelatihan' }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Diajukan oleh <span class="font-bold text-slate-700">{{ $req->diajukanOleh->name ?? 'User Departemen' }}</span>
                                ({{ $req->department?->nama_departemen ?? $req->employee?->department?->nama_departemen ?? '-' }})
                                pada {{ $req->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                        <button type="button" @click="activeModalId = null" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500">
                            <i class="ph-bold ph-x"></i>
                        </button>
                    </div>

                    <!-- Rincian Informasi Pengajuan (10 Field untuk Training Baru / Info Katalog) -->
                    @if($req->jenis_request === 'training_baru')
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/80 space-y-3 text-xs">
                            <div class="text-[11px] font-extrabold text-indigo-700 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="ph-fill ph-clipboard-text"></i> Rincian 10 Field Usulan Training Baru
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="bg-white p-3 rounded-xl border border-slate-100">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">1. Department Pengaju</span>
                                    <span class="font-extrabold text-slate-800">{{ $req->department?->nama_departemen ?? '-' }}</span>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-100">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">2. Periode Pengajuan</span>
                                    <span class="font-extrabold text-slate-800">{{ $req->periode_pengajuan ?? '-' }}</span>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-100">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">7. Estimasi Biaya</span>
                                    <span class="font-extrabold text-emerald-700">Rp {{ number_format((float) $req->estimasi_biaya, 0, ',', '.') }}</span>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-100">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">8. Pelaksana</span>
                                    <span class="font-extrabold text-slate-800">{{ $req->pelaksana ?? '-' }}</span>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-100">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">10. Metode Pelaksanaan</span>
                                    <span class="font-extrabold text-slate-800">{{ $req->metode_pelaksanaan ?? '-' }}</span>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-100">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">3. Scope / Program</span>
                                    <span class="font-extrabold text-slate-800">{{ $req->scope_program ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="bg-white p-3 rounded-xl border border-slate-100">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">4. Kondisi Karyawan Saat Ini</span>
                                    <p class="font-medium text-slate-700 mt-1">{{ $req->kondisi_saat_ini ?? '-' }}</p>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-100">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">5. Training Need</span>
                                    <p class="font-medium text-slate-700 mt-1">{{ $req->training_need ?? '-' }}</p>
                                </div>
                                <div class="bg-white p-3 rounded-xl border border-slate-100">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">6. Target / KPI Setelah Pelatihan</span>
                                    <p class="font-medium text-slate-700 mt-1">{{ $req->target_kpi ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @elseif($req->training)
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200/80 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Kode Program</span>
                                <span class="font-extrabold text-slate-800">{{ $req->training->kode_program ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Jadwal</span>
                                <span class="font-extrabold text-slate-800">{{ \Carbon\Carbon::parse($req->training->tanggal_mulai)->format('d M Y') }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Kuota Total / Terisi</span>
                                <span class="font-extrabold text-slate-800">{{ $req->training->kuota }} / {{ $req->training->approvedParticipantsCount() }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase block">Sisa Kuota Tersedia</span>
                                <span class="font-extrabold text-emerald-600">{{ $req->training->remainingQuota() }} Kursi</span>
                            </div>
                            @if($req->kondisi_saat_ini)
                                <div class="col-span-full pt-2 border-t border-slate-200/60">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase block">Catatan / Alasan Pengajuan Departemen:</span>
                                    <p class="font-medium text-slate-700 mt-0.5">{{ $req->kondisi_saat_ini }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Form Keputusan HRGA -->
                    <form action="{{ route('hrd.requests.process', $req->id) }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="decision_mode" :value="decisionMode">
                        <input type="hidden" name="status" :value="computedOverallStatus">

                        <!-- Mode Selector Tabs -->
                        <div class="flex flex-wrap items-center justify-between gap-2 bg-slate-100 p-1.5 rounded-xl">
                            <button type="button" @click="decisionMode = 'bulk'"
                                    :class="decisionMode === 'bulk' ? 'bg-white text-slate-800 shadow-xs' : 'text-slate-500 hover:text-slate-700'"
                                    class="flex-1 py-2 px-3 rounded-lg text-xs font-extrabold transition">
                                Keputusan Sekaligus (Semua Peserta)
                            </button>
                            <button type="button" @click="decisionMode = 'per_participant'"
                                    :class="decisionMode === 'per_participant' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-500 hover:text-slate-700'"
                                    class="flex-1 py-2 px-3 rounded-lg text-xs font-extrabold transition">
                                Keputusan Per Peserta (Disetujui Sebagian)
                            </button>
                        </div>

                        <!-- Mode 1: Keputusan Sekaligus (Bulk) -->
                        <div x-show="decisionMode === 'bulk'" class="space-y-3">
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider">Pilih Keputusan untuk Seluruh Peserta:</label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <button type="button" @click="bulkStatus = 'approved'"
                                        :class="bulkStatus === 'approved' ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-emerald-50'"
                                        class="py-3 px-4 rounded-xl border text-xs font-extrabold transition flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-check-circle text-base"></i> Setujui Semua
                                </button>
                                <button type="button" @click="bulkStatus = 'pending'"
                                        :class="bulkStatus === 'pending' ? 'bg-amber-500 text-white border-amber-500 shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-amber-50'"
                                        class="py-3 px-4 rounded-xl border text-xs font-extrabold transition flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-hourglass-high text-base"></i> Pending / Tunda
                                </button>
                                <button type="button" @click="bulkStatus = 'rejected'"
                                        :class="bulkStatus === 'rejected' ? 'bg-rose-600 text-white border-rose-600 shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-rose-50'"
                                        class="py-3 px-4 rounded-xl border text-xs font-extrabold transition flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-x-circle text-base"></i> Tolak Semua
                                </button>
                            </div>
                        </div>

                        <!-- Mode 2: Keputusan Per Peserta -->
                        <div x-show="decisionMode === 'per_participant'" class="space-y-3">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                    9. Daftar Identitas Peserta & Keputusan Per Peserta:
                                </label>
                                <div class="flex items-center gap-1.5">
                                    <button type="button" @click="setAllParticipants('approved')" class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[10px] font-bold hover:bg-emerald-100">Setujui Semua</button>
                                    <button type="button" @click="setAllParticipants('pending')" class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 text-[10px] font-bold hover:bg-amber-100">Pending Semua</button>
                                    <button type="button" @click="setAllParticipants('rejected')" class="px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 text-[10px] font-bold hover:bg-rose-100">Tolak Semua</button>
                                </div>
                            </div>

                            <div class="border border-slate-200 rounded-xl overflow-hidden divide-y divide-slate-100">
                                @forelse($participants as $part)
                                    <div class="p-3.5 space-y-2 bg-white">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                            <div>
                                                <div class="text-xs font-extrabold text-slate-800">{{ $part->nama_peserta ?? $part->employee?->nama ?? '-' }}</div>
                                                <div class="text-[11px] text-slate-500">
                                                    Jabatan: <span class="font-semibold text-slate-700">{{ $part->jabatan_peserta ?? $part->employee?->jabatan ?? '-' }}</span>
                                                    @if($part->employee?->nik) • NIK: {{ $part->employee->nik }} @endif
                                                </div>
                                            </div>

                                            <input type="hidden" name="participant_decisions[{{ $part->id }}]" :value="decisions[{{ $part->id }}]">
                                            <div class="inline-flex items-center gap-1.5">
                                                <button type="button" @click="decisions[{{ $part->id }}] = 'approved'"
                                                        :class="decisions[{{ $part->id }}] === 'approved' ? 'bg-emerald-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-emerald-50'"
                                                        class="px-3 py-1.5 rounded-lg text-[11px] font-extrabold transition">
                                                    Setujui
                                                </button>
                                                <button type="button" @click="decisions[{{ $part->id }}] = 'pending'"
                                                        :class="decisions[{{ $part->id }}] === 'pending' ? 'bg-amber-500 text-white' : 'bg-slate-100 text-slate-600 hover:bg-amber-50'"
                                                        class="px-3 py-1.5 rounded-lg text-[11px] font-extrabold transition">
                                                    Pending
                                                </button>
                                                <button type="button" @click="decisions[{{ $part->id }}] = 'rejected'"
                                                        :class="decisions[{{ $part->id }}] === 'rejected' ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-rose-50'"
                                                        class="px-3 py-1.5 rounded-lg text-[11px] font-extrabold transition">
                                                    Tolak
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <input type="text"
                                                   name="participant_notes[{{ $part->id }}]"
                                                   x-model="notes[{{ $part->id }}]"
                                                   placeholder="Catatan keputusan untuk peserta ini (opsional bila catatan umum diisi)..."
                                                   class="w-full px-3 py-1.5 text-[11px] font-medium rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-400 outline-none">
                                        </div>
                                    </div>
                                @empty
                                    @if($req->employee)
                                        <div class="p-3.5 text-xs font-bold text-slate-700">
                                            {{ $req->employee->nama }} ({{ $req->employee->nik }}) — {{ $req->employee->jabatan }}
                                        </div>
                                    @endif
                                @endforelse
                            </div>

                            <!-- Preview Status Akhir Otomatis -->
                            <div class="p-3 rounded-xl bg-blue-50/70 border border-blue-200 flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-600">Status Pengajuan Otomatis:</span>
                                <span class="font-extrabold uppercase px-2.5 py-1 rounded bg-white border border-blue-200 text-blue-700"
                                      x-text="computedOverallStatus === 'partially_approved' ? 'Disetujui Sebagian (Partially Approved)' : (computedOverallStatus === 'approved' ? 'Disetujui Seluruhnya' : (computedOverallStatus === 'rejected' ? 'Ditolak Seluruhnya' : 'Pending / Tunda'))"></span>
                            </div>
                        </div>

                        <!-- Catatan Admin HRGA (Wajib jika Ditolak atau Pending sesuai BR-07) -->
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                                Catatan Keputusan Admin HRGA
                                <span x-show="needsNote" class="text-rose-600 font-extrabold">(Wajib Diisi sesuai BR-07 *)</span>
                            </label>
                            <textarea name="catatan_admin" rows="2"
                                      x-model="catatanAdmin"
                                      :required="needsNote && decisionMode === 'bulk'"
                                      placeholder="Tuliskan alasan penolakan, alasan penundaan (pending), atau instruksi pelaksanaan pelatihan..."
                                      class="w-full px-4 py-2.5 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 outline-none transition"></textarea>
                            <p x-show="needsNote" class="text-[11px] text-rose-600 font-semibold mt-1">
                                * Sesuai aturan BR-07, Anda wajib mencantumkan alasan apabila pengajuan atau salah satu peserta berstatus Ditolak / Pending.
                            </p>
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <button type="button" @click="activeModalId = null"
                                    class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">
                                Batal
                            </button>
                            <button type="submit"
                                    class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-600/20 transition inline-flex items-center gap-2">
                                <i class="ph-bold ph-check-square-offset"></i>
                                Simpan Keputusan HRGA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
