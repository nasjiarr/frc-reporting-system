<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rekapitulasi Seluruh Laporan</h2>
    </x-slot>

    <x-card>
        <form method="GET" class="mb-6 flex items-end gap-4">
            <div>
                <x-input-label value="Filter Status" />
                <select name="status" class="border-gray-300 rounded-md shadow-sm" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Baru" {{ request('status') == 'Baru' ? 'selected' : '' }}>Baru</option>
                    <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b bg-gray-50 text-gray-600 text-sm uppercase">
                        <th class="p-3">Tanggal Lapor</th>
                        <th class="p-3">Judul & Lokasi</th>
                        <th class="p-3">Pelapor</th>
                        <th class="p-3">Teknisi Bertugas</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($laporans as $lap)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 text-sm">{{ $lap->created_at->format('d/m/Y') }}</td>
                        <td class="p-3">
                            <div class="font-bold text-gray-900">{{ $lap->judul }}</div>
                            <div class="text-xs text-gray-500">{{ $lap->lokasi }}</div>
                        </td>
                        <td class="p-3 text-sm">{{ $lap->pelapor->nama_lengkap ?? '-' }}</td>
                        <td class="p-3 text-sm">{{ $lap->penugasan->teknisi->nama_lengkap ?? 'Belum Ditugaskan' }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 text-xs rounded-full font-bold
                                {{ $lap->status == 'Baru' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $lap->status == 'Diproses' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $lap->status == 'Selesai' ? 'bg-green-100 text-green-800' : '' }}">
                                {{ $lap->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500 italic">Tidak ada laporan yang ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $laporans->links() }}
            </div>
        </div>
    </x-card>
</x-app-layout>