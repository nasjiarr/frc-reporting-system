<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">Catat Stand Meter Utilitas</h2>
    </x-slot>

    <div x-data="{ jenis: 'AirBersih' }" class="max-w-4xl mx-auto">
        <x-card>
            <form action="{{ route('admin.utilitas.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b dark:border-gray-700">
                    <div>
                        <x-input-label value="Jenis Utilitas" />
                        <select x-model="jenis" name="jenis_utilitas" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded-md shadow-sm font-bold text-indigo-700 dark:text-indigo-400">
                            <option value="AirBersih">Air Bersih</option>
                            <option value="AirHujan">Air Hujan</option>
                            <option value="MDP">Listrik MDP (Panel Utama)</option>
                            <option value="SDP">Listrik SDP (Panel Distribusi)</option>
                            <option value="Lift">Listrik Lift</option>
                            <option value="AC">Listrik AC</option>
                            <option value="Lampu">Listrik Lampu</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label value="Periode Bulan (YYYY-MM)" />
                        <x-text-input type="month" name="periode" value="{{ date('Y-m') }}" required />
                    </div>
                    <div>
                        <x-input-label value="Tanggal Pengecekan Awal" />
                        <x-text-input type="date" name="tgl_awal" required />
                    </div>
                    <div>
                        <x-input-label value="Tanggal Pengecekan Akhir" />
                        <x-text-input type="date" name="tgl_akhir" required />
                    </div>
                </div>

                <div x-show="['AirBersih', 'AirHujan', 'MDP'].includes(jenis)" class="space-y-4">
                    <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg border border-blue-100 dark:border-blue-800 grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Stand Meter AWAL" />
                            <x-text-input type="number" step="0.01" name="stand_awal" />
                        </div>
                        <div>
                            <x-input-label value="Stand Meter AKHIR" />
                            <x-text-input type="number" step="0.01" name="stand_akhir" />
                        </div>
                    </div>
                </div>

                <div x-show="['SDP', 'Lift'].includes(jenis)" style="display: none;" class="space-y-4">
                    <div class="bg-yellow-50 dark:bg-yellow-900/30 p-4 rounded-lg border border-yellow-100 dark:border-yellow-800 mb-4 grid grid-cols-2 gap-4">
                        <div class="col-span-2 font-bold text-yellow-800 dark:text-yellow-300" x-text="jenis === 'Lift' ? 'Data Lift G (Kiri)' : 'Data SDP 1'"></div>
                        <div>
                            <x-input-label value="Stand AWAL" />
                            <x-text-input type="number" step="0.01" name="stand_awal_1" />
                        </div>
                        <div>
                            <x-input-label value="Stand AKHIR" />
                            <x-text-input type="number" step="0.01" name="stand_akhir_1" />
                        </div>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900/30 p-4 rounded-lg border border-yellow-100 dark:border-yellow-800 grid grid-cols-2 gap-4">
                        <div class="col-span-2 font-bold text-yellow-800 dark:text-yellow-300" x-text="jenis === 'Lift' ? 'Data Lift G2 (Kanan)' : 'Data SDP 2'"></div>
                        <div>
                            <x-input-label value="Stand AWAL" />
                            <x-text-input type="number" step="0.01" name="stand_awal_2" />
                        </div>
                        <div>
                            <x-input-label value="Stand AKHIR" />
                            <x-text-input type="number" step="0.01" name="stand_akhir_2" />
                        </div>
                    </div>
                </div>

                <div x-show="['AC', 'Lampu'].includes(jenis)" style="display: none;" class="space-y-4">
                    @for($i = 1; $i <= 3; $i++)
                        <div class="bg-purple-50 dark:bg-purple-900/30 p-4 rounded-lg border border-purple-100 dark:border-purple-800 mb-4 grid grid-cols-2 gap-4">
                        <div class="col-span-2 font-bold text-purple-800 dark:text-purple-300">Lantai {{ $i }}</div>
                        <div>
                            <x-input-label value="Stand AWAL (L{{ $i }})" />
                            <x-text-input type="number" step="0.01" name="stand_awal_l{{ $i }}" />
                        </div>
                        <div>
                            <x-input-label value="Stand AKHIR (L{{ $i }})" />
                            <x-text-input type="number" step="0.01" name="stand_akhir_l{{ $i }}" />
                        </div>
                </div>
                @endfor
    </div>

    <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
        <a href="{{ route('admin.utilitas.index') }}" class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">Kembali</a>
        <x-primary-button class="py-3 px-8 text-sm">Simpan Data Utilitas</x-primary-button>
    </div>
    </form>
    </x-card>
    </div>
</x-app-layout>