<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 tracking-tight">Edit Pencatatan Utilitas</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Perbarui data meteran untuk periode {{ $utilitas->periode }}.</p>
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-indigo-50 dark:bg-indigo-900/30 flex justify-between items-center">
            <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-300 tracking-tight">Kategori: {{ $utilitas->jenis_utilitas }}</h3>
            <span class="px-3 py-1 bg-white dark:bg-gray-700 rounded-md text-xs font-bold text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                Periode: {{ $utilitas->periode }}
            </span>
        </div>

        <form action="{{ route('admin.utilitas.update', $utilitas->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">

                @switch($utilitas->jenis_utilitas)
                @case('AirBersih')
                @case('AirHujan')
                @case('MDP')
                @php
                // Mendapatkan relasi yang tepat secara dinamis
                $relasi = match($utilitas->jenis_utilitas) {
                'AirBersih' => $utilitas->airBersih,
                'AirHujan' => $utilitas->airHujan,
                'MDP' => $utilitas->listrikMdp,
                };
                @endphp

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Awal</label>
                        <input type="date" name="tgl_awal" value="{{ $relasi->tgl_awal ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Akhir</label>
                        <input type="date" name="tgl_akhir" value="{{ $relasi->tgl_akhir ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stand Awal</label>
                        <input type="number" step="0.01" name="stand_awal" value="{{ $relasi->stand_awal ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stand Akhir</label>
                        <input type="number" step="0.01" name="stand_akhir" value="{{ $relasi->stand_akhir ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                </div>
                @break

                @case('SDP')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stand Awal SDP 1</label>
                        <input type="number" step="0.01" name="stand_awal_sdp1" value="{{ $utilitas->listrikSdp->stand_awal_sdp1 ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm sm:text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stand Akhir SDP 1</label>
                        <input type="number" step="0.01" name="stand_akhir_sdp1" value="{{ $utilitas->listrikSdp->stand_akhir_sdp1 ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm sm:text-sm" required>
                    </div>
                </div>
                <div class="p-4 bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800 rounded-md text-amber-800 dark:text-amber-300 text-sm">
                    <em>Silakan tambahkan kolom input lainnya di kode Blade ini (seperti SDP 2, form AC, Lift) dengan menyesuaikan nama kolom yang Anda buat di halaman <strong>create.blade.php</strong> sebelumnya.</em>
                </div>
                @break
                @endswitch

            </div>

            <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                <a href="{{ route('admin.utilitas.index') }}" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 transition">Batal</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">Perbarui Data</button>
            </div>
        </form>
    </div>
</x-app-layout>