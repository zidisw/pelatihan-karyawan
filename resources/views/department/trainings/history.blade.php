<x-app-layout>
    <x-slot name="header">Riwayat Pengajuan Pelatihan</x-slot>

    <div class="w-full space-y-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Riwayat Pengajuan</h2>
                <p class="text-sm text-slate-500 mt-2 font-medium">Pantau status seluruh pengajuan pelatihan yang telah Anda buat.</p>
            </div>
            <a href="{{ route('department.trainings.index') }}" class="flex items-center gap-2 px-6 py-3 bg-slate-800 text-white text-sm font-bold rounded-xl shadow-lg hover:bg-slate-900 transition">
                <i class="ph-bold ph-plus text-lg"></i>
                Tambah Pengajuan Baru
            </a>
        </div>

        <div class="bg-white rounded-[32px] p-8 shadow-[0_4px_12px_rgba(0,0,0,0.05)] border border-slate-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-bold uppercase tracking-widest border-b border-slate-100">
                            <th class="pb-4 px-4">Tanggal Pengajuan</th>
                            <th class="pb-4 px-4">Karyawan</th>
                            <th class="pb-4 px-4">Pelatihan</th>
                            <th class="pb-4 px-4 text-center">Status</th>
                            <th class="pb-4 px-4">Catatan HRD</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($requests as $req)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-5 px-4 text-sm font-medium text-slate-600">
                                {{ $req->created_at->format('d M Y') }}
                                <div class="text-[10px] text-slate-400">{{ $req->created_at->format('H:i') }}</div>
                            </td>
                            <td class="py-5 px-4">
                                <div class="font-bold text-slate-800">{{ $req->employee?->nama ?? 'Karyawan Dihapus' }}</div>
                                <div class="text-[11px] text-slate-400 uppercase tracking-wide">NIK: {{ $req->employee?->nik ?? '-' }}</div>
                            </td>
                            <td class="py-5 px-4 font-medium text-slate-700">{{ $req->training?->nama_pelatihan ?? $req->training_need ?? $req->scope_program ?? 'Pelatihan Dihapus' }}</td>
                            <td class="py-5 px-4 text-center">
                                @php
                                    $statusClasses = [
                                        'menunggu_review'    => 'bg-amber-50 text-amber-600 border border-amber-200',
                                        'pending'            => 'bg-amber-50 text-amber-600 border border-amber-200',
                                        'disetujui'          => 'bg-emerald-50 text-emerald-600 border border-emerald-200',
                                        'approved'           => 'bg-emerald-50 text-emerald-600 border border-emerald-200',
                                        'partially_approved' => 'bg-blue-50 text-blue-600 border border-blue-200',
                                        'ditolak'            => 'bg-rose-50 text-rose-600 border border-rose-200',
                                        'rejected'           => 'bg-rose-50 text-rose-600 border border-rose-200',
                                        'cancelled'          => 'bg-slate-100 text-slate-500 border border-slate-200',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusClasses[$req->status] ?? 'bg-slate-100' }}">
                                    {{ str_replace('_', ' ', $req->status) }}
                                </span>
                            </td>
                            <td class="py-5 px-4 text-sm text-slate-500 font-medium italic">
                                {{ $req->catatan_admin ?? '-' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-slate-400 font-medium">
                                Anda belum pernah membuat pengajuan pelatihan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
