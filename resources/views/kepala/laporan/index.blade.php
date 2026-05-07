<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200 tracking-tight">Pengawasan Laporan Kerusakan</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Pantau seluruh laporan kerja dan progres penanganannya.</p>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <form action="{{ route('kepala.laporan.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="w-full sm:w-1/3">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Status Laporan</label>
                    <select name="status" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="Semua" {{ request('status') == 'Semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>Menunggu (Baru)</option>
                        <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="w-full sm:w-1/3">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Periode (Bulan)</label>
                    <input type="month" name="periode" value="{{ request('periode') }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div class="flex gap-2 w-full sm:w-auto">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-bold shadow-sm hover:bg-indigo-700 w-full sm:w-auto">Cari Data</button>
                    <a href="{{ route('kepala.laporan.index') }}" class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-200 border border-gray-300 dark:border-gray-600 px-4 py-2 rounded-md text-sm font-bold shadow-sm hover:bg-gray-200 dark:hover:bg-gray-600 text-center w-full sm:w-auto transition-colors">Reset</a>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Pelapor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Kerusakan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @forelse($laporans as $laporan)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $laporan->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $laporan->pelapor->nama_lengkap ?? 'Anonim' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                <p class="font-bold truncate max-w-xs">{{ $laporan->judul }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $laporan->lokasi }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($laporan->status == 'Selesai') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 
                                    @elseif($laporan->status == 'Diproses') bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 
                                    @elseif($laporan->status == 'Ditolak') bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300 
                                    @else bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 @endif">
                                    {{ $laporan->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('kepala.laporan.show', $laporan->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-bold">Pantau Detail &rarr;</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">Tidak ada data laporan yang sesuai dengan kriteria.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($laporans->hasPages())
            <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $laporans->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>