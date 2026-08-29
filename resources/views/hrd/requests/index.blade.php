<x-app-layout>
    <x-slot name="header">Persetujuan Pengajuan Pelatihan</x-slot>

    <div class="w-full space-y-6">
        <!-- Page Title -->
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Persetujuan Pengajuan</h2>
            <p class="text-sm text-slate-500 mt-2 font-medium">Tinjau dan proses permintaan pelatihan dari departemen.
            </p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-[32px] p-8 shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-slate-100">

            @if (session('success'))
                <div
                    class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-bold">
                    {{ session('success') }}
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

    <div class="bg-white border border-amber-100 rounded-3xl p-6">
        <div class="text-xs font-bold uppercase tracking-widest text-amber-500">
            Menunggu Persetujuan
        </div>

        <div class="mt-3 text-4xl font-extrabold text-slate-800">
            {{ $pendingRequests }}
        </div>

        <div class="mt-2 text-sm text-slate-500">
            Pengajuan pending
        </div>
    </div>

    <div class="bg-white border border-emerald-100 rounded-3xl p-6">
        <div class="text-xs font-bold uppercase tracking-widest text-emerald-500">
            Disetujui
        </div>

        <div class="mt-3 text-4xl font-extrabold text-slate-800">
            {{ $approvedRequests }}
        </div>

        <div class="mt-2 text-sm text-slate-500">
            Pengajuan approved
        </div>
    </div>

    <div class="bg-white border border-rose-100 rounded-3xl p-6">
        <div class="text-xs font-bold uppercase tracking-widest text-rose-500">
            Ditolak
        </div>

        <div class="mt-3 text-4xl font-extrabold text-slate-800">
            {{ $rejectedRequests }}
        </div>

        <div class="mt-2 text-sm text-slate-500">
            Pengajuan rejected
        </div>
    </div>

</div>
            {{-- Filter Pelatihan --}}
            @if (isset($trainings))
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-slate-700 mb-4 uppercase tracking-wider">
                        Filter Berdasarkan Pelatihan
                    </h3>

                    <div class="flex flex-wrap gap-3">

                        <a href="{{ route('hrd.requests.index') }}"
                            class="px-4 py-2 rounded-xl text-sm font-bold transition
            {{ !request('training_id') ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                            Semua Pengajuan
                        </a>

                        @foreach ($trainings as $training)
                            <a href="{{ route('hrd.requests.index', ['training_id' => $training->id]) }}"
                                class="px-4 py-2 rounded-xl text-sm font-bold transition
                {{ request('training_id') == $training->id
                    ? 'bg-blue-600 text-white'
                    : 'bg-blue-50 text-blue-600 hover:bg-blue-100' }}">
                                {{ $training->nama_pelatihan }}
                            </a>
                        @endforeach

                    </div>
                </div>
            @endif
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="text-slate-400 text-[10px] font-bold uppercase tracking-widest border-b border-slate-100">
                            <th class="pb-4 px-4">Karyawan & Dept</th>
                            <th class="pb-4 px-4">Pelatihan</th>
                            <th class="pb-4 px-4">Pengaju</th>
                            <th class="pb-4 px-4 text-center">Status</th>
                            <th class="pb-4 px-4 text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($requests as $req)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-5 px-4">
                                    <div class="font-bold text-slate-800">{{ $req->employee->nama }}</div>
                                    <div class="text-[11px] text-slate-400 uppercase tracking-wide">NIK:
                                        {{ $req->employee->nik }}</div>
                                </td>
                                <td class="py-5 px-4 font-medium text-slate-600">{{ $req->training->nama_pelatihan }}
                                </td>
                                <td class="py-5 px-4 text-sm font-medium text-slate-500">
                                    {{ $req->diajukanOleh->name ?? 'Sistem' }}</td>
                                <td class="py-5 px-4 text-center">
                                    @php
                                        $statusClasses = [
                                            'pending' => 'bg-amber-50 text-amber-600 border border-amber-200',
                                            'approved' => 'bg-emerald-50 text-emerald-600 border border-emerald-200',
                                            'rejected' => 'bg-rose-50 text-rose-600 border border-rose-200',
                                        ];
                                    @endphp
                                    <span
                                        class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusClasses[$req->status] ?? 'bg-slate-100' }}">
                                        {{ $req->status }}
                                    </span>
                                    @if ($req->catatan_admin)
                                        <p class="text-[10px] text-slate-400 italic mt-1 max-w-[150px] truncate">
                                            "{{ $req->catatan_admin }}"</p>
                                    @endif
                                </td>
                                <td class="py-5 px-4">
                                    @if ($req->status == 'pending')
                                        <form action="{{ route('hrd.requests.process', $req->id) }}" method="POST"
                                            class="flex flex-col gap-2">
                                            @csrf @method('PATCH')
                                            <input type="text" name="catatan_admin"
                                                placeholder="Catatan (opsional)..."
                                                class="w-full text-[11px] border-2 border-slate-100 rounded-xl px-3 py-2 bg-slate-50 focus:border-blue-400 outline-none">

                                            <div class="flex justify-center gap-2">
                                                <button type="submit" name="status" value="approved"
                                                    class="px-3 py-1.5 bg-emerald-600 text-white text-[11px] font-bold rounded-lg hover:bg-emerald-700 transition"
                                                    onclick="return confirm('Setujui pengajuan ini?')">
                                                    Setujui
                                                </button>
                                                <button type="submit" name="status" value="rejected"
                                                    class="px-3 py-1.5 bg-rose-600 text-white text-[11px] font-bold rounded-lg hover:bg-rose-700 transition"
                                                    onclick="return confirm('Tolak pengajuan ini?')">
                                                    Tolak
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <div class="text-center text-slate-300 text-xs font-semibold italic">Sudah
                                            diproses</div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 text-center text-slate-400 font-medium">Belum ada
                                    pengajuan masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
