<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard Pimpinan (Kepala FRC)</h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-b-4 border-indigo-600 dark:border-indigo-500">
            <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase">Total Laporan Masuk</h3>
            <p class="text-4xl font-black mt-2 text-gray-900 dark:text-gray-100">{{ $kpi['total_masuk'] }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Sejak awal sistem berjalan</p>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-b-4 border-green-500 dark:border-green-400">
            <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase">Laporan Selesai</h3>
            <p class="text-4xl font-black mt-2 text-green-600 dark:text-green-400">{{ $kpi['total_selesai'] }}</p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Efektivitas: {{ $kpi['total_masuk'] > 0 ? round(($kpi['total_selesai']/$kpi['total_masuk'])*100) : 0 }}%</p>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border-b-4 border-yellow-500 dark:border-yellow-400">
            <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase">Rata-rata Penanganan</h3>
            <p class="text-4xl font-black mt-2 text-gray-900 dark:text-gray-100">{{ $kpi['rata_waktu'] }} <span class="text-lg font-normal">Jam</span></p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Metrik dari laporan dibuat s/d selesai</p>
        </div>
    </div>

    <x-card>
        <h3 class="font-bold text-lg mb-4 text-gray-800 dark:text-gray-200">Menu Cepat Pimpinan</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('kepala.laporan.index') }}" class="p-4 border dark:border-gray-700 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition flex items-center gap-4">
                <div class="bg-indigo-100 dark:bg-indigo-900/50 p-3 rounded text-indigo-600 dark:text-indigo-400 font-bold">📋</div>
                <div>
                    <div class="font-bold dark:text-gray-200">Rekap Laporan</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Buka daftar semua laporan untuk analisis.</div>
                </div>
            </a>
            <a href="{{ route('kepala.laporan.index') }}" class="p-4 border dark:border-gray-700 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition flex items-center gap-4">
                <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded text-green-600 dark:text-green-400 font-bold">🕒</div>
                <div>
                    <div class="font-bold dark:text-gray-200">Laporan Terbaru</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Cek laporan masuk terbaru dan statusnya.</div>
                </div>
            </a>
        </div>
    </x-card>
</x-app-layout>