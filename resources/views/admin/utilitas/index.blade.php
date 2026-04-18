<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">Rekapitulasi Utilitas Gedung</h2>
                <p class="text-sm text-gray-600 mt-1">Data penggunaan air dan listrik berdasarkan kategori utilitas.</p>
            </div>
            <a href="{{ route('admin.utilitas.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md font-bold text-xs uppercase tracking-widest hover:bg-indigo-700 transition shadow-sm">
                + Tambah Catatan
            </a>
        </div>
    </x-slot>

    <div x-data="{ 
        deleteModalOpen: false, 
        deleteUrl: '', 
        deleteInfo: '' 
    }">

        <div class="bg-white overflow-hidden border border-gray-200 sm:rounded-xl shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe Utilitas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Petugas Catat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode Cek</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Konsumsi</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($rekapUtilitas as $util)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ str_contains($util->jenis_utilitas, 'Air') ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $util->jenis_utilitas }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $util->petugas->nama_lengkap ?? 'Anonim' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                                {{ $util->periode }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="font-bold text-gray-900">
                                    {{ number_format($util->total_konsumsi, 2, ',', '.') }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    {{ str_contains($util->jenis_utilitas, 'Air') ? 'm³' : 'kWh' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">

                                <a href="{{ route('admin.utilitas.edit', $util->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold transition-colors">
                                    Edit
                                </a>

                                <button type="button"
                                    @click="deleteModalOpen = true; 
                                            deleteUrl = '{{ route('admin.utilitas.destroy', $util->id) }}'; 
                                            deleteInfo = '{{ $util->jenis_utilitas }} (Periode: {{ $util->periode }})';"
                                    class="ml-4 text-rose-600 hover:text-rose-900 font-bold transition-colors">
                                    Hapus
                                </button>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada data terekam.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($rekapUtilitas, 'hasPages') && $rekapUtilitas->hasPages())
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                {{ $rekapUtilitas->links() }}
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

                    <h3 class="text-lg font-bold text-gray-900 tracking-tight">Hapus Catatan Utilitas</h3>
                    <p class="text-sm text-gray-500 mt-2">Apakah Anda yakin ingin menghapus catatan <span class="font-bold text-gray-800" x-text="deleteInfo"></span>? Data yang dihapus tidak dapat dikembalikan.</p>
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