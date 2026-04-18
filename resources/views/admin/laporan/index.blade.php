<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-end">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">Daftar Laporan Saya</h2>
                <p class="text-sm text-gray-600 mt-1">Kelola dan pantau seluruh riwayat pelaporan Anda.</p>
            </div>
            <a href="{{ route('admin.laporan.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Laporan Baru
            </a>
        </div>
    </x-slot>

    <div class="mb-6 p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="flex flex-col sm:flex-row items-end gap-4">
            <div class="w-full sm:w-64">
                <label for="status" class="block text-sm font-medium leading-6 text-gray-900 mb-1">Saring berdasarkan status</label>
                <select name="status" id="status" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    <option value="">Semua Status Laporan</option>
                    <option value="Baru" {{ request('status') == 'Baru' ? 'selected' : '' }}>Menunggu Diproses (Baru)</option>
                    <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai Diperbaiki</option>
                </select>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 h-9">
                Terapkan Filter
            </button>
        </form>
    </div>

    <div class="bg-white overflow-hidden border border-gray-200 sm:rounded-lg shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul / Kerusakan</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($laporans as $lap)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $lap->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="font-medium text-gray-900">{{ $lap->judul }}</div>
                            <div class="text-gray-500 text-xs mt-1">{{ $lap->lokasi }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $lap->status == 'Baru' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $lap->status == 'Diproses' ? 'bg-amber-100 text-amber-800' : '' }}
                                {{ $lap->status == 'Selesai' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                {{ $lap->status == 'Ditolak' ? 'bg-rose-100 text-rose-800' : '' }}">
                                {{ $lap->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.laporan.show', $lap->id) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors">Lihat Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-sm font-medium text-gray-900">Belum ada data laporan</h3>
                            <p class="text-sm text-gray-500 mt-1">Anda belum pernah membuat laporan atau filter tidak cocok.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($laporans->hasPages())
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
            {{ $laporans->links() }}
        </div>
        @endif
    </div>
</x-app-layout>