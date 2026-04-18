<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">Arsip Laporan Selesai</h2>
        <p class="text-sm text-gray-600 mt-1">Daftar seluruh laporan kerusakan yang telah berhasil diperbaiki oleh teknisi.</p>
    </x-slot>

    <div x-data="{ 
        deleteModalOpen: false, 
        deleteUrl: '', 
        deleteTitle: '' 
    }">

        <div class="mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="w-full md:w-1/2 lg:w-1/3">
                <form method="GET" action="{{ route('admin.laporan.selesai') }}" class="relative flex items-center">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, lokasi, pelapor..." class="block w-full pl-10 pr-24 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out">
                    <div class="absolute inset-y-0 right-0 flex items-center">
                        @if(request('search'))
                        <a href="{{ route('admin.laporan.selesai') }}" class="p-2 text-gray-400 hover:text-rose-500 transition-colors" title="Hapus Pencarian">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                        @endif
                        <button type="submit" class="h-full px-4 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-r-lg focus:outline-none transition-colors">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white overflow-hidden border border-gray-200 sm:rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl. Selesai</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Laporan & Lokasi</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teknisi Penanggung Jawab</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($laporans as $lap)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $lap->updated_at->format('d M Y') }}
                                <div class="text-xs text-gray-400">{{ $lap->updated_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $lap->judul }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ $lap->lokasi }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $lap->pelapor->nama_lengkap ?? 'Anonim' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($lap->penugasan && $lap->penugasan->teknisi)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-indigo-50 text-indigo-700 border border-indigo-100 font-medium text-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $lap->penugasan->teknisi->nama_lengkap }}
                                </span>
                                @else
                                <span class="text-gray-400 italic">Tidak ada data teknisi</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.laporan.show', $lap->id) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 bg-white border border-gray-200 hover:bg-gray-50 px-3 py-1.5 rounded-md transition-colors" title="Lihat Detail & Bukti">
                                        Lihat
                                    </a>

                                    <button type="button"
                                        @click="deleteModalOpen = true; 
                                                deleteUrl = '{{ route('admin.laporan.destroy', $lap->id) }}'; 
                                                deleteTitle = '{{ addslashes($lap->judul) }}';"
                                        class="inline-flex items-center text-rose-600 hover:text-rose-900 bg-white border border-rose-200 hover:bg-rose-50 px-3 py-1.5 rounded-md transition-colors" title="Hapus Laporan">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-4">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-medium text-gray-900">
                                    {{ request('search') ? 'Pencarian tidak ditemukan' : 'Belum Ada Laporan Selesai' }}
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ request('search') ? 'Coba gunakan kata kunci yang berbeda.' : 'Laporan yang telah diselesaikan oleh teknisi akan muncul di sini sebagai arsip.' }}
                                </p>
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

        <div x-show="deleteModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center backdrop-blur-sm">
            <div @click.away="deleteModalOpen = false" class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 overflow-hidden transform transition-all" x-transition>
                <div class="px-6 py-6 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-rose-100 mb-5">
                        <svg class="h-8 w-8 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 tracking-tight">Hapus Arsip Laporan</h3>
                    <p class="text-sm text-gray-500 mt-2">Apakah Anda yakin ingin menghapus laporan <span class="font-bold text-gray-800" x-text="deleteTitle"></span>? Semua data terkait termasuk foto dokumentasi akan ikut terhapus.</p>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-row-reverse gap-3">
                    <form :action="deleteUrl" method="POST" class="inline-flex">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-rose-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-rose-700 transition">
                            Hapus Permanen
                        </button>
                    </form>
                    <button type="button" @click="deleteModalOpen = false" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                        Batal
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>