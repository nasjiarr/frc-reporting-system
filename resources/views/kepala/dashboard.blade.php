<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Pimpinan (Kepala FRC)</h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow border-b-4 border-indigo-600">
            <h3 class="text-sm font-bold text-gray-500 uppercase">Total Laporan Masuk</h3>
            <p class="text-4xl font-black mt-2 text-gray-900">{{ $kpi['total_masuk'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Sejak awal sistem berjalan</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border-b-4 border-green-500">
            <h3 class="text-sm font-bold text-gray-500 uppercase">Laporan Selesai</h3>
            <p class="text-4xl font-black mt-2 text-green-600">{{ $kpi['total_selesai'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Efektivitas: {{ $kpi['total_masuk'] > 0 ? round(($kpi['total_selesai']/$kpi['total_masuk'])*100) : 0 }}%</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border-b-4 border-yellow-500">
            <h3 class="text-sm font-bold text-gray-500 uppercase">Rata-rata Penanganan</h3>
            <p class="text-4xl font-black mt-2 text-gray-900">{{ $kpi['rata_waktu'] }} <span class="text-lg font-normal">Jam</span></p>
            <p class="text-xs text-gray-400 mt-1">Metrik dari laporan dibuat s/d selesai</p>
        </div>
    </div>

    <x-card>
        <h3 class="font-bold text-lg mb-4 text-gray-800">Menu Cepat Pimpinan</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('kepala.utilitas.index') }}" class="p-4 border rounded-lg hover:bg-indigo-50 transition flex items-center gap-4">
                <div class="bg-indigo-100 p-3 rounded text-indigo-600 font-bold">📊</div>
                <div>
                    <div class="font-bold">Rekap Utilitas</div>
                    <div class="text-xs text-gray-500">Pantau tren air dan listrik gedung.</div>
                </div>
            </a>
            <a href="{{ route('kepala.kinerja.index') }}" class="p-4 border rounded-lg hover:bg-green-50 transition flex items-center gap-4">
                <div class="bg-green-100 p-3 rounded text-green-600 font-bold">👷</div>
                <div>
                    <div class="font-bold">Kinerja Teknisi</div>
                    <div class="text-xs text-gray-500">Monitor produktivitas staf teknis.</div>
                </div>
            </a>
        </div>
    </x-card>
</x-app-layout>