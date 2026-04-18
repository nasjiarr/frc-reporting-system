<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">Edit Pencatatan Utilitas</h2>
        <p class="text-sm text-gray-600 mt-1">Perbarui data meteran untuk periode {{ $utilitas->periode }}.</p>
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-indigo-50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-indigo-900 tracking-tight">Kategori: {{ $utilitas->jenis_utilitas }}</h3>
            <span class="px-3 py-1 bg-white rounded-md text-xs font-bold text-gray-600 border border-gray-200">
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
                        <label class="block text-sm font-medium text-gray-700">Tanggal Awal</label>
                        <input type="date" name="tgl_awal" value="{{ $relasi->tgl_awal ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                        <input type="date" name="tgl_akhir" value="{{ $relasi->tgl_akhir ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stand Awal</label>
                        <input type="number" step="0.01" name="stand_awal" value="{{ $relasi->stand_awal ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stand Akhir</label>
                        <input type="number" step="0.01" name="stand_akhir" value="{{ $relasi->stand_akhir ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                </div>
                @break

                @case('SDP')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stand Awal SDP 1</label>
                        <input type="number" step="0.01" name="stand_awal_sdp1" value="{{ $utilitas->listrikSdp->stand_awal_sdp1 ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stand Akhir SDP 1</label>
                        <input type="number" step="0.01" name="stand_akhir_sdp1" value="{{ $utilitas->listrikSdp->stand_akhir_sdp1 ?? '' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm" required>
                    </div>
                </div>
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-md text-amber-800 text-sm">
                    <em>Silakan tambahkan kolom input lainnya di kode Blade ini (seperti SDP 2, form AC, Lift) dengan menyesuaikan nama kolom yang Anda buat di halaman <strong>create.blade.php</strong> sebelumnya.</em>
                </div>
                @break
                @endswitch

            </div>

            <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.utilitas.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">Batal</a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">Perbarui Data</button>
            </div>
        </form>
    </div>
</x-app-layout>