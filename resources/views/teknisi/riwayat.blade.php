<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Riwayat Pekerjaan Selesai</h2>
    </x-slot>

    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b bg-gray-50 text-gray-600 text-sm uppercase">
                        <th class="p-3">Waktu Selesai</th>
                        <th class="p-3">Judul Laporan</th>
                        <th class="p-3">Lokasi</th>
                        <th class="p-3 text-right">Status Akhir</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($riwayat as $tugas)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 text-sm">{{ $tugas->hasilPerbaikan->selesai_pada->format('d M Y H:i') ?? '-' }}</td>
                        <td class="p-3 font-bold text-gray-900">{{ $tugas->laporan->judul }}</td>
                        <td class="p-3">{{ $tugas->laporan->lokasi }}</td>
                        <td class="p-3 text-right">
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 font-bold">
                                Selesai
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-gray-500 italic">Belum ada riwayat pekerjaan yang diselesaikan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $riwayat->links() }}
            </div>
        </div>
    </x-card>
</x-app-layout>