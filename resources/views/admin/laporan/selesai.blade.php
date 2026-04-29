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

        <div class="mb-6 bg-white p-5 rounded-xl border border-gray-200 shadow-sm flex flex-col lg:flex-row justify-between items-start lg:items-end gap-5">

            <form method="GET" action="{{ route('admin.laporan.selesai') }}" class="w-full lg:w-auto flex flex-col sm:flex-row items-end gap-3">

                <div class="w-full sm:w-56">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Cari Data</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Judul, lokasi..." class="block w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                </div>

                <div class="w-full sm:w-36">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                    <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" class="block w-full py-2 px-3 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>

                <div class="w-full sm:w-36">
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}" class="block w-full py-2 px-3 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 transition">
                </div>

                <div class="flex gap-2 w-full sm:w-auto">
                    <button type="submit" class="w-full sm:w-auto bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-bold shadow-sm hover:bg-indigo-700 transition">
                        Filter
                    </button>

                    @if(request('search') || request('tgl_mulai') || request('tgl_selesai'))
                    <a href="{{ route('admin.laporan.selesai') }}" class="w-full sm:w-auto flex items-center justify-center bg-gray-100 text-gray-600 px-3 py-2 rounded-lg text-sm font-bold border border-gray-300 hover:bg-gray-200 transition" title="Reset Filter">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </a>
                    @endif
                </div>
            </form>

            <div class="w-full lg:w-auto">
                <a href="{{ route('admin.laporan.export_selesai', request()->query()) }}"
                    class="inline-flex w-full sm:w-auto justify-center items-center gap-2 px-5 py-2 bg-rose-600 text-white rounded-lg font-bold text-sm tracking-wide hover:bg-rose-700 transition shadow-sm border border-transparent focus:ring-2 focus:ring-offset-2 focus:ring-rose-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Ekspor PDF
                </a>
            </div>

        </div>

        <div class="bg-white overflow-hidden border border-gray-200 sm:rounded-xl shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl. Selesai</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Judul Laporan & Lokasi</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pelapor</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Teknisi Bertugas</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($laporans as $lap)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <span class="font-semibold text-gray-800">{{ $lap->updated_at->format('d M Y') }}</span>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $lap->updated_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-indigo-700">{{ $lap->judul }}</div>
                                <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $lap->lokasi }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $lap->pelapor->nama_lengkap ?? 'Anonim' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($lap->penugasan && $lap->penugasan->teknisi)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100 font-medium text-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    {{ $lap->penugasan->teknisi->nama_lengkap }}
                                </span>
                                @else
                                <span class="text-gray-400 italic text-xs">Tidak ada data</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.laporan.show', $lap->id) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 bg-white border border-gray-200 hover:bg-gray-50 px-3 py-1.5 rounded-md transition-colors font-semibold" title="Lihat Detail & Bukti">
                                        Lihat
                                    </a>

                                    <button type="button"
                                        @click="deleteModalOpen = true; 
                                                deleteUrl = '{{ route('admin.laporan.destroy', $lap->id) }}'; 
                                                deleteTitle = '{{ addslashes($lap->judul) }}';"
                                        class="inline-flex items-center text-rose-600 hover:text-rose-900 bg-white border border-rose-200 hover:bg-rose-50 px-3 py-1.5 rounded-md transition-colors font-semibold" title="Hapus Laporan">
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
                                    {{ request('search') ? 'Coba gunakan kata kunci yang berbeda atau hapus filter tanggal.' : 'Laporan yang telah diselesaikan oleh teknisi akan muncul di sini sebagai arsip.' }}
                                </p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($laporans->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
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
                    <form :action="deleteUrl" method="POST" class="inline-flex w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-rose-600 border border-transparent rounded-lg font-bold text-sm text-white hover:bg-rose-700 transition">
                            Hapus Permanen
                        </button>
                    </form>
                    <button type="button" @click="deleteModalOpen = false" class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-300 rounded-lg font-bold text-sm text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>