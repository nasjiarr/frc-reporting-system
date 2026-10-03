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
            <span class="text-gray-800 dark:text-gray-200 font-semibold flex-shrink-0" aria-current="page">
                Laporan Saya
            </span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                    Daftar Laporan Saya
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Kelola, lacak, dan pantau seluruh riwayat pelaporan kendala fasilitas gedung FRC.
                </p>
            </div>
            <div class="flex items-center gap-3 flex-shrink-0 self-start sm:self-center">
                <a href="{{ route('pelapor.laporan.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl font-semibold text-xs shadow-sm hover:shadow-md transition-all duration-150 gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>+ Buat Laporan Baru</span>
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Filter Bar Card -->
    <div class="mb-6 p-4 sm:p-5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm transition-colors">
        <form method="GET" action="{{ route('pelapor.laporan.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3 sm:gap-4">
            <div class="w-full sm:w-72">
                <label for="status" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <span>Saring Berdasarkan Status</span>
                </label>
                <div class="relative">
                    <select name="status" id="status" class="block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                        <option value="">Semua Status Laporan</option>
                        <option value="Baru" {{ request('status') == 'Baru' ? 'selected' : '' }}>Menunggu Diproses (Baru)</option>
                        <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                        <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai Diperbaiki</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-gray-900 dark:bg-gray-700 hover:bg-black dark:hover:bg-gray-600 text-white rounded-xl font-semibold text-xs shadow-sm transition-all duration-150 gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span>Terapkan Filter</span>
                </button>

                @if(request()->filled('status'))
                <a href="{{ route('pelapor.laporan.index') }}" class="inline-flex items-center justify-center px-3 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-xl text-xs font-semibold transition" title="Reset Filter">
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
            <div class="p-4 sm:p-5 space-y-3 hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">
                <div class="flex items-center justify-between gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-mono font-bold bg-slate-100 dark:bg-gray-700/60 text-slate-700 dark:text-gray-300 border border-slate-200 dark:border-gray-600">
                        #{{ $lap->id }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $lap->status == 'Baru' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' : '' }}
                        {{ $lap->status == 'Diproses' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' : '' }}
                        {{ $lap->status == 'Selesai' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : '' }}
                        {{ $lap->status == 'Ditolak' ? 'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300' : '' }}">
                        <span class="w-1.5 h-1.5 rounded-full
                            {{ $lap->status == 'Baru' ? 'bg-blue-500 animate-pulse' : '' }}
                            {{ $lap->status == 'Diproses' ? 'bg-amber-500 animate-pulse' : '' }}
                            {{ $lap->status == 'Selesai' ? 'bg-emerald-500' : '' }}
                            {{ $lap->status == 'Ditolak' ? 'bg-rose-500' : '' }}"></span>
                        {{ $lap->status }}
                    </span>
                </div>

                <div>
                    <h3 class="font-bold text-gray-900 dark:text-gray-100 text-sm leading-snug">
                        {{ $lap->judul }}
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2 leading-relaxed">
                        {{ $lap->deskripsi }}
                    </p>
                </div>

                <div class="pt-2 border-t border-gray-100 dark:border-gray-700/80 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400 min-w-0">
                        <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="truncate font-medium text-gray-700 dark:text-gray-300">{{ $lap->lokasi }}</span>
                    </div>

                    <a href="{{ route('pelapor.laporan.show', $lap->id) }}" class="inline-flex items-center px-3 py-1.5 text-xs font-semibold rounded-lg text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/40 hover:bg-indigo-100 dark:hover:bg-indigo-900/70 transition-colors gap-1 flex-shrink-0">
                        <span>Lihat Detail</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
            @empty
            <div class="px-6 py-12 text-center">
                <div class="w-14 h-14 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">Belum ada data laporan</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">Anda belum pernah membuat laporan kerusakan atau tidak ada laporan yang sesuai dengan filter.</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View (>= md) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50/80 dark:bg-gray-900/60">
                    <tr>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">ID &amp; Tanggal</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Kendala Fasilitas</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status Laporan</th>
                        <th scope="col" class="px-6 py-3.5 text-right text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700/80">
                    @forelse($laporans as $lap)
                    <tr class="hover:bg-indigo-50/30 dark:hover:bg-gray-700/40 transition-colors duration-150 group">
                        <td class="px-6 py-4 whitespace-nowrap text-xs">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-gray-300 border border-slate-200 dark:border-gray-600 mb-1">
                                #{{ $lap->id }}
                            </span>
                            <div class="text-gray-500 dark:text-gray-400 font-medium">
                                {{ $lap->created_at->format('d M Y, H:i') }}
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                                {{ $lap->status == 'Baru' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 ring-1 ring-blue-500/20' : '' }}
                                {{ $lap->status == 'Diproses' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 ring-1 ring-amber-500/20' : '' }}
                                {{ $lap->status == 'Selesai' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 ring-1 ring-emerald-500/20' : '' }}
                                {{ $lap->status == 'Ditolak' ? 'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300 ring-1 ring-rose-500/20' : '' }}">
                                <span class="w-1.5 h-1.5 rounded-full
                                    {{ $lap->status == 'Baru' ? 'bg-blue-500 animate-pulse' : '' }}
                                    {{ $lap->status == 'Diproses' ? 'bg-amber-500 animate-pulse' : '' }}
                                    {{ $lap->status == 'Selesai' ? 'bg-emerald-500' : '' }}
                                    {{ $lap->status == 'Ditolak' ? 'bg-rose-500' : '' }}"></span>
                                {{ $lap->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-semibold">
                            <a href="{{ route('pelapor.laporan.show', $lap->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/40 hover:bg-indigo-100 dark:hover:bg-indigo-900/70 transition-colors gap-1">
                                <span>Lihat Detail</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="w-14 h-14 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto mb-3">
                                <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">Belum ada data laporan</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">Anda belum pernah membuat laporan kerusakan atau tidak ada laporan yang sesuai dengan filter.</p>
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
</x-app-layout>