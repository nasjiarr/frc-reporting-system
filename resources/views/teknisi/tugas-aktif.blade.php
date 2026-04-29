<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">Daftar Tugas Aktif</h2>
        <p class="text-sm text-gray-600 mt-1">Laporan kerusakan yang saat ini ditugaskan kepada Anda dan sedang dalam proses penyelesaian.</p>
    </x-slot>

    <div x-data="{ 
        previewOpen: false, 
        p_judul: '', 
        p_lokasi: '', 
        p_deskripsi: '', 
        p_instruksi: '', 
        p_foto: '',
        
        openPreview(judul, lokasi, deskripsi, instruksi, foto) {
            this.p_judul = judul;
            this.p_lokasi = lokasi;
            this.p_deskripsi = deskripsi;
            this.p_instruksi = instruksi;
            this.p_foto = foto;
            this.previewOpen = true;
        }
    }" class="max-w-7xl mx-auto">

        <div class="bg-white overflow-hidden border border-gray-200 sm:rounded-xl shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tgl Ditugaskan</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Judul & Lokasi Kerusakan</th>
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tugasAktif as $tugas)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <span class="font-semibold text-gray-800">{{ $tugas->assigned_at ? $tugas->assigned_at->format('d M Y') : $tugas->created_at->format('d M Y') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-indigo-700">{{ $tugas->laporan->judul }}</div>
                                <div class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $tugas->laporan->lokasi }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium border
                                    {{ $tugas->status_tugas == 'Dikerjakan' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                                    {{ $tugas->status_tugas }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">

                                    <button type="button"
                                        @click="openPreview(
                                            {{ json_encode($tugas->laporan->judul) }},
                                            {{ json_encode($tugas->laporan->lokasi) }},
                                            {{ json_encode($tugas->laporan->deskripsi) }},
                                            {{ json_encode($tugas->instruksi ?? 'Tidak ada instruksi khusus.') }},
                                            {{ json_encode($tugas->laporan->foto_sebelum ? asset('storage/' . $tugas->laporan->foto_sebelum) : '') }}
                                        )"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-gray-300 rounded-lg text-xs font-bold text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition shadow-sm" title="Preview Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Preview
                                    </button>

                                    <a href="{{ route('teknisi.tugas.show', $tugas->id) }}"
                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 border border-transparent rounded-lg text-xs font-bold text-white hover:bg-indigo-700 transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Kerjakan
                                    </a>

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-4">
                                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-medium text-gray-900">Tidak Ada Tugas Aktif</h3>
                                <p class="text-sm text-gray-500 mt-1">Bagus! Semua penugasan Anda telah diselesaikan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="previewOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-60 flex items-center justify-center backdrop-blur-sm p-4">
            <div @click.away="previewOpen = false" class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden transform transition-all"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Preview Laporan Kerusakan
                    </h3>
                    <button @click="previewOpen = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-200 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-5 max-h-[70vh] overflow-y-auto space-y-5">

                    <div>
                        <h4 class="text-xl font-bold text-indigo-700" x-text="p_judul"></h4>
                        <p class="text-sm text-gray-600 mt-1 flex items-center gap-1.5 font-medium">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span x-text="p_lokasi"></span>
                        </p>
                    </div>

                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <p class="text-xs font-bold text-amber-800 uppercase tracking-wider mb-1">Instruksi Spesifik Admin</p>
                        <p class="text-sm text-amber-900 italic font-medium leading-relaxed" x-text="p_instruksi"></p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Keluhan Pelapor</p>
                            <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-line" x-text="p_deskripsi"></p>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Foto Kondisi (Sebelum)</p>

                            <template x-if="p_foto">
                                <img :src="p_foto" class="w-full h-48 object-cover rounded-lg border border-gray-200 shadow-sm cursor-zoom-in hover:opacity-90 transition" @click="window.open(p_foto, '_blank')">
                            </template>

                            <template x-if="!p_foto">
                                <div class="w-full h-48 bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg flex flex-col items-center justify-center text-gray-400 p-4 text-center">
                                    <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-xs font-medium">Tidak ada foto terlampir</span>
                                </div>
                            </template>
                        </div>
                    </div>

                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                    <button type="button" @click="previewOpen = false" class="px-5 py-2.5 bg-gray-800 text-white rounded-lg font-bold text-sm shadow-sm hover:bg-gray-700 transition focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">
                        Tutup Preview
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>