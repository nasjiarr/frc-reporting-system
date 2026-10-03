<x-app-layout>
    <x-slot name="header">
        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-3 overflow-x-auto no-scrollbar whitespace-nowrap py-0.5" aria-label="Breadcrumb">
            <a href="{{ route('pelapor.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors inline-flex items-center gap-1.5 font-medium flex-shrink-0">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Dashboard</span>
            </a>
            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('pelapor.laporan.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium flex-shrink-0">
                Laporan Saya
            </a>
            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-800 dark:text-gray-200 font-semibold flex-shrink-0 truncate max-w-[200px] sm:max-w-xs" aria-current="page">
                Detail Laporan #{{ $laporan->id }}
            </span>
        </nav>

        <!-- Header Title & Action Buttons -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-1.5 min-w-0">
                <div class="flex flex-wrap items-center gap-2.5">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-mono font-bold bg-slate-100 dark:bg-gray-800 text-slate-700 dark:text-gray-300 border border-slate-200 dark:border-gray-700">
                        #{{ $laporan->id }}
                    </span>

                    {{-- Thematic Status Badge --}}
                    @if($laporan->status === 'Baru')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 ring-1 ring-blue-500/20 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                        Baru (Menunggu Verifikasi)
                    </span>
                    @elseif($laporan->status === 'Diproses')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 ring-1 ring-amber-500/20 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        Sedang Diproses
                    </span>
                    @elseif($laporan->status === 'Selesai')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 ring-1 ring-emerald-500/20 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Selesai Diperbaiki
                    </span>
                    @elseif($laporan->status === 'Ditolak')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300 ring-1 ring-rose-500/20 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        Laporan Ditolak
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 ring-1 ring-gray-400/20">
                        {{ $laporan->status }}
                    </span>
                    @endif
                </div>

                <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight break-words">
                    {{ $laporan->judul }}
                </h1>

                <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $laporan->lokasi }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Dilaporkan pada {{ $laporan->created_at->format('d F Y, H:i') }} ({{ $laporan->created_at->diffForHumans() }})</span>
                    </span>
                </div>
            </div>

            <!-- Header Action: Safe Back Button -->
            <div class="flex items-center gap-3 flex-shrink-0 self-start md:self-center">
                <a href="{{ route('pelapor.laporan.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl font-semibold text-xs text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-150 gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali ke Daftar Laporan</span>
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Main Container with Alpine Lightbox State -->
    <div x-data="{
        lightboxOpen: false,
        lightboxSrc: '',
        lightboxTitle: '',
        openLightbox(src, title) {
            this.lightboxSrc = src;
            this.lightboxTitle = title;
            this.lightboxOpen = true;
            document.body.style.overflow = 'hidden';
        },
        closeLightbox() {
            this.lightboxOpen = false;
            this.lightboxSrc = '';
            document.body.style.overflow = '';
        }
    }" @keydown.escape.window="closeLightbox()">

        <!-- Hero Rejection Alert Banner (when status == 'Ditolak') -->
        @if($laporan->status === 'Ditolak')
        <div class="mb-6 sm:mb-8 p-5 sm:p-6 bg-rose-50/95 dark:bg-rose-950/40 border-2 border-rose-200 dark:border-rose-900/60 rounded-2xl shadow-sm">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-rose-100 dark:bg-rose-900/60 rounded-xl text-rose-600 dark:text-rose-400 flex-shrink-0 mt-0.5 sm:mt-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div class="space-y-1.5">
                        <h3 class="text-base sm:text-lg font-bold text-rose-900 dark:text-rose-200">
                            Laporan Kerusakan Ditolak
                        </h3>
                        <p class="text-xs sm:text-sm text-rose-700 dark:text-rose-300">
                            Laporan ini tidak dapat ditindaklanjuti oleh pengelola FRC. Silakan periksa alasan penolakan di bawah dan buat laporan baru jika kendala masih perlu ditangani.
                        </p>
                        @if($laporan->alasan_penolakan)
                        <div class="mt-3 p-4 bg-white/90 dark:bg-gray-800/90 rounded-xl border border-rose-200 dark:border-rose-800/80 shadow-inner">
                            <span class="block text-xs font-bold text-rose-800 dark:text-rose-400 uppercase tracking-wider mb-1">
                                Alasan Penolakan:
                            </span>
                            <p class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-line leading-relaxed font-medium">
                                {{ $laporan->alasan_penolakan }}
                            </p>
                        </div>
                        @endif
                        <div class="text-xs text-rose-600 dark:text-rose-400 pt-1 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Ditolak pada {{ $laporan->updated_at->format('d F Y, H:i') }} ({{ $laporan->updated_at->diffForHumans() }})</span>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0 w-full sm:w-auto pt-2 sm:pt-0">
                    <a href="{{ route('pelapor.laporan.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white text-xs font-semibold rounded-xl shadow-sm transition-all duration-150 gap-2 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Buat Laporan Baru</span>
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Responsive Grid Layout: Left (Core Content) & Right (Sidebar / Timeline) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start pb-12">

            <!-- Left Column: Report Info, Photos, Resolution (lg:col-span-7 xl:col-span-8) -->
            <div class="lg:col-span-7 xl:col-span-8 space-y-6">

                <!-- Core Information & Evidence Card -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden transition-colors">
                    <div class="p-5 sm:p-6 border-b border-gray-100 dark:border-gray-700/80 bg-gradient-to-r from-gray-50/80 via-indigo-50/20 to-transparent dark:from-gray-800 dark:via-gray-800 dark:to-gray-800/80">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100">Informasi Laporan &amp; Bukti Kerusakan</h2>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Data keluhan fasilitas yang dikirimkan oleh pelapor.</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 sm:p-7 space-y-6">

                        <!-- Evidence Photo Section (Always Visible Across All Statuses) -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Foto Bukti Kerusakan Awal</span>
                                </h3>
                                @if($laporan->foto_sebelum)
                                <span class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">Klik untuk memperbesar</span>
                                @endif
                            </div>

                            @if($laporan->foto_sebelum)
                            <div class="group relative rounded-2xl overflow-hidden bg-slate-900 border border-gray-200 dark:border-gray-700 shadow-sm cursor-zoom-in"
                                 data-img-src="{{ asset('storage/' . $laporan->foto_sebelum) }}"
                                 data-img-title="Foto Bukti Kerusakan: {{ $laporan->judul }}"
                                 @click="openLightbox($el.dataset.imgSrc, $el.dataset.imgTitle)">
                                <img src="{{ asset('storage/' . $laporan->foto_sebelum) }}"
                                     alt="Bukti Kerusakan: {{ $laporan->judul }}"
                                     class="w-full h-64 sm:h-80 md:h-96 object-cover object-center transition-transform duration-500 group-hover:scale-105">

                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20 opacity-80 group-hover:opacity-100 transition-opacity pointer-events-none"></div>

                                <div class="absolute top-3 left-3 z-10">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-black/60 backdrop-blur-md text-white text-[11px] font-semibold rounded-full border border-white/20 shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        </svg>
                                        Bukti Pelapor (Kondisi Sebelum)
                                    </span>
                                </div>

                                <div class="absolute bottom-3 right-3 z-10">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-black/70 hover:bg-black/90 backdrop-blur-md text-white text-xs font-semibold rounded-xl border border-white/20 shadow-md transition-all group-hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                        <span>Perbesar Foto</span>
                                    </span>
                                </div>
                            </div>
                            @else
                            <div class="p-8 sm:p-10 rounded-2xl bg-gray-50 dark:bg-gray-800/60 border border-dashed border-gray-300 dark:border-gray-700 text-center">
                                <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700/60 text-gray-400 dark:text-gray-500 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Tidak ada foto bukti kerusakan yang dilampirkan</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Laporan ini dibuat tanpa mengunggah dokumen gambar pendukung.</p>
                            </div>
                            @endif
                        </div>

                        <!-- Structured Metadata Badges & Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-xl bg-gray-50/70 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-700/70 text-xs sm:text-sm">
                            <div class="space-y-1">
                                <span class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider block">Lokasi Fasilitas</span>
                                <div class="flex items-center gap-2 font-semibold text-gray-800 dark:text-gray-200">
                                    <span class="p-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </span>
                                    <span>{{ $laporan->lokasi }}</span>
                                </div>
                            </div>

                            <div class="space-y-1">
                                <span class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider block">Waktu Pelaporan</span>
                                <div class="flex items-center gap-2 font-semibold text-gray-800 dark:text-gray-200">
                                    <span class="p-1.5 rounded-lg bg-sky-50 dark:bg-sky-900/40 text-sky-600 dark:text-sky-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </span>
                                    <span>{{ $laporan->created_at->format('d F Y, H:i') }} WIB</span>
                                </div>
                            </div>

                            <div class="space-y-1">
                                <span class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider block">Pelapor</span>
                                <div class="flex items-center gap-2 font-semibold text-gray-800 dark:text-gray-200">
                                    <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">
                                        {{ strtoupper(substr($laporan->pelapor->nama_lengkap ?? $laporan->pelapor->name ?? 'P', 0, 2)) }}
                                    </div>
                                    <span>{{ $laporan->pelapor->nama_lengkap ?? $laporan->pelapor->name ?? 'Pelapor FRC' }}</span>
                                </div>
                            </div>

                            <div class="space-y-1">
                                <span class="text-gray-500 dark:text-gray-400 text-xs font-medium uppercase tracking-wider block">Status Terkini</span>
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full
                                        {{ $laporan->status == 'Baru' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' : '' }}
                                        {{ $laporan->status == 'Diproses' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' : '' }}
                                        {{ $laporan->status == 'Selesai' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : '' }}
                                        {{ $laporan->status == 'Ditolak' ? 'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300' : '' }}">
                                        {{ $laporan->status }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Detailed Description Section -->
                        <div class="space-y-2">
                            <h3 class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                                <span>Deskripsi Rinci Kerusakan</span>
                            </h3>
                            <div class="p-4 sm:p-5 rounded-xl bg-slate-50 dark:bg-gray-800/80 border border-gray-200/80 dark:border-gray-700 text-sm sm:text-base text-gray-800 dark:text-gray-200 whitespace-pre-line leading-relaxed">
                                {{ $laporan->deskripsi }}
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Resolution / Hasil Perbaikan Showcase (When Status == 'Selesai') -->
                @if($laporan->status === 'Selesai' && $laporan->penugasan && $laporan->penugasan->hasilPerbaikan)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border-2 border-emerald-300 dark:border-emerald-800/80 shadow-md overflow-hidden transition-colors">
                    <div class="p-5 sm:p-6 border-b border-emerald-100 dark:border-emerald-900/40 bg-gradient-to-r from-emerald-50/90 via-teal-50/30 to-transparent dark:from-emerald-950/40 dark:via-gray-800 dark:to-gray-800">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100">Dokumentasi &amp; Hasil Perbaikan</h2>
                                    <p class="text-xs text-emerald-700 dark:text-emerald-400 font-medium">Perbaikan telah rampung dan pekerjaan dikonfirmasi selesai.</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300 ring-1 ring-emerald-500/30 self-start sm:self-auto">
                                Selesai: {{ $laporan->penugasan->hasilPerbaikan->selesai_pada?->format('d M Y, H:i') ?? $laporan->updated_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5 sm:p-7 space-y-6">

                        <!-- Before & After Visual Comparison Showcase -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                    <span>Komparasi Visual (Sebelum vs Sesudah Perbaikan)</span>
                                </h3>
                                <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Klik foto untuk perbesar</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                <!-- Foto Sebelum -->
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-600 dark:text-gray-300">Kondisi Sebelum</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300 uppercase tracking-wider">Sebelum</span>
                                    </div>
                                    @if($laporan->foto_sebelum || $laporan->penugasan->hasilPerbaikan->foto_sebelum)
                                    <div class="group relative rounded-xl overflow-hidden bg-slate-900 border border-gray-200 dark:border-gray-700 shadow-sm cursor-zoom-in"
                                         data-img-src="{{ asset('storage/' . ($laporan->foto_sebelum ?? $laporan->penugasan->hasilPerbaikan->foto_sebelum)) }}"
                                         data-img-title="Kondisi Sebelum Perbaikan: {{ $laporan->judul }}"
                                         @click="openLightbox($el.dataset.imgSrc, $el.dataset.imgTitle)">
                                        <img src="{{ asset('storage/' . ($laporan->foto_sebelum ?? $laporan->penugasan->hasilPerbaikan->foto_sebelum)) }}"
                                             alt="Kondisi Sebelum Perbaikan"
                                             class="w-full h-56 sm:h-64 object-cover object-center transition-transform duration-500 group-hover:scale-105">
                                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
                                        <div class="absolute bottom-2 right-2">
                                            <span class="px-2.5 py-1 bg-black/70 backdrop-blur-sm text-white text-[11px] font-medium rounded-lg flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                                Zoom
                                            </span>
                                        </div>
                                    </div>
                                    @else
                                    <div class="w-full h-56 sm:h-64 flex flex-col items-center justify-center text-gray-400 bg-gray-100 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 cursor-default">
                                        <svg class="w-8 h-8 mb-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-xs">Foto sebelum tidak tersedia</p>
                                    </div>
                                    @endif
                                </div>

                                <!-- Foto Sesudah -->
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-600 dark:text-gray-300">Kondisi Sesudah</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300 uppercase tracking-wider">Sesudah Diperbaiki</span>
                                    </div>
                                    @if($laporan->penugasan->hasilPerbaikan->foto_sesudah)
                                    <div class="group relative rounded-xl overflow-hidden bg-slate-900 border border-gray-200 dark:border-gray-700 shadow-sm cursor-zoom-in"
                                         data-img-src="{{ asset('storage/' . $laporan->penugasan->hasilPerbaikan->foto_sesudah) }}"
                                         data-img-title="Kondisi Sesudah Perbaikan: {{ $laporan->judul }}"
                                         @click="openLightbox($el.dataset.imgSrc, $el.dataset.imgTitle)">
                                        <img src="{{ asset('storage/' . $laporan->penugasan->hasilPerbaikan->foto_sesudah) }}"
                                             alt="Kondisi Sesudah Perbaikan"
                                             class="w-full h-56 sm:h-64 object-cover object-center transition-transform duration-500 group-hover:scale-105">
                                        <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
                                        <div class="absolute bottom-2 right-2">
                                            <span class="px-2.5 py-1 bg-black/70 backdrop-blur-sm text-white text-[11px] font-medium rounded-lg flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                                Zoom
                                            </span>
                                        </div>
                                    </div>
                                    @else
                                    <div class="w-full h-56 sm:h-64 flex flex-col items-center justify-center text-gray-400 bg-gray-100 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 cursor-default">
                                        <svg class="w-8 h-8 mb-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-xs">Foto bukti sesudah tidak tersedia</p>
                                    </div>
                                    @endif
                                </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tindakan & Material Digunakan Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-gray-900/50 border border-gray-200/80 dark:border-gray-700/80 space-y-1">
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider block">Tindakan Teknisi</span>
                                <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed font-medium">
                                    {{ $laporan->penugasan->hasilPerbaikan->tindakan }}
                                </p>
                            </div>

                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-gray-900/50 border border-gray-200/80 dark:border-gray-700/80 space-y-1">
                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider block">Material / Suku Cadang</span>
                                <p class="text-sm text-gray-800 dark:text-gray-200 font-medium">
                                    {{ $laporan->penugasan->hasilPerbaikan->material_digunakan ?? $laporan->penugasan->hasilPerbaikan->material ?? '-' }}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
                @endif

            </div>

            <!-- Right Column: Timeline Stepper, Assigned Technician & Help (lg:col-span-5 xl:col-span-4) -->
            <div class="lg:col-span-5 xl:col-span-4 space-y-6">

                <!-- Lifecycle Stepper / Progress Timeline Card -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-5 sm:p-6 transition-colors">
                    <div class="flex items-center justify-between pb-4 mb-6 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center gap-2.5">
                            <div class="p-2 rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Tahapan Penanganan</h3>
                        </div>
                        <span class="text-[11px] font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Status Alur</span>
                    </div>

                    <!-- Vertical Stepper Component -->
                    <div class="relative pl-6 space-y-8 before:absolute before:left-[15px] before:top-3 before:bottom-3 before:w-0.5 before:bg-gray-200 dark:before:bg-gray-700">

                        <!-- Step 1: Laporan Dibuat / Diterima (Selalu Selesai) -->
                        <div class="relative group">
                            <span class="absolute -left-[33px] top-0 flex items-center justify-center w-7 h-7 rounded-full bg-emerald-600 text-white ring-4 ring-white dark:ring-gray-800 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </span>
                            <div class="space-y-0.5">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">Laporan Diterima</h4>
                                    <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">Terkirim</span>
                                </div>
                                <time class="block text-xs text-gray-500 dark:text-gray-400">
                                    {{ $laporan->created_at->format('d M Y, H:i') }}
                                </time>
                                <p class="text-xs text-gray-600 dark:text-gray-400 pt-1 leading-relaxed">
                                    Laporan kerusakan berhasil tercatat pada sistem dan menunggu verifikasi admin FRC.
                                </p>
                            </div>
                        </div>

                        <!-- Step 2: Verifikasi & Penugasan Teknisi -->
                        <div class="relative group">
                            @if($laporan->status === 'Ditolak')
                                <span class="absolute -left-[33px] top-0 flex items-center justify-center w-7 h-7 rounded-full bg-rose-600 text-white ring-4 ring-white dark:ring-gray-800 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </span>
                                <div class="space-y-0.5">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-bold text-rose-700 dark:text-rose-400">Verifikasi Admin (Ditolak)</h4>
                                        <span class="text-[11px] text-rose-600 dark:text-rose-400 font-semibold">Ditolak</span>
                                    </div>
                                    <time class="block text-xs text-gray-500 dark:text-gray-400">
                                        {{ $laporan->updated_at->format('d M Y, H:i') }}
                                    </time>
                                    <p class="text-xs text-rose-600 dark:text-rose-400 pt-1 leading-relaxed font-medium">
                                        Laporan tidak disetujui untuk penugasan. Alasan tercantum pada kotak pengumuman di atas.
                                    </p>
                                </div>
                            @elseif($laporan->penugasan)
                                <span class="absolute -left-[33px] top-0 flex items-center justify-center w-7 h-7 rounded-full bg-emerald-600 text-white ring-4 ring-white dark:ring-gray-800 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </span>
                                <div class="space-y-0.5">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">Ditugaskan ke Teknisi</h4>
                                        <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">Selesai</span>
                                    </div>
                                    <time class="block text-xs text-gray-500 dark:text-gray-400">
                                        {{ $laporan->penugasan->assigned_at?->format('d M Y, H:i') ?? $laporan->penugasan->created_at?->format('d M Y, H:i') }}
                                    </time>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 pt-1 leading-relaxed">
                                        Admin telah mendelegasikan tugas ke teknisi: <strong class="text-gray-800 dark:text-gray-200">{{ $laporan->penugasan->teknisi?->nama_lengkap ?? $laporan->penugasan->teknisi?->name ?? 'Teknisi FRC' }}</strong>.
                                    </p>
                                </div>
                            @else
                                <span class="absolute -left-[33px] top-0 flex items-center justify-center w-7 h-7 rounded-full bg-blue-600 text-white ring-4 ring-blue-100 dark:ring-blue-900/50 shadow-sm animate-pulse">
                                    <span class="w-2 h-2 rounded-full bg-white"></span>
                                </span>
                                <div class="space-y-0.5">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-bold text-blue-600 dark:text-blue-400">Verifikasi &amp; Penugasan</h4>
                                        <span class="text-[11px] text-blue-600 dark:text-blue-400 font-semibold">Menunggu</span>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 pt-1 leading-relaxed">
                                        Admin FRC sedang meninjau laporan Anda untuk menetapkan teknisi yang sesuai.
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Step 3: Sedang Dikerjakan oleh Teknisi -->
                        <div class="relative group">
                            @if($laporan->status === 'Ditolak')
                                <span class="absolute -left-[33px] top-0 flex items-center justify-center w-7 h-7 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-400 ring-4 ring-white dark:ring-gray-800">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </span>
                                <div class="space-y-0.5 opacity-60">
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Pengerjaan di Lapangan</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Tahap pengerjaan dibatalkan.</p>
                                </div>
                            @elseif($laporan->status === 'Selesai')
                                <span class="absolute -left-[33px] top-0 flex items-center justify-center w-7 h-7 rounded-full bg-emerald-600 text-white ring-4 ring-white dark:ring-gray-800 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </span>
                                <div class="space-y-0.5">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">Perbaikan Selesai</h4>
                                        <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">Tuntas</span>
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 pt-1 leading-relaxed">
                                        Teknisi telah menyelesaikan tindakan perbaikan fisik di lokasi.
                                    </p>
                                </div>
                            @elseif($laporan->penugasan && $laporan->penugasan->status_tugas === 'Dikerjakan')
                                <span class="absolute -left-[33px] top-0 flex items-center justify-center w-7 h-7 rounded-full bg-amber-500 text-white ring-4 ring-amber-100 dark:ring-amber-900/50 shadow-sm animate-pulse">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </span>
                                <div class="space-y-0.5">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-bold text-amber-600 dark:text-amber-400">Sedang Dikerjakan</h4>
                                        <span class="text-[11px] text-amber-600 dark:text-amber-400 font-semibold">Proses</span>
                                    </div>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 pt-1 leading-relaxed">
                                        Teknisi sedang berada di lokasi untuk melakukan diagnosa dan perbaikan sarana.
                                    </p>
                                </div>
                            @elseif($laporan->penugasan)
                                <span class="absolute -left-[33px] top-0 flex items-center justify-center w-7 h-7 rounded-full bg-amber-100 dark:bg-amber-900/60 text-amber-600 dark:text-amber-400 ring-4 ring-white dark:ring-gray-800">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </span>
                                <div class="space-y-0.5">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Menunggu Tindakan</h4>
                                        <span class="text-[11px] text-gray-500 font-medium">Antrean</span>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 pt-1 leading-relaxed">
                                        Teknisi telah menerima tugas dan akan segera menuju ke lokasi kendala.
                                    </p>
                                </div>
                            @else
                                <span class="absolute -left-[33px] top-0 flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-400 ring-4 ring-white dark:ring-gray-800">
                                    <span class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                                </span>
                                <div class="space-y-0.5 opacity-60">
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Pengerjaan di Lapangan</h4>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">Menunggu teknisi ditugaskan.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Step 4: Selesai Diperbaiki / Hasil Diunggah -->
                        <div class="relative group">
                            @if($laporan->status === 'Ditolak')
                                <span class="absolute -left-[33px] top-0 flex items-center justify-center w-7 h-7 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-400 ring-4 ring-white dark:ring-gray-800">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </span>
                                <div class="space-y-0.5 opacity-60">
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Resolusi Selesai</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Laporan ditutup dengan status ditolak.</p>
                                </div>
                            @elseif($laporan->status === 'Selesai')
                                <span class="absolute -left-[33px] top-0 flex items-center justify-center w-7 h-7 rounded-full bg-emerald-600 text-white ring-4 ring-emerald-100 dark:ring-emerald-900/50 shadow-md">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </span>
                                <div class="space-y-0.5">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-bold text-emerald-700 dark:text-emerald-300">Selesai &amp; Terverifikasi</h4>
                                        <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-bold uppercase">Selesai</span>
                                    </div>
                                    <time class="block text-xs text-gray-500 dark:text-gray-400">
                                        {{ $laporan->penugasan?->hasilPerbaikan?->selesai_pada?->format('d M Y, H:i') ?? $laporan->updated_at->format('d M Y, H:i') }}
                                    </time>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 pt-1 leading-relaxed">
                                        Hasil perbaikan dan dokumentasi foto telah diunggah oleh teknisi. Fasilitas siap digunakan.
                                    </p>
                                </div>
                            @else
                                <span class="absolute -left-[33px] top-0 flex items-center justify-center w-7 h-7 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-400 ring-4 ring-white dark:ring-gray-800">
                                    <span class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                                </span>
                                <div class="space-y-0.5 opacity-60">
                                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Selesai &amp; Terverifikasi</h4>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">Tahap akhir setelah perbaikan tuntas.</p>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

                <!-- Assigned Technician Card (When Assigned) -->
                @if($laporan->penugasan && $laporan->penugasan->teknisi)
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden transition-colors">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-700/80 bg-gradient-to-r from-gray-50/80 to-transparent dark:from-gray-800 dark:to-gray-800">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Teknisi Penanggung Jawab</span>
                            </h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold
                                {{ $laporan->penugasan->status_tugas == 'Selesai' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : '' }}
                                {{ $laporan->penugasan->status_tugas == 'Dikerjakan' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' : '' }}
                                {{ in_array($laporan->penugasan->status_tugas, ['Ditugaskan', 'Menunggu']) ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' : '' }}">
                                {{ $laporan->penugasan->status_tugas }}
                            </span>
                        </div>
                    </div>

                    <div class="p-5 space-y-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-amber-500 to-amber-600 text-white flex items-center justify-center font-bold text-base shadow-sm flex-shrink-0">
                                {{ strtoupper(substr($laporan->penugasan->teknisi->nama_lengkap ?? $laporan->penugasan->teknisi->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate">
                                    {{ $laporan->penugasan->teknisi->nama_lengkap ?? $laporan->penugasan->teknisi->name }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                    {{ $laporan->penugasan->teknisi->email }}
                                </p>
                                @if($laporan->penugasan->teknisi->no_telepon)
                                <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-0.5 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span>{{ $laporan->penugasan->teknisi->no_telepon }}</span>
                                </p>
                                @endif
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-100 dark:border-gray-700/80 text-xs space-y-2">
                            <div class="flex justify-between text-gray-500 dark:text-gray-400">
                                <span>Ditugaskan Pada:</span>
                                <span class="font-medium text-gray-800 dark:text-gray-200">
                                    {{ $laporan->penugasan->assigned_at?->format('d M Y, H:i') ?? $laporan->penugasan->created_at?->format('d M Y, H:i') }}
                                </span>
                            </div>
                            @if($laporan->penugasan->instruksi)
                            <div class="mt-2 p-3 bg-amber-50/70 dark:bg-amber-950/30 rounded-xl border border-amber-200/60 dark:border-amber-900/40">
                                <span class="block text-[11px] font-bold text-amber-800 dark:text-amber-300 uppercase tracking-wider mb-0.5">Catatan Instruksi:</span>
                                <p class="text-xs text-amber-900 dark:text-amber-200 leading-relaxed">{{ $laporan->penugasan->instruksi }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Assistance & Quick Action Card -->
                <div class="bg-gradient-to-br from-indigo-50/80 via-white to-sky-50/50 dark:from-gray-800 dark:via-gray-800 dark:to-slate-800/80 border border-indigo-100 dark:border-gray-700 rounded-2xl shadow-sm p-5 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">Bantuan &amp; Kontak FRC</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Pusat layanan informasi fasilitas.</p>
                        </div>
                    </div>

                    <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">
                        Jika Anda mendapati kondisi darurat gedung (bocor deras, korsleting aktif, lift macet), hubungi langsung pos keamanan atau helpdesk sarpras FRC.
                    </p>

                    <div class="pt-2 border-t border-indigo-100/80 dark:border-gray-700/80 flex flex-col gap-2">
                        <a href="{{ route('pelapor.laporan.create') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-xs font-semibold rounded-xl shadow-sm transition gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>+ Buat Laporan Kerusakan Lain</span>
                        </a>
                        <a href="{{ route('pelapor.laporan.index') }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-700/60 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-xs font-semibold rounded-xl transition">
                            <span>Kembali ke Riwayat Laporan</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>

        <!-- Fullscreen Accessible Alpine Lightbox Modal -->
        <div x-show="lightboxOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-black/90 backdrop-blur-md"
             style="display: none;"
             role="dialog"
             aria-modal="true"
             @click.self="closeLightbox()">

            <div class="relative max-w-5xl w-full flex flex-col items-center">
                <!-- Close Button -->
                <button type="button"
                        @click="closeLightbox()"
                        class="absolute -top-12 right-0 sm:right-2 p-2 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors focus:outline-none focus:ring-2 focus:ring-white"
                        title="Tutup (Esc)"
                        aria-label="Tutup pratinjau gambar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Fullscreen Image -->
                <div class="w-full max-h-[82vh] flex items-center justify-center overflow-hidden rounded-2xl bg-black/50 shadow-2xl border border-white/10">
                    <img :src="lightboxSrc"
                         :alt="lightboxTitle"
                         class="max-w-full max-h-[82vh] object-contain select-none">
                </div>

                <!-- Caption Title -->
                <div class="mt-3 text-center px-4 py-1.5 bg-black/50 backdrop-blur-sm rounded-xl border border-white/10">
                    <p class="text-xs sm:text-sm font-medium text-white/90" x-text="lightboxTitle"></p>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>