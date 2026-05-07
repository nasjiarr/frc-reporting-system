<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 tracking-tight">Statistik Konsumsi: {{ $jenis }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Laporan penggunaan tahun {{ $tahun }}</p>
            </div>
            <form action="{{ route('admin.utilitas.detail', $jenis) }}" method="GET" class="flex gap-2">
                <input type="number" name="tahun" value="{{ $tahun }}" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 text-sm w-24">
                <button type="submit" class="bg-gray-800 dark:bg-gray-700 text-white px-3 py-1 rounded text-sm font-bold">Filter</button>
            </form>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4 text-center">Grafik Konsumsi Bulanan ({{ str_contains($jenis, 'Air') ? 'm³' : 'kWh' }})</h3>
            <div class="h-80">
                <canvas id="consumptionChart"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <h3 class="text-base font-bold text-gray-800 dark:text-gray-100">Detail Rincian Periode</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Petugas</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total Konsumsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($dataUtilitas->sortBy('periode') as $data)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 text-sm font-mono text-gray-900 dark:text-gray-100">{{ $data->periode }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $data->petugas->nama_lengkap ?? '-' }}</td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-gray-100">
                            {{ number_format($data->total_konsumsi, 2, ',', '.') }} {{ str_contains($jenis, 'Air') ? 'm³' : 'kWh' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Data tidak ditemukan untuk tahun ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('consumptionChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {
                    !!json_encode($labels) !!
                },
                datasets: [{
                    label: 'Konsumsi',
                    data: {
                        !!json_encode($consumptions) !!
                    },
                    backgroundColor: 'rgba(79, 70, 229, 0.6)',
                    borderColor: 'rgb(79, 70, 229)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</x-app-layout>