<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <nav class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 mb-1.5">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Dashboard</a>
                    <span>/</span>
                    <a href="{{ route('admin.utilitas.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Utilitas</a>
                    <span>/</span>
                    <span class="text-gray-800 dark:text-gray-200 font-semibold">Catat Stand Meter</span>
                </nav>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">Catat Stand Meter Utilitas</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Input data fisik stand meteran gedung FRC UGM per periode bulanan.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.utilitas.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-xl shadow-xs hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Kembali ke Utilitas</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div x-data="{
        jenis: '{{ old('jenis_utilitas', request('jenis', 'AirBersih')) }}',
        standAwal: '{{ old('stand_awal', '') }}',
        standAkhir: '{{ old('stand_akhir', '') }}',
        standAwal1: '{{ old('stand_awal_1', '') }}',
        standAkhir1: '{{ old('stand_akhir_1', '') }}',
        standAwal2: '{{ old('stand_awal_2', '') }}',
        standAkhir2: '{{ old('stand_akhir_2', '') }}',
        standAwalL1: '{{ old('stand_awal_l1', '') }}',
        standAkhirL1: '{{ old('stand_akhir_l1', '') }}',
        standAwalL2: '{{ old('stand_awal_l2', '') }}',
        standAkhirL2: '{{ old('stand_akhir_l2', '') }}',
        standAwalL3: '{{ old('stand_awal_l3', '') }}',
        standAkhirL3: '{{ old('stand_akhir_l3', '') }}',
        hitungKonsumsi(awal, akhir) {
            let a = parseFloat(awal);
            let b = parseFloat(akhir);
            if (isNaN(a) || isNaN(b)) return null;
            return (b - a).toFixed(2);
        }
    }" class="max-w-4xl mx-auto space-y-6">

        <!-- Category Quick Switcher Pills -->
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Pilih Kategori Cepat:</span>
                <span class="text-2xs font-mono font-medium text-indigo-600 dark:text-indigo-400" x-text="'Aktif: ' + jenis"></span>
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="button" @click="jenis = 'AirBersih'"
                    :class="jenis === 'AirBersih' ? 'bg-blue-600 text-white shadow-xs font-bold' : 'bg-gray-100 hover:bg-gray-200/80 dark:bg-gray-700/60 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium'"
                    class="px-3 py-1.5 rounded-xl text-xs flex items-center gap-1.5 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"/></svg>
                    <span>Air Bersih</span>
                </button>
                <button type="button" @click="jenis = 'AirHujan'"
                    :class="jenis === 'AirHujan' ? 'bg-cyan-600 text-white shadow-xs font-bold' : 'bg-gray-100 hover:bg-gray-200/80 dark:bg-gray-700/60 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium'"
                    class="px-3 py-1.5 rounded-xl text-xs flex items-center gap-1.5 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 00-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                    <span>Air Hujan</span>
                </button>
                <button type="button" @click="jenis = 'MDP'"
                    :class="jenis === 'MDP' ? 'bg-amber-600 text-white shadow-xs font-bold' : 'bg-gray-100 hover:bg-gray-200/80 dark:bg-gray-700/60 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium'"
                    class="px-3 py-1.5 rounded-xl text-xs flex items-center gap-1.5 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Listrik MDP</span>
                </button>
                <button type="button" @click="jenis = 'SDP'"
                    :class="jenis === 'SDP' ? 'bg-yellow-600 text-white shadow-xs font-bold' : 'bg-gray-100 hover:bg-gray-200/80 dark:bg-gray-700/60 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium'"
                    class="px-3 py-1.5 rounded-xl text-xs flex items-center gap-1.5 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>Listrik SDP</span>
                </button>
                <button type="button" @click="jenis = 'Lift'"
                    :class="jenis === 'Lift' ? 'bg-orange-600 text-white shadow-xs font-bold' : 'bg-gray-100 hover:bg-gray-200/80 dark:bg-gray-700/60 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium'"
                    class="px-3 py-1.5 rounded-xl text-xs flex items-center gap-1.5 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7l4-4m0 0l4 4m-4-4v18m0 0l-4-4m4 4l4-4"/></svg>
                    <span>Listrik Lift</span>
                </button>
                <button type="button" @click="jenis = 'AC'"
                    :class="jenis === 'AC' ? 'bg-indigo-600 text-white shadow-xs font-bold' : 'bg-gray-100 hover:bg-gray-200/80 dark:bg-gray-700/60 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium'"
                    class="px-3 py-1.5 rounded-xl text-xs flex items-center gap-1.5 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21a9 9 0 110-18 9 9 0 010 18z"/></svg>
                    <span>Listrik AC</span>
                </button>
                <button type="button" @click="jenis = 'Lampu'"
                    :class="jenis === 'Lampu' ? 'bg-emerald-600 text-white shadow-xs font-bold' : 'bg-gray-100 hover:bg-gray-200/80 dark:bg-gray-700/60 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium'"
                    class="px-3 py-1.5 rounded-xl text-xs flex items-center gap-1.5 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    <span>Listrik Lampu</span>
                </button>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 sm:p-8">
            <form action="{{ route('admin.utilitas.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Metadata Inputs Card -->
                <div class="bg-gray-50/70 dark:bg-gray-900/40 p-5 rounded-2xl border border-gray-200/80 dark:border-gray-700/80 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label value="Jenis Utilitas Gedung" />
                            <select x-model="jenis" name="jenis_utilitas" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 font-semibold focus:border-indigo-500 focus:ring-indigo-500 shadow-xs py-2.5 px-3.5 text-sm">
                                <option value="AirBersih" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">Air Bersih (m³)</option>
                                <option value="AirHujan" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">Air Hujan (m³)</option>
                                <option value="MDP" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">Listrik MDP - Panel Utama (kWh)</option>
                                <option value="SDP" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">Listrik SDP - Panel Distribusi (kWh)</option>
                                <option value="Lift" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">Listrik Lift Gedung (kWh)</option>
                                <option value="AC" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">Listrik AC - Lantai 1, 2, 3 (kWh)</option>
                                <option value="Lampu" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">Listrik Lampu - Lantai 1, 2, 3 (kWh)</option>
                            </select>
                            <p class="text-2xs text-gray-500 dark:text-gray-400 mt-1">Pilih tipe utilitas atau gunakan tombol pintas di atas</p>
                        </div>
                        <div>
                            <x-input-label value="Periode Bulan (YYYY-MM)" />
                            <x-text-input type="month" name="periode" value="{{ old('periode', request('periode', date('Y-m'))) }}" required class="rounded-xl" />
                            <p class="text-2xs text-gray-500 dark:text-gray-400 mt-1">Satu periode bulan hanya dapat dicatat satu kali</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-gray-200/70 dark:border-gray-700/70">
                        <div>
                            <x-input-label value="Tanggal Pengecekan Awal" />
                            <x-text-input type="date" name="tgl_awal" value="{{ old('tgl_awal') }}" required class="rounded-xl" />
                            <p class="text-2xs text-gray-500 dark:text-gray-400 mt-1">Tanggal saat stand meter awal dibaca</p>
                        </div>
                        <div>
                            <x-input-label value="Tanggal Pengecekan Akhir" />
                            <x-text-input type="date" name="tgl_akhir" value="{{ old('tgl_akhir') }}" required class="rounded-xl" />
                            <p class="text-2xs text-gray-500 dark:text-gray-400 mt-1">Tanggal saat stand meter akhir dibaca (harus ≥ tgl awal)</p>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Context Banner -->
                <div class="p-3.5 rounded-xl bg-indigo-50/70 dark:bg-gray-900/50 border border-indigo-100 dark:border-gray-700/80 flex items-center justify-between gap-3 text-xs">
                    <div class="flex items-center gap-2 text-indigo-900 dark:text-indigo-300">
                        <span class="inline-flex p-1 rounded-md bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <span>
                            Kategori Aktif: <strong class="font-bold text-indigo-950 dark:text-indigo-200" x-text="jenis"></strong>
                            <span class="text-gray-500 dark:text-gray-400 ml-1" x-text="['AirBersih', 'AirHujan'].includes(jenis) ? '• Satuan: m³' : '• Satuan: kWh'"></span>
                        </span>
                    </div>
                    <span class="text-2xs font-semibold px-2.5 py-1 rounded-md bg-white dark:bg-gray-800 border border-indigo-200/60 dark:border-gray-700 text-gray-700 dark:text-gray-300"
                          x-text="['AirBersih', 'AirHujan', 'MDP'].includes(jenis) ? 'Single Meter' : (['SDP', 'Lift'].includes(jenis) ? 'Dual Meter' : '3 Lantai')"></span>
                </div>

                <!-- Section 1: Air Bersih, Air Hujan, Listrik MDP -->
                <div x-show="['AirBersih', 'AirHujan', 'MDP'].includes(jenis)" class="space-y-4">
                    <div class="bg-blue-50/50 dark:bg-gray-900/50 p-5 rounded-2xl border border-blue-200/60 dark:border-blue-500/20 shadow-xs space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-blue-200/60 dark:border-gray-700 gap-2">
                            <div class="flex items-center gap-2.5">
                                <div class="p-2 rounded-xl bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400">
                                    <svg x-show="['AirBersih', 'AirHujan'].includes(jenis)" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"/></svg>
                                    <svg x-show="jenis === 'MDP'" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-gray-900 dark:text-gray-100" x-text="jenis === 'MDP' ? 'Meteran Listrik Panel Utama (MDP)' : (jenis === 'AirHujan' ? 'Meteran Air Hujan FRC' : 'Meteran Air Bersih FRC')"></h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Pencatatan meteran tunggal periode berjalan</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 self-start sm:self-auto px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100/80 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300" x-text="jenis === 'MDP' ? 'Satuan: kWh' : 'Satuan: m³'"></span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Stand Meter AWAL" />
                                <div class="relative rounded-xl shadow-xs">
                                    <x-text-input type="number" step="0.01" min="0" name="stand_awal" x-model="standAwal" value="{{ old('stand_awal') }}" placeholder="0.00" class="rounded-xl pr-14" />
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5">
                                        <span class="text-xs font-mono font-semibold text-gray-400 dark:text-gray-500" x-text="jenis === 'MDP' ? 'kWh' : 'm³'"></span>
                                    </div>
                                </div>
                                <p class="text-2xs text-gray-500 dark:text-gray-400 mt-1">Stand meter pada awal periode pemeriksaan</p>
                            </div>
                            <div>
                                <x-input-label value="Stand Meter AKHIR" />
                                <div class="relative rounded-xl shadow-xs">
                                    <x-text-input type="number" step="0.01" min="0" name="stand_akhir" x-model="standAkhir" value="{{ old('stand_akhir') }}" placeholder="0.00" class="rounded-xl pr-14" />
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5">
                                        <span class="text-xs font-mono font-semibold text-gray-400 dark:text-gray-500" x-text="jenis === 'MDP' ? 'kWh' : 'm³'"></span>
                                    </div>
                                </div>
                                <p class="text-2xs text-gray-500 dark:text-gray-400 mt-1">Stand meter pada akhir periode pemeriksaan</p>
                            </div>
                        </div>

                        <!-- Live Calculation Preview -->
                        <template x-if="hitungKonsumsi(standAwal, standAkhir) !== null">
                            <div class="p-3.5 rounded-xl border flex items-center justify-between transition-all"
                                 :class="parseFloat(hitungKonsumsi(standAwal, standAkhir)) >= 0 ? 'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-200' : 'bg-rose-50 dark:bg-rose-950/30 border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-200'">
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="font-bold" x-text="parseFloat(hitungKonsumsi(standAwal, standAkhir)) >= 0 ? 'Estimasi Konsumsi:' : 'Peringatan:'"></span>
                                    <span x-text="parseFloat(hitungKonsumsi(standAwal, standAkhir)) >= 0 ? 'Pemakaian periode ini' : 'Stand akhir tidak boleh lebih kecil dari stand awal!'"></span>
                                </div>
                                <div class="font-mono font-bold text-sm" x-show="parseFloat(hitungKonsumsi(standAwal, standAkhir)) >= 0">
                                    <span x-text="hitungKonsumsi(standAwal, standAkhir)"></span>
                                    <span class="text-xs font-sans font-normal" x-text="jenis === 'MDP' ? 'kWh' : 'm³'"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Section 2: Listrik SDP dan Listrik Lift -->
                <div x-show="['SDP', 'Lift'].includes(jenis)" style="display: none;" class="space-y-4">
                    <!-- Unit 1 -->
                    <div class="bg-amber-50/50 dark:bg-gray-900/50 p-5 rounded-2xl border border-amber-200/60 dark:border-amber-500/20 shadow-xs space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-amber-200/60 dark:border-gray-700 gap-2">
                            <div class="flex items-center gap-2.5">
                                <div class="p-2 rounded-xl bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-gray-900 dark:text-gray-100" x-text="jenis === 'Lift' ? 'Data Lift G (Kiri)' : 'Data SDP 1 (Panel Distribusi 1)'"></h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="jenis === 'Lift' ? 'Motor penggerak lift sisi kiri' : 'Panel pembagi listrik unit 1'"></p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 self-start sm:self-auto px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100/80 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300">Satuan: kWh</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Stand AWAL (Unit 1)" />
                                <div class="relative rounded-xl shadow-xs">
                                    <x-text-input type="number" step="0.01" min="0" name="stand_awal_1" x-model="standAwal1" value="{{ old('stand_awal_1') }}" placeholder="0.00" class="rounded-xl pr-14" />
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5">
                                        <span class="text-xs font-mono font-semibold text-gray-400 dark:text-gray-500">kWh</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <x-input-label value="Stand AKHIR (Unit 1)" />
                                <div class="relative rounded-xl shadow-xs">
                                    <x-text-input type="number" step="0.01" min="0" name="stand_akhir_1" x-model="standAkhir1" value="{{ old('stand_akhir_1') }}" placeholder="0.00" class="rounded-xl pr-14" />
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5">
                                        <span class="text-xs font-mono font-semibold text-gray-400 dark:text-gray-500">kWh</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <template x-if="hitungKonsumsi(standAwal1, standAkhir1) !== null">
                            <div class="p-3 rounded-xl border flex items-center justify-between text-xs transition-all"
                                 :class="parseFloat(hitungKonsumsi(standAwal1, standAkhir1)) >= 0 ? 'bg-emerald-50/80 dark:bg-emerald-950/30 border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-200' : 'bg-rose-50/80 dark:bg-rose-950/30 border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-200'">
                                <span x-text="parseFloat(hitungKonsumsi(standAwal1, standAkhir1)) >= 0 ? 'Estimasi Konsumsi Unit 1:' : 'Stand akhir (1) lebih kecil dari stand awal!'"></span>
                                <span class="font-mono font-bold" x-show="parseFloat(hitungKonsumsi(standAwal1, standAkhir1)) >= 0" x-text="hitungKonsumsi(standAwal1, standAkhir1) + ' kWh'"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Unit 2 -->
                    <div class="bg-amber-50/50 dark:bg-gray-900/50 p-5 rounded-2xl border border-amber-200/60 dark:border-amber-500/20 shadow-xs space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-amber-200/60 dark:border-gray-700 gap-2">
                            <div class="flex items-center gap-2.5">
                                <div class="p-2 rounded-xl bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-gray-900 dark:text-gray-100" x-text="jenis === 'Lift' ? 'Data Lift G2 (Kanan)' : 'Data SDP 2 (Panel Distribusi 2)'"></h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="jenis === 'Lift' ? 'Motor penggerak lift sisi kanan' : 'Panel pembagi listrik unit 2'"></p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 self-start sm:self-auto px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100/80 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300">Satuan: kWh</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Stand AWAL (Unit 2)" />
                                <div class="relative rounded-xl shadow-xs">
                                    <x-text-input type="number" step="0.01" min="0" name="stand_awal_2" x-model="standAwal2" value="{{ old('stand_awal_2') }}" placeholder="0.00" class="rounded-xl pr-14" />
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5">
                                        <span class="text-xs font-mono font-semibold text-gray-400 dark:text-gray-500">kWh</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <x-input-label value="Stand AKHIR (Unit 2)" />
                                <div class="relative rounded-xl shadow-xs">
                                    <x-text-input type="number" step="0.01" min="0" name="stand_akhir_2" x-model="standAkhir2" value="{{ old('stand_akhir_2') }}" placeholder="0.00" class="rounded-xl pr-14" />
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5">
                                        <span class="text-xs font-mono font-semibold text-gray-400 dark:text-gray-500">kWh</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <template x-if="hitungKonsumsi(standAwal2, standAkhir2) !== null">
                            <div class="p-3 rounded-xl border flex items-center justify-between text-xs transition-all"
                                 :class="parseFloat(hitungKonsumsi(standAwal2, standAkhir2)) >= 0 ? 'bg-emerald-50/80 dark:bg-emerald-950/30 border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-200' : 'bg-rose-50/80 dark:bg-rose-950/30 border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-200'">
                                <span x-text="parseFloat(hitungKonsumsi(standAwal2, standAkhir2)) >= 0 ? 'Estimasi Konsumsi Unit 2:' : 'Stand akhir (2) lebih kecil dari stand awal!'"></span>
                                <span class="font-mono font-bold" x-show="parseFloat(hitungKonsumsi(standAwal2, standAkhir2)) >= 0" x-text="hitungKonsumsi(standAwal2, standAkhir2) + ' kWh'"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Section 3: Listrik AC dan Listrik Lampu (3 Lantai) -->
                <div x-show="['AC', 'Lampu'].includes(jenis)" style="display: none;" class="space-y-4">
                    @for($i = 1; $i <= 3; $i++)
                    <div class="bg-indigo-50/50 dark:bg-gray-900/50 p-5 rounded-2xl border border-indigo-200/60 dark:border-indigo-500/20 shadow-xs space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-indigo-200/60 dark:border-gray-700 gap-2">
                            <div class="flex items-center gap-2.5">
                                <div class="p-2 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-gray-900 dark:text-gray-100" x-text="'Lantai {{ $i }} • ' + (jenis === 'AC' ? 'Sistem AC Pendingin' : 'Sistem Lampu Penerangan')"></h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Stand meteran listrik Lantai {{ $i }}</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 self-start sm:self-auto px-2.5 py-1 rounded-full text-xs font-semibold bg-indigo-100/80 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300">Satuan: kWh</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Stand AWAL (Lantai {{ $i }})" />
                                <div class="relative rounded-xl shadow-xs">
                                    <x-text-input type="number" step="0.01" min="0" name="stand_awal_l{{ $i }}" x-model="standAwalL{{ $i }}" value="{{ old('stand_awal_l'.$i) }}" placeholder="0.00" class="rounded-xl pr-14" />
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5">
                                        <span class="text-xs font-mono font-semibold text-gray-400 dark:text-gray-500">kWh</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <x-input-label value="Stand AKHIR (Lantai {{ $i }})" />
                                <div class="relative rounded-xl shadow-xs">
                                    <x-text-input type="number" step="0.01" min="0" name="stand_akhir_l{{ $i }}" x-model="standAkhirL{{ $i }}" value="{{ old('stand_akhir_l'.$i) }}" placeholder="0.00" class="rounded-xl pr-14" />
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5">
                                        <span class="text-xs font-mono font-semibold text-gray-400 dark:text-gray-500">kWh</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <template x-if="hitungKonsumsi(standAwalL{{ $i }}, standAkhirL{{ $i }}) !== null">
                            <div class="p-3 rounded-xl border flex items-center justify-between text-xs transition-all"
                                 :class="parseFloat(hitungKonsumsi(standAwalL{{ $i }}, standAkhirL{{ $i }})) >= 0 ? 'bg-emerald-50/80 dark:bg-emerald-950/30 border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-200' : 'bg-rose-50/80 dark:bg-rose-950/30 border-rose-200 dark:border-rose-800/60 text-rose-800 dark:text-rose-200'">
                                <span x-text="parseFloat(hitungKonsumsi(standAwalL{{ $i }}, standAkhirL{{ $i }})) >= 0 ? 'Estimasi Konsumsi Lantai {{ $i }}:' : 'Stand akhir Lantai {{ $i }} lebih kecil dari stand awal!'"></span>
                                <span class="font-mono font-bold" x-show="parseFloat(hitungKonsumsi(standAwalL{{ $i }}, standAkhirL{{ $i }})) >= 0" x-text="hitungKonsumsi(standAwalL{{ $i }}, standAkhirL{{ $i }}) + ' kWh'"></span>
                            </div>
                        </template>
                    </div>
                    @endfor
                </div>

                <!-- Info Guide Box -->
                <div class="p-4 rounded-xl bg-gray-50/90 dark:bg-gray-900/60 border border-gray-200/80 dark:border-gray-700/80 text-xs text-gray-600 dark:text-gray-400 flex items-start gap-3">
                    <div class="p-1 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="space-y-1">
                        <p class="font-semibold text-gray-800 dark:text-gray-200">Petunjuk Pencatatan Stand Meter</p>
                        <p>Pastikan angka Stand Akhir tidak lebih kecil dari Stand Awal. Konsumsi pemakaian dan kalkulasi biaya akan dihitung otomatis oleh sistem FRC untuk rekapitulasi pelaporan manajemen.</p>
                    </div>
                </div>

                <!-- Form Action Buttons -->
                <div class="mt-8 flex flex-col-reverse sm:flex-row items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('admin.utilitas.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-wider shadow-xs hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                        Batal
                    </a>
                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-semibold text-sm rounded-xl shadow-sm hover:shadow transition focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan Data Utilitas</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>