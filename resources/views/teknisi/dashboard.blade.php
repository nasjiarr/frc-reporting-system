<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Teknisi</h2>
    </x-slot>

    <div class="mb-6">
        <h3 class="text-lg font-bold text-gray-700 mb-4">Tugas Aktif Anda</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($tugasAktif as $tugas)
            <div class="bg-white p-5 rounded-lg shadow-sm border-l-4 border-indigo-500">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="text-xs font-bold uppercase text-indigo-600">{{ $tugas->laporan->lokasi }}</span>
                        <h4 class="text-lg font-bold mt-1">{{ $tugas->laporan->judul }}</h4>
                        <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $tugas->instruksi }}</p>
                    </div>
                    <span class="bg-blue-100 text-blue-800 text-[10px] px-2 py-1 rounded font-bold uppercase">
                        {{ $tugas->status_tugas }}
                    </span>
                </div>
                <div class="mt-4 flex justify-end">
                    <a href="{{ route('teknisi.tugas.show', $tugas->id) }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800">
                        Kerjakan & Lapor →
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-2 bg-gray-50 p-10 text-center rounded-lg border-2 border-dashed">
                <p class="text-gray-500 italic">Tidak ada tugas aktif saat ini. Kerja bagus!</p>
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>