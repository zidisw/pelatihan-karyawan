<x-app-layout>
    <x-slot name="header"><h2>Manajemen Peserta Pelatihan</h2></x-slot>

    <div class="w-full space-y-6">
        <!-- Page Title -->
        <div class="mb-8">
            {{-- <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Manajemen Peserta</h2> --}}
            <p class="text-sm text-slate-500 mt-2 font-medium">Pantau status partisipasi dan perbarui progress pelatihan
                karyawan.</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-[32px] p-8 shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-slate-100">

            @if (session('success'))
                <div
                    class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm font-bold">
                    {{ session('success') }}
                </div>
            @endif
            <div class="mb-8">
    <h3 class="text-sm font-bold text-slate-700 mb-4 uppercase tracking-wider">
        Filter Pelatihan
    </h3>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">

        {{-- Semua Pelatihan --}}
        <a href="{{ route('hrd.participants.index') }}"
            class="group rounded-2xl border p-5 transition-all duration-200
            {{ !request('training_id')
                ? 'border-slate-800 bg-slate-800 text-white shadow-lg'
                : 'border-slate-200 bg-white hover:border-slate-300 hover:shadow-md' }}">

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium opacity-80">
                        Semua Data
                    </p>

                    <h4 class="font-bold text-base mt-1">
                        Semua Pelatihan
                    </h4>
                </div>

                <div
                    class="w-10 h-10 rounded-xl flex items-center justify-center
                    {{ !request('training_id')
                        ? 'bg-white/20'
                        : 'bg-slate-100' }}">
                    <i class="ph ph-list text-lg"></i>
                </div>
            </div>
        </a>

        {{-- Daftar Pelatihan --}}
        @foreach($trainings as $training)
            <a href="{{ route('hrd.participants.index', ['training_id' => $training->id]) }}"
                class="group rounded-2xl border p-5 transition-all duration-200
                {{ request('training_id') == $training->id
                    ? 'border-blue-600 bg-blue-600 text-white shadow-lg'
                    : 'border-slate-200 bg-white hover:border-blue-300 hover:shadow-md' }}">

                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs opacity-80">
                            Pelatihan
                        </p>

                        <h4 class="font-bold text-sm mt-1 line-clamp-2">
                            {{ $training->nama_pelatihan }}
                        </h4>

                        <p class="text-xs mt-2 opacity-75">
                            {{ $training->employees()->count() }} Peserta
                        </p>
                    </div>

                    <div
                        class="w-10 h-10 rounded-xl flex items-center justify-center
                        {{ request('training_id') == $training->id
                            ? 'bg-white/20'
                            : 'bg-blue-50 text-blue-600' }}">
                        <i class="ph ph-graduation-cap text-lg"></i>
                    </div>
                </div>
            </a>
        @endforeach

    </div>
</div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="text-slate-400 text-[10px] font-bold uppercase tracking-widest border-b border-slate-100">
                            <th class="pb-4 px-4">Nama Peserta</th>
                            <th class="pb-4 px-4">Pelatihan</th>
                            <th class="pb-4 px-4 text-center">Status</th>
                            <th class="pb-4 px-4 text-center">Update Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($participants as $p)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-5 px-4">
                                    <div class="font-bold text-slate-800">{{ $p->employee->nama }}</div>
                                    <div class="text-[11px] text-slate-400 uppercase tracking-wide">NIK:
                                        {{ $p->employee->nik }}</div>
                                </td>
                                <td class="py-5 px-4">
                                    <div class="font-bold text-slate-700 text-sm">{{ $p->training->nama_pelatihan }}
                                    </div>
                                    <div class="text-[11px] text-slate-400 font-medium">Mulai:
                                        {{ \Carbon\Carbon::parse($p->training->tanggal_mulai)->format('d M Y') }}</div>
                                </td>
                                <td class="py-5 px-4 text-center">
                                    @php
                                        $statusClasses = [
                                            'disetujui' => 'bg-blue-50 text-blue-600 border border-blue-200',
                                            'sedang_pelatihan' => 'bg-amber-50 text-amber-600 border border-amber-200',
                                            'selesai' => 'bg-emerald-50 text-emerald-600 border border-emerald-200',
                                        ];
                                    @endphp
                                    <span
                                        class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusClasses[$p->status_peserta] ?? 'bg-slate-100' }}">
                                        {{ str_replace('_', ' ', $p->status_peserta) }}
                                    </span>
                                </td>
                                <td class="py-5 px-4">
                                    <form action="{{ route('hrd.participants.updateStatus', $p->id) }}" method="POST"
                                        class="flex items-center justify-center gap-2">
                                        @csrf @method('PATCH')
                                        <select name="status_peserta"
                                            class="text-[11px] font-bold border-2 border-slate-100 rounded-xl px-3 py-2 bg-slate-50 focus:border-blue-400 outline-none cursor-pointer">
                                            <option value="disetujui"
                                                {{ $p->status_peserta == 'disetujui' ? 'selected' : '' }}>Menunggu
                                                Mulai</option>
                                            <option value="sedang_pelatihan"
                                                {{ $p->status_peserta == 'sedang_pelatihan' ? 'selected' : '' }}>Sedang
                                                Pelatihan</option>
                                            <option value="selesai"
                                                {{ $p->status_peserta == 'selesai' ? 'selected' : '' }}>Selesai
                                            </option>
                                        </select>
                                        <button type="submit"
                                            class="px-3 py-2 bg-slate-800 text-white text-[11px] font-bold rounded-xl hover:bg-slate-900 transition">
                                            Update
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-10 text-center text-slate-400 font-medium">Belum ada
                                    peserta pelatihan yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
