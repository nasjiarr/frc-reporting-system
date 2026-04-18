<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('teknisi.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">Detail Penugasan</h2>
                <p class="text-sm text-gray-600 mt-1">Laporan: {{ $penugasan->laporan->judul }}</p>
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-bold text-gray-900 border-b pb-2 w-full">Deskripsi Laporan Kerusakan</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Lokasi Kejadian</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $penugasan->laporan->lokasi }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Waktu Dilaporkan</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $penugasan->laporan->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-2">Detail Kerusakan</p>
                    <div class="p-4 bg-gray-50 rounded-lg text-sm text-gray-800 leading-relaxed border border-gray-100">
                        {{ $penugasan->laporan->deskripsi }}
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h3 class="text-base font-bold text-gray-900 mb-4">Status Tugas</h3>

                <div class="mb-6 flex items-center gap-3">
                    <span class="relative flex h-4 w-4">
                        @if($penugasan->status_tugas == 'Ditugaskan')
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-blue-500"></span>
                        @elseif($penugasan->status_tugas == 'Dikerjakan')
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-amber-500"></span>
                        @else
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-emerald-500"></span>
                        @endif
                    </span>
                    <span class="font-bold text-gray-800 text-lg">{{ $penugasan->status_tugas }}</span>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <p class="text-xs text-gray-500 mb-2">Pemberi Tugas (Admin)</p>
                    <p class="text-sm font-medium text-gray-900">{{ $penugasan->admin->nama_lengkap ?? 'Administrator' }}</p>
                    <p class="text-xs text-gray-400 mt-1">Ditugaskan pada: {{ \Carbon\Carbon::parse($penugasan->assigned_at)->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>