<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <nav class="flex text-sm text-gray-500 mb-1">
                    <a href="{{ route('admin.utilitas.index') }}" class="hover:text-indigo-600">Utilitas</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-800 font-medium">{{ $jenis }}</span>
                </nav>
                <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">Detail Utilitas: {{ $jenis }}</h2>
            </div>

            <div class="flex gap-2">
                <form action="{{ route('admin.utilitas.show', $jenis) }}" method="GET" class="flex gap-2">
                    <input type="number" name="tahun" value="{{ $tahun }}" class="rounded-md border-gray-300 text-sm w-24">
                    <button type="submit" class="bg-gray-100 px-3 py-2 rounded-md text-sm font-bold border hover:bg-gray-200">Filter</button>
                </form>
                <a href="{{ route('admin.utilitas.export_pdf', ['jenis' => $jenis, 'tahun' => $tahun]) }}" class="bg-rose-600 text-white px-3 py-2 rounded-md font-bold text-xs uppercase hover:bg-rose-700 transition flex items-center gap-1 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    PDF
                </a>
                <a href="{{ route('admin.utilitas.create', ['jenis' => $jenis]) }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md font-bold text-xs uppercase hover:bg-indigo-700 transition">
                    + Tambah Data
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">Grafik Konsumsi Bulanan</h3>
            <div class="relative w-full" style="height: 350px;">
                <canvas id="chartUtilitas"
                    data-labels="{{ json_encode($labels) }}"
                    data-konsumsi="{{ json_encode($consumptions) }}">
                </canvas>
            </div>
        </div>

        <div class="bg-white overflow-hidden border border-gray-200 rounded-xl shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-base font-bold text-gray-800">Riwayat Pencatatan {{ $tahun }}</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Petugas</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Konsumsi</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($riwayat as $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-mono font-bold">{{ $data->periode }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $data->petugas->nama_lengkap ?? '-' }}</td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-indigo-700">
                            {{ number_format($data->total_konsumsi, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.utilitas.edit', $data->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form action="{{ route('admin.utilitas.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-rose-600 hover:text-rose-900">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">Belum ada data untuk tahun {{ $tahun }}.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvasElement = document.getElementById('chartUtilitas');

            if (canvasElement) {
                const ctx = canvasElement.getContext('2d');

                // Ambil data dengan aman dari HTML dan ubah kembali menjadi array Javascript
                const labelBulan = JSON.parse(canvasElement.getAttribute('data-labels'));
                const dataKonsumsi = JSON.parse(canvasElement.getAttribute('data-konsumsi'));

                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labelBulan,
                        datasets: [{
                            label: 'Total Konsumsi',
                            data: dataKonsumsi,
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: '#4f46e5',
                            pointRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
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