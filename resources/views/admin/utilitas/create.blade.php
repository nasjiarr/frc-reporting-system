<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Catat Stand Meter Utilitas</h2>
    </x-slot>

    <div x-data="{ jenis: 'AirBersih' }" class="max-w-4xl mx-auto">
        <x-card>
            <form action="{{ route('admin.utilitas.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 pb-6 border-b">
                    <div>
                        <x-input-label value="Jenis Utilitas" />
                        <select x-model="jenis" name="jenis_utilitas" class="w-full border-gray-300 rounded-md shadow-sm font-bold text-indigo-700">
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
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 grid grid-cols-2 gap-4">
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
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100 mb-4 grid grid-cols-2 gap-4">
                        <div class="col-span-2 font-bold text-yellow-800" x-text="jenis === 'Lift' ? 'Data Lift G (Kiri)' : 'Data SDP 1'"></div>
                        <div>
                            <x-input-label value="Stand AWAL" />
                            <x-text-input type="number" step="0.01" name="stand_awal_1" />
                        </div>
                        <div>
                            <x-input-label value="Stand AKHIR" />
                            <x-text-input type="number" step="0.01" name="stand_akhir_1" />
                        </div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100 grid grid-cols-2 gap-4">
                        <div class="col-span-2 font-bold text-yellow-800" x-text="jenis === 'Lift' ? 'Data Lift G2 (Kanan)' : 'Data SDP 2'"></div>
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
                        <div class="bg-purple-50 p-4 rounded-lg border border-purple-100 mb-4 grid grid-cols-2 gap-4">
                        <div class="col-span-2 font-bold text-purple-800">Lantai {{ $i }}</div>
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

    <div class="mt-8 flex justify-end">
        <x-primary-button class="py-3 px-8 text-sm">Simpan Data Utilitas</x-primary-button>
    </div>
    </form>
    </x-card>
    </div>
</x-app-layout>