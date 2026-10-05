<x-app-layout>
    <div x-data="{ 
        activeTab: '{{ request('tab', request('page') ? 'monitoring' : ($laporanBaru->isEmpty() && $penugasans->isNotEmpty() ? 'monitoring' : 'menunggu')) }}',
        previewOpen: false, 
        assignModalOpen: false, 
        tolakModalOpen: false,

        // Data Penugasan
        selectedLaporanId: '', 
        selectedJudul: '',
        selectedLokasi: '',
        selectedPelapor: '',
        showLaporanDropdown: false,

        // Data Pratinjau
        p_id: '',
        p_judul: '', 
        p_lokasi: '', 
        p_pelapor: '',
        p_tanggal: '',
        p_deskripsi: '', 
        p_foto: '',

        // Data Tolak
        tolakLaporanId: '',
        tolakJudul: '',
        
        openPreview(id, judul, lokasi, pelapor, tanggal, deskripsi, foto) {
            this.p_id = id;
            this.p_judul = judul;
            this.p_lokasi = lokasi;
            this.p_pelapor = pelapor;
            this.p_tanggal = tanggal;
            this.p_deskripsi = deskripsi;
            this.p_foto = foto;
            this.previewOpen = true;
        },

        openAssignModal(id = '', judul = '', lokasi = '', pelapor = '') {
            this.selectedLaporanId = id;
            this.selectedJudul = judul;
            this.selectedLokasi = lokasi;
            this.selectedPelapor = pelapor;
            this.showLaporanDropdown = !id;
            this.assignModalOpen = true;
        },

        openTolakModal(id, judul) {
            this.tolakLaporanId = id;
            this.tolakJudul = judul;
            this.tolakModalOpen = true;
        },

        onLaporanSelectChange(event) {
            const select = event.target;
            const option = select.options[select.selectedIndex];
            if (option && option.value) {
                this.selectedLaporanId = option.value;
                this.selectedJudul = option.getAttribute('data-judul') || '';
                this.selectedLokasi = option.getAttribute('data-lokasi') || '';
                this.selectedPelapor = option.getAttribute('data-pelapor') || '';
            }
        }
    }" class="pb-12">

        {{-- Header Breadcrumb & Context --}}
        <x-slot name="header">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                            Koordinasi Operasional
                        </span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 tracking-tight mt-1">
                        Manajemen Penugasan Teknisi
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        Kelola alokasi pekerjaan teknisi, pantau penugasan aktif, dan verifikasi laporan masuk.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 w-full sm:w-auto">
                    <a href="{{ route('admin.laporan.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/80 text-gray-700 dark:text-gray-200 text-xs font-semibold rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm transition gap-2">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Buat Laporan Baru
                    </a>

                    <button type="button" @click="openAssignModal('', '', '', '')" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm hover:shadow transition gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Tugaskan Teknisi
                    </button>
                </div>
            </div>
        </x-slot>

        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8 mt-6">

            {{-- 1. KPI Summary Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Card 1: Menunggu Penugasan --}}
                <div @click="activeTab = 'menunggu'" 
                    class="cursor-pointer group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-rose-400 dark:hover:border-rose-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5"
                    :class="activeTab === 'menunggu' ? 'ring-2 ring-rose-500 dark:ring-rose-400 border-transparent' : ''">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Menunggu Tugas</span>
                        <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline justify-between">
                        <div class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                            {{ $stats['menunggu'] }}
                        </div>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full {{ $stats['menunggu'] > 0 ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ $stats['menunggu'] > 0 ? 'Perlu Respon' : 'Nihil' }}
                        </span>
                    </div>
                </div>

                {{-- Card 2: Sedang Dikerjakan --}}
                <div @click="activeTab = 'monitoring'" 
                    class="cursor-pointer group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5"
                    :class="activeTab === 'monitoring' ? 'ring-2 ring-indigo-500 dark:ring-indigo-400 border-transparent' : ''">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sedang Dikerjakan</span>
                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline justify-between">
                        <div class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                            {{ $stats['dikerjakan'] }}
                        </div>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                            Di Lapangan
                        </span>
                    </div>
                </div>

                {{-- Card 3: Menunggu Teknisi Mulai --}}
                <div @click="activeTab = 'monitoring'" 
                    class="cursor-pointer group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-amber-400 dark:hover:border-amber-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Telah Ditugaskan</span>
                        <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline justify-between">
                        <div class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                            {{ $stats['ditugaskan'] }}
                        </div>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                            Antrean Tugas
                        </span>
                    </div>
                </div>

                {{-- Card 4: Kesiapan Teknisi --}}
                <div @click="activeTab = 'teknisi'" 
                    class="cursor-pointer group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-emerald-400 dark:hover:border-emerald-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5"
                    :class="activeTab === 'teknisi' ? 'ring-2 ring-emerald-500 dark:ring-emerald-400 border-transparent' : ''">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Teknisi Siaga</span>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-baseline justify-between">
                        <div class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                            {{ $stats['teknisi_ready'] }} <span class="text-sm font-semibold text-gray-400 dark:text-gray-500">/ {{ $stats['teknisi_total'] }}</span>
                        </div>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                            {{ $stats['teknisi_ready'] }} Siaga (0 Tugas)
                        </span>
                    </div>
                </div>
            </div>

            {{-- 2. Segmented Navigation Tabs --}}
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-2 sm:space-x-8 overflow-x-auto" aria-label="Tabs">
                    {{-- Tab 1: Menunggu Penugasan --}}
                    <button type="button" @click="activeTab = 'menunggu'"
                        class="whitespace-nowrap pb-4 px-1 border-b-2 font-bold text-sm flex items-center gap-2 transition-colors duration-150"
                        :class="activeTab === 'menunggu' 
                            ? 'border-indigo-600 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' 
                            : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Laporan Menunggu Penugasan
                        <span class="px-2 py-0.5 text-xs rounded-full font-bold"
                            :class="activeTab === 'menunggu' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300'">
                            {{ $laporanBaru->count() }}
                        </span>
                    </button>

                    {{-- Tab 2: Monitoring Penugasan Lapangan --}}
                    <button type="button" @click="activeTab = 'monitoring'"
                        class="whitespace-nowrap pb-4 px-1 border-b-2 font-bold text-sm flex items-center gap-2 transition-colors duration-150"
                        :class="activeTab === 'monitoring' 
                            ? 'border-indigo-600 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' 
                            : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        Monitoring Penugasan Lapangan
                        <span class="px-2 py-0.5 text-xs rounded-full font-bold"
                            :class="activeTab === 'monitoring' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300'">
                            {{ $penugasans->total() }}
                        </span>
                    </button>

                    {{-- Tab 3: Radar Kesiapan Teknisi --}}
                    <button type="button" @click="activeTab = 'teknisi'"
                        class="whitespace-nowrap pb-4 px-1 border-b-2 font-bold text-sm flex items-center gap-2 transition-colors duration-150"
                        :class="activeTab === 'teknisi' 
                            ? 'border-indigo-600 text-indigo-600 dark:border-indigo-400 dark:text-indigo-400' 
                            : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Radar Kesiapan Teknisi
                        <span class="px-2 py-0.5 text-xs rounded-full font-bold"
                            :class="activeTab === 'teknisi' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300'">
                            {{ $teknisi->count() }}
                        </span>
                    </button>
                </nav>
            </div>

            {{-- ========================================================================= --}}
            {{-- TAB 1: LAPORAN MENUNGGU PENUGASAN --}}
            {{-- ========================================================================= --}}
            <div x-show="activeTab === 'menunggu'" x-cloak class="space-y-4">
                <div class="flex items-center justify-between px-1">
                    <div class="flex items-center gap-2">
                        <span class="flex h-2.5 w-2.5 rounded-full bg-rose-500 animate-pulse"></span>
                        <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">
                            Daftar Laporan Baru (Menunggu Penugasan)
                        </h3>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        Total: <strong>{{ $laporanBaru->count() }}</strong> laporan
                    </span>
                </div>

                {{-- Tabel Desktop --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden hidden md:block">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-3.5 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal & SLA</th>
                                    <th class="px-6 py-3.5 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pelapor</th>
                                    <th class="px-6 py-3.5 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kerusakan & Lokasi</th>
                                    <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi Cepat</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($laporanBaru as $lap)
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $lap->created_at->format('d/m/Y H:i') }}
                                        </div>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $lap->created_at->diffForHumans() }}
                                            </span>
                                            @if($lap->created_at->lt(now()->subHours(24)))
                                            <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                </svg>
                                                &gt; 24 Jam
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                            {{ $lap->pelapor->nama_lengkap ?? 'Anonim' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $lap->pelapor->no_telepon ?? $lap->pelapor->email ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                            {{ $lap->judul }}
                                        </div>
                                        <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            </svg>
                                            <span class="truncate max-w-xs">{{ $lap->lokasi }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            {{-- Tombol Pratinjau --}}
                                            <button type="button"
                                                @click="openPreview(
                                                    '{{ $lap->id }}',
                                                    {{ json_encode($lap->judul) }},
                                                    {{ json_encode($lap->lokasi) }},
                                                    {{ json_encode($lap->pelapor->nama_lengkap ?? 'Anonim') }},
                                                    '{{ $lap->created_at->format('d F Y, H:i') }}',
                                                    {{ json_encode($lap->deskripsi) }},
                                                    {{ json_encode($lap->foto_sebelum ? asset('storage/' . $lap->foto_sebelum) : '') }}
                                                )"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition shadow-sm">
                                                <svg class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Pratinjau
                                            </button>

                                            {{-- Tombol Tugaskan --}}
                                            <button type="button"
                                                @click="openAssignModal(
                                                    '{{ $lap->id }}',
                                                    {{ json_encode($lap->judul) }},
                                                    {{ json_encode($lap->lokasi) }},
                                                    {{ json_encode($lap->pelapor->nama_lengkap ?? 'Anonim') }}
                                                )"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 transition shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                                </svg>
                                                Tugaskan
                                            </button>

                                            {{-- Tombol Tolak --}}
                                            <button type="button"
                                                @click="openTolakModal('{{ $lap->id }}', {{ json_encode($lap->judul) }})"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-white dark:bg-gray-800 text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 rounded-lg text-xs font-bold transition shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Tolak
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center bg-gray-50/50 dark:bg-gray-800/30">
                                        <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                            <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-base font-bold text-gray-800 dark:text-gray-200">Semua Laporan Telah Ditugaskan</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">
                                                Tidak ada laporan baru yang menunggu penugasan saat ini. Cek tab Monitoring untuk memantau pekerjaan di lapangan.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Card Stack Khusus Mobile --}}
                <div class="grid grid-cols-1 gap-3 md:hidden">
                    @forelse($laporanBaru as $lap)
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4 shadow-sm space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="text-xs text-gray-400 dark:text-gray-500 font-mono">{{ $lap->created_at->format('d/m/Y H:i') }}</span>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 mt-0.5">{{ $lap->judul }}</h4>
                            </div>
                            @if($lap->created_at->lt(now()->subHours(24)))
                            <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300 border border-rose-200 dark:border-rose-800 flex-shrink-0">
                                &gt; 24 Jam
                            </span>
                            @endif
                        </div>

                        <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                <span>{{ $lap->lokasi }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Pelapor: {{ $lap->pelapor->nama_lengkap ?? 'Anonim' }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                            <button type="button"
                                @click="openPreview(
                                    '{{ $lap->id }}',
                                    {{ json_encode($lap->judul) }},
                                    {{ json_encode($lap->lokasi) }},
                                    {{ json_encode($lap->pelapor->nama_lengkap ?? 'Anonim') }},
                                    '{{ $lap->created_at->format('d F Y, H:i') }}',
                                    {{ json_encode($lap->deskripsi) }},
                                    {{ json_encode($lap->foto_sebelum ? asset('storage/' . $lap->foto_sebelum) : '') }}
                                )"
                                class="inline-flex items-center justify-center py-2 px-2 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs font-bold text-gray-700 dark:text-gray-200">
                                Pratinjau
                            </button>

                            <button type="button"
                                @click="openAssignModal(
                                    '{{ $lap->id }}',
                                    {{ json_encode($lap->judul) }},
                                    {{ json_encode($lap->lokasi) }},
                                    {{ json_encode($lap->pelapor->nama_lengkap ?? 'Anonim') }}
                                )"
                                class="inline-flex items-center justify-center py-2 px-2 bg-indigo-600 text-white rounded-lg text-xs font-bold">
                                Tugaskan
                            </button>

                            <button type="button"
                                @click="openTolakModal('{{ $lap->id }}', {{ json_encode($lap->judul) }})"
                                class="inline-flex items-center justify-center py-2 px-2 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-lg text-xs font-bold">
                                Tolak
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 text-center text-xs text-gray-500 dark:text-gray-400">
                        Tidak ada laporan baru yang menunggu penugasan.
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- ========================================================================= --}}
            {{-- TAB 2: MONITORING PENUGASAN LAPANGAN --}}
            {{-- ========================================================================= --}}
            <div x-show="activeTab === 'monitoring'" x-cloak class="space-y-4">
                
                {{-- Filter & Search Form --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4 shadow-sm">
                    <form method="GET" action="{{ route('admin.penugasan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
                        <input type="hidden" name="tab" value="monitoring">

                        {{-- Search Input --}}
                        <div class="lg:col-span-5 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Cari judul, lokasi, pelapor, atau teknisi..." 
                                class="block w-full pl-9 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 text-xs focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        {{-- Status Filter --}}
                        <div class="lg:col-span-3">
                            <select name="status" class="block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-xs focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Semua Status Pengerjaan</option>
                                <option value="Ditugaskan" {{ request('status') === 'Ditugaskan' ? 'selected' : '' }}>Ditugaskan (Antrean)</option>
                                <option value="Dikerjakan" {{ request('status') === 'Dikerjakan' ? 'selected' : '' }}>Dikerjakan (Di Lapangan)</option>
                                <option value="Selesai" {{ request('status') === 'Selesai' ? 'selected' : '' }}>Selesai (Tuntas)</option>
                            </select>
                        </div>

                        {{-- Teknisi Filter --}}
                        <div class="lg:col-span-2">
                            <select name="teknisi_id" class="block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-xs focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Semua Teknisi</option>
                                @foreach($teknisi as $tek)
                                <option value="{{ $tek->id }}" {{ request('teknisi_id') == $tek->id ? 'selected' : '' }}>
                                    {{ $tek->nama_lengkap }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="lg:col-span-2 flex items-center gap-2">
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                                Filter
                            </button>
                            @if(request()->hasAny(['search', 'status', 'teknisi_id']))
                            <a href="{{ route('admin.penugasan.index', ['tab' => 'monitoring']) }}" class="inline-flex items-center justify-center px-3 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-xl transition" title="Reset filter">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Tabel Desktop Monitoring --}}
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden hidden md:block">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-6 py-3.5 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Laporan & Lokasi</th>
                                    <th class="px-6 py-3.5 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Teknisi Ditugaskan</th>
                                    <th class="px-6 py-3.5 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu Penugasan</th>
                                    <th class="px-6 py-3.5 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status Tugas</th>
                                    <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($penugasans as $p)
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/50 transition-colors">
                                    {{-- Laporan & Lokasi --}}
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.laporan.show', $p->laporan_id) }}" class="text-sm font-bold text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                            {{ $p->laporan->judul ?? 'Laporan #' . $p->laporan_id }}
                                        </a>
                                        <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            </svg>
                                            <span class="truncate max-w-xs">{{ $p->laporan->lokasi ?? '-' }}</span>
                                        </div>
                                        <div class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">
                                            Pelapor: {{ $p->laporan->pelapor->nama_lengkap ?? 'Anonim' }}
                                        </div>
                                    </td>

                                    {{-- Teknisi --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                                {{ strtoupper(substr($p->teknisi->nama_lengkap ?? 'T', 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                                    {{ $p->teknisi->nama_lengkap ?? 'Teknisi' }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $p->teknisi->no_telepon ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Waktu --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $p->assigned_at ? $p->assigned_at->format('d/m/Y H:i') : '-' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            {{ $p->assigned_at ? $p->assigned_at->diffForHumans() : '' }}
                                        </div>
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($p->status_tugas === 'Ditugaskan')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Ditugaskan
                                        </span>
                                        @elseif($p->status_tugas === 'Dikerjakan')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                            Dikerjakan
                                        </span>
                                        @elseif($p->status_tugas === 'Selesai')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                            <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Selesai
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                            {{ $p->status_tugas }}
                                        </span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.laporan.show', $p->laporan_id) }}" 
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-xs font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition shadow-sm">
                                                <svg class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Detail
                                            </a>

                                            @if($p->status_tugas === 'Selesai')
                                            <a href="{{ route('admin.laporan.export_pdf', $p->laporan_id) }}" target="_blank"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 rounded-lg text-xs font-bold hover:bg-emerald-100 transition shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                PDF
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center bg-gray-50/50 dark:bg-gray-800/30">
                                        <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                            <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 flex items-center justify-center mb-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                            </div>
                                            <h4 class="text-base font-bold text-gray-800 dark:text-gray-200">Tidak Ada Penugasan</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-center">
                                                Belum ada penugasan yang sesuai dengan kriteria filter saat ini.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($penugasans->hasPages())
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                        {{ $penugasans->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>

                {{-- Card Stack Mobile Monitoring --}}
                <div class="grid grid-cols-1 gap-3 md:hidden">
                    @forelse($penugasans as $p)
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-4 shadow-sm space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <span class="text-xs text-gray-400 dark:text-gray-500 font-mono">{{ $p->assigned_at ? $p->assigned_at->format('d/m/Y H:i') : '-' }}</span>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100 mt-0.5">
                                    {{ $p->laporan->judul ?? 'Laporan #' . $p->laporan_id }}
                                </h4>
                            </div>
                            <div>
                                @if($p->status_tugas === 'Ditugaskan')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                    Ditugaskan
                                </span>
                                @elseif($p->status_tugas === 'Dikerjakan')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                    Dikerjakan
                                </span>
                                @elseif($p->status_tugas === 'Selesai')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                    Selesai
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                <span>{{ $p->laporan->lokasi ?? '-' }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Teknisi: <strong>{{ $p->teknisi->nama_lengkap ?? 'Teknisi' }}</strong></span>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100 dark:border-gray-700">
                            <a href="{{ route('admin.laporan.show', $p->laporan_id) }}" class="inline-flex items-center justify-center py-2 px-3 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs font-bold text-gray-700 dark:text-gray-200">
                                Detail Laporan
                            </a>
                            @if($p->status_tugas === 'Selesai')
                            <a href="{{ route('admin.laporan.export_pdf', $p->laporan_id) }}" target="_blank" class="inline-flex items-center justify-center py-2 px-3 bg-emerald-600 text-white rounded-lg text-xs font-bold">
                                Unduh PDF
                            </a>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 text-center text-xs text-gray-500 dark:text-gray-400">
                        Tidak ada riwayat penugasan yang sesuai.
                    </div>
                    @endforelse

                    @if($penugasans->hasPages())
                    <div class="py-2">
                        {{ $penugasans->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>

            {{-- ========================================================================= --}}
            {{-- TAB 3: RADAR KESIAPAN TEKNISI --}}
            {{-- ========================================================================= --}}
            <div x-show="activeTab === 'teknisi'" x-cloak class="space-y-4">
                <div class="flex items-center justify-between px-1">
                    <div>
                        <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">
                            Ketersediaan & Beban Kerja Teknisi Lapangan
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Gunakan radar ini untuk mendistribusikan penugasan secara berimbang dan menghindari beban berlebih.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($teknisi as $tek)
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-sm flex flex-col justify-between hover:shadow-md transition-shadow">
                        <div>
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-2xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 font-extrabold text-sm flex items-center justify-center">
                                        {{ strtoupper(substr($tek->nama_lengkap, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                            {{ $tek->nama_lengkap }}
                                        </h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $tek->email }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Status Siaga Badge --}}
                                @if(($tek->tugas_aktif_count ?? 0) === 0)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Siaga (0 Tugas)
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    {{ $tek->tugas_aktif_count }} Tugas Aktif
                                </span>
                                @endif
                            </div>

                            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 space-y-1.5 text-xs text-gray-600 dark:text-gray-300">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-400 dark:text-gray-500">No. Telepon / WA:</span>
                                    @if($tek->no_telepon)
                                    <a href="tel:{{ $tek->no_telepon }}" class="font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $tek->no_telepon }}
                                    </a>
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-400 dark:text-gray-500">Status Akun:</span>
                                    <span class="font-medium text-emerald-600 dark:text-emerald-400">Aktif</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 flex items-center gap-2">
                            <a href="{{ route('admin.penugasan.index', ['tab' => 'monitoring', 'teknisi_id' => $tek->id]) }}" 
                                class="w-full inline-flex items-center justify-center py-2 px-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-xl text-xs font-bold transition">
                                Lihat Tugas Teknisi Ini
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-8 text-center text-xs text-gray-500 dark:text-gray-400">
                        Tidak ada akun teknisi aktif yang terdaftar.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ========================================================================= --}}
        {{-- MODAL 1: ASSIGN / PENUGASAN TEKNISI --}}
        {{-- ========================================================================= --}}
        <div x-show="assignModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-gray-950/75 dark:bg-black/80 flex items-center justify-center backdrop-blur-sm p-4">
            <div @click.away="assignModalOpen = false" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden transform transition-all border border-gray-200 dark:border-gray-700" x-transition>

                <form :action="'{{ route('admin.penugasan.store', 999) }}'.replace('999', selectedLaporanId)" method="POST">
                    @csrf
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                            Penugasan Teknisi Lapangan
                        </h3>
                        <button type="button" @click="assignModalOpen = false" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 p-1.5 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="px-6 py-5 space-y-4">
                        {{-- Ringkasan Laporan Terpilih --}}
                        <template x-if="selectedLaporanId && selectedJudul && !showLaporanDropdown">
                            <div class="p-3.5 bg-indigo-50/70 dark:bg-indigo-950/30 border border-indigo-100 dark:border-indigo-800/60 rounded-xl space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">Laporan Yang Ditugaskan</span>
                                    <button type="button" @click="showLaporanDropdown = true" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                                        Ganti Laporan
                                    </button>
                                </div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100" x-text="selectedJudul"></h4>
                                <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        </svg>
                                        <span x-text="selectedLokasi"></span>
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span x-text="selectedPelapor"></span>
                                    </span>
                                </div>
                            </div>
                        </template>

                        {{-- Dropdown Pilih Laporan (Tampil jika dibuka tanpa laporan spesifik atau ingin ganti) --}}
                        <div x-show="showLaporanDropdown || !selectedLaporanId">
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1.5 uppercase tracking-wider">
                                Pilih Laporan Kerusakan <span class="text-rose-500">*</span>
                            </label>
                            <select name="laporan_id" x-model="selectedLaporanId" @change="onLaporanSelectChange($event)" required 
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-xs py-2.5">
                                <option value="">-- Pilih Laporan Masuk --</option>
                                @foreach($laporanBaru as $lap)
                                <option value="{{ $lap->id }}" 
                                    data-judul="{{ $lap->judul }}" 
                                    data-lokasi="{{ $lap->lokasi }}" 
                                    data-pelapor="{{ $lap->pelapor->nama_lengkap ?? 'Anonim' }}">
                                    {{ $lap->judul }} - {{ $lap->lokasi }} ({{ $lap->created_at->diffForHumans() }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dropdown Pilih Teknisi dengan Info Beban Kerja --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1.5 uppercase tracking-wider">
                                Delegasikan Ke Teknisi <span class="text-rose-500">*</span>
                            </label>
                            <select name="teknisi_id" required 
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-xs py-2.5">
                                <option value="">-- Pilih Teknisi Bertugas --</option>
                                @foreach($teknisi as $tek)
                                <option value="{{ $tek->id }}">
                                    {{ $tek->nama_lengkap }} ({{ ($tek->tugas_aktif_count ?? 0) === 0 ? 'Tersedia - 0 tugas aktif' : $tek->tugas_aktif_count . ' tugas aktif' }})
                                </option>
                                @endforeach
                            </select>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1">
                                Rekomendasi: Prioritaskan teknisi dengan beban tugas aktif terkecil (Tersedia).
                            </p>
                        </div>

                        {{-- Instruksi Khusus --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1.5 uppercase tracking-wider">
                                Instruksi / Catatan Khusus Pengerjaan (Opsional)
                            </label>
                            <textarea name="instruksi" rows="3" 
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-xs placeholder-gray-400 dark:placeholder-gray-500" 
                                placeholder="Contoh: Bawa tangga lipat dan obeng tespen. Pastikan koordinasi dengan penanggung jawab ruangan sebelum tindakan."></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-2">
                        <button type="button" @click="assignModalOpen = false" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            Batal
                        </button>
                        <button type="submit" onclick="this.disabled=true; this.form.submit(); this.innerHTML='Menyimpan...';" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-sm hover:bg-indigo-700 transition">
                            Kirim Penugasan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- MODAL 2: PRATINJAU DETAIL LAPORAN --}}
        {{-- ========================================================================= --}}
        <div x-show="previewOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-gray-950/75 dark:bg-black/80 flex items-center justify-center backdrop-blur-sm p-4">
            <div @click.away="previewOpen = false" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden transform transition-all border border-gray-200 dark:border-gray-700" x-transition>

                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        Pratinjau Data Laporan
                    </h3>
                    <button @click="previewOpen = false" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-5 max-h-[70vh] overflow-y-auto space-y-5">
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100" x-text="p_judul"></h4>
                        <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <span class="flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                </svg>
                                <span x-text="p_lokasi"></span>
                            </span>
                            <span class="flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Pelapor: <span x-text="p_pelapor"></span>
                            </span>
                            <span class="flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span x-text="p_tanggal"></span>
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Deskripsi Kerusakan</p>
                            <div class="p-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-xs text-gray-700 dark:text-gray-200 leading-relaxed whitespace-pre-line min-h-[100px]" x-text="p_deskripsi"></div>
                        </div>

                        <div>
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Foto Kondisi Awal</p>
                            <template x-if="p_foto">
                                <img :src="p_foto" class="w-full h-44 bg-gray-100 dark:bg-gray-700 object-cover rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm cursor-zoom-in hover:opacity-95 transition" @click="window.open(p_foto, '_blank')" title="Klik untuk memperbesar">
                            </template>
                            <template x-if="!p_foto">
                                <div class="w-full h-44 bg-gray-50 dark:bg-gray-700/40 border-2 border-dashed border-gray-200 dark:border-gray-600 rounded-xl flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                    <svg class="w-8 h-8 mb-1.5 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-xs">Tidak ada foto dokumentasi</span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Direct Action Footer: Tidak terisolasi lagi! --}}
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 flex flex-wrap items-center justify-between gap-2">
                    <button type="button" @click="previewOpen = false" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl font-bold text-xs text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                        Tutup
                    </button>

                    <div class="flex items-center gap-2">
                        <button type="button" 
                            @click="previewOpen = false; openTolakModal(p_id, p_judul)" 
                            class="px-3.5 py-2 bg-white dark:bg-gray-800 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900/60 rounded-xl font-bold text-xs hover:bg-rose-50 dark:hover:bg-rose-950/40 transition">
                            Tolak Laporan
                        </button>
                        <button type="button" 
                            @click="previewOpen = false; openAssignModal(p_id, p_judul, p_lokasi, p_pelapor)" 
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-sm transition">
                            Tugaskan Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================================= --}}
        {{-- MODAL 3: TOLAK LAPORAN --}}
        {{-- ========================================================================= --}}
        <div x-show="tolakModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-gray-950/75 dark:bg-black/80 flex items-center justify-center backdrop-blur-sm p-4">
            <div @click.away="tolakModalOpen = false" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden transform transition-all border border-gray-200 dark:border-gray-700" x-transition>

                <form :action="'{{ route('admin.penugasan.tolak', 999) }}'.replace('999', tolakLaporanId)" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-rose-50 dark:bg-rose-900/20">
                        <h3 class="text-base font-bold text-rose-800 dark:text-rose-200 flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-rose-100 dark:bg-rose-900/50 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            Konfirmasi Tolak Laporan
                        </h3>
                        <button type="button" @click="tolakModalOpen = false" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 p-1.5 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="px-6 py-5 space-y-4">
                        <div class="p-3 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-xl">
                            <p class="text-xs text-rose-800 dark:text-rose-200">
                                Anda akan menolak laporan: <strong x-text="tolakJudul"></strong>. Pelapor akan menerima notifikasi beserta alasan penolakan ini.
                            </p>
                        </div>

                        <div>
                            <label for="alasan_penolakan" class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1.5 uppercase tracking-wider">
                                Alasan Penolakan <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="alasan_penolakan" id="alasan_penolakan" rows="4" required
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-rose-500 focus:ring-rose-500 text-xs placeholder-gray-400 dark:placeholder-gray-500"
                                placeholder="Jelaskan secara sopan dan jelas mengapa laporan ini tidak dapat ditindaklanjuti..."></textarea>
                            @error('alasan_penolakan')
                                <p class="text-xs text-rose-600 dark:text-rose-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-2">
                        <button type="button" @click="tolakModalOpen = false" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            Batal
                        </button>
                        <button type="submit" onclick="this.disabled=true; this.form.submit(); this.innerHTML='Memproses...';" class="px-4 py-2 bg-rose-600 text-white rounded-xl text-xs font-bold shadow-sm hover:bg-rose-700 transition">
                            Konfirmasi Tolak Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>