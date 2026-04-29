<x-app-layout>
    <div x-data="{ 
        previewOpen: false, 
        assignModalOpen: false, 
        selectedLaporanId: '', 
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
        },

        openAssignModal(id = '') {
            this.selectedLaporanId = id;
            this.assignModalOpen = true;
        }
    }">

        <x-slot name="header">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">Manajemen Penugasan Teknisi</h2>
                    <p class="text-sm text-gray-600 mt-1">Kelola laporan masuk dan pantau progres perbaikan oleh tim teknisi.</p>
                </div>
            </div>
        </x-slot>

        <div class="py-6 max-w-7xl mx-auto space-y-8 sm:px-6 lg:px-8">

            <div class="space-y-4">
                <div class="flex items-center gap-2 px-1">
                    <span class="flex h-3 w-3 rounded-full bg-rose-500 animate-pulse"></span>
                    <h3 class="text-lg font-bold text-gray-700">Laporan Menunggu Penugasan</h3>
                </div>
                <div class="bg-white overflow-hidden border border-gray-200 sm:rounded-xl shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-left">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Tanggal Lapor</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Pelapor</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Kerusakan & Lokasi</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($laporanBaru as $lap)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $lap->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $lap->pelapor->nama_lengkap ?? 'Anonim' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-800">{{ $lap->judul }}</div>
                                        <div class="text-xs text-gray-500">{{ $lap->lokasi }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button"
                                                @click="openPreview(
                                                    {{ json_encode($lap->judul) }},
                                                    {{ json_encode($lap->lokasi) }},
                                                    {{ json_encode($lap->deskripsi) }},
                                                    'Silakan pilih teknisi dan berikan instruksi pengerjaan.',
                                                    {{ json_encode($lap->foto_sebelum ? asset('storage/' . $lap->foto_sebelum) : '') }}
                                                )"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-300 rounded-lg text-xs font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Preview
                                            </button>

                                            <button @click="openAssignModal('{{ $lap->id }}')"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 transition shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Tugaskan
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic bg-gray-50/50">Tidak ada laporan baru yang menunggu penugasan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <div x-show="assignModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-60 flex items-center justify-center backdrop-blur-sm p-4">
            <div @click.away="assignModalOpen = false" class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden transform transition-all" x-transition>

                <form :action="'{{ route('admin.penugasan.store', 999) }}'.replace('999', selectedLaporanId)" method="POST">
                    @csrf
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Penugasan Baru
                        </h3>
                        <button type="button" @click="assignModalOpen = false" class="text-gray-400 hover:text-gray-600 hover:bg-gray-200 p-1.5 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Laporan Baru <span class="text-rose-500">*</span></label>
                            <select name="laporan_id" x-model="selectedLaporanId" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">-- Pilih Laporan Kerusakan --</option>
                                @foreach($laporanBaru as $lap)
                                <option value="{{ $lap->id }}">{{ $lap->judul }} ({{ $lap->lokasi }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Teknisi <span class="text-rose-500">*</span></label>
                            <select name="teknisi_id" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">-- Pilih Teknisi --</option>
                                @foreach($teknisi as $tek)
                                <option value="{{ $tek->id }}">{{ $tek->nama_lengkap }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Instruksi Khusus (Opsional)</label>
                            <textarea name="instruksi" rows="3" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Berikan arahan spesifik untuk teknisi..."></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                        <button type="button" @click="assignModalOpen = false" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" onclick="this.disabled=true; this.form.submit(); this.innerHTML='Menyimpan...';" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold shadow-sm hover:bg-indigo-700 transition">
                            Simpan Penugasan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="previewOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-60 flex items-center justify-center backdrop-blur-sm p-4">
            <div @click.away="previewOpen = false" class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden transform transition-all" x-transition>

                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Pratinjau Data Laporan
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
                            </svg>
                            <span x-text="p_lokasi"></span>
                        </p>
                    </div>

                    <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4">
                        <p class="text-[10px] font-bold text-indigo-800 uppercase tracking-widest mb-1">Informasi Penanganan</p>
                        <p class="text-sm text-indigo-900 italic font-medium leading-relaxed" x-text="p_instruksi"></p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Deskripsi Kerusakan</p>
                            <p class="text-sm text-gray-800 leading-relaxed whitespace-pre-line" x-text="p_deskripsi"></p>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Foto Kondisi Awal</p>
                            <template x-if="p_foto">
                                <img :src="p_foto" class="w-full h-48 bg-slate-100 object-cover rounded-lg border border-gray-200 shadow-sm cursor-zoom-in hover:opacity-90 transition" @click="window.open(p_foto, '_blank')">
                            </template>
                            <template x-if="!p_foto">
                                <div class="w-full h-48 bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-xs">Tidak ada foto</span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                    <button type="button" @click="previewOpen = false" class="px-5 py-2.5 bg-gray-800 text-white rounded-lg font-bold text-sm shadow-sm hover:bg-gray-700 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>