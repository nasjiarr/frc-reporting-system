<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rekapitulasi Utilitas</h2>
    </x-slot>

    <div class="space-y-6">
        <x-card>
            <div class="flex flex-col md:flex-row justify-between items-end gap-4">
                <form method="GET" class="flex items-end gap-3 w-full md:w-auto">
                    <div>
                        <x-input-label value="Pilih Bulan Laporan" />
                        <x-text-input type="month" name="bulan" value="{{ $periode }}" onchange="this.form.submit()" />
                    </div>
                </form>

                <a href="{{ route('kepala.utilitas.index') }}/export?bulan={{ $periode }}" class="px-4 py-2 bg-red-600 text-white rounded font-bold hover:bg-red-700 transition flex items-center gap-2">
                    <span>📄</span> Ekspor PDF
                </a>
            </div>
        </x-card>

        <x-card>
            <h3 class="font-bold mb-4">Detail Pemakaian Periode: {{ $periode }}</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="p-3">Tipe Utilitas</th>
                            <th class="p-3">Petugas Catat</th>
                            <th class="p-3">Periode Cek</th>
                            <th class="p-3 text-right">Konsumsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($details as $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3 font-bold">{{ $item->jenis_utilitas }}</td>
                            <td class="p-3 text-sm">{{ $item->petugas->nama_lengkap }}</td>
                            <td class="p-3 text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($item->detail->tgl_awal)->format('d/m') }} -
                                {{ \Carbon\Carbon::parse($item->detail->tgl_akhir)->format('d/m/Y') }}
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-indigo-600">
                                {{ $item->detail->konsumsi ?? $item->detail->konsumsi_total ?? 'N/A' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-gray-400 italic">Belum ada data di periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</x-app-layout>