<x-app-layout>
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
                Laporan Selesai
            </span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                    Arsip Laporan Selesai
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Daftar seluruh laporan kerusakan fasilitas yang telah berhasil diperbaiki dan dituntaskan oleh teknisi.
                </p>
            </div>
            <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap sm:flex-nowrap flex-shrink-0 self-start sm:self-center">
                <a href="{{ route('admin.laporan.export_selesai', request()->query()) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white rounded-xl font-semibold text-xs shadow-sm hover:shadow-md transition-all duration-150 gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Ekspor Rekap (PDF)</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div x-data="{ 
        deleteModalOpen: false, 
        deleteUrl: '', 
        deleteTitle: '' 
    }">

        <!-- Filter Bar Card -->
        <div class="mb-6 p-4 sm:p-5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm transition-colors">
            <form method="GET" action="{{ route('admin.laporan.selesai') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 sm:gap-4 items-end">
                <!-- Search Input -->
                <div class="sm:col-span-2 lg:col-span-5">
                    <label for="search" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Cari Data Laporan</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari judul, lokasi, pelapor, atau teknisi..."
                            class="block w-full rounded-xl pl-10 pr-3.5 py-2.5 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors placeholder:text-gray-400 dark:placeholder:text-gray-500">
                    </div>
                </div>

                <!-- Dari Tanggal -->
                <div class="lg:col-span-3">
                    <label for="tgl_mulai" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Dari Tanggal</span>
                    </label>
                    <input type="date" name="tgl_mulai" id="tgl_mulai" value="{{ request('tgl_mulai') }}"
                        class="block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                </div>

                <!-- Sampai Tanggal -->
                <div class="lg:col-span-3">
                    <label for="tgl_selesai" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Sampai Tanggal</span>
                    </label>
                    <input type="date" name="tgl_selesai" id="tgl_selesai" value="{{ request('tgl_selesai') }}"
                        class="block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                </div>

                <!-- Filter Action Buttons -->
                <div class="sm:col-span-2 lg:col-span-1 flex items-center gap-2">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gray-900 dark:bg-gray-700 hover:bg-black dark:hover:bg-gray-600 text-white rounded-xl font-semibold text-xs shadow-sm transition-all duration-150 gap-1.5" title="Terapkan Filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span class="lg:hidden">Cari</span>
                    </button>

                    @if(request('search') || request('tgl_mulai') || request('tgl_selesai'))
                    <a href="{{ route('admin.laporan.selesai') }}" class="inline-flex items-center justify-center px-3 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-xl text-xs font-semibold transition" title="Reset Filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="sr-only">Reset</span>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Main Content Container -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden transition-colors mb-12">
            <!-- Mobile Card List (< md) -->
            <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700/80">
                @forelse($laporans as $lap)
                @php
                    $tglSelesai = $lap->penugasan?->hasilPerbaikan?->selesai_pada ? \Carbon\Carbon::parse($lap->penugasan->hasilPerbaikan->selesai_pada) : $lap->updated_at;
                @endphp
                <div class="p-4 sm:p-5 space-y-3 hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                    <div class="flex items-center justify-between gap-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-mono font-bold bg-slate-100 dark:bg-gray-700/60 text-slate-700 dark:text-gray-300 border border-slate-200 dark:border-gray-600">
                            #{{ $lap->id }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 ring-1 ring-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span>Selesai</span>
                        </span>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-gray-100 text-sm leading-snug">
                            {{ $lap->judul }}
                        </h3>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex flex-wrap items-center gap-x-3 gap-y-1">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $lap->lokasi }}</span>
                            </span>
                            <span class="text-gray-300 dark:text-gray-600">&bull;</span>
                            <span>{{ $tglSelesai->format('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-100 dark:border-gray-700/80 flex flex-wrap items-center justify-between gap-2 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="text-gray-500 dark:text-gray-400">Oleh: <strong class="text-gray-700 dark:text-gray-200">{{ $lap->pelapor->nama_lengkap ?? 'Anonim' }}</strong></span>
                            @if($lap->penugasan && $lap->penugasan->teknisi)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 text-[11px] font-medium border border-indigo-100 dark:border-indigo-800">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span>{{ $lap->penugasan->teknisi->nama_lengkap }}</span>
                            </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-1.5 ml-auto">
                            <a href="{{ route('admin.laporan.show', $lap->id) }}" class="inline-flex items-center px-2.5 py-1.5 text-xs font-semibold rounded-lg text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/40 hover:bg-indigo-100 dark:hover:bg-indigo-900/70 transition-colors gap-1" title="Lihat Detail & Bukti">
                                <span>Lihat</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>

                            @if($lap->penugasan && $lap->penugasan->hasilPerbaikan)
                            <a href="{{ route('admin.laporan.export_pdf', $lap->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-900/40 hover:bg-emerald-100 dark:hover:bg-emerald-900/70 border border-emerald-200/80 dark:border-emerald-700/60 transition-colors" title="Cetak Berita Acara PDF">
                                <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>PDF</span>
                            </a>
                            @endif

                            <button type="button"
                                @click="deleteModalOpen = true; 
                                        deleteUrl = '{{ route('admin.laporan.destroy', $lap->id) }}'; 
                                        deleteTitle = '{{ addslashes($lap->judul) }}';"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-rose-600 dark:text-rose-400 bg-rose-50/70 dark:bg-rose-500/10 hover:bg-rose-100 dark:hover:bg-rose-500/20 border border-rose-200/70 dark:border-rose-500/20 transition-colors" title="Hapus Laporan">
                                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <div class="w-14 h-14 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">
                        {{ request('search') || request('tgl_mulai') || request('tgl_selesai') ? 'Pencarian tidak ditemukan' : 'Belum Ada Laporan Selesai' }}
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                        {{ request('search') || request('tgl_mulai') || request('tgl_selesai') ? 'Coba gunakan kata kunci yang berbeda atau reset filter tanggal.' : 'Laporan yang telah diselesaikan oleh teknisi akan muncul di sini sebagai arsip.' }}
                    </p>
                </div>
                @endforelse
            </div>

            <!-- Desktop Table View (>= md) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50/80 dark:bg-gray-900/60">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">ID &amp; Tgl. Selesai</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Kendala &amp; Lokasi</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Pelapor</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Teknisi Bertugas</th>
                            <th scope="col" class="px-6 py-3.5 text-right text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/80">
                        @forelse($laporans as $lap)
                        @php
                            $tglSelesai = $lap->penugasan?->hasilPerbaikan?->selesai_pada ? \Carbon\Carbon::parse($lap->penugasan->hasilPerbaikan->selesai_pada) : $lap->updated_at;
                        @endphp
                        <tr class="hover:bg-indigo-50/30 dark:hover:bg-gray-700/40 transition-colors duration-150 group">
                            <td class="px-6 py-4 whitespace-nowrap text-xs">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 border border-slate-200 dark:border-gray-600 mb-1">
                                    #{{ $lap->id }}
                                </span>
                                <div class="font-bold text-gray-900 dark:text-gray-100">
                                    {{ $tglSelesai->format('d M Y') }}
                                </div>
                                <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                    {{ $tglSelesai->format('H:i') }} WIB
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-sm text-gray-900 dark:text-gray-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                    {{ $lap->judul }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $lap->lokasi }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="font-medium">{{ $lap->pelapor->nama_lengkap ?? 'Anonim' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($lap->penugasan && $lap->penugasan->teknisi)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-800/80 font-medium text-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.03a4.5 4.5 0 004.486-6.32l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.32 4.486c.158.58.134 1.193-.03 1.743l-1.42 1.42"></path>
                                    </svg>
                                    <span>{{ $lap->penugasan->teknisi->nama_lengkap }}</span>
                                </span>
                                @else
                                <span class="text-gray-400 dark:text-gray-500 italic text-xs">Tidak ada data</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-semibold">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.laporan.show', $lap->id) }}" class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/40 hover:bg-indigo-100 dark:hover:bg-indigo-900/70 transition-colors gap-1" title="Lihat Detail & Bukti">
                                        <span>Lihat</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>

                                    @if($lap->penugasan && $lap->penugasan->hasilPerbaikan)
                                    <a href="{{ route('admin.laporan.export_pdf', $lap->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-900/40 hover:bg-emerald-100 dark:hover:bg-emerald-900/70 border border-emerald-200/80 dark:border-emerald-700/60 transition-colors" title="Cetak Berita Acara PDF">
                                        <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span>PDF</span>
                                    </a>
                                    @endif

                                    <button type="button"
                                        @click="deleteModalOpen = true; 
                                                deleteUrl = '{{ route('admin.laporan.destroy', $lap->id) }}'; 
                                                deleteTitle = '{{ addslashes($lap->judul) }}';"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-semibold text-rose-600 dark:text-rose-400 bg-rose-50/70 dark:bg-rose-500/10 hover:bg-rose-100 dark:hover:bg-rose-500/20 border border-rose-200/70 dark:border-rose-500/20 transition-colors" title="Hapus Laporan">
                                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="w-14 h-14 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                    {{ request('search') || request('tgl_mulai') || request('tgl_selesai') ? 'Pencarian tidak ditemukan' : 'Belum Ada Laporan Selesai' }}
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                                    {{ request('search') || request('tgl_mulai') || request('tgl_selesai') ? 'Coba gunakan kata kunci yang berbeda atau reset filter tanggal.' : 'Laporan yang telah diselesaikan oleh teknisi akan muncul di sini sebagai arsip.' }}
                                </p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($laporans->hasPages())
            <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $laporans->links() }}
            </div>
            @endif
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="deleteModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-gray-950/70 flex items-center justify-center backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div @click.away="deleteModalOpen = false" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden border border-gray-200 dark:border-gray-700 transform transition-all"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">
                <div class="px-6 py-6 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-100 dark:bg-rose-900/50 text-rose-600 dark:text-rose-400 mb-4 shadow-sm">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 tracking-tight">Hapus Arsip Laporan</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                        Apakah Anda yakin ingin menghapus laporan <span class="font-bold text-gray-900 dark:text-gray-100" x-text="deleteTitle"></span>? Semua data terkait termasuk foto dokumentasi akan dihapus secara permanen.
                    </p>
                </div>

                <div class="px-6 py-4 bg-gray-50/80 dark:bg-gray-900/60 border-t border-gray-200 dark:border-gray-700 flex flex-col-reverse sm:flex-row items-center justify-end gap-2.5">
                    <button type="button" @click="deleteModalOpen = false" class="w-full sm:w-auto px-4 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl font-semibold text-xs text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition shadow-xs">
                        Batal
                    </button>
                    <form :action="deleteUrl" method="POST" class="inline-flex w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full sm:w-auto px-4 py-2.5 bg-rose-600 border border-transparent rounded-xl font-semibold text-xs text-white hover:bg-rose-700 active:bg-rose-800 transition shadow-sm gap-1.5 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Hapus Permanen</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>