{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Persetujuan Pengajuan Pelatihan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 px-4 py-2 text-left">Karyawan & Dept</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Pelatihan</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Pengaju</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Status Saat Ini</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Aksi / Tindakan HRD</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requests as $req)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">
                                    <span class="font-bold text-gray-900">{{ $req->employee->nama }}</span><br>
                                    <span class="text-xs text-gray-500">NIK: {{ $req->employee->nik }}</span>
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    {{ $req->training->nama_pelatihan }}
                                </td>
                                <td class="border border-gray-300 px-4 py-2 text-sm">
                                    {{ $req->diajukanOleh->name ?? 'Sistem' }}
                                </td>
                                <td class="border border-gray-300 px-4 py-2 text-center">
                                    @if($req->status == 'pending')
                                        <span class="px-2 py-1 text-xs rounded bg-yellow-100 text-yellow-800 font-semibold">PENDING</span>
                                    @elseif($req->status == 'approved')
                                        <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800 font-semibold">APPROVED</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800 font-semibold">REJECTED</span>
                                    @endif

                                    @if($req->catatan_admin)
                                        <p class="text-xs text-gray-500 italic mt-1">"{{ $req->catatan_admin }}"</p>
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    @if($req->status == 'pending')
                                        <form action="{{ route('hrd.requests.process', $req->id) }}" method="POST" class="space-y-2">
                                            @csrf
                                            @method('PATCH')

                                            <input type="text" name="catatan_admin" placeholder="Tulis catatan (opsional)..." class="w-full text-xs border-gray-300 rounded-md p-1 shadow-sm">

                                            <div class="flex justify-center space-x-2">
                                                <button type="submit" name="status" value="approved" class="bg-green-500 hover:bg-green-600 text-black text-xs px-2 py-1 rounded shadow" onclick="return confirm('Setujui karyawan ini untuk mengikuti pelatihan?')">
                                                    Setujui
                                                </button>
                                                <button type="submit" name="status" value="rejected" class="bg-red-500 hover:bg-red-600 text-black text-xs px-2 py-1 rounded shadow" onclick="return confirm('Tolak pengajuan karyawan ini?')">
                                                    Tolak
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <p class="text-center text-xs text-gray-400 italic">Sudah diproses</p>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="border border-gray-300 px-4 py-2 text-center text-gray-500">
                                    Belum ada pengajuan masuk dari departemen manapun.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout> --}}

<x-app-layout>
    <x-slot name="header">Persetujuan Pengajuan Pelatihan</x-slot>

    <div class="w-full space-y-6">
        <!-- Page Title -->
        <div class="mb-8">
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Persetujuan Pengajuan</h2>
            <p class="text-sm text-slate-500 mt-2 font-medium">Tinjau dan proses permintaan pelatihan dari departemen.</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-[32px] p-8 shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-slate-100">

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-bold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-bold uppercase tracking-widest border-b border-slate-100">
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
                                <div class="font-bold text-slate-800">{{ $req->employee?->nama ?? 'Multi Peserta' }}</div>
                                <div class="text-[11px] text-slate-400 uppercase tracking-wide">NIK: {{ $req->employee?->nik ?? '-' }}</div>
                            </td>
                            <td class="py-5 px-4 font-medium text-slate-600">
                                {{ $req->training?->nama_pelatihan ?? $req->training_need ?? $req->scope_program ?? 'Usulan Training Baru' }}
                                @if($req->jenis_request === 'training_baru')
                                    <span class="ml-1 inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-200">Training Baru</span>
                                @endif
                            </td>
                            <td class="py-5 px-4 text-sm font-medium text-slate-500">{{ $req->diajukanOleh->name ?? 'Sistem' }}</td>
                            <td class="py-5 px-4 text-center">
                                @php
                                    $statusClasses = [
                                        'menunggu_review'    => 'bg-amber-50 text-amber-600 border border-amber-200',
                                        'pending'            => 'bg-amber-50 text-amber-600 border border-amber-200',
                                        'approved'           => 'bg-emerald-50 text-emerald-600 border border-emerald-200',
                                        'partially_approved' => 'bg-blue-50 text-blue-600 border border-blue-200',
                                        'rejected'           => 'bg-rose-50 text-rose-600 border border-rose-200',
                                        'cancelled'          => 'bg-slate-100 text-slate-500 border border-slate-200',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusClasses[$req->status] ?? 'bg-slate-100' }}">
                                    {{ str_replace('_', ' ', $req->status) }}
                                </span>
                                @if($req->catatan_admin)
                                    <p class="text-[10px] text-slate-400 italic mt-1 max-w-[150px] truncate">"{{ $req->catatan_admin }}"</p>
                                @endif
                            </td>
                            <td class="py-5 px-4">
                                @if(in_array($req->status, ['pending', 'menunggu_review']))
                                    <form action="{{ route('hrd.requests.process', $req->id) }}" method="POST" class="flex flex-col gap-2">
                                        @csrf @method('PATCH')
                                        <input type="text" name="catatan_admin" placeholder="Catatan (opsional)..." class="w-full text-[11px] border-2 border-slate-100 rounded-xl px-3 py-2 bg-slate-50 focus:border-blue-400 outline-none">

                                        <div class="flex justify-center gap-2">
                                            <button type="submit" name="status" value="approved" class="px-3 py-1.5 bg-emerald-600 text-white text-[11px] font-bold rounded-lg hover:bg-emerald-700 transition" onclick="return confirm('Setujui pengajuan ini?')">
                                                Setujui
                                            </button>
                                            <button type="submit" name="status" value="rejected" class="px-3 py-1.5 bg-rose-600 text-white text-[11px] font-bold rounded-lg hover:bg-rose-700 transition" onclick="return confirm('Tolak pengajuan ini?')">
                                                Tolak
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <div class="text-center text-slate-300 text-xs font-semibold italic">Sudah diproses</div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-slate-400 font-medium">Belum ada pengajuan masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
