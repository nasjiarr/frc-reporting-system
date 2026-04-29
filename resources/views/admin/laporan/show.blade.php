<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Laporan: {{ $laporan->judul }}</h2>

            @if($laporan->status === 'Selesai' && $laporan->penugasan && $laporan->penugasan->hasilPerbaikan)
            <a href="{{ route('admin.laporan.export_pdf', $laporan->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-rose-700 focus:bg-rose-700 active:bg-rose-900 shadow-sm transition ease-in-out duration-150">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Ekspor PDF
            </a>
            @endif
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-card>
            <h3 class="text-lg font-bold border-b pb-2 mb-4">Informasi Laporan</h3>
            <table class="w-full text-sm">
                <tbody>
                    <tr class="border-b">
                        <td class="py-2 text-gray-500 w-1/3">Tanggal Lapor</td>
                        <td class="py-2 font-medium">{{ $laporan->created_at->format('d F Y H:i') }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 text-gray-500">Status Saat Ini</td>
                        <td class="py-2 font-medium">{{ $laporan->status }}</td>
                    </tr>
                    <tr class="border-b">
                        <td class="py-2 text-gray-500">Lokasi</td>
                        <td class="py-2 font-medium">{{ $laporan->lokasi }}</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-500 align-top">Deskripsi</td>
                        <td class="py-2 font-medium whitespace-pre-line">{{ $laporan->deskripsi }}</td>
                    </tr>
                </tbody>
            </table>
        </x-card>

        <x-card>
            <h3 class="text-lg font-bold border-b pb-2 mb-4">Riwayat Penanganan (Timeline)</h3>

            <div class="relative border-l border-gray-200 ml-3">
                <div class="mb-8 ml-6">
                    <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white">
                        <svg class="w-3 h-3 text-blue-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                        </svg>
                    </span>
                    <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-900">Laporan Diterima Sistem</h3>
                    <time class="block mb-2 text-xs font-normal leading-none text-gray-400">{{ $laporan->created_at->format('d M Y, H:i') }}</time>
                </div>

                @if($laporan->penugasan)
                <div class="mb-8 ml-6">
                    <span class="absolute flex items-center justify-center w-6 h-6 bg-yellow-100 rounded-full -left-3 ring-8 ring-white">
                        <svg class="w-3 h-3 text-yellow-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m18.774 8.245-.892-.893a1.5 1.5 0 0 1-.437-1.052V5.036a2.484 2.484 0 0 0-2.48-2.48H13.7a1.5 1.5 0 0 1-1.052-.438l-.893-.892a2.484 2.484 0 0 0-3.51 0l-.893.892a1.5 1.5 0 0 1-1.052.437H5.036a2.484 2.484 0 0 0-2.48 2.481V6.3a1.5 1.5 0 0 1-.438 1.052l-.892.893a2.484 2.484 0 0 0 0 3.511l.892.893a1.5 1.5 0 0 1 .437 1.052v1.264a2.484 2.484 0 0 0 2.481 2.481H6.3a1.5 1.5 0 0 1 1.052.437l.893.892a2.484 2.484 0 0 0 3.511 0l.893-.892a1.5 1.5 0 0 1 1.052-.437h1.264a2.484 2.484 0 0 0 2.481-2.48V13.7a1.5 1.5 0 0 1 .437-1.052l.892-.893a2.484 2.484 0 0 0 0-3.511Z" />
                        </svg>
                    </span>
                    <h3 class="mb-1 text-sm font-semibold text-gray-900">Ditugaskan ke Teknisi: {{ $laporan->penugasan->teknisi->nama_lengkap }}</h3>
                    <time class="block mb-2 text-xs font-normal leading-none text-gray-400">{{ $laporan->penugasan->assigned_at->format('d M Y, H:i') }}</time>
                    <p class="text-sm text-gray-500">Status Tugas: {{ $laporan->penugasan->status_tugas }}</p>
                </div>
                @endif

                @if($laporan->penugasan && $laporan->penugasan->hasilPerbaikan)
                <div class="ml-6">
                    <span class="absolute flex items-center justify-center w-6 h-6 bg-green-100 rounded-full -left-3 ring-8 ring-white">
                        <svg class="w-3 h-3 text-green-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5" />
                        </svg>
                    </span>
                    <h3 class="mb-1 text-sm font-semibold text-gray-900">Perbaikan Selesai</h3>
                    <time class="block mb-2 text-xs font-normal leading-none text-gray-400">{{ $laporan->penugasan->hasilPerbaikan->selesai_pada->format('d M Y, H:i') }}</time>
                    <p class="text-sm text-gray-500">Tindakan: {{ $laporan->penugasan->hasilPerbaikan->tindakan }}</p>
                </div>
                @endif
            </div>

            @if(!$laporan->penugasan)
            <p class="text-sm text-gray-500 italic mt-4">Belum ada penugasan teknisi untuk laporan ini.</p>
            @endif

            @if($laporan->penugasan && $laporan->penugasan->hasilPerbaikan)
            <div class="mt-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Dokumentasi Perbaikan
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="group relative bg-slate-100 rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                        <div class="absolute top-2 left-2 z-10">
                            <span class="px-3 py-1 bg-rose-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-full shadow-lg">Kondisi Sebelum</span>
                        </div>

                        @if($laporan->foto_sebelum)
                        <img src="{{ asset('storage/' . $laporan->foto_sebelum) }}"
                            class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105 cursor-zoom-in"
                            onclick="window.open(this.src, '_blank')">
                        @else
                        <div class="w-full h-64 flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-xs">Tidak ada foto sebelum</p>
                        </div>
                        @endif
                    </div>

                    <div class="group relative bg-slate-100 rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                        <div class="absolute top-2 left-2 z-10">
                            <span class="px-3 py-1 bg-emerald-600 text-white text-[10px] font-bold uppercase tracking-widest rounded-full shadow-lg">Kondisi Sesudah</span>
                        </div>
                        @if($laporan->penugasan->hasilPerbaikan->foto_sesudah)
                        <img src="{{ asset('storage/' . $laporan->penugasan->hasilPerbaikan->foto_sesudah) }}"
                            class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-105 cursor-zoom-in"
                            onclick="window.open(this.src, '_blank')">
                        @else
                        <div class="w-full h-64 flex flex-col items-center justify-center text-gray-400">
                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="text-xs">Tidak ada foto sesudah</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mt-4 p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Tindakan Teknisi</p>
                            <p class="text-sm text-gray-800 leading-relaxed">{{ $laporan->penugasan->hasilPerbaikan->tindakan }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Material Digunakan</p>
                            <p class="text-sm text-gray-800">{{ $laporan->penugasan->hasilPerbaikan->material_digunakan ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </x-card>
    </div>
</x-app-layout>