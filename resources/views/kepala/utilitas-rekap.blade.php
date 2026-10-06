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

                <a href="{{ route('kepala.utilitas.export', ['bulan' => $periode]) }}" class="px-4 py-2 bg-red-600 text-white rounded-lg font-bold hover:bg-red-700 transition flex items-center gap-2 text-sm shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Ekspor PDF</span>
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
                            <td class="p-3 text-sm">{{ $item->petugas->nama_lengkap ?? '-' }}</td>
                            <td class="p-3 text-xs text-gray-500">
                                @if($item->detail && $item->detail->tgl_awal)
                                    {{ \Carbon\Carbon::parse($item->detail->tgl_awal)->format('d/m') }} -
                                    {{ \Carbon\Carbon::parse($item->detail->tgl_akhir)->format('d/m/Y') }}
                                @else
                                    {{ $item->periode }}
                                @endif
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-indigo-600">
                                {{ number_format($item->total_konsumsi, 2, ',', '.') }} {{ str_contains($item->jenis_utilitas, 'Air') ? 'm³' : 'kWh' }}
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