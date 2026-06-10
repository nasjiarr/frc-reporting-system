<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard Pimpinan (Kepala FRC)</h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Card Laporan Masuk -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400 mr-4">
                <!-- Ikon (Contoh) -->
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Laporan Masuk</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $kpi['total_masuk'] }}</p>
            </div>
        </div>

        <!-- Card Sedang Dikerjakan -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex items-center">
            <div class="p-3 rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900/50 dark:text-amber-400 mr-4">
                <!-- Ikon (Contoh) -->
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Sedang Dikerjakan</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $kpi['sedang_dikerjakan'] }}</p>
            </div>
        </div>

        <!-- Card Laporan Selesai -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 flex items-center">
            <div class="p-3 rounded-full bg-emerald-100 text-emerald-600 dark:bg-emerald-900/50 dark:text-emerald-400 mr-4">
                <!-- Ikon (Contoh) -->
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Laporan Selesai</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $kpi['total_selesai'] }}</p>
            </div>
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
            <a href="{{ route('kepala.laporan.create') }}" class="p-4 border dark:border-gray-700 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition flex items-center gap-4">
                <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded text-green-600 dark:text-green-400 font-bold">➕</div>
                <div>
                    <div class="font-bold dark:text-gray-200">Tambah Laporan Kerusakan</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">Buat laporan kerusakan baru secara langsung.</div>
                </div>
            </a>
        </div>
    </x-card>
</x-app-layout>