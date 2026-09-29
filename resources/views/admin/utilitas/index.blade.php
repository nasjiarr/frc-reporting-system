<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 tracking-tight">Manajemen Utilitas Gedung</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Monitoring konsumsi energi dan air, pencatatan stand meter, dan rekapitulasi data FRC UGM.
                </p>
            </div>
            <div class="flex gap-2.5">
                <a href="{{ route('admin.utilitas.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow transition-all duration-150">
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
            'blue'    => ['bg' => 'bg-blue-500/10 dark:bg-blue-500/20', 'text' => 'text-blue-600 dark:text-blue-400', 'border' => 'hover:border-blue-400 dark:hover:border-blue-500', 'btn' => 'hover:bg-blue-50 dark:hover:bg-blue-950/40 text-blue-600 dark:text-blue-400'],
            'cyan'    => ['bg' => 'bg-cyan-500/10 dark:bg-cyan-500/20', 'text' => 'text-cyan-600 dark:text-cyan-400', 'border' => 'hover:border-cyan-400 dark:hover:border-cyan-500', 'btn' => 'hover:bg-cyan-50 dark:hover:bg-cyan-950/40 text-cyan-600 dark:text-cyan-400'],
            'amber'   => ['bg' => 'bg-amber-500/10 dark:bg-amber-500/20', 'text' => 'text-amber-600 dark:text-amber-400', 'border' => 'hover:border-amber-400 dark:hover:border-amber-500', 'btn' => 'hover:bg-amber-50 dark:hover:bg-amber-950/40 text-amber-600 dark:text-amber-400'],
            'yellow'  => ['bg' => 'bg-yellow-500/10 dark:bg-yellow-500/20', 'text' => 'text-yellow-600 dark:text-yellow-400', 'border' => 'hover:border-yellow-400 dark:hover:border-yellow-500', 'btn' => 'hover:bg-yellow-50 dark:hover:bg-yellow-950/40 text-yellow-600 dark:text-yellow-400'],
            'orange'  => ['bg' => 'bg-orange-500/10 dark:bg-orange-500/20', 'text' => 'text-orange-600 dark:text-orange-400', 'border' => 'hover:border-orange-400 dark:hover:border-orange-500', 'btn' => 'hover:bg-orange-50 dark:hover:bg-orange-950/40 text-orange-600 dark:text-orange-400'],
            'indigo'  => ['bg' => 'bg-indigo-500/10 dark:bg-indigo-500/20', 'text' => 'text-indigo-600 dark:text-indigo-400', 'border' => 'hover:border-indigo-400 dark:hover:border-indigo-500', 'btn' => 'hover:bg-indigo-50 dark:hover:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400'],
            'emerald' => ['bg' => 'bg-emerald-500/10 dark:bg-emerald-500/20', 'text' => 'text-emerald-600 dark:text-emerald-400', 'border' => 'hover:border-emerald-400 dark:hover:border-emerald-500', 'btn' => 'hover:bg-emerald-50 dark:hover:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400'],
        ];
    @endphp

    <div class="space-y-6">
        <!-- Summary KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Listrik Utama (MDP) -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Listrik MDP Terakhir</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($totalListrikBulanIni, 2, ',', '.') }}
                        </span>
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">kWh</span>
                    </div>
                    <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-1 font-medium">
                        Periode: {{ $periodeTerbaru ?? 'Belum ada data' }}
                    </p>
                </div>
                <div class="p-3 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>

            <!-- Konsumsi Air (Bersih + Hujan) -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Konsumsi Air Terakhir</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($totalAirBulanIni, 2, ',', '.') }}
                        </span>
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">m³</span>
                    </div>
                    <p class="text-xs text-sky-600 dark:text-sky-400 mt-1 font-medium">
                        Air Bersih &amp; Air Hujan
                    </p>
                </div>
                <div class="p-3 bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z" />
                    </svg>
                </div>
            </div>

            <!-- Status Monitoring -->
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Cakupan Kategori</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">7 / 7</span>
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400">Kategori Aktif</span>
                    </div>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1 font-medium flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Sistem Siap Catat &amp; Ekspor PDF
                    </p>
                </div>
                <div class="p-3 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- 7 Category Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($jenisUtilitas as $item)
            @php
                $style = $colorMap[$item['warna']] ?? $colorMap['indigo'];
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md {{ $style['border'] }} transition-all flex flex-col justify-between overflow-hidden">
                <!-- Top Header & Icon -->
                <div class="p-6 pb-4">
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="p-3 rounded-xl {{ $style['bg'] }} {{ $style['text'] }}">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @switch($item['icon'])
                                @case('droplet')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"></path>
                                @break
                                @case('cloud-rain')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 14v6"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v6"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16v6"></path>
                                @break
                                @case('arrow-up-down')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v18"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16 17-4 4-4-4"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 7 4-4 4 4"></path>
                                @break
                                @case('wind')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.7 7.7a2.5 2.5 0 1 1 1.8 4.3H2"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.6 4.6A2 2 0 1 1 11 8H2"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.6 19.4A2 2 0 1 0 14 16H2"></path>
                                @break
                                @case('lightbulb')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.9 1.2 1.5 1.5 2.5"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 18h6"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 22h4"></path>
                                @break
                                @default
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                @endswitch
                            </svg>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[11px] font-mono font-semibold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            {{ $item['unit'] }}
                        </span>
                    </div>

                    <h3 class="font-bold text-gray-900 dark:text-gray-100 text-base leading-tight">
                        {{ $item['nama'] }}
                    </h3>

                    <!-- Latest Reading Display -->
                    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700/60">
                        <p class="text-[11px] font-medium text-gray-400 dark:text-gray-500 uppercase tracking-wider">Pemakaian Terakhir</p>
                        @if($item['latest_konsumsi'] !== null)
                            <div class="mt-1 flex items-baseline justify-between">
                                <span class="text-xl font-bold font-mono text-gray-900 dark:text-gray-100">
                                    {{ number_format($item['latest_konsumsi'], 2, ',', '.') }}
                                    <span class="text-xs font-sans text-gray-500">{{ $item['unit'] }}</span>
                                </span>

                                @if($item['tren'] === 'up')
                                    <span class="inline-flex items-center gap-0.5 text-xs font-semibold text-rose-600 dark:text-rose-400" title="Naik dibanding bulan sebelumnya">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                                        {{ $item['tren_persen'] }}%
                                    </span>
                                @elseif($item['tren'] === 'down')
                                    <span class="inline-flex items-center gap-0.5 text-xs font-semibold text-emerald-600 dark:text-emerald-400" title="Turun dibanding bulan sebelumnya (lebih hemat)">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                        {{ $item['tren_persen'] }}%
                                    </span>
                                @elseif($item['tren'] === 'same')
                                    <span class="text-xs font-semibold text-gray-500">0%</span>
                                @endif
                            </div>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                                Periode: <span class="font-mono font-medium text-gray-700 dark:text-gray-300">{{ $item['latest_periode'] }}</span>
                            </p>
                        @else
                            <p class="text-sm italic text-gray-400 mt-2">Belum ada pencatatan</p>
                        @endif
                    </div>
                </div>

                <!-- Footer Quick Actions -->
                <div class="px-5 py-3 bg-gray-50 dark:bg-gray-900/40 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs font-semibold">
                    <a href="{{ route('admin.utilitas.create', ['jenis' => $item['slug']]) }}"
                       class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 flex items-center gap-1 transition-colors"
                       title="Catat stand meter baru untuk {{ $item['nama'] }}">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Catat Data</span>
                    </a>

                    <a href="{{ route('admin.utilitas.show', $item['slug']) }}"
                       class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 flex items-center gap-1 transition-colors">
                        <span>Lihat Riwayat</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
