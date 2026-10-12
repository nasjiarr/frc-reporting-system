<x-app-layout>
    {{-- Header Slot --}}
    <x-slot name="header">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                Dashboard Pelapor
            </h1>
            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                Pusat pemantauan status dan pelaporan fasilitas gedung FRC.
            </p>
        </div>
    </x-slot>

    <div class="space-y-6 pb-12">
        <!-- 1. Hero Greeting Banner -->
        <div class="relative overflow-hidden bg-gradient-to-br from-indigo-50/90 via-white to-sky-50/60 dark:from-gray-800 dark:via-gray-800 dark:to-gray-800/90 rounded-2xl border border-indigo-100/80 dark:border-gray-700 p-5 sm:p-7 shadow-sm">
            <div class="absolute -right-8 -bottom-8 w-48 h-48 bg-indigo-500/10 dark:bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="max-w-2xl">
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                        Halo, {{ auth()->user()->nama_lengkap ?? auth()->user()->name }}! 👋
                    </h2>
                    <p class="mt-2 text-xs sm:text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                        Laporkan kendala fasilitas, kelistrikan, dan sarana gedung dengan cepat. Pantau tindak lanjut perbaikan secara transparan oleh tim teknisi FRC.
                    </p>
                </div>
                <div class="flex-shrink-0 flex items-center gap-3">
                    <a href="{{ route('pelapor.laporan.create') }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-3 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-md shadow-indigo-500/20 hover:shadow-lg hover:shadow-indigo-500/30 transition-all duration-150 gap-2 group">
                        <svg class="w-4 h-4 transition-transform group-hover:rotate-90 duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Buat Laporan Kerusakan</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. Alert Callout Ditolak (Jika Ada) -->
        @if(isset($statistik['ditolak']) && $statistik['ditolak'] > 0)
        <div class="p-4 sm:p-5 bg-rose-50/90 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 rounded-2xl shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start sm:items-center gap-3.5">
                    <div class="w-10 h-10 bg-rose-100 dark:bg-rose-900/60 rounded-xl text-rose-600 dark:text-rose-400 flex items-center justify-center flex-shrink-0 shadow-xs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-rose-900 dark:text-rose-200">
                            Perhatian: Terdapat {{ $statistik['ditolak'] }} Laporan Ditolak
                        </h3>
                        <p class="text-xs text-rose-700 dark:text-rose-300 mt-0.5">
                            Ada laporan Anda yang ditolak oleh admin. Silakan periksa alasan penolakan dan sesuaikan data laporan Anda.
                        </p>
                    </div>
                </div>
                <div class="flex-shrink-0 w-full sm:w-auto">
                    <a href="{{ route('pelapor.laporan.index', ['status' => 'Ditolak']) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white text-xs font-semibold rounded-xl shadow-sm transition gap-1.5 whitespace-nowrap">
                        <span>Lihat Laporan Ditolak</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- 3. 5 KPI Summary Cards Interaktif -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3.5 sm:gap-4">
            <!-- 1. Total Laporan -->
            <a href="{{ route('pelapor.laporan.index') }}" 
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
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $statistik['total'] }}</p>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                            Semua
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-between truncate">
                        <span>Seluruh riwayat tiket</span>
                        <span class="text-indigo-600 dark:text-indigo-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </p>
                </div>
            </a>

            <!-- 2. Status Baru -->
            <a href="{{ route('pelapor.laporan.index', ['status' => 'Baru']) }}" 
               class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-blue-400 dark:hover:border-blue-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors uppercase tracking-wider">Menunggu</span>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $statistik['baru'] }}</p>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                            Baru
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-between truncate">
                        <span>Menunggu verifikasi admin</span>
                        <span class="text-blue-600 dark:text-blue-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </p>
                </div>
            </a>

            <!-- 3. Sedang Diproses -->
            <a href="{{ route('pelapor.laporan.index', ['status' => 'Diproses']) }}" 
               class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-amber-400 dark:hover:border-amber-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors uppercase tracking-wider">Diproses</span>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $statistik['diproses'] }}</p>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                            Diproses
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-between truncate">
                        <span>Ditangani teknisi lapangan</span>
                        <span class="text-amber-600 dark:text-amber-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </p>
                </div>
            </a>

            <!-- 4. Selesai -->
            <a href="{{ route('pelapor.laporan.index', ['status' => 'Selesai']) }}" 
               class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-emerald-400 dark:hover:border-emerald-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors uppercase tracking-wider">Selesai</span>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $statistik['selesai'] }}</p>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                            Selesai
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-between truncate">
                        <span>Perbaikan telah tuntas</span>
                        <span class="text-emerald-600 dark:text-emerald-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </p>
                </div>
            </a>

            <!-- 5. Ditolak -->
            <a href="{{ route('pelapor.laporan.index', ['status' => 'Ditolak']) }}" 
               class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-rose-400 dark:hover:border-rose-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5 col-span-2 lg:col-span-1">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors uppercase tracking-wider">Ditolak</span>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $statistik['ditolak'] }}</p>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">
                            Ditolak
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-between truncate">
                        <span>Perlu revisi / di luar lingkup</span>
                        <span class="text-rose-600 dark:text-rose-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </p>
                </div>
            </a>
        </div>

        <!-- 4. Laporan Terbaru Section -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden transition-colors">
            <!-- Header Table Bar -->
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gray-50/75 dark:bg-gray-900/40">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-gray-100">
                            5 Laporan Terbaru
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Riwayat pembaruan status laporan terakhir yang Anda ajukan.
                        </p>
                    </div>
                </div>
                <div>
                    <a href="{{ route('pelapor.laporan.index') }}" class="inline-flex items-center text-xs sm:text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors group">
                        <span>Lihat Semua Laporan</span>
                        <svg class="w-4 h-4 ml-1 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Mobile Card View (< md) -->
            <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700/80">
                @forelse($laporanTerbaru as $lap)
                <div class="p-4 sm:p-5 space-y-3 hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                            {{ $lap->created_at->format('d M Y') }}
                        </span>
                        
                        <!-- Status Badge -->
                        @if($lap->status == 'Baru')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                            Baru
                        </span>
                        @elseif($lap->status == 'Diproses')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            Diproses
                        </span>
                        @elseif($lap->status == 'Selesai')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Selesai
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            Ditolak
                        </span>
                        @endif
                    </div>

                    <div>
                        <h4 class="font-bold text-sm text-gray-900 dark:text-gray-100">{{ $lap->judul }}</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2 leading-relaxed">{{ $lap->deskripsi }}</p>
                    </div>

                    <div class="pt-2 border-t border-gray-100 dark:border-gray-700/80 flex items-center justify-between text-xs">
                        <span class="inline-flex items-center gap-1.5 text-gray-600 dark:text-gray-400 truncate max-w-[200px]">
                            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="truncate">{{ $lap->lokasi }}</span>
                        </span>
                        <a href="{{ route('pelapor.laporan.show', $lap->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-xl text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/40 hover:bg-indigo-100 dark:hover:bg-indigo-900/70 border border-indigo-200 dark:border-indigo-800 transition shadow-xs">
                            <span>Detail</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="py-12 px-4 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">Belum Ada Laporan Kerusakan</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-xs mx-auto">
                        Anda belum pernah membuat laporan. Jika Anda menemukan fasilitas atau sarana yang rusak, segera laporkan agar dapat ditangani teknisi.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('pelapor.laporan.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-semibold rounded-xl shadow-sm transition gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Buat Laporan Kerusakan</span>
                        </a>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Desktop Table View (>= md) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50/75 dark:bg-gray-900/50 text-gray-600 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-5">Tanggal</th>
                            <th class="py-3.5 px-5">Judul Laporan</th>
                            <th class="py-3.5 px-5">Lokasi</th>
                            <th class="py-3.5 px-5">Status</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/80 text-gray-700 dark:text-gray-300 text-sm">
                        @forelse($laporanTerbaru as $lap)
                        <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30 transition-colors duration-150">
                            <td class="py-4 px-5 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400 font-medium">
                                {{ $lap->created_at->format('d M Y') }}
                            </td>
                            <td class="py-4 px-5">
                                <div class="font-bold text-gray-900 dark:text-gray-100 text-sm">{{ $lap->judul }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 max-w-sm truncate">{{ $lap->deskripsi }}</div>
                            </td>
                            <td class="py-4 px-5 whitespace-nowrap text-xs text-gray-600 dark:text-gray-300">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $lap->lokasi }}</span>
                                </span>
                            </td>
                            <td class="py-4 px-5 whitespace-nowrap">
                                @if($lap->status == 'Baru')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                    Baru
                                </span>
                                @elseif($lap->status == 'Diproses')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Diproses
                                </span>
                                @elseif($lap->status == 'Selesai')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Selesai
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Ditolak
                                </span>
                                @endif
                            </td>
                            <td class="py-4 px-5 whitespace-nowrap text-right">
                                <a href="{{ route('pelapor.laporan.show', $lap->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-xl text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 border border-indigo-200 dark:border-indigo-800 transition shadow-xs">
                                    <span>Detail</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-14 px-4 text-center">
                                <div class="max-w-xs mx-auto">
                                    <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">Belum Ada Laporan Kerusakan</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Anda belum pernah membuat laporan. Jika Anda menemukan fasilitas atau sarana yang rusak, segera laporkan agar dapat ditangani teknisi.
                                    </p>
                                    <div class="mt-4">
                                        <a href="{{ route('pelapor.laporan.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-semibold rounded-xl shadow-sm transition gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            <span>Buat Laporan Kerusakan</span>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>