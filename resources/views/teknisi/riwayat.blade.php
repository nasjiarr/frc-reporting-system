<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">Riwayat Pekerjaan Selesai</h2>
        <p class="text-sm text-gray-600 mt-1">Daftar penugasan perbaikan yang telah berhasil Anda selesaikan.</p>
    </x-slot>

    <div class="bg-white overflow-hidden border border-gray-200 sm:rounded-xl shadow-sm max-w-7xl mx-auto">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu Selesai</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Laporan Kerusakan</th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Lokasi</th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($riwayat as $data)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-semibold text-sm text-gray-800">
                                {{ $data->hasilPerbaikan ? $data->hasilPerbaikan->selesai_pada->format('d M Y') : $data->updated_at->format('d M Y') }}
                            </span>
                            <div class="text-xs text-gray-400 mt-0.5">
                                {{ $data->hasilPerbaikan ? $data->hasilPerbaikan->selesai_pada->format('H:i') : $data->updated_at->format('H:i') }} WIB
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-bold text-indigo-700">{{ $data->laporan->judul }}</div>
                            <div class="text-xs text-gray-500 mt-1 line-clamp-1">{{ $data->laporan->deskripsi }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $data->laporan->lokasi }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('teknisi.tugas.show', $data->id) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 bg-white border border-gray-200 hover:bg-gray-50 px-3 py-1.5 rounded-md transition-colors font-semibold" title="Lihat Detail Riwayat">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-4">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900">Belum Ada Riwayat</h3>
                            <p class="text-sm text-gray-500 mt-1">Pekerjaan yang telah Anda selesaikan akan muncul di sini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($riwayat->hasPages())
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $riwayat->links() }}
        </div>
        @endif
    </div>
</x-app-layout>