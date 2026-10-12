<x-app-layout>
    <div x-data="{
        photoModalOpen: false,
        modalPhotoUrl: '',
        modalPhotoTitle: '',
        modalPhotoLocation: '',
        openPhoto(url, title, location) {
            this.modalPhotoUrl = url;
            this.modalPhotoTitle = title;
            this.modalPhotoLocation = location;
            this.photoModalOpen = true;
        }
    }" class="pb-12">

        {{-- Header Slot --}}
        <x-slot name="header">
            <!-- Breadcrumb Navigation -->
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-3 overflow-x-auto no-scrollbar whitespace-nowrap py-0.5" aria-label="Breadcrumb">
                <a href="{{ route('kepala.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors inline-flex items-center gap-1.5 font-medium flex-shrink-0">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-800 dark:text-gray-200 font-semibold flex-shrink-0" aria-current="page">
                    Rekap Seluruh Laporan
                </span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                        Rekapitulasi Laporan Kerusakan
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Pengawasan komprehensif, evaluasi tindak lanjut, dan audit seluruh tiket pemeliharaan gedung FRC.
                    </p>
                </div>
                <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap sm:flex-nowrap flex-shrink-0 self-start sm:self-center">
                    <a href="{{ route('kepala.laporan.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl font-semibold text-xs shadow-sm hover:shadow-md transition-all duration-150 gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Buat Laporan Baru</span>
                    </a>
                </div>
            </div>
        </x-slot>

        <!-- 1. Interactive Status KPI Tabs -->
        @php
            $currentStatus = request('status', 'Semua');
            if (empty($currentStatus)) $currentStatus = 'Semua';
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 mb-6">
            <!-- Semua Status -->
            <a href="{{ route('kepala.laporan.index', array_merge(request()->except(['status', 'page']), ['status' => 'Semua'])) }}" 
               class="p-4 rounded-2xl border transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5 shadow-sm {{ $currentStatus === 'Semua' ? 'bg-indigo-50/90 dark:bg-indigo-950/40 border-indigo-400 dark:border-indigo-500 ring-2 ring-indigo-500/20' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider {{ $currentStatus === 'Semua' ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-500 dark:text-gray-400' }}">Semua Laporan</span>
                    <span class="w-2.5 h-2.5 rounded-full {{ $currentStatus === 'Semua' ? 'bg-indigo-600 ring-4 ring-indigo-200 dark:ring-indigo-900/50' : 'bg-gray-300 dark:bg-gray-600' }}"></span>
                </div>
                <div class="flex items-baseline justify-between">
                    <p class="text-2xl font-extrabold {{ $currentStatus === 'Semua' ? 'text-indigo-900 dark:text-indigo-100' : 'text-gray-900 dark:text-white' }}">{{ $stats['total'] ?? 0 }}</p>
                    <span class="text-[11px] font-medium text-gray-500 dark:text-gray-400">Total Tiket</span>
                </div>
            </a>

            <!-- Status Baru (Menunggu) -->
            <a href="{{ route('kepala.laporan.index', array_merge(request()->except(['status', 'page']), ['status' => 'Baru'])) }}" 
               class="p-4 rounded-2xl border transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5 shadow-sm {{ $currentStatus === 'Baru' ? 'bg-amber-50/90 dark:bg-amber-950/40 border-amber-400 dark:border-amber-500 ring-2 ring-amber-500/20' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider {{ $currentStatus === 'Baru' ? 'text-amber-700 dark:text-amber-300' : 'text-gray-500 dark:text-gray-400' }}">Baru</span>
                    <span class="w-2.5 h-2.5 rounded-full {{ $currentStatus === 'Baru' ? 'bg-amber-500 ring-4 ring-amber-200 dark:ring-amber-900/50 animate-pulse' : 'bg-amber-300 dark:bg-amber-600' }}"></span>
                </div>
                <div class="flex items-baseline justify-between">
                    <p class="text-2xl font-extrabold {{ $currentStatus === 'Baru' ? 'text-amber-900 dark:text-amber-100' : 'text-gray-900 dark:text-white' }}">{{ $stats['baru'] ?? 0 }}</p>
                    <span class="text-[11px] font-medium text-amber-600 dark:text-amber-400">Menunggu</span>
                </div>
            </a>

            <!-- Status Diproses -->
            <a href="{{ route('kepala.laporan.index', array_merge(request()->except(['status', 'page']), ['status' => 'Diproses'])) }}" 
               class="p-4 rounded-2xl border transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5 shadow-sm {{ $currentStatus === 'Diproses' ? 'bg-blue-50/90 dark:bg-blue-950/40 border-blue-400 dark:border-blue-500 ring-2 ring-blue-500/20' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider {{ $currentStatus === 'Diproses' ? 'text-blue-700 dark:text-blue-300' : 'text-gray-500 dark:text-gray-400' }}">Diproses</span>
                    <span class="w-2.5 h-2.5 rounded-full {{ $currentStatus === 'Diproses' ? 'bg-blue-500 ring-4 ring-blue-200 dark:ring-blue-900/50 animate-pulse' : 'bg-blue-300 dark:bg-blue-600' }}"></span>
                </div>
                <div class="flex items-baseline justify-between">
                    <p class="text-2xl font-extrabold {{ $currentStatus === 'Diproses' ? 'text-blue-900 dark:text-blue-100' : 'text-gray-900 dark:text-white' }}">{{ $stats['diproses'] ?? 0 }}</p>
                    <span class="text-[11px] font-medium text-blue-600 dark:text-blue-400">Dikerjakan</span>
                </div>
            </a>

            <!-- Status Selesai -->
            <a href="{{ route('kepala.laporan.index', array_merge(request()->except(['status', 'page']), ['status' => 'Selesai'])) }}" 
               class="p-4 rounded-2xl border transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5 shadow-sm {{ $currentStatus === 'Selesai' ? 'bg-emerald-50/90 dark:bg-emerald-950/40 border-emerald-400 dark:border-emerald-500 ring-2 ring-emerald-500/20' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider {{ $currentStatus === 'Selesai' ? 'text-emerald-700 dark:text-emerald-300' : 'text-gray-500 dark:text-gray-400' }}">Selesai</span>
                    <span class="w-2.5 h-2.5 rounded-full {{ $currentStatus === 'Selesai' ? 'bg-emerald-500 ring-4 ring-emerald-200 dark:ring-emerald-900/50' : 'bg-emerald-300 dark:bg-emerald-600' }}"></span>
                </div>
                <div class="flex items-baseline justify-between">
                    <p class="text-2xl font-extrabold {{ $currentStatus === 'Selesai' ? 'text-emerald-900 dark:text-emerald-100' : 'text-gray-900 dark:text-white' }}">{{ $stats['selesai'] ?? 0 }}</p>
                    <span class="text-[11px] font-medium text-emerald-600 dark:text-emerald-400">Tuntas</span>
                </div>
            </a>

            <!-- Status Ditolak -->
            <a href="{{ route('kepala.laporan.index', array_merge(request()->except(['status', 'page']), ['status' => 'Ditolak'])) }}" 
               class="p-4 rounded-2xl border transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5 shadow-sm col-span-2 sm:col-span-1 {{ $currentStatus === 'Ditolak' ? 'bg-rose-50/90 dark:bg-rose-950/40 border-rose-400 dark:border-rose-500 ring-2 ring-rose-500/20' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600' }}">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold uppercase tracking-wider {{ $currentStatus === 'Ditolak' ? 'text-rose-700 dark:text-rose-300' : 'text-gray-500 dark:text-gray-400' }}">Ditolak</span>
                    <span class="w-2.5 h-2.5 rounded-full {{ $currentStatus === 'Ditolak' ? 'bg-rose-500 ring-4 ring-rose-200 dark:ring-rose-900/50' : 'bg-rose-300 dark:bg-rose-600' }}"></span>
                </div>
                <div class="flex items-baseline justify-between">
                    <p class="text-2xl font-extrabold {{ $currentStatus === 'Ditolak' ? 'text-rose-900 dark:text-rose-100' : 'text-gray-900 dark:text-white' }}">{{ $stats['ditolak'] ?? 0 }}</p>
                    <span class="text-[11px] font-medium text-rose-600 dark:text-rose-400">Ditolak</span>
                </div>
            </a>
        </div>

        <!-- 2. Search & Filter Bar Card -->
        <div class="mb-6 p-4 sm:p-5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm transition-colors">
            <form action="{{ route('kepala.laporan.index') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-end gap-3.5 sm:gap-4">
                <!-- Search Box -->
                <div class="flex-1">
                    <label for="search" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Pencarian Kata Kunci</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}" 
                               placeholder="Cari judul kerusakan, lokasi ruangan, nama pelapor..." 
                               class="block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 pl-3.5 pr-10 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400 dark:placeholder-gray-500 transition-colors">
                        @if(request('search'))
                            <a href="{{ route('kepala.laporan.index', request()->except('search')) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Status Selector -->
                <div class="w-full sm:w-56">
                    <label for="status" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <span>Status</span>
                    </label>
                    <select name="status" id="status" class="block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                        <option value="Semua" {{ request('status') == 'Semua' ? 'selected' : '' }}>Semua Status</option>
                        <option value="Baru" {{ request('status') == 'Baru' ? 'selected' : '' }}>Baru (Menunggu)</option>
                        <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <!-- Periode Bulan -->
                <div class="w-full sm:w-52">
                    <label for="periode" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Periode (Bulan)</span>
                    </label>
                    <input type="month" name="periode" id="periode" value="{{ request('periode') }}" 
                           class="block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                </div>

                <!-- Submit & Reset Action Buttons -->
                <div class="flex items-center gap-2 flex-shrink-0">
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4.5 py-2.5 bg-gray-900 dark:bg-gray-700 hover:bg-black dark:hover:bg-gray-600 text-white rounded-xl font-semibold text-xs shadow-sm transition-all duration-150 gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Terapkan</span>
                    </button>

                    @if(request()->filled('status') || request()->filled('periode') || request()->filled('search'))
                        <a href="{{ route('kepala.laporan.index') }}" class="inline-flex items-center justify-center px-3 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-xl text-xs font-semibold transition" title="Reset Semua Filter">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="sr-only">Reset</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- 3. Dual-View Main Container (Desktop Table + Mobile Cards) -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden transition-colors">
            <!-- Filter active banner notification (if filtering) -->
            @if(request()->filled('status') || request()->filled('periode') || request()->filled('search'))
            <div class="px-5 py-3 bg-indigo-50/70 dark:bg-indigo-950/30 border-b border-indigo-100 dark:border-indigo-900/50 flex items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2 text-indigo-700 dark:text-indigo-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>
                        Filter aktif:
                        @if(request('status') && request('status') !== 'Semua') <strong>Status "{{ request('status') }}"</strong> @endif
                        @if(request('periode')) &bull; <strong>Bulan {{ request('periode') }}</strong> @endif
                        @if(request('search')) &bull; <strong>Kata kunci "{{ request('search') }}"</strong> @endif
                    </span>
                </div>
                <a href="{{ route('kepala.laporan.index') }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                    Reset Filter
                </a>
            </div>
            @endif

            <!-- Mobile Card View (< md) -->
            <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700/80">
                @forelse($laporans as $lap)
                <div class="p-4 sm:p-5 space-y-3.5 hover:bg-gray-50/50 dark:hover:bg-gray-750/30 transition-colors">
                    <div class="flex items-center justify-between gap-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-mono font-bold bg-slate-100 dark:bg-gray-700/60 text-slate-700 dark:text-gray-300 border border-slate-200 dark:border-gray-600">
                            #{{ $lap->id }}
                        </span>
                        
                        {{-- Status Pill --}}
                        @if($lap->status === 'Baru')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 border border-amber-200/80 dark:border-amber-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Baru
                            </span>
                        @elseif($lap->status === 'Ditugaskan')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300 border border-sky-200/80 dark:border-sky-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse"></span>
                                Ditugaskan
                            </span>
                        @elseif($lap->status === 'Diproses')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 border border-blue-200/80 dark:border-blue-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                Diproses
                            </span>
                        @elseif($lap->status === 'Selesai')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Selesai
                            </span>
                        @elseif($lap->status === 'Ditolak')
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300 border border-rose-200/80 dark:border-rose-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                Ditolak
                            </span>
                        @endif
                    </div>

                    <!-- Title & Details -->
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-gray-100 text-sm leading-snug">
                            {{ $lap->judul }}
                        </h3>
                        <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <svg class="w-3.5 h-3.5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="font-medium text-gray-700 dark:text-gray-300 truncate">{{ $lap->lokasi }}</span>
                        </div>
                    </div>

                    <!-- Photo preview thumbnail (if exists) -->
                    @if($lap->foto_sebelum)
                        <div class="flex items-center gap-3 p-2 bg-gray-50 dark:bg-gray-750/60 rounded-xl border border-gray-100 dark:border-gray-700/60">
                            <button type="button" 
                                    @click="openPhoto('{{ asset('storage/' . $lap->foto_sebelum) }}', '{{ addslashes($lap->judul) }}', '{{ addslashes($lap->lokasi) }}')" 
                                    class="w-14 h-14 rounded-lg overflow-hidden bg-gray-200 dark:bg-gray-700 flex-shrink-0 group relative">
                                <img src="{{ asset('storage/' . $lap->foto_sebelum) }}" alt="{{ $lap->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                </div>
                            </button>
                            <div class="min-w-0 text-xs">
                                <p class="text-gray-500 dark:text-gray-400">Bukti Kerusakan:</p>
                                <button type="button" 
                                        @click="openPhoto('{{ asset('storage/' . $lap->foto_sebelum) }}', '{{ addslashes($lap->judul) }}', '{{ addslashes($lap->lokasi) }}')" 
                                        class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline inline-flex items-center gap-1 mt-0.5">
                                    <span>Pratinjau Foto</span> &rarr;
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Reporter & Technician info -->
                    <div class="pt-2 border-t border-gray-100 dark:border-gray-700/80 grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="text-gray-400 dark:text-gray-500 text-[11px] block">Pelapor</span>
                            <span class="font-semibold text-gray-700 dark:text-gray-200 truncate block">
                                {{ $lap->pelapor->nama_lengkap ?? $lap->pelapor->name ?? 'Anonim' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-400 dark:text-gray-500 text-[11px] block">Teknisi</span>
                            @if($lap->penugasan && $lap->penugasan->teknisi)
                                <span class="font-semibold text-gray-700 dark:text-gray-200 truncate block">
                                    {{ $lap->penugasan->teknisi->nama_lengkap ?? $lap->penugasan->teknisi->name }}
                                </span>
                            @else
                                <span class="text-gray-400 dark:text-gray-500 italic block">Belum ditugaskan</span>
                            @endif
                        </div>
                    </div>

                    <!-- Footer Action -->
                    <div class="pt-2 flex items-center justify-between text-xs">
                        <span class="text-[11px] text-gray-400 dark:text-gray-500">
                            {{ $lap->created_at->translatedFormat('d M Y, H:i') }}
                        </span>
                        <a href="{{ route('kepala.laporan.show', $lap->id) }}" 
                           class="inline-flex items-center justify-center px-3.5 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:hover:bg-indigo-900/60 dark:text-indigo-300 rounded-lg font-semibold text-xs transition gap-1.5">
                            <span>Detail Laporan</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700/60 text-gray-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">Tidak Ada Laporan yang Sesuai</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-xs mx-auto">
                        Coba sesuaikan kata kunci pencarian, filter status, atau periode tanggal Anda.
                    </p>
                    @if(request()->filled('status') || request()->filled('periode') || request()->filled('search'))
                        <div class="mt-4">
                            <a href="{{ route('kepala.laporan.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                                Reset Filter
                            </a>
                        </div>
                    @endif
                </div>
                @endforelse
            </div>

            <!-- Desktop Table View (md+) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50/75 dark:bg-gray-750/60">
                        <tr>
                            <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tiket & Waktu
                            </th>
                            <th scope="col" class="px-3.5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Kerusakan & Lokasi
                            </th>
                            <th scope="col" class="px-3.5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Pelapor
                            </th>
                            <th scope="col" class="px-3.5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Teknisi Ditugaskan
                            </th>
                            <th scope="col" class="px-3.5 py-3.5 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="py-3.5 pl-3 pr-6 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 bg-white dark:bg-gray-800">
                        @forelse($laporans as $lap)
                        <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-750/50 transition-colors group">
                            <!-- ID & Tanggal -->
                            <td class="py-4 pl-6 pr-3 whitespace-nowrap align-top">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-mono font-bold bg-slate-100 dark:bg-gray-700/80 text-slate-700 dark:text-gray-300 border border-slate-200 dark:border-gray-600">
                                        #{{ $lap->id }}
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $lap->created_at->format('d M Y') }}
                                </div>
                                <div class="text-[11px] text-gray-400 dark:text-gray-500">
                                    {{ $lap->created_at->format('H:i') }} WIB
                                </div>
                            </td>

                            <!-- Kerusakan & Lokasi -->
                            <td class="px-3.5 py-4 align-top">
                                <div class="flex items-start gap-3">
                                    @if($lap->foto_sebelum)
                                        <button type="button" 
                                                @click="openPhoto('{{ asset('storage/' . $lap->foto_sebelum) }}', '{{ addslashes($lap->judul) }}', '{{ addslashes($lap->lokasi) }}')" 
                                                class="w-11 h-11 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0 group/img relative border border-gray-200 dark:border-gray-600 cursor-pointer" 
                                                title="Klik untuk memperbesar foto">
                                            <img src="{{ asset('storage/' . $lap->foto_sebelum) }}" alt="{{ $lap->judul }}" class="w-full h-full object-cover group-hover/img:scale-110 transition-transform">
                                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover/img:opacity-100 transition-opacity flex items-center justify-center text-white">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                            </div>
                                        </button>
                                    @else
                                        <div class="w-11 h-11 rounded-lg bg-gray-100 dark:bg-gray-700 flex-shrink-0 flex items-center justify-center text-gray-400 border border-gray-200 dark:border-gray-600" title="Tidak ada foto">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif

                                    <div class="min-w-0 max-w-sm">
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-snug">
                                            {{ $lap->judul }}
                                        </h4>
                                        <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-gray-100 dark:bg-gray-700/60 text-gray-600 dark:text-gray-300 text-[11px] font-medium mt-1">
                                            <svg class="w-3 h-3 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            <span class="truncate">{{ $lap->lokasi }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Pelapor -->
                            <td class="px-3.5 py-4 whitespace-nowrap align-top">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                        {{ strtoupper(substr($lap->pelapor->nama_lengkap ?? $lap->pelapor->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-gray-900 dark:text-gray-100 truncate max-w-[140px]">
                                            {{ $lap->pelapor->nama_lengkap ?? $lap->pelapor->name ?? 'Anonim' }}
                                        </div>
                                        <div class="text-[11px] text-gray-400 dark:text-gray-500">
                                            {{ $lap->pelapor->role ?? 'Pelapor' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Teknisi Ditugaskan -->
                            <td class="px-3.5 py-4 whitespace-nowrap align-top">
                                @if($lap->penugasan && $lap->penugasan->teknisi)
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-300 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                            🛠️
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-gray-900 dark:text-gray-100 truncate max-w-[140px]">
                                                {{ $lap->penugasan->teknisi->nama_lengkap ?? $lap->penugasan->teknisi->name }}
                                            </div>
                                            <div class="text-[11px] text-gray-400 dark:text-gray-500">
                                                {{ $lap->penugasan->status_tugas ?? 'Ditugaskan' }}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400 italic">
                                        Belum Ditugaskan
                                    </span>
                                @endif
                            </td>

                            <!-- Status Badge -->
                            <td class="px-3.5 py-4 whitespace-nowrap text-center align-top">
                                @if($lap->status === 'Baru')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 border border-amber-200/80 dark:border-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Baru
                                    </span>
                                @elseif($lap->status === 'Ditugaskan')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300 border border-sky-200/80 dark:border-sky-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-pulse"></span>
                                        Ditugaskan
                                    </span>
                                @elseif($lap->status === 'Diproses')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 border border-blue-200/80 dark:border-blue-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                        Diproses
                                    </span>
                                @elseif($lap->status === 'Selesai')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Selesai
                                    </span>
                                @elseif($lap->status === 'Ditolak')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300 border border-rose-200/80 dark:border-rose-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Ditolak
                                    </span>
                                @endif
                            </td>

                            <!-- Action -->
                            <td class="py-4 pl-3 pr-6 whitespace-nowrap text-right align-top">
                                <a href="{{ route('kepala.laporan.show', $lap->id) }}" 
                                   class="inline-flex items-center justify-center px-3.5 py-1.5 bg-gray-100 hover:bg-indigo-50 hover:text-indigo-600 dark:bg-gray-700 dark:hover:bg-indigo-900/40 dark:hover:text-indigo-300 text-gray-700 dark:text-gray-200 text-xs font-bold rounded-xl transition gap-1 shadow-2xs">
                                    <span>Detail</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-14 text-center">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700/60 text-gray-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-800 dark:text-gray-200">Tidak Ada Laporan yang Sesuai</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                                    Tidak ditemukan tiket laporan kerusakan yang cocok dengan kriteria filter saat ini.
                                </p>
                                @if(request()->filled('status') || request()->filled('periode') || request()->filled('search'))
                                    <div class="mt-4">
                                        <a href="{{ route('kepala.laporan.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                                            Reset Semua Filter
                                        </a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Container -->
            @if($laporans->hasPages() || $laporans->total() > 0)
            <div class="px-6 py-4 bg-gray-50/75 dark:bg-gray-750/50 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    Menampilkan <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $laporans->firstItem() ?? 0 }}</span>
                    sampai <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $laporans->lastItem() ?? 0 }}</span>
                    dari <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $laporans->total() }}</span> laporan
                </div>
                <div>
                    {{ $laporans->links() }}
                </div>
            </div>
            @endif
        </div>

        <!-- 4. Quick Photo Lightbox Modal -->
        <div x-show="photoModalOpen" 
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-black/80 backdrop-blur-sm flex items-center justify-center p-4"
             @keydown.escape.window="photoModalOpen = false">
            <div class="relative max-w-3xl w-full bg-white dark:bg-gray-900 rounded-2xl overflow-hidden shadow-2xl border border-gray-700/50" 
                 @click.away="photoModalOpen = false">
                <!-- Modal Header -->
                <div class="p-4 sm:p-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <div>
                        <h4 class="text-sm sm:text-base font-bold text-gray-900 dark:text-white" x-text="modalPhotoTitle"></h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            <span x-text="modalPhotoLocation"></span>
                        </p>
                    </div>
                    <button type="button" @click="photoModalOpen = false" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Modal Body: Image Preview -->
                <div class="p-4 bg-gray-950 flex items-center justify-center max-h-[75vh]">
                    <img :src="modalPhotoUrl" :alt="modalPhotoTitle" class="max-h-[70vh] w-auto object-contain rounded-lg shadow-md">
                </div>

                <!-- Modal Footer -->
                <div class="p-3 bg-gray-50 dark:bg-gray-800/80 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                    <button type="button" @click="photoModalOpen = false" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 text-xs font-semibold rounded-xl transition">
                        Tutup Pratinjau
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>