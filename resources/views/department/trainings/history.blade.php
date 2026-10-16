<x-app-layout>
    <x-slot name="header">Riwayat Pengajuan Pelatihan</x-slot>

    <div class="w-full space-y-6" x-data="{ detailModalId: null }">
        <!-- Header & Action Buttons -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Riwayat Pengajuan Pelatihan</h2>
                <p class="text-sm text-slate-500 mt-1 font-medium">Pantau status persetujuan pengajuan katalog maupun usulan training baru departemen Anda.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('department.trainings.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    <i class="ph-bold ph-books text-base"></i>
                    Ajukan dari Katalog
                </a>
                <a href="{{ route('department.requests.createNew') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md shadow-blue-600/20 transition">
                    <i class="ph-bold ph-plus-circle text-base"></i>
                    + Request Training Baru
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-bold flex items-center gap-2">
                <i class="ph-fill ph-check-circle text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter Bar -->
        <form method="GET" action="{{ route('department.trainings.history') }}" class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-wrap items-center gap-3">
            <select name="jenis_request" class="px-3.5 py-2 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50">
                <option value="">Semua Jenis Pengajuan</option>
                <option value="katalog" @selected(request('jenis_request') === 'katalog')>Dari Katalog Pelatihan</option>
                <option value="training_baru" @selected(request('jenis_request') === 'training_baru')>Usulan Training Baru</option>
            </select>

            <select name="status" class="px-3.5 py-2 text-xs font-semibold rounded-xl border border-slate-200 bg-slate-50">
                <option value="">Semua Status</option>
                <option value="pending_group" @selected(request('status') === 'pending_group')>Menunggu Review / Pending</option>
                <option value="approved" @selected(request('status') === 'approved')>Disetujui Seluruhnya</option>
                <option value="partially_approved" @selected(request('status') === 'partially_approved')>Disetujui Sebagian</option>
                <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">
                Terapkan Filter
            </button>
            @if(request()->hasAny(['jenis_request', 'status']))
                <a href="{{ route('department.trainings.history') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold rounded-xl transition">
                    Reset
                </a>
            @endif
        </form>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-bold uppercase tracking-widest border-b border-slate-100">
                            <th class="pb-3.5 px-4">No. Request & Tanggal</th>
                            <th class="pb-3.5 px-4">Program / Scope Pelatihan</th>
                            <th class="pb-3.5 px-4">Peserta Diajukan</th>
                            <th class="pb-3.5 px-4 text-center">Status Pengajuan</th>
                            <th class="pb-3.5 px-4">Catatan HRGA</th>
                            <th class="pb-3.5 px-4 text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($requests as $req)
                            @php
                                $statusMeta = match($req->status) {
                                    'approved', 'disetujui' => ['label' => 'Disetujui', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                    'partially_approved' => ['label' => 'Disetujui Sebagian', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                    'rejected', 'ditolak' => ['label' => 'Ditolak', 'class' => 'bg-rose-50 text-rose-700 border-rose-200'],
                                    'pending' => ['label' => 'Pending / Revisi', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                    default => ['label' => 'Menunggu Review', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                };
                                $participants = $req->participants;
                                $partCount = $participants->count() ?: ($req->employee ? 1 : 0);
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-4 px-4 align-top">
                                    <div class="text-xs font-extrabold text-slate-800">{{ $req->nomor_request ?? ('#REQ-' . $req->id) }}</div>
                                    <div class="text-[11px] text-slate-400 font-medium mt-0.5">
                                        {{ $req->created_at->format('d M Y, H:i') }}
                                    </div>
                                    @if($req->periode_pengajuan)
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[10px] font-bold">
                                            Periode: {{ $req->periode_pengajuan }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 align-top">
                                    <div class="flex flex-wrap items-center gap-1.5 mb-1">
                                        @if($req->jenis_request === 'training_baru')
                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase">
                                                Training Baru
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200 uppercase">
                                                Katalog
                                            </span>
                                        @endif
                                        @if($req->metode_pelaksanaan)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 uppercase">
                                                {{ $req->metode_pelaksanaan }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs font-extrabold text-slate-800">
                                        {{ $req->training?->nama_pelatihan ?? $req->scope_program ?? $req->training_need ?? 'Usulan Pelatihan' }}
                                    </div>
                                    @if($req->jenis_request === 'training_baru' && $req->training_need)
                                        <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">Need: {{ $req->training_need }}</p>
                                    @endif
                                </td>
                                <td class="py-4 px-4 align-top">
                                    <div class="text-xs font-extrabold text-slate-700 mb-1">{{ $partCount }} Peserta:</div>
                                    <div class="space-y-1">
                                        @if($participants->isNotEmpty())
                                            @foreach($participants->take(3) as $part)
                                                @php
                                                    $pDot = match($part->status_peserta) {
                                                        'approved' => 'bg-emerald-500',
                                                        'rejected' => 'bg-rose-500',
                                                        default => 'bg-amber-400',
                                                    };
                                                @endphp
                                                <div class="flex items-center gap-1.5 text-[11px] text-slate-600 font-semibold">
                                                    <span class="w-2 h-2 rounded-full {{ $pDot }}"></span>
                                                    <span>{{ $part->nama_peserta ?? $part->employee?->nama ?? '-' }}</span>
                                                </div>
                                            @endforeach
                                            @if($participants->count() > 3)
                                                <div class="text-[10px] font-bold text-blue-600">+{{ $participants->count() - 3 }} peserta lainnya</div>
                                            @endif
                                        @elseif($req->employee)
                                            <div class="text-[11px] text-slate-600 font-semibold">{{ $req->employee->nama }} ({{ $req->employee->nik }})</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-4 align-top text-center">
                                    <span class="inline-block px-3 py-1 rounded-lg border text-[10px] font-extrabold uppercase tracking-wider {{ $statusMeta['class'] }}">
                                        {{ $statusMeta['label'] }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 align-top text-xs text-slate-600 max-w-xs">
                                    @if($req->catatan_admin)
                                        <p class="italic font-medium">"{{ $req->catatan_admin }}"</p>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 align-top text-center">
                                    <button type="button" @click="detailModalId = {{ $req->id }}"
                                            class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-blue-50 text-slate-700 hover:text-blue-600 text-xs font-bold transition inline-flex items-center gap-1">
                                        <i class="ph-bold ph-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 text-xs font-semibold">
                                    Belum ada riwayat pengajuan pelatihan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $requests->links() }}
            </div>
        </div>

        <!-- Modal Detail Pengajuan -->
        @foreach($requests as $req)
            <div x-show="detailModalId === {{ $req->id }}"
                 x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4"
                 @keydown.escape.window="detailModalId = null">
                <div @click.outside="detailModalId = null"
                     class="bg-white rounded-2xl max-w-2xl w-full max-h-[88vh] overflow-y-auto shadow-2xl border border-slate-100 p-6 space-y-5">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                        <div>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider px-2.5 py-1 rounded bg-blue-50 text-blue-700">
                                {{ $req->nomor_request ?? ('#REQ-' . $req->id) }} • {{ $req->jenis_request === 'training_baru' ? 'Usulan Training Baru' : 'Katalog Pelatihan' }}
                            </span>
                            <h3 class="text-lg font-extrabold text-slate-800 mt-2">
                                {{ $req->training?->nama_pelatihan ?? $req->scope_program ?? 'Detail Pengajuan Pelatihan' }}
                            </h3>
                        </div>
                        <button type="button" @click="detailModalId = null" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500">
                            <i class="ph-bold ph-x"></i>
                        </button>
                    </div>

                    @if($req->jenis_request === 'training_baru')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-100 text-xs">
                            <div><span class="text-slate-400 font-bold block text-[10px] uppercase">Department Pengaju</span><span class="font-extrabold text-slate-700">{{ $req->department?->nama_departemen ?? '-' }}</span></div>
                            <div><span class="text-slate-400 font-bold block text-[10px] uppercase">Periode Pengajuan</span><span class="font-extrabold text-slate-700">{{ $req->periode_pengajuan ?? '-' }}</span></div>
                            <div><span class="text-slate-400 font-bold block text-[10px] uppercase">Pelaksana & Metode</span><span class="font-extrabold text-slate-700">{{ $req->pelaksana ?? '-' }} • {{ $req->metode_pelaksanaan ?? '-' }}</span></div>
                            <div><span class="text-slate-400 font-bold block text-[10px] uppercase">Estimasi Biaya</span><span class="font-extrabold text-slate-700">Rp {{ number_format((float) $req->estimasi_biaya, 0, ',', '.') }}</span></div>
                            <div class="sm:col-span-2"><span class="text-slate-400 font-bold block text-[10px] uppercase">Kondisi Karyawan Saat Ini</span><p class="font-medium text-slate-700 mt-0.5">{{ $req->kondisi_saat_ini ?? '-' }}</p></div>
                            <div class="sm:col-span-2"><span class="text-slate-400 font-bold block text-[10px] uppercase">Training Need</span><p class="font-medium text-slate-700 mt-0.5">{{ $req->training_need ?? '-' }}</p></div>
                            <div class="sm:col-span-2"><span class="text-slate-400 font-bold block text-[10px] uppercase">Target / KPI Setelah Pelatihan</span><p class="font-medium text-slate-700 mt-0.5">{{ $req->target_kpi ?? '-' }}</p></div>
                        </div>
                    @elseif($req->kondisi_saat_ini)
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-100 text-xs">
                            <span class="text-[10px] font-bold text-slate-400 uppercase block">Alasan / Catatan Pengajuan Departemen:</span>
                            <p class="font-medium text-slate-700 mt-0.5">{{ $req->kondisi_saat_ini }}</p>
                        </div>
                    @endif

                    <!-- Daftar Peserta & Keputusan Per Peserta -->
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2.5">Daftar Peserta & Status Keputusan</h4>
                        <div class="border border-slate-200 rounded-xl overflow-hidden divide-y divide-slate-100">
                            @forelse($req->participants as $part)
                                @php
                                    $pBadge = match($part->status_peserta) {
                                        'approved' => ['Disetujui', 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                        'rejected' => ['Ditolak', 'bg-rose-50 text-rose-700 border-rose-200'],
                                        'pending' => ['Pending', 'bg-amber-50 text-amber-700 border-amber-200'],
                                        default => ['Menunggu Review', 'bg-slate-100 text-slate-600 border-slate-200'],
                                    };
                                @endphp
                                <div class="p-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
                                    <div>
                                        <div class="font-extrabold text-slate-800">{{ $part->nama_peserta ?? $part->employee?->nama ?? '-' }}</div>
                                        <div class="text-[11px] text-slate-500">
                                            Jabatan: {{ $part->jabatan_peserta ?? $part->employee?->jabatan ?? '-' }}
                                            @if($part->employee?->nik) • NIK: {{ $part->employee->nik }} @endif
                                        </div>
                                        @if($part->catatan_keputusan)
                                            <div class="text-[11px] text-slate-500 italic mt-0.5">Catatan: "{{ $part->catatan_keputusan }}"</div>
                                        @endif
                                    </div>
                                    <span class="px-2.5 py-1 rounded-lg border text-[10px] font-extrabold uppercase {{ $pBadge[1] }}">
                                        {{ $pBadge[0] }}
                                    </span>
                                </div>
                            @empty
                                @if($req->employee)
                                    <div class="p-3 text-xs font-bold text-slate-700">{{ $req->employee->nama }} ({{ $req->employee->nik }})</div>
                                @endif
                            @endforelse
                        </div>
                    </div>

                    @if($req->catatan_admin)
                        <div class="p-3.5 bg-amber-50/70 border border-amber-200 rounded-xl text-xs">
                            <span class="text-[10px] font-extrabold text-amber-700 uppercase block">Catatan Admin HRGA:</span>
                            <p class="font-semibold text-amber-900 mt-0.5">{{ $req->catatan_admin }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
