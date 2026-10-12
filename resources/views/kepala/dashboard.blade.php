<x-app-layout>
    {{-- Header Slot: Clean & modern without redundant top-right badges/buttons --}}
    <x-slot name="header">
        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-3 overflow-x-auto no-scrollbar whitespace-nowrap py-0.5" aria-label="Breadcrumb">
            <span class="inline-flex items-center gap-1.5 font-semibold text-gray-800 dark:text-gray-200 flex-shrink-0">
                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Dashboard</span>
            </span>
            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-500 dark:text-gray-400 font-medium flex-shrink-0" aria-current="page">
                Ikhtisar Eksekutif
            </span>
        </nav>

        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                Dashboard Kepala FRC
            </h1>
            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                Pusat pengawasan eksekutif fasilitas, rekapitulasi utilitas, dan evaluasi pemeliharaan gedung FRC.
            </p>
        </div>
    </x-slot>

    <div class="space-y-6 pb-12">
        <!-- 1. Hero Greeting Banner (Executive Theme) -->
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-50/90 via-white to-sky-50/60 dark:from-gray-800 dark:via-gray-800 dark:to-gray-800/90 rounded-2xl border border-indigo-100/80 dark:border-gray-700 p-5 sm:p-7 shadow-sm">
            <div class="absolute -right-8 -bottom-8 w-48 h-48 bg-indigo-500/10 dark:bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-100/80 dark:bg-indigo-900/40 text-indigo-800 dark:text-indigo-300 text-xs font-semibold mb-3 border border-indigo-200/80 dark:border-indigo-800/60">
                        <svg class="w-3.5 h-3.5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                        <span class="text-indigo-400 dark:text-indigo-500">&bull;</span>
                        <span>Konsol Pimpinan FRC</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                        Selamat Datang, {{ auth()->user()->nama_lengkap ?? auth()->user()->name }}! 👋
                    </h2>
                    <p class="mt-2 text-xs sm:text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                        Pantau seluruh tiket pemeliharaan fasilitas gedung, konsumsi utilitas bulanan, serta evaluasi produktivitas dan respons tim teknisi secara menyeluruh.
                    </p>
                </div>
                <div class="flex-shrink-0 flex flex-wrap sm:flex-nowrap items-center gap-3">
                    <a href="{{ route('kepala.laporan.index') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-4.5 py-2.5 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs sm:text-sm font-semibold rounded-xl border border-gray-200 dark:border-gray-600 shadow-xs hover:shadow-sm transition-all gap-2">
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span>Rekap Laporan</span>
                    </a>
                    <a href="{{ route('kepala.laporan.create') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-4.5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-md shadow-indigo-500/20 hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-150 gap-2 group">
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-90 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Buat Laporan Baru</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. Interactive KPI Executive Summary Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
            <!-- 1. Total Laporan Masuk -->
            <a href="{{ route('kepala.laporan.index') }}" 
               class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors uppercase tracking-wider">Total Laporan</span>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $kpi['total_masuk'] }}</p>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                            Akumulasi
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-between truncate">
                        <span>Seluruh tiket kerusakan</span>
                        <span class="text-indigo-600 dark:text-indigo-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </p>
                </div>
            </a>

            <!-- 2. Laporan Baru / Menunggu Tindak Lanjut -->
            <a href="{{ route('kepala.laporan.index', ['status' => 'Baru']) }}" 
               class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-amber-400 dark:hover:border-amber-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors uppercase tracking-wider">Laporan Baru</span>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $kpi['baru'] }}</p>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                            Menunggu
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-between truncate">
                        <span>Belum didelegasikan</span>
                        <span class="text-amber-600 dark:text-amber-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </p>
                </div>
            </a>

            <!-- 3. Sedang Dikerjakan -->
            <a href="{{ route('kepala.laporan.index', ['status' => 'Diproses']) }}" 
               class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-blue-400 dark:hover:border-blue-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors uppercase tracking-wider">Sedang Dikerjakan</span>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.03a4.5 4.5 0 004.486-6.32l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.32 4.486c.158.58.134 1.193-.03 1.743l-1.42 1.42"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $kpi['sedang_dikerjakan'] }}</p>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                            Diproses
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-between truncate">
                        <span>Penanganan teknisi</span>
                        <span class="text-blue-600 dark:text-blue-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </p>
                </div>
            </a>

            <!-- 4. Laporan Selesai -->
            <a href="{{ route('kepala.laporan.index', ['status' => 'Selesai']) }}" 
               class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-emerald-400 dark:hover:border-emerald-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors uppercase tracking-wider">Laporan Selesai</span>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $kpi['total_selesai'] }}</p>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                            {{ $kpi['completion_rate'] }}% Tuntas
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-between truncate">
                        <span>Perbaikan tuntas</span>
                        <span class="text-emerald-600 dark:text-emerald-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </p>
                </div>
            </a>
        </div>

        <!-- 3. Executive Operations Health & Performance Bar -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 sm:p-6 shadow-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <!-- Tingkat Keberhasilan Penanganan -->
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <h3 class="text-xs sm:text-sm font-bold text-gray-800 dark:text-gray-200 uppercase tracking-wider">
                                Rasio Keberhasilan Penanganan Fasilitas
                            </h3>
                        </div>
                        <span class="text-sm sm:text-base font-extrabold text-emerald-600 dark:text-emerald-400">
                            {{ $kpi['completion_rate'] }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-3 overflow-hidden shadow-inner">
                        <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-3 rounded-full transition-all duration-700" 
                             style="width: {{ min(100, max(0, $kpi['completion_rate'])) }}%"></div>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-2 mt-2 text-[11px] sm:text-xs text-gray-500 dark:text-gray-400">
                        <span>Sebanyak <strong>{{ $kpi['total_selesai'] }}</strong> dari <strong>{{ $kpi['total_masuk'] }}</strong> laporan kerusakan telah diselesaikan.</span>
                        @if($kpi['ditolak'] > 0)
                            <span class="text-rose-600 dark:text-rose-400 font-medium">({{ $kpi['ditolak'] }} laporan ditolak)</span>
                        @endif
                    </div>
                </div>

                <div class="hidden lg:block w-px h-16 bg-gray-200 dark:bg-gray-700"></div>

                <!-- Secondary Operational Indicators -->
                <div class="grid grid-cols-2 gap-3.5 sm:gap-4 flex-shrink-0">
                    <div class="p-3.5 bg-gray-50 dark:bg-gray-750/50 rounded-xl border border-gray-200/80 dark:border-gray-700/80">
                        <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Tim Teknisi</span>
                        </div>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $kpi['total_teknisi'] }} Personel</p>
                        <a href="{{ route('kepala.kinerja.index') }}" class="text-[11px] font-medium text-indigo-600 dark:text-indigo-400 hover:underline inline-flex items-center gap-1 mt-0.5">
                            <span>Evaluasi Kinerja</span> &rarr;
                        </a>
                    </div>

                    <div class="p-3.5 bg-gray-50 dark:bg-gray-750/50 rounded-xl border border-gray-200/80 dark:border-gray-700/80">
                        <div class="flex items-center gap-2 text-sky-600 dark:text-sky-400 mb-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Utilitas {{ now()->translatedFormat('M Y') }}</span>
                        </div>
                        <p class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $kpi['utilitas_bulan_ini'] }} Data Catatan</p>
                        <a href="{{ route('kepala.utilitas.index') }}" class="text-[11px] font-medium text-sky-600 dark:text-sky-400 hover:underline inline-flex items-center gap-1 mt-0.5">
                            <span>Rekap Utilitas</span> &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Executive Quick Access Hub (Menu Cepat Pimpinan) -->
        <div>
            <div class="flex items-center justify-between mb-3.5">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-gray-100">
                        Menu Kendali Eksekutif
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Akses langsung ke modul pengawasan, audit, dan pelaporan pimpinan.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- 1. Rekap Seluruh Laporan -->
                <a href="{{ route('kepala.laporan.index') }}" 
                   class="group p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-500 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                    <div>
                        <div class="w-11 h-11 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors text-sm sm:text-base">
                            Rekap Seluruh Laporan
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">
                            Audit daftar lengkap tiket kerusakan, filter status & periode, serta tinjau histori penanganan.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs font-semibold text-indigo-600 dark:text-indigo-400">
                        <span>Buka Rekap</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </a>

                <!-- 2. Rekap Utilitas Gedung -->
                <a href="{{ route('kepala.utilitas.index') }}" 
                   class="group p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-sky-400 dark:hover:border-sky-500 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                    <div>
                        <div class="w-11 h-11 rounded-xl bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-gray-100 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors text-sm sm:text-base">
                            Rekap Utilitas Gedung
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">
                            Pengawasan konsumsi listrik MDP/SDP, lift, AC, serta air bersih & air hujan beserta ekspor PDF resmi.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs font-semibold text-sky-600 dark:text-sky-400">
                        <span>Pantau Utilitas</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </a>

                <!-- 3. Evaluasi Kinerja Teknisi -->
                <a href="{{ route('kepala.kinerja.index') }}" 
                   class="group p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-emerald-400 dark:hover:border-emerald-500 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                    <div>
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-gray-100 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors text-sm sm:text-base">
                            Kinerja Teknisi
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">
                            Evaluasi produktivitas, total penugasan terselesaikan, dan performa penanganan masing-masing teknisi.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs font-semibold text-emerald-600 dark:text-emerald-400">
                        <span>Lihat Performa</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </a>

                <!-- 4. Buat Laporan Kerusakan -->
                <a href="{{ route('kepala.laporan.create') }}" 
                   class="group p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-purple-400 dark:hover:border-purple-500 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                    <div>
                        <div class="w-11 h-11 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-gray-100 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors text-sm sm:text-base">
                            Buat Laporan Langsung
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed">
                            Input tiket laporan temuan kerusakan sarana atau fasilitas saat melakukan peninjauan/inspeksi gedung.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs font-semibold text-purple-600 dark:text-purple-400">
                        <span>Input Laporan</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </a>
            </div>
        </div>

        <!-- 5. Dual Executive Section: Recent Activity & Top Technicians -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Laporan Kerusakan Terbaru (2 Cols) -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-gray-200/80 dark:border-gray-700/80 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <span>Laporan Kerusakan Terbaru</span>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-semibold">
                                {{ $laporanTerbaru->count() }} Data
                            </span>
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            5 tiket fasilitas teranyar yang dilaporkan ke sistem FRC.
                        </p>
                    </div>
                    <a href="{{ route('kepala.laporan.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 inline-flex items-center gap-1 flex-shrink-0">
                        <span>Lihat Semua</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                @if($laporanTerbaru->isNotEmpty())
                    <div class="overflow-x-auto divide-y divide-gray-100 dark:divide-gray-700/60">
                        @foreach($laporanTerbaru as $lap)
                        <div class="p-4 sm:p-5 hover:bg-gray-50/70 dark:hover:bg-gray-750/50 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-3.5 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center flex-shrink-0 text-gray-600 dark:text-gray-300 font-bold text-xs">
                                    #{{ $lap->id }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">
                                            {{ $lap->judul }}
                                        </h4>
                                        {{-- Badge Status --}}
                                        @if($lap->status === 'Baru')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 border border-amber-200/80 dark:border-amber-800">Baru</span>
                                        @elseif($lap->status === 'Ditugaskan')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-sky-50 text-sky-700 dark:bg-sky-900/30 dark:text-sky-300 border border-sky-200/80 dark:border-sky-800">Ditugaskan</span>
                                        @elseif($lap->status === 'Diproses')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200/80 dark:border-blue-800">Diproses</span>
                                        @elseif($lap->status === 'Selesai')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-800">Selesai</span>
                                        @elseif($lap->status === 'Ditolak')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300 border border-rose-200/80 dark:border-rose-800">Ditolak</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400 mt-1 flex-wrap">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $lap->lokasi }}
                                        </span>
                                        <span>&bull;</span>
                                        <span>Pelapor: <strong>{{ $lap->pelapor->nama_lengkap ?? $lap->pelapor->name ?? 'Anonim' }}</strong></span>
                                        <span>&bull;</span>
                                        <span>{{ $lap->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end gap-3 flex-shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-gray-100 dark:border-gray-700/60">
                                @if($lap->penugasan && $lap->penugasan->teknisi)
                                    <div class="text-right hidden sm:block">
                                        <p class="text-[11px] text-gray-400 dark:text-gray-500">Teknisi</p>
                                        <p class="text-xs font-semibold text-gray-700 dark:text-gray-200 truncate max-w-[120px]">
                                            {{ $lap->penugasan->teknisi->nama_lengkap ?? $lap->penugasan->teknisi->name }}
                                        </p>
                                    </div>
                                @else
                                    <span class="text-[11px] text-gray-400 dark:text-gray-500 italic hidden sm:block">Belum ada teknisi</span>
                                @endif

                                <a href="{{ route('kepala.laporan.show', $lap->id) }}" 
                                   class="inline-flex items-center justify-center px-3 py-1.5 bg-gray-100 hover:bg-indigo-50 hover:text-indigo-600 dark:bg-gray-700 dark:hover:bg-indigo-900/40 dark:hover:text-indigo-300 text-gray-700 dark:text-gray-200 text-xs font-semibold rounded-lg transition-colors gap-1">
                                    <span>Detail</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-10 text-center flex-1 flex flex-col items-center justify-center">
                        <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700/60 text-gray-400 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200">Belum Ada Laporan Masuk</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm">
                            Saat ini belum ada data tiket laporan kerusakan yang tercatat di sistem.
                        </p>
                    </div>
                @endif
            </div>

            <!-- Right: Ringkasan Produktivitas Tim Teknisi (1 Col) -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-gray-200/80 dark:border-gray-700/80 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-gray-100">
                            Produktivitas Teknisi
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Penyelesaian tugas perbaikan.
                        </p>
                    </div>
                    <a href="{{ route('kepala.kinerja.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 inline-flex items-center gap-1 flex-shrink-0">
                        <span>Lengkap</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                @if($kinerjaTeknisi->isNotEmpty())
                    <div class="p-4 sm:p-5 divide-y divide-gray-100 dark:divide-gray-700/60 flex-1">
                        @foreach($kinerjaTeknisi as $index => $teknisi)
                        <div class="py-3 first:pt-0 last:pb-0 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs flex-shrink-0 {{ $index === 0 ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                                    @if($index === 0)
                                        🥇
                                    @elseif($index === 1)
                                        🥈
                                    @elseif($index === 2)
                                        🥉
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm font-bold text-gray-900 dark:text-gray-100 truncate">
                                        {{ $teknisi->nama_lengkap ?? $teknisi->name }}
                                    </p>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate">
                                        {{ $teknisi->email }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-800">
                                    {{ $teknisi->selesai_count }} Selesai
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center flex-1 flex flex-col items-center justify-center">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 dark:bg-gray-700/60 text-gray-400 flex items-center justify-center mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Belum ada data teknisi.</p>
                    </div>
                @endif

                <div class="p-3.5 bg-gray-50/80 dark:bg-gray-750/50 border-t border-gray-100 dark:border-gray-700/60 text-center">
                    <a href="{{ route('kepala.kinerja.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                        Lihat Seluruh Laporan Kinerja Teknisi &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>