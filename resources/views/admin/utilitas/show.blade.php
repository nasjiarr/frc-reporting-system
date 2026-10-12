<x-app-layout>
    {{-- Header Slot: Breadcrumbs, Title, Subtitle, & Actions --}}
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
            <a href="{{ route('admin.utilitas.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors inline-flex items-center gap-1.5 font-medium flex-shrink-0">
                Utilitas
            </a>
            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-800 dark:text-gray-200 font-semibold flex-shrink-0" aria-current="page">
                {{ $jenis }}
            </span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                    Detail Utilitas: {{ $jenis }}
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Analisis grafik tren bulanan dan riwayat pencatatan stand meteran gedung FRC UGM.
                </p>
            </div>

            <div class="flex items-center flex-wrap gap-2.5 sm:gap-3 flex-shrink-0 self-start sm:self-center">
                <!-- Year Filter Form -->
                <form action="{{ route('admin.utilitas.show', $jenis) }}" method="GET" class="flex items-center gap-1.5 bg-white dark:bg-gray-800 p-1 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <input type="number" name="tahun" value="{{ $tahun }}" min="2020" max="2050"
                           class="rounded-lg border-0 py-1.5 px-3 text-xs font-semibold text-gray-800 dark:text-gray-100 dark:bg-gray-700 w-20 focus:ring-2 focus:ring-indigo-500">
                    <button type="submit" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-200 transition">
                        Filter
                    </button>
                </form>

                <!-- Export PDF -->
                <a href="{{ route('admin.utilitas.export_pdf', ['jenis' => $jenis, 'tahun' => $tahun]) }}"
                   class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white rounded-xl font-semibold text-xs shadow-sm hover:shadow transition-all duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Ekspor PDF</span>
                </a>

                <!-- Add Record -->
                <a href="{{ route('admin.utilitas.create', ['jenis' => $jenis]) }}"
                   class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl font-semibold text-xs shadow-sm hover:shadow transition-all duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Tambah Data</span>
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $unit = str_contains($jenis, 'Air') ? 'm³' : 'kWh';
    @endphp

    <div class="space-y-6 pb-12">
        <!-- KPI Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Total Konsumsi</span>
                    <span class="text-2xs font-bold font-mono px-2 py-0.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300">
                        Tahun {{ $tahun }}
                    </span>
                </div>
                <div class="mt-2.5 flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-extrabold font-mono text-indigo-600 dark:text-indigo-400">
                        {{ number_format($totalTahunIni, 2, ',', '.') }}
                    </span>
                    <span class="text-xs font-semibold text-gray-500">{{ $unit }}</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Akumulasi seluruh bulan di tahun {{ $tahun }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Rata-rata Bulanan</span>
                    <span class="text-2xs font-bold font-mono px-2 py-0.5 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                        Rerata
                    </span>
                </div>
                <div class="mt-2.5 flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-extrabold font-mono text-gray-900 dark:text-white">
                        {{ number_format($rataRataBulanan, 2, ',', '.') }}
                    </span>
                    <span class="text-xs font-semibold text-gray-500">{{ $unit }}</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Berdasarkan bulan yang telah tercatat</p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Puncak Konsumsi Bulanan</span>
                    <span class="text-2xs font-bold font-mono px-2 py-0.5 rounded-lg bg-rose-50 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300">
                        Tertinggi
                    </span>
                </div>
                <div class="mt-2.5 flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-extrabold font-mono text-rose-600 dark:text-rose-400">
                        {{ number_format($konsumsiTertinggi, 2, ',', '.') }}
                    </span>
                    <span class="text-xs font-semibold text-gray-500">{{ $unit }}</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Bulan dengan konsumsi tertinggi</p>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">
                        Grafik Tren Konsumsi Bulanan ({{ $unit }})
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Pemantauan visual fluktuasi konsumsi sepanjang tahun {{ $tahun }}</p>
                </div>
                <span class="text-xs font-mono font-bold px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl border border-gray-200/80 dark:border-gray-600">
                    Tahun {{ $tahun }}
                </span>
            </div>
            <div class="relative w-full" style="height: 320px;">
                <canvas id="chartUtilitas"
                    data-labels="{{ json_encode($labels) }}"
                    data-konsumsi="{{ json_encode($consumptions) }}">
                </canvas>
            </div>
        </div>

        <!-- History Records Table -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/75 dark:bg-gray-900/50 flex justify-between items-center">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Riwayat Pencatatan Stand Meter</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Daftar log meteran fisik yang telah diverifikasi</p>
                </div>
                <span class="text-xs font-mono font-semibold px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                    {{ $riwayat->count() }} data tercatat
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-5 py-3.5 text-left text-2xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Periode</th>
                            <th class="px-5 py-3.5 text-left text-2xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Tgl Cek</th>
                            <th class="px-5 py-3.5 text-left text-2xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Rincian Stand Meter</th>
                            <th class="px-5 py-3.5 text-left text-2xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Petugas</th>
                            <th class="px-5 py-3.5 text-right text-2xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Konsumsi ({{ $unit }})</th>
                            <th class="px-5 py-3.5 text-right text-2xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($riwayat as $data)
                        @php
                            $det = $data->detail;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                            <td class="px-5 py-4 text-xs font-mono font-bold text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                {{ $data->periode }}
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                @if($det && $det->tgl_awal)
                                    {{ \Carbon\Carbon::parse($det->tgl_awal)->format('d/m') }} - {{ \Carbon\Carbon::parse($det->tgl_akhir)->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs font-mono text-gray-600 dark:text-gray-300">
                                @if($det)
                                    @if(in_array($jenis, ['AirBersih', 'AirHujan', 'MDP']))
                                        <span>{{ number_format($det->stand_awal, 2, ',', '.') }} &rarr; {{ number_format($det->stand_akhir, 2, ',', '.') }}</span>
                                    @elseif($jenis === 'SDP')
                                        <div class="space-y-0.5">
                                            <div>SDP1: {{ number_format($det->stand_awal_sdp1, 2, ',', '.') }} &rarr; {{ number_format($det->stand_akhir_sdp1, 2, ',', '.') }}</div>
                                            <div>SDP2: {{ number_format($det->stand_awal_sdp2, 2, ',', '.') }} &rarr; {{ number_format($det->stand_akhir_sdp2, 2, ',', '.') }}</div>
                                        </div>
                                    @elseif($jenis === 'Lift')
                                        <div class="space-y-0.5">
                                            <div>G: {{ number_format($det->stand_awal_g, 2, ',', '.') }} &rarr; {{ number_format($det->stand_akhir_g, 2, ',', '.') }}</div>
                                            <div>G2: {{ number_format($det->stand_awal_g2, 2, ',', '.') }} &rarr; {{ number_format($det->stand_akhir_g2, 2, ',', '.') }}</div>
                                        </div>
                                    @elseif(in_array($jenis, ['AC', 'Lampu']))
                                        <div class="space-y-0.5">
                                            <div>L1: {{ number_format($det->stand_awal_l1, 2, ',', '.') }} &rarr; {{ number_format($det->stand_akhir_l1, 2, ',', '.') }}</div>
                                            <div>L2: {{ number_format($det->stand_awal_l2, 2, ',', '.') }} &rarr; {{ number_format($det->stand_akhir_l2, 2, ',', '.') }}</div>
                                            <div>L3: {{ number_format($det->stand_awal_l3, 2, ',', '.') }} &rarr; {{ number_format($det->stand_akhir_l3, 2, ',', '.') }}</div>
                                        </div>
                                    @endif
                                @else
                                    <span class="text-gray-400 italic">Data stand tidak tersedia</span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-xs text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-2xs font-bold text-gray-700 dark:text-gray-300 uppercase">
                                        {{ substr($data->petugas->nama_lengkap ?? $data->petugas->name ?? 'A', 0, 1) }}
                                    </div>
                                    <span>{{ $data->petugas->nama_lengkap ?? $data->petugas->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right text-xs font-bold font-mono text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                {{ number_format($data->total_konsumsi, 2, ',', '.') }} {{ $unit }}
                            </td>
                            <td class="px-5 py-4 text-right text-xs font-semibold whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.utilitas.edit', $data->id) }}" class="p-1 rounded-lg text-gray-500 hover:text-amber-600 dark:hover:text-amber-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Edit Data">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.utilitas.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data periode {{ $data->periode }} ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded-lg text-gray-500 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Hapus Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">Belum ada data pencatatan</p>
                                <p class="text-xs text-gray-400 mt-1">Tidak ditemukan riwayat utilitas {{ $jenis }} untuk tahun {{ $tahun }}.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvasElement = document.getElementById('chartUtilitas');

            if (canvasElement) {
                const ctx = canvasElement.getContext('2d');
                const labelBulan = JSON.parse(canvasElement.getAttribute('data-labels'));
                const dataKonsumsi = JSON.parse(canvasElement.getAttribute('data-konsumsi'));

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labelBulan,
                        datasets: [{
                            label: "Total Konsumsi ({{ $unit }})",
                            data: dataKonsumsi,
                            backgroundColor: 'rgba(99, 102, 241, 0.75)',
                            hoverBackgroundColor: 'rgba(79, 70, 229, 0.95)',
                            borderColor: 'rgb(79, 70, 229)',
                            borderWidth: 1.5,
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(156, 163, 175, 0.15)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
