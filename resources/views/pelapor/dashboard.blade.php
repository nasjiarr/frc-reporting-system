<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Pelapor') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-500">
            <h3 class="text-gray-500 text-sm font-medium">Total Laporan</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $statistik['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm font-medium">Status Baru</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $statistik['baru'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-sm font-medium">Sedang Diproses</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $statistik['diproses'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-medium">Selesai</h3>
            <p class="text-3xl font-bold text-gray-900">{{ $statistik['selesai'] }}</p>
        </div>
    </div>

    <x-card>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">5 Laporan Terbaru</h3>
            <a href="{{ route('pelapor.laporan.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700">+ Buat Laporan</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b bg-gray-50 text-gray-600 text-sm uppercase">
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Judul</th>
                        <th class="p-3">Lokasi</th>
                        <th class="p-3">Status</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse($laporanTerbaru as $lap)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $lap->created_at->format('d M Y') }}</td>
                        <td class="p-3">{{ $lap->judul }}</td>
                        <td class="p-3">{{ $lap->lokasi }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $lap->status == 'Baru' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $lap->status == 'Diproses' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $lap->status == 'Selesai' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $lap->status == 'Ditolak' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $lap->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500">Belum ada laporan yang dibuat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>