<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">Statistik Konsumsi: {{ $jenis }}</h2>
                <p class="text-sm text-gray-600 mt-1">Laporan penggunaan tahun {{ $tahun }}</p>
            </div>
            <form action="{{ route('admin.utilitas.detail', $jenis) }}" method="GET" class="flex gap-2">
                <input type="number" name="tahun" value="{{ $tahun }}" class="rounded-md border-gray-300 text-sm w-24">
                <button type="submit" class="bg-gray-800 text-white px-3 py-1 rounded text-sm font-bold">Filter</button>
            </form>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">Grafik Konsumsi Bulanan ({{ str_contains($jenis, 'Air') ? 'm³' : 'kWh' }})</h3>
            <div class="h-80">
                <canvas id="consumptionChart"></canvas>
            </div>
        </div>

        <div class="bg-white overflow-hidden border border-gray-200 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-base font-bold text-gray-800">Detail Rincian Periode</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Petugas</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Konsumsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($dataUtilitas->sortBy('periode') as $data)
                    <tr>
                        <td class="px-6 py-4 text-sm font-mono">{{ $data->periode }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $data->petugas->nama_lengkap ?? '-' }}</td>
                        <td class="px-6 py-4 text-right text-sm font-bold">
                            {{ number_format($data->total_konsumsi, 2, ',', '.') }} {{ str_contains($jenis, 'Air') ? 'm³' : 'kWh' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">Data tidak ditemukan untuk tahun ini.</td>
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