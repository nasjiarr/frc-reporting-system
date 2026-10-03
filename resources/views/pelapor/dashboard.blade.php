<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Dashboard Pelapor') }}
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Pusat pemantauan status dan pelaporan fasilitas gedung FRC.</p>
    </x-slot>

    <!-- Hero Section / Greeting Banner -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-50 via-white to-sky-50 dark:from-gray-800 dark:via-gray-800 dark:to-slate-800/80 rounded-2xl border border-indigo-100/80 dark:border-gray-700/80 p-6 sm:p-8 shadow-sm mb-8">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-indigo-500/5 dark:bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-100/80 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 text-xs font-semibold mb-3">
                    <span class="w-2 h-2 rounded-full bg-indigo-600 dark:bg-indigo-400 animate-pulse"></span>
                    Sistem Pelaporan Fasilitas FRC
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                    Halo, {{ auth()->user()->name ?? auth()->user()->nama_lengkap }}! 👋
                </h1>
                <p class="mt-2 text-sm sm:text-base text-gray-600 dark:text-gray-300 leading-relaxed">
                    Laporkan kendala fasilitas, kelistrikan, dan sarana gedung dengan cepat. Pantau tindak lanjut perbaikan secara transparan oleh tim teknisi FRC.
                </p>
            </div>
            <div class="flex-shrink-0 w-full sm:w-auto">
                <a href="{{ route('pelapor.laporan.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl shadow-md shadow-indigo-500/20 hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-200 gap-2 group">
                    <svg class="w-5 h-5 transition-transform group-hover:rotate-90 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>+ Buat Laporan Kerusakan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Callout Ditolak -->
    @if(isset($statistik['ditolak']) && $statistik['ditolak'] > 0)
    <div class="mb-6 sm:mb-8 p-4 sm:p-5 bg-rose-50/90 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 rounded-xl shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-start sm:items-center gap-3.5">
                <div class="p-2.5 bg-rose-100 dark:bg-rose-900/60 rounded-xl text-rose-600 dark:text-rose-400 flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-rose-900 dark:text-rose-200">
                        Perhatian: Terdapat {{ $statistik['ditolak'] }} Laporan Ditolak
                    </h3>
                    <p class="text-xs sm:text-sm text-rose-700 dark:text-rose-300 mt-0.5">
                        Ada laporan Anda yang ditolak oleh admin. Silakan periksa alasan penolakan dan sesuaikan data laporan Anda.
                    </p>
                </div>
            </div>
            <div class="flex-shrink-0 w-full sm:w-auto">
                <a href="{{ route('pelapor.laporan.index', ['status' => 'Ditolak']) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 sm:py-2 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white text-xs font-semibold rounded-lg shadow-sm transition gap-1.5 whitespace-nowrap">
                    <span>Lihat Laporan Ditolak</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Interactive Statistics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-6 mb-6 sm:mb-8">
        <!-- Total Laporan -->
        <a href="{{ route('pelapor.laporan.index') }}" class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-3.5 sm:p-5 shadow-sm hover:shadow-md hover:border-indigo-400 dark:hover:border-indigo-500 transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
            <div class="flex items-center justify-between mb-2.5 sm:mb-3">
                <span class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">Total Laporan</span>
                <div class="p-1.5 sm:p-2.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <p class="text-xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $statistik['total'] }}</p>
                <span class="text-xs text-indigo-600 dark:text-indigo-400 font-medium group-hover:underline inline-flex items-center">
                    Semua
                    <svg class="w-3.5 h-3.5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            </div>
        </a>

        <!-- Status Baru -->
        <a href="{{ route('pelapor.laporan.index', ['status' => 'Baru']) }}" class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-3.5 sm:p-5 shadow-sm hover:shadow-md hover:border-blue-400 dark:hover:border-blue-500 transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
            <div class="flex items-center justify-between mb-2.5 sm:mb-3">
                <span class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">Status Baru</span>
                <div class="p-1.5 sm:p-2.5 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <p class="text-xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $statistik['baru'] }}</p>
                <span class="text-xs text-blue-600 dark:text-blue-400 font-medium group-hover:underline inline-flex items-center">
                    Baru
                    <svg class="w-3.5 h-3.5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            </div>
        </a>

        <!-- Sedang Diproses -->
        <a href="{{ route('pelapor.laporan.index', ['status' => 'Diproses']) }}" class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-3.5 sm:p-5 shadow-sm hover:shadow-md hover:border-amber-400 dark:hover:border-amber-500 transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
            <div class="flex items-center justify-between mb-2.5 sm:mb-3">
                <span class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">Sedang Diproses</span>
                <div class="p-1.5 sm:p-2.5 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <p class="text-xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $statistik['diproses'] }}</p>
                <span class="text-xs text-amber-600 dark:text-amber-400 font-medium group-hover:underline inline-flex items-center">
                    Diproses
                    <svg class="w-3.5 h-3.5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            </div>
        </a>

        <!-- Selesai -->
        <a href="{{ route('pelapor.laporan.index', ['status' => 'Selesai']) }}" class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-3.5 sm:p-5 shadow-sm hover:shadow-md hover:border-emerald-400 dark:hover:border-emerald-500 transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
            <div class="flex items-center justify-between mb-2.5 sm:mb-3">
                <span class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">Selesai</span>
                <div class="p-1.5 sm:p-2.5 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <p class="text-xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $statistik['selesai'] }}</p>
                <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium group-hover:underline inline-flex items-center">
                    Selesai
                    <svg class="w-3.5 h-3.5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            </div>
        </a>

        <!-- Ditolak -->
        <a href="{{ route('pelapor.laporan.index', ['status' => 'Ditolak']) }}" class="group bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-3.5 sm:p-5 shadow-sm hover:shadow-md hover:border-rose-400 dark:hover:border-rose-500 transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5 col-span-2 sm:col-span-1 lg:col-span-1">
            <div class="flex items-center justify-between mb-2.5 sm:mb-3">
                <span class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">Ditolak</span>
                <div class="p-1.5 sm:p-2.5 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 group-hover:scale-110 transition-transform">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <p class="text-xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $statistik['ditolak'] }}</p>
                <span class="text-xs text-rose-600 dark:text-rose-400 font-medium group-hover:underline inline-flex items-center">
                    Ditolak
                    <svg class="w-3.5 h-3.5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </span>
            </div>
        </a>
    </div>

    <!-- 5 Laporan Terbaru Section -->
    <x-card>
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    5 Laporan Terbaru
                </h3>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    Riwayat pembaruan status laporan terakhir yang Anda buat.
                </p>
            </div>
            <div>
                <a href="{{ route('pelapor.laporan.index') }}" class="inline-flex items-center text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors group">
                    <span>Lihat Semua Laporan</span>
                    <svg class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Mobile Card View (< md) -->
        <div class="md:hidden space-y-3">
            @forelse($laporanTerbaru as $lap)
            <div class="p-4 bg-gray-50/70 dark:bg-gray-800/80 rounded-xl border border-gray-200 dark:border-gray-700 space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $lap->created_at->format('d M Y') }}
                    </span>
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full
                        {{ $lap->status == 'Baru' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' : '' }}
                        {{ $lap->status == 'Diproses' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' : '' }}
                        {{ $lap->status == 'Selesai' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : '' }}
                        {{ $lap->status == 'Ditolak' ? 'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300' : '' }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-1.5
                            {{ $lap->status == 'Baru' ? 'bg-blue-500' : '' }}
                            {{ $lap->status == 'Diproses' ? 'bg-amber-500' : '' }}
                            {{ $lap->status == 'Selesai' ? 'bg-emerald-500' : '' }}
                            {{ $lap->status == 'Ditolak' ? 'bg-rose-500' : '' }}"></span>
                        {{ $lap->status }}
                    </span>
                </div>

                <div>
                    <h4 class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ $lap->judul }}</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">{{ $lap->deskripsi }}</p>
                </div>

                <div class="pt-2 border-t border-gray-200/80 dark:border-gray-700 flex items-center justify-between text-xs">
                    <span class="inline-flex items-center gap-1.5 text-gray-600 dark:text-gray-300 truncate max-w-[200px]">
                        <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="truncate">{{ $lap->lokasi }}</span>
                    </span>
                    <a href="{{ route('pelapor.laporan.show', $lap->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/40 hover:bg-indigo-100 dark:hover:bg-indigo-900/70 transition-colors gap-1">
                        <span>Detail</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="py-8 px-4 text-center">
                <div class="flex flex-col items-center justify-center">
                    <div class="w-14 h-14 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Belum Ada Laporan Kerusakan</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-xs">
                        Anda belum pernah membuat laporan. Jika Anda menemukan fasilitas atau sarana yang rusak, segera laporkan agar dapat ditangani teknisi.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('pelapor.laporan.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-semibold rounded-lg shadow-sm transition gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>+ Buat Laporan Kerusakan</span>
                        </a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View (>= md) -->
        <div class="hidden md:block overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700/80">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 text-gray-600 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">
                        <th class="py-3.5 px-4">Tanggal</th>
                        <th class="py-3.5 px-4">Judul Laporan</th>
                        <th class="py-3.5 px-4">Lokasi</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-700 dark:text-gray-300 text-sm">
                    @forelse($laporanTerbaru as $lap)
                    <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/40 transition-colors duration-150">
                        <td class="py-3.5 px-4 whitespace-nowrap text-gray-600 dark:text-gray-400">
                            {{ $lap->created_at->format('d M Y') }}
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $lap->judul }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 max-w-xs truncate">{{ $lap->deskripsi }}</div>
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap text-gray-600 dark:text-gray-300">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $lap->lokasi }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full
                                {{ $lap->status == 'Baru' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' : '' }}
                                {{ $lap->status == 'Diproses' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' : '' }}
                                {{ $lap->status == 'Selesai' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : '' }}
                                {{ $lap->status == 'Ditolak' ? 'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300' : '' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5
                                    {{ $lap->status == 'Baru' ? 'bg-blue-500' : '' }}
                                    {{ $lap->status == 'Diproses' ? 'bg-amber-500' : '' }}
                                    {{ $lap->status == 'Selesai' ? 'bg-emerald-500' : '' }}
                                    {{ $lap->status == 'Ditolak' ? 'bg-rose-500' : '' }}"></span>
                                {{ $lap->status }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 whitespace-nowrap text-right">
                            <a href="{{ route('pelapor.laporan.show', $lap->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 transition-colors gap-1">
                                <span>Detail</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 px-4 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100">Belum Ada Laporan Kerusakan</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-sm">
                                    Anda belum pernah membuat laporan. Jika Anda menemukan fasilitas atau sarana yang rusak, segera laporkan agar dapat ditangani teknisi.
                                </p>
                                <div class="mt-5">
                                    <a href="{{ route('pelapor.laporan.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-semibold rounded-lg shadow-sm transition gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <span>+ Buat Laporan Kerusakan</span>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>