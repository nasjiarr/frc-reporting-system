<x-app-layout>
    @php
        $prevMonth = \Carbon\Carbon::parse($periode . '-01')->subMonth()->format('Y-m');
        $nextMonth = \Carbon\Carbon::parse($periode . '-01')->addMonth()->format('Y-m');
        $currentMonthFormatted = \Carbon\Carbon::parse($periode . '-01')->translatedFormat('F Y');

        $categoriesMeta = [
            'AirBersih' => [
                'name' => 'Air Bersih',
                'desc' => 'Meteran air PDAM / sumur dalam',
                'unit' => 'm³',
                'color' => 'blue',
                'bg' => 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-800',
            ],
            'AirHujan' => [
                'name' => 'Air Hujan',
                'desc' => 'Pemanfaatan rain harvesting',
                'unit' => 'm³',
                'color' => 'sky',
                'bg' => 'bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 border-sky-200 dark:border-sky-800',
            ],
            'MDP' => [
                'name' => 'Listrik MDP',
                'desc' => 'Main Distribution Panel utama',
                'unit' => 'kWh',
                'color' => 'amber',
                'bg' => 'bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-800',
            ],
            'SDP' => [
                'name' => 'Listrik SDP',
                'desc' => 'Sub Distribution Panel 1 & 2',
                'unit' => 'kWh',
                'color' => 'orange',
                'bg' => 'bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 border-orange-200 dark:border-orange-800',
            ],
            'Lift' => [
                'name' => 'Beban Lift',
                'desc' => 'Elevator penumpang gedung FRC',
                'unit' => 'kWh',
                'color' => 'indigo',
                'bg' => 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 border-indigo-200 dark:border-indigo-800',
            ],
            'AC' => [
                'name' => 'Beban AC',
                'desc' => 'Pendingin ruangan Lt 1, 2, & 3',
                'unit' => 'kWh',
                'color' => 'cyan',
                'bg' => 'bg-cyan-50 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 border-cyan-200 dark:border-cyan-800',
            ],
            'Lampu' => [
                'name' => 'Lampu & Stop Kontak',
                'desc' => 'Penerangan & beban Lt 1, 2, & 3',
                'unit' => 'kWh',
                'color' => 'yellow',
                'bg' => 'bg-yellow-50 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 border-yellow-200 dark:border-yellow-800',
            ],
        ];

        $detailsByJenis = $details->keyBy('jenis_utilitas');
    @endphp

    <div class="pb-12 space-y-6">
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
                    Rekapitulasi Utilitas
                </span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                        Rekapitulasi Utilitas Gedung
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Pengawasan konsumsi listrik, efisiensi air, serta audit rekapitulasi stand meter gedung FRC UGM.
                    </p>
                </div>
                <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap sm:flex-nowrap flex-shrink-0 self-start sm:self-center">
                    <a href="{{ route('kepala.utilitas.export', ['bulan' => $periode]) }}" 
                       class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white rounded-xl font-semibold text-xs shadow-sm hover:shadow-md transition-all duration-150 gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Ekspor Laporan PDF</span>
                    </a>
                </div>
            </div>
        </x-slot>

        <!-- 1. Periode Control & Navigation Card -->
        <div class="p-4 sm:p-5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm transition-colors">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <!-- Month Navigator Controls -->
                <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                    <a href="{{ route('kepala.utilitas.index', ['bulan' => $prevMonth]) }}" 
                       class="inline-flex items-center px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-semibold transition gap-1.5"
                       title="Lihat Bulan Sebelumnya">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        <span>{{ \Carbon\Carbon::parse($prevMonth . '-01')->translatedFormat('M Y') }}</span>
                    </a>

                    <div class="px-4 py-2 rounded-xl bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-200/80 dark:border-indigo-800 text-indigo-700 dark:text-indigo-300 text-xs sm:text-sm font-bold flex items-center gap-2">
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Periode: {{ $currentMonthFormatted }}</span>
                    </div>

                    <a href="{{ route('kepala.utilitas.index', ['bulan' => $nextMonth]) }}" 
                       class="inline-flex items-center px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-semibold transition gap-1.5"
                       title="Lihat Bulan Berikutnya">
                        <span>{{ \Carbon\Carbon::parse($nextMonth . '-01')->translatedFormat('M Y') }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <!-- Month Picker Input Form -->
                <form method="GET" action="{{ route('kepala.utilitas.index') }}" class="flex items-center gap-2">
                    <div class="relative">
                        <input type="month" name="bulan" value="{{ $periode }}" onchange="this.form.submit()" 
                               class="rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                    </div>
                    <button type="submit" class="px-3.5 py-2 bg-gray-900 hover:bg-black dark:bg-gray-700 dark:hover:bg-gray-600 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                        Pilih
                    </button>
                    @if($periode !== date('Y-m'))
                        <a href="{{ route('kepala.utilitas.index') }}" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-xl text-xs font-semibold transition" title="Kembali ke Bulan Berjalan">
                            Bulan Ini
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- 2. Summary KPI Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
            <!-- 1. Listrik Total -->
            <div class="p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono block">Konsumsi Listrik</span>
                    <div class="mt-2 flex items-baseline gap-1.5">
                        <span class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            {{ number_format($totalListrik, 2, ',', '.') }}
                        </span>
                        <span class="text-xs font-bold text-amber-600 dark:text-amber-400">kWh</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        MDP, SDP, AC, Lift, & Lampu
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>

            <!-- 2. Air Total -->
            <div class="p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono block">Konsumsi Air</span>
                    <div class="mt-2 flex items-baseline gap-1.5">
                        <span class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            {{ number_format($totalAir, 2, ',', '.') }}
                        </span>
                        <span class="text-xs font-bold text-sky-600 dark:text-sky-400">m³</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Air Bersih & Air Hujan
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"></path>
                    </svg>
                </div>
            </div>

            <!-- 3. Kelengkapan Kategori -->
            <div class="p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono block">Kelengkapan Data</span>
                    <div class="mt-2 flex items-baseline gap-1.5">
                        <span class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            {{ $kategoriTercatat }} / {{ $totalKategori }}
                        </span>
                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Kategori</span>
                    </div>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1 font-medium flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        {{ round(($kategoriTercatat / max(1, $totalKategori)) * 100) }}% Kategori Tercatat
                    </p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- 3. Visual 7 Categories Status Grid -->
        <div>
            <div class="flex items-center justify-between mb-3.5">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-gray-100">
                        Monitoring Kategori Utilitas ({{ $currentMonthFormatted }})
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Status pencatatan dan distribusi konsumsi energi per sektor fasilitas gedung.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-3 sm:gap-4">
                @foreach($categoriesMeta as $key => $meta)
                    @php
                        $recorded = $detailsByJenis->get($key);
                    @endphp
                    <div class="p-4 bg-white dark:bg-gray-800 rounded-2xl border transition-all duration-200 flex flex-col justify-between shadow-xs {{ $recorded ? 'border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-500' : 'border-dashed border-gray-300 dark:border-gray-700/80 bg-gray-50/50 dark:bg-gray-800/40 opacity-80' }}">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="w-2 h-2 rounded-full {{ $recorded ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                <span class="text-[10px] font-bold uppercase tracking-wider {{ $recorded ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400' }}">
                                    {{ $recorded ? 'Tercatat' : 'Belum Ada' }}
                                </span>
                            </div>
                            <h4 class="font-bold text-xs sm:text-sm text-gray-900 dark:text-gray-100 truncate">
                                {{ $meta['name'] }}
                            </h4>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500 truncate mt-0.5">
                                {{ $key }}
                            </p>
                        </div>

                        <div class="mt-3 pt-2.5 border-t border-gray-100 dark:border-gray-700/60">
                            @if($recorded)
                                <div class="font-mono font-extrabold text-sm sm:text-base text-gray-900 dark:text-white truncate">
                                    {{ number_format($recorded->total_konsumsi, 1, ',', '.') }}
                                    <span class="text-[11px] font-semibold text-gray-500">{{ $meta['unit'] }}</span>
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic">0.0 {{ $meta['unit'] }}</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 4. Detail Tabel Pemakaian Utilitas -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden transition-colors">
            <div class="p-5 sm:p-6 border-b border-gray-200/80 dark:border-gray-700/80 flex items-center justify-between gap-4">
                <div>
                    <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <span>Detail Pemakaian Periode: {{ $periode }}</span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300 font-semibold">
                            {{ $details->count() }} Data
                        </span>
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        Rekapitulasi resmi stand awal, stand akhir, dan kalkulasi konsumsi utilitas.
                    </p>
                </div>
                <div class="text-xs text-gray-400 dark:text-gray-500 hidden sm:block">
                    Format: <span class="font-mono font-semibold">m³ &bull; kWh</span>
                </div>
            </div>

            <!-- Mobile Cards (< md) -->
            <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700/80">
                @forelse($details as $item)
                @php
                    $isAir = str_contains($item->jenis_utilitas, 'Air');
                    $unit = $isAir ? 'm³' : 'kWh';
                    $meta = $categoriesMeta[$item->jenis_utilitas] ?? null;
                @endphp
                <div class="p-4 sm:p-5 space-y-3 hover:bg-gray-50/50 dark:hover:bg-gray-750/30 transition-colors">
                    <div class="flex items-center justify-between gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $meta['bg'] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $item->jenis_utilitas }}
                        </span>
                        <span class="text-xs font-mono font-bold text-gray-500 dark:text-gray-400">
                            {{ $item->periode }}
                        </span>
                    </div>

                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-gray-100 text-sm">
                            {{ $meta['name'] ?? $item->jenis_utilitas }}
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Petugas: <strong>{{ $item->petugas->nama_lengkap ?? $item->petugas->name ?? '-' }}</strong>
                        </p>
                    </div>

                    <div class="pt-2 border-t border-gray-100 dark:border-gray-700/80 flex items-center justify-between">
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            @if($item->detail && $item->detail->tgl_awal)
                                {{ \Carbon\Carbon::parse($item->detail->tgl_awal)->format('d/m') }} -
                                {{ \Carbon\Carbon::parse($item->detail->tgl_akhir)->format('d/m/Y') }}
                            @else
                                {{ $item->periode }}
                            @endif
                        </div>
                        <div class="text-right font-mono font-bold text-base text-indigo-600 dark:text-indigo-400">
                            {{ number_format($item->total_konsumsi, 2, ',', '.') }} {{ str_contains($item->jenis_utilitas, 'Air') ? 'm³' : 'kWh' }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center">
                    <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700/60 text-gray-400 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200">Belum ada data di periode ini.</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                        Petugas utilitas belum menginput pencatatan stand meter untuk bulan {{ $currentMonthFormatted }}.
                    </p>
                </div>
                @endforelse
            </div>

            <!-- Desktop Table (md+) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50/75 dark:bg-gray-750/60">
                        <tr>
                            <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tipe Utilitas
                            </th>
                            <th scope="col" class="px-3.5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Petugas Catat
                            </th>
                            <th scope="col" class="px-3.5 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Periode Cek
                            </th>
                            <th scope="col" class="py-3.5 pl-3 pr-6 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Konsumsi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 bg-white dark:bg-gray-800">
                        @forelse($details as $item)
                        @php
                            $meta = $categoriesMeta[$item->jenis_utilitas] ?? null;
                        @endphp
                        <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-750/50 transition-colors">
                            <!-- Tipe Utilitas -->
                            <td class="py-4 pl-6 pr-3 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold {{ $meta['bg'] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ $item->jenis_utilitas }}
                                    </span>
                                    <div>
                                        <div class="font-bold text-sm text-gray-900 dark:text-gray-100">
                                            {{ $meta['name'] ?? $item->jenis_utilitas }}
                                        </div>
                                        <div class="text-[11px] text-gray-400 dark:text-gray-500">
                                            {{ $meta['desc'] ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Petugas Catat -->
                            <td class="px-3.5 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                        {{ strtoupper(substr($item->petugas->nama_lengkap ?? $item->petugas->name ?? 'P', 0, 1)) }}
                                    </div>
                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                        {{ $item->petugas->nama_lengkap ?? $item->petugas->name ?? '-' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Periode Cek -->
                            <td class="px-3.5 py-4 whitespace-nowrap text-xs text-gray-600 dark:text-gray-400">
                                @if($item->detail && $item->detail->tgl_awal)
                                    <div class="font-medium text-gray-800 dark:text-gray-200">
                                        {{ \Carbon\Carbon::parse($item->detail->tgl_awal)->format('d/m') }} -
                                        {{ \Carbon\Carbon::parse($item->detail->tgl_akhir)->format('d/m/Y') }}
                                    </div>
                                    <div class="text-[11px] text-gray-400">
                                        {{ \Carbon\Carbon::parse($item->detail->tgl_awal)->diffInDays(\Carbon\Carbon::parse($item->detail->tgl_akhir)) + 1 }} Hari Operasional
                                    </div>
                                @else
                                    <span class="font-mono">{{ $item->periode }}</span>
                                @endif
                            </td>

                            <!-- Konsumsi -->
                            <td class="py-4 pl-3 pr-6 whitespace-nowrap text-right">
                                <div class="font-mono font-extrabold text-base text-indigo-600 dark:text-indigo-400">
                                    {{ number_format($item->total_konsumsi, 2, ',', '.') }} {{ str_contains($item->jenis_utilitas, 'Air') ? 'm³' : 'kWh' }}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-14 text-center">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700/60 text-gray-400 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200">Belum ada data di periode ini.</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                                    Belum terdapat pencatatan stand meter utilitas pada bulan {{ $currentMonthFormatted }}.
                                </p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>