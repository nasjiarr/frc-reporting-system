<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Manajemen Penugasan Teknisi</h2>
    </x-slot>

    <div x-data="{ assignModalOpen: false, actionUrl: '', laporanTitle: '', laporanLokasi: '' }">

        <x-card>
            <h3 class="text-lg font-bold mb-4 text-gray-800">Daftar Laporan Baru (Belum Ditangani)</h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50 text-gray-600 text-sm uppercase">
                            <th class="p-3">Tanggal</th>
                            <th class="p-3">Pelapor</th>
                            <th class="p-3">Judul & Lokasi</th>
                            <th class="p-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @forelse($laporanBaru as $lap)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 text-sm">{{ $lap->created_at->format('d M Y H:i') }}</td>
                            <td class="p-3">
                                <div class="font-bold text-sm">{{ $lap->pelapor->nama_lengkap ?? 'Tidak diketahui' }}</div>
                                <div class="text-xs text-gray-500">{{ $lap->pelapor->no_telepon ?? '-' }}</div>
                            </td>
                            <td class="p-3">
                                <div class="font-bold text-indigo-600">{{ $lap->judul }}</div>
                                <div class="text-xs text-gray-500">{{ $lap->lokasi }}</div>
                            </td>
                            <td class="p-3 text-right">
                                <button type="button"
                                    @click="assignModalOpen = true; 
                                            actionUrl = '{{ route('admin.penugasan.store', $lap->id) }}'; 
                                            laporanTitle = '{{ addslashes($lap->judul) }}';
                                            laporanLokasi = '{{ addslashes($lap->lokasi) }}';"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded text-sm font-semibold hover:bg-indigo-700 transition">
                                    Tugaskan
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-500 border-2 border-dashed rounded">
                                <span class="block mb-2">🎉</span>
                                Tidak ada laporan baru saat ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>

        <div x-show="assignModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center">

            <div @click.away="assignModalOpen = false" class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 overflow-hidden" x-transition>

                <div class="px-6 py-4 border-b bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">Beri Penugasan Teknisi</h3>
                </div>

                <form :action="actionUrl" method="POST">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div class="bg-blue-50 p-3 rounded border border-blue-100">
                            <p class="text-xs text-blue-500 uppercase font-bold" x-text="laporanLokasi"></p>
                            <p class="text-sm font-semibold text-blue-900 mt-1" x-text="laporanTitle"></p>
                        </div>

                        <div>
                            <x-input-label value="Pilih Teknisi yang Bertugas" />
                            <select name="teknisi_id" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                <option value="">-- Pilih Teknisi --</option>
                                @foreach($teknisi as $tek)
                                <option value="{{ $tek->id }}">{{ $tek->nama_lengkap }} (Sedang aktif: {{ $tek->tugas_teknisi_count ?? 0 }} tugas)</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label value="Instruksi Khusus (Opsional)" />
                            <textarea name="instruksi" rows="3" class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Segera ganti suku cadang dari gudang utama..."></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t flex justify-end gap-3">
                        <button type="button" @click="assignModalOpen = false" class="px-4 py-2 bg-white border border-gray-300 rounded text-gray-700 hover:bg-gray-50 font-medium">
                            Batal
                        </button>
                        <x-primary-button>
                            Simpan Penugasan
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>