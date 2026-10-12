<x-app-layout>
    {{-- Header Slot: Breadcrumbs, Title, Subtitle, & CTA Button --}}
    <x-slot name="header">
        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-3 overflow-x-auto no-scrollbar whitespace-nowrap py-0.5" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors inline-flex items-center gap-1.5 font-medium flex-shrink-0">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Dashboard</span>
            </a>
            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-800 dark:text-gray-200 font-semibold flex-shrink-0" aria-current="page">
                Utilitas Gedung
            </span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                    Manajemen Utilitas Gedung
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Monitoring konsumsi energi dan air, pencatatan stand meter, dan rekapitulasi data FRC UGM.
                </p>
            </div>
            <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap sm:flex-nowrap flex-shrink-0 self-start sm:self-center">
                <a href="{{ route('admin.utilitas.create') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl font-semibold text-xs shadow-sm hover:shadow-md transition-all duration-150 gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Catat Stand Meter</span>
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $colorMap = [
            'blue'    => [
                'bg'     => 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 ring-1 ring-blue-500/20',
                'border' => 'hover:border-blue-400 dark:hover:border-blue-500',
                'badge'  => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                'btn'    => 'bg-blue-50 hover:bg-blue-100 dark:bg-blue-950/40 dark:hover:bg-blue-900/60 text-blue-700 dark:text-blue-300',
            ],
            'cyan'    => [
                'bg'     => 'bg-cyan-50 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 ring-1 ring-cyan-500/20',
                'border' => 'hover:border-cyan-400 dark:hover:border-cyan-500',
                'badge'  => 'bg-cyan-50 dark:bg-cyan-950/60 text-cyan-700 dark:text-cyan-300 border-cyan-200 dark:border-cyan-800',
                'btn'    => 'bg-cyan-50 hover:bg-cyan-100 dark:bg-cyan-950/40 dark:hover:bg-cyan-900/60 text-cyan-700 dark:text-cyan-300',
            ],
            'amber'   => [
                'bg'     => 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 ring-1 ring-amber-500/20',
                'border' => 'hover:border-amber-400 dark:hover:border-amber-500',
                'badge'  => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                'btn'    => 'bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/40 dark:hover:bg-amber-900/60 text-amber-700 dark:text-amber-300',
            ],
            'yellow'  => [
                'bg'     => 'bg-yellow-50 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 ring-1 ring-yellow-500/20',
                'border' => 'hover:border-yellow-400 dark:hover:border-yellow-500',
                'badge'  => 'bg-yellow-50 dark:bg-yellow-950/60 text-yellow-700 dark:text-yellow-300 border-yellow-200 dark:border-yellow-800',
                'btn'    => 'bg-yellow-50 hover:bg-yellow-100 dark:bg-yellow-950/40 dark:hover:bg-yellow-900/60 text-yellow-700 dark:text-yellow-300',
            ],
            'orange'  => [
                'bg'     => 'bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 ring-1 ring-orange-500/20',
                'border' => 'hover:border-orange-400 dark:hover:border-orange-500',
                'badge'  => 'bg-orange-50 dark:bg-orange-950/60 text-orange-700 dark:text-orange-300 border-orange-200 dark:border-orange-800',
                'btn'    => 'bg-orange-50 hover:bg-orange-100 dark:bg-orange-950/40 dark:hover:bg-orange-900/60 text-orange-700 dark:text-orange-300',
            ],
            'indigo'  => [
                'bg'     => 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 ring-1 ring-indigo-500/20',
                'border' => 'hover:border-indigo-400 dark:hover:border-indigo-500',
                'badge'  => 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800',
                'btn'    => 'bg-indigo-50 hover:bg-indigo-100 dark:bg-indigo-950/40 dark:hover:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300',
            ],
            'emerald' => [
                'bg'     => 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-500/20',
                'border' => 'hover:border-emerald-400 dark:hover:border-emerald-500',
                'badge'  => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                'btn'    => 'bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:hover:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300',
            ],
        ];
    @endphp

    <div class="space-y-6 pb-12" x-data="{ search: '' }">
        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/80 rounded-2xl flex items-center justify-between text-emerald-800 dark:text-emerald-200 shadow-sm animate-fade-in" role="alert">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/20 flex items-center justify-center flex-shrink-0 text-emerald-600 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <span class="text-xs sm:text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" @click="$el.closest('div[role=alert]').remove()" class="text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-300 p-1.5 rounded-lg hover:bg-emerald-100/50 dark:hover:bg-emerald-900/50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/80 rounded-2xl flex items-center justify-between text-rose-800 dark:text-rose-200 shadow-sm animate-fade-in" role="alert">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-rose-500/20 flex items-center justify-center flex-shrink-0 text-rose-600 dark:text-rose-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-xs sm:text-sm font-semibold">{{ session('error') }}</span>
                </div>
                <button type="button" @click="$el.closest('div[role=alert]').remove()" class="text-rose-500 hover:text-rose-700 dark:hover:text-rose-300 p-1.5 rounded-lg hover:bg-rose-100/50 dark:hover:bg-rose-900/50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        {{-- Executive KPI Metrics Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
            <!-- Card 1: Listrik Utama (MDP) -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <span class="text-2xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 font-mono">Listrik MDP Terakhir</span>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white font-mono">
                                {{ number_format($totalListrikBulanIni, 2, ',', '.') }}
                            </span>
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">kWh</span>
                        </div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 ring-1 ring-amber-500/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs">
                    <span class="text-gray-500 dark:text-gray-400">Periode:</span>
                    <span class="font-mono font-semibold text-amber-600 dark:text-amber-400">
                        {{ $periodeTerbaru ?? 'Belum ada data' }}
                    </span>
                </div>
            </div>

            <!-- Card 2: Konsumsi Air (Bersih + Hujan) -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <span class="text-2xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 font-mono">Konsumsi Air Terakhir</span>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white font-mono">
                                {{ number_format($totalAirBulanIni, 2, ',', '.') }}
                            </span>
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">m³</span>
                        </div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 ring-1 ring-blue-500/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs">
                    <span class="text-gray-500 dark:text-gray-400">Cakupan:</span>
                    <span class="font-medium text-blue-600 dark:text-blue-400">
                        Air Bersih &amp; Hujan
                    </span>
                </div>
            </div>

            <!-- Card 3: Cakupan Kategori -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <span class="text-2xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 font-mono">Cakupan Kategori</span>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white font-mono">
                                7 / 7
                            </span>
                            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Aktif</span>
                        </div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 ring-1 ring-emerald-500/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs">
                    <span class="text-gray-500 dark:text-gray-400">Status Sistem:</span>
                    <span class="inline-flex items-center gap-1.5 font-semibold text-emerald-600 dark:text-emerald-400">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        100% Termonitor
                    </span>
                </div>
            </div>

            <!-- Card 4: Total Riwayat Tercatat -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <span class="text-2xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 font-mono">Total Pencatatan</span>
                        <div class="mt-2 flex items-baseline gap-2">
                            <span class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white font-mono">
                                {{ $totalPencatatan ?? 0 }}
                            </span>
                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Log Entri</span>
                        </div>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 ring-1 ring-indigo-500/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs">
                    <span class="text-gray-500 dark:text-gray-400">Database:</span>
                    <span class="font-medium text-indigo-600 dark:text-indigo-400">
                        Stand Meter Fisik
                    </span>
                </div>
            </div>
        </div>

        {{-- Section 1: 7 Utility Category Cards --}}
        <div class="space-y-4">
            <!-- Section Header & Filter Toolbar -->
            <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">
                            Kategori Utilitas &amp; Stand Meter
                        </h2>
                        <span class="px-2 py-0.5 rounded-lg text-2xs font-bold font-mono bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                            7 Kategori
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        Pilih jenis utilitas untuk meninjau riwayat, input data stand meter, atau cetak dokumen PDF.
                    </p>
                </div>

                <!-- Instant Search Input -->
                <div class="relative w-full md:w-72">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" 
                           x-model="search"
                           placeholder="Cari kategori utilitas..." 
                           class="w-full pl-9 pr-8 py-2 text-xs rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition">
                    <button x-show="search.length > 0" 
                            @click="search = ''" 
                            type="button" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- 7 Category Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($jenisUtilitas as $item)
                @php
                    $style = $colorMap[$item['warna']] ?? $colorMap['indigo'];
                @endphp
                <div x-show="!search || '{{ strtolower($item['nama']) }} {{ strtolower($item['slug']) }} {{ strtolower($item['desc'] ?? '') }}'.includes(search.toLowerCase())"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md {{ $style['border'] }} transition-all duration-200 flex flex-col justify-between overflow-hidden group">
                    
                    <!-- Card Top Header -->
                    <div class="p-5 pb-4">
                        <div class="flex items-start justify-between gap-3 mb-3.5">
                            <div class="p-3 rounded-xl {{ $style['bg'] }} transition-transform duration-200 group-hover:scale-105">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @switch($item['icon'])
                                    @case('droplet')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z" />
                                        @break
                                    @case('cloud-rain')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 14v6m-8-6v6m4-4v6" />
                                        @break
                                    @case('arrow-up-down')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 4v12m0 0l4-4m-4 4l-4-4" />
                                        @break
                                    @case('wind')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.59 4.59A2 2 0 1 1 11 8H2m10.59 11.41A2 2 0 1 0 14 16H2m15.73-8.27A2.5 2.5 0 1 1 19.5 12H2" />
                                        @break
                                    @case('lightbulb')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 1 1 7.072 0l-.548.547A3.374 3.374 0 0 0 14 18.469V19a2 2 0 1 1-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                        @break
                                    @default
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    @endswitch
                                </svg>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg text-2xs font-mono font-bold uppercase tracking-wider bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 border border-gray-200/80 dark:border-gray-600">
                                {{ $item['unit'] }}
                            </span>
                        </div>

                        <h3 class="font-bold text-gray-900 dark:text-white text-base leading-snug">
                            {{ $item['nama'] }}
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">
                            {{ $item['desc'] ?? 'Monitoring stand fisik FRC UGM' }}
                        </p>

                        <!-- Metric Display -->
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60">
                            <span class="text-2xs font-mono font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
                                Pemakaian Terakhir
                            </span>

                            @if($item['latest_konsumsi'] !== null)
                                <div class="mt-1 flex items-baseline justify-between gap-2">
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-xl sm:text-2xl font-bold font-mono text-gray-900 dark:text-white">
                                            {{ number_format($item['latest_konsumsi'], 2, ',', '.') }}
                                        </span>
                                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">{{ $item['unit'] }}</span>
                                    </div>

                                    @if($item['tren'] === 'up')
                                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-lg text-2xs font-bold font-mono bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800" title="Konsumsi meningkat dibanding periode sebelumnya">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                            +{{ $item['tren_persen'] }}%
                                        </span>
                                    @elseif($item['tren'] === 'down')
                                        <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-lg text-2xs font-bold font-mono bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800" title="Konsumsi menurun (efisien)">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                            -{{ $item['tren_persen'] }}%
                                        </span>
                                    @elseif($item['tren'] === 'same')
                                        <span class="px-2 py-0.5 rounded-lg text-2xs font-bold font-mono bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                            0%
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-2 flex items-center justify-between text-2xs text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="font-mono font-medium">{{ $item['latest_periode'] }}</span>
                                    </span>
                                    <span class="font-mono text-gray-400 dark:text-gray-500">
                                        {{ $item['total_records'] }} entri
                                    </span>
                                </div>
                            @else
                                <div class="mt-2 py-2 px-3 rounded-xl bg-gray-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-800 text-center">
                                    <p class="text-xs italic text-gray-400">Belum ada pencatatan</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Footer Actions -->
                    <div class="px-4 py-3 bg-gray-50/80 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between gap-2">
                        <a href="{{ route('admin.utilitas.show', $item['slug']) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                           title="Lihat riwayat dan grafik tren {{ $item['nama'] }}">
                            <span>Riwayat</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>

                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('admin.utilitas.export_pdf', ['jenis' => $item['slug']]) }}"
                               class="p-1.5 rounded-lg text-gray-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 dark:text-gray-400 dark:hover:text-rose-400 transition-colors"
                               title="Ekspor PDF {{ $item['nama'] }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </a>

                            <a href="{{ route('admin.utilitas.create', ['jenis' => $item['slug']]) }}"
                               class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold {{ $style['btn'] }} transition-colors"
                               title="Catat stand meter baru untuk {{ $item['nama'] }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Catat</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Section 2: Recent Activity Stand Meter Entries Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">
                            Aktivitas Pencatatan Terakhir
                        </h2>
                        <span class="px-2 py-0.5 rounded-lg text-2xs font-bold font-mono bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            Log Terbaru
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        Daftar pembacaan meteran fisik terbaru yang diinput ke dalam sistem operasional gedung FRC.
                    </p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('admin.utilitas.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Input Baru</span>
                    </a>
                </div>
            </div>

            @if(isset($recentRecords) && $recentRecords->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50/75 dark:bg-gray-900/40">
                            <tr>
                                <th class="px-5 py-3.5 text-left text-2xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Kategori Utilitas</th>
                                <th class="px-5 py-3.5 text-left text-2xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Periode</th>
                                <th class="px-5 py-3.5 text-left text-2xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Rentang Cek</th>
                                <th class="px-5 py-3.5 text-left text-2xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Petugas</th>
                                <th class="px-5 py-3.5 text-right text-2xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Konsumsi</th>
                                <th class="px-5 py-3.5 text-right text-2xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentRecords as $rec)
                            @php
                                $uUnit = str_contains($rec->jenis_utilitas, 'Air') ? 'm³' : 'kWh';
                                $det = $rec->detail;
                                $colorInfo = match($rec->jenis_utilitas) {
                                    'AirBersih' => ['text' => 'text-blue-700 dark:text-blue-300', 'bg' => 'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-800'],
                                    'AirHujan'  => ['text' => 'text-cyan-700 dark:text-cyan-300', 'bg' => 'bg-cyan-50 dark:bg-cyan-900/30 border-cyan-200 dark:border-cyan-800'],
                                    'MDP'       => ['text' => 'text-amber-700 dark:text-amber-300', 'bg' => 'bg-amber-50 dark:bg-amber-900/30 border-amber-200 dark:border-amber-800'],
                                    'SDP'       => ['text' => 'text-yellow-700 dark:text-yellow-300', 'bg' => 'bg-yellow-50 dark:bg-yellow-900/30 border-yellow-200 dark:border-yellow-800'],
                                    'Lift'      => ['text' => 'text-orange-700 dark:text-orange-300', 'bg' => 'bg-orange-50 dark:bg-orange-900/30 border-orange-200 dark:border-orange-800'],
                                    'AC'        => ['text' => 'text-indigo-700 dark:text-indigo-300', 'bg' => 'bg-indigo-50 dark:bg-indigo-900/30 border-indigo-200 dark:border-indigo-800'],
                                    'Lampu'     => ['text' => 'text-emerald-700 dark:text-emerald-300', 'bg' => 'bg-emerald-50 dark:bg-emerald-900/30 border-emerald-200 dark:border-emerald-800'],
                                    default     => ['text' => 'text-gray-700 dark:text-gray-300', 'bg' => 'bg-gray-50 dark:bg-gray-800 border-gray-200 dark:border-gray-700'],
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        <span class="px-2.5 py-1 rounded-lg text-2xs font-bold border {{ $colorInfo['bg'] }} {{ $colorInfo['text'] }}">
                                            {{ $rec->jenis_utilitas }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-xs font-mono font-bold text-gray-900 dark:text-white">
                                    {{ $rec->periode }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                    @if($det && $det->tgl_awal)
                                        {{ \Carbon\Carbon::parse($det->tgl_awal)->format('d/m') }} - {{ \Carbon\Carbon::parse($det->tgl_akhir)->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-xs text-gray-600 dark:text-gray-300">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-2xs font-bold text-gray-700 dark:text-gray-300 uppercase">
                                            {{ substr($rec->petugas->nama_lengkap ?? $rec->petugas->name ?? 'A', 0, 1) }}
                                        </div>
                                        <span>{{ $rec->petugas->nama_lengkap ?? $rec->petugas->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-right font-mono font-bold text-xs text-indigo-600 dark:text-indigo-400">
                                    {{ number_format($rec->total_konsumsi, 2, ',', '.') }} {{ $uUnit }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-right text-xs font-semibold">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.utilitas.show', $rec->jenis_utilitas) }}"
                                           class="p-1 rounded-lg text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                                           title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <a href="{{ route('admin.utilitas.edit', $rec->id) }}"
                                           class="p-1 rounded-lg text-gray-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                                           title="Edit Stand Meter">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.utilitas.destroy', $rec->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan periode {{ $rec->periode }} ({{ $rec->jenis_utilitas }}) ini?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="p-1 rounded-lg text-gray-500 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                                                    title="Hapus Catatan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Belum Ada Catatan Stand Meter</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                        Mulai mencatat angka stand meteran fisik gedung FRC untuk melacak pemakaian energi dan air bulanan.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('admin.utilitas.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Catat Stand Meter Pertama</span>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
