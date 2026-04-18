<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Kinerja Teknisi</h2>
    </x-slot>

    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="p-4">Nama Teknisi</th>
                        <th class="p-4 text-center">Tugas Selesai</th>
                        <th class="p-4">Persentase Kontribusi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalSelesai = $kinerja->sum('selesai_count'); @endphp
                    @foreach($kinerja as $tek)
                    <tr class="border-b">
                        <td class="p-4 font-bold text-gray-800">{{ $tek->nama_lengkap }}</td>
                        <td class="p-4 text-center">
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-bold">
                                {{ $tek->selesai_count }} Pekerjaan
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
                                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: <?php echo $totalSelesai > 0 ? ($tek->selesai_count / $totalSelesai * 100) : 0; ?>%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
</x-app-layout>