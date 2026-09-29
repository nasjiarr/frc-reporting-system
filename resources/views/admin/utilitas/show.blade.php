<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <nav class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 mb-1.5">
                    <a href="{{ route('admin.utilitas.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Utilitas</a>
                    <span>/</span>
                    <span class="text-gray-800 dark:text-gray-200 font-semibold">{{ $jenis }}</span>
                </nav>
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 tracking-tight">Detail Utilitas: {{ $jenis }}</h2>
            </div>

            <div class="flex items-center flex-wrap gap-2.5">
                <form action="{{ route('admin.utilitas.show', $jenis) }}" method="GET" class="flex items-center gap-1.5 bg-white dark:bg-gray-800 p-1 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <input type="number" name="tahun" value="{{ $tahun }}" min="2020" max="2050"
                           class="rounded-lg border-0 py-1.5 px-3 text-xs font-semibold text-gray-800 dark:text-gray-100 dark:bg-gray-700 w-20 focus:ring-2 focus:ring-indigo-500">
                    <button type="submit" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-200 transition">
                        Filter
                    </button>
                </form>

                <a href="{{ route('admin.utilitas.export_pdf', ['jenis' => $jenis, 'tahun' => $tahun]) }}"
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-semibold text-xs shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Ekspor PDF</span>
                </a>

                <a href="{{ route('admin.utilitas.create', ['jenis' => $jenis]) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold text-xs shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Tambah Data</span>
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $unit = str_contains($jenis, 'Air') ? 'm³' : 'kWh';
    @endphp

    <div class="space-y-6">
        <!-- KPI Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Total Konsumsi {{ $tahun }}</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold font-mono text-indigo-600 dark:text-indigo-400">
                        {{ number_format($totalTahunIni, 2, ',', '.') }}
                    </span>
                    <span class="text-xs font-medium text-gray-500">{{ $unit }}</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Akumulasi seluruh bulan di tahun {{ $tahun }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Rata-rata Bulanan</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold font-mono text-gray-900 dark:text-white">
                        {{ number_format($rataRataBulanan, 2, ',', '.') }}
                    </span>
                    <span class="text-xs font-medium text-gray-500">{{ $unit }}</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Berdasarkan bulan yang telah tercatat</p>
            </div>

            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider font-mono">Puncak Konsumsi Bulanan</p>
                <div class="mt-2 flex items-baseline gap-2">
                    <span class="text-2xl font-bold font-mono text-rose-600 dark:text-rose-400">
                        {{ number_format($konsumsiTertinggi, 2, ',', '.') }}
                    </span>
                    <span class="text-xs font-medium text-gray-500">{{ $unit }}</span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Bulan dengan konsumsi tertinggi</p>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    Grafik Tren Konsumsi Bulanan ({{ $unit }})
                </h3>
                <span class="text-xs font-mono font-medium px-2.5 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-lg">
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
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Riwayat Pencatatan Stand Meter</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Daftar log meteran fisik yang telah diverifikasi</p>
                </div>
                <span class="text-xs font-mono text-gray-500">{{ $riwayat->count() }} data tercatat</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Periode</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tgl Cek</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rincian Stand Meter</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Petugas</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Konsumsi ({{ $unit }})</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($riwayat as $data)
                        @php
                            $det = $data->detail;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                            <td class="px-5 py-4 text-sm font-mono font-bold text-gray-900 dark:text-gray-100">
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
                            <td class="px-5 py-4 text-xs text-gray-600 dark:text-gray-400">
                                {{ $data->petugas->nama_lengkap ?? '-' }}
                            </td>
                            <td class="px-5 py-4 text-right text-sm font-bold font-mono text-indigo-600 dark:text-indigo-400 whitespace-nowrap">
                                {{ number_format($data->total_konsumsi, 2, ',', '.') }} {{ $unit }}
                            </td>
                            <td class="px-5 py-4 text-right text-xs font-semibold whitespace-nowrap">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.utilitas.edit', $data->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition">
                                        Edit
                                    </a>
                                    <span class="text-gray-300 dark:text-gray-700">|</span>
                                    <form action="{{ route('admin.utilitas.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data periode {{ $data->periode }} ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-rose-600 dark:text-rose-400 hover:text-rose-900 dark:hover:text-rose-300 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">
                                Belum ada data pencatatan utilitas untuk tahun {{ $tahun }}.
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
                            borderRadius: 6
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
