<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Teknisi</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-indigo-500">
                    <h3 class="text-gray-500 text-sm font-semibold uppercase">Tugas Aktif</h3>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $jumlahTugasAktif }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <h3 class="text-gray-500 text-sm font-semibold uppercase">Tugas Selesai</h3>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $jumlahTugasSelesai }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-700 mb-4">Tugas Aktif Saat Ini</h3>
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm border-b">
                                <th class="py-3 px-4 font-semibold">No</th>
                                <th class="py-3 px-4 font-semibold">Judul Laporan</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                                <th class="py-3 px-4 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($tugasAktif as $index => $tugas)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 whitespace-normal min-w-[200px]">{{ $tugas->laporan->judul }}</td>
                                <td class="py-3 px-4">
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded font-bold uppercase">
                                        {{ $tugas->status_tugas }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <a href="{{ route('teknisi.tugas.show', $tugas->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-1 px-3 rounded text-xs transition inline-block">
                                        Kerjakan
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-4 px-4 text-center text-gray-500 italic">Tidak ada tugas aktif saat ini. Kerja bagus!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>