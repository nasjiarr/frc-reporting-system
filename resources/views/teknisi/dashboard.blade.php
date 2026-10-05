<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-bold text-2xl text-slate-900 dark:text-gray-100 tracking-tight leading-tight">
                    Dashboard Teknisi
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                    Pusat operasional dan penanganan perbaikan fasilitas gedung FRC.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 text-xs font-semibold border border-amber-200/80 dark:border-amber-800/60 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Teknisi Lapangan
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-2" x-data="{ 
        previewOpen: false,
        search: '',
        statusFilter: 'all',
        
        p_id: null,
        p_judul: '', 
        p_lokasi: '', 
        p_deskripsi: '', 
        p_instruksi: '', 
        p_foto: '',
        p_pelapor_nama: '',
        p_pelapor_telp: '',
        p_pelapor_wa: '',
        p_status: '',
        p_waktu: '',
        p_start_route: '',
        p_show_route: '',
        
        openPreview(id, judul, lokasi, deskripsi, instruksi, foto, pelaporNama, pelaporTelp, pelaporWa, status, waktu, startRoute, showRoute) {
            this.p_id = id;
            this.p_judul = judul;
            this.p_lokasi = lokasi;
            this.p_deskripsi = deskripsi;
            this.p_instruksi = instruksi;
            this.p_foto = foto;
            this.p_pelapor_nama = pelaporNama;
            this.p_pelapor_telp = pelaporTelp;
            this.p_pelapor_wa = pelaporWa;
            this.p_status = status;
            this.p_waktu = waktu;
            this.p_start_route = startRoute;
            this.p_show_route = showRoute;
            this.previewOpen = true;
        },

        matchesFilter(status, text) {
            const matchStatus = (this.statusFilter === 'all') || (status === this.statusFilter);
            const q = this.search.toLowerCase().trim();
            if (!q) return matchStatus;
            return matchStatus && text.toLowerCase().includes(q);
        }
    }">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Flash Notification -->
            @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-1 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" @click="$el.closest('div').remove()" class="text-emerald-500 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            @endif

            @if(session('info'))
            <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-1 rounded-lg bg-blue-100 dark:bg-blue-900/60 text-blue-600 dark:text-blue-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold">{{ session('info') }}</span>
                </div>
                <button type="button" @click="$el.closest('div').remove()" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            @endif

            <!-- Hero Section / Greeting Banner -->
            <div class="relative overflow-hidden bg-gradient-to-br from-amber-500/10 via-white to-indigo-50/40 dark:from-slate-800 dark:via-slate-800/90 dark:to-amber-950/20 rounded-2xl border border-amber-200/60 dark:border-slate-700/80 p-5 sm:p-7 shadow-sm">
                <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-amber-500/10 dark:bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                    <div class="space-y-1.5 max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-white dark:bg-slate-700/70 text-slate-700 dark:text-slate-200 text-xs font-medium border border-slate-200/80 dark:border-slate-600 shadow-xs">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                            Halo, {{ auth()->user()->nama_lengkap ?? auth()->user()->name }}! 🛠️
                        </h1>
                        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            @if($totalAktif > 0)
                                Terdapat <strong class="text-amber-600 dark:text-amber-400 font-bold">{{ $totalAktif }} tugas aktif</strong> yang menanti intervensi Anda ({{ $totalDitugaskan }} baru masuk, {{ $totalDikerjakan }} dalam proses). Pastikan alat pelindung diri (APD) terpasang sebelum bekerja.
                            @else
                                Luar biasa! Seluruh penugasan perbaikan telah diselesaikan. Tetap siaga untuk tugas berikutnya.
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
                        <a href="{{ route('teknisi.riwayat') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-600 shadow-sm transition gap-2">
                            <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Riwayat Pekerjaan</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Modern KPI Stat Cards (4 Cards Grid) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5">
                <!-- 1. Tugas Baru Ditugaskan -->
                <div @click="statusFilter = statusFilter === 'Ditugaskan' ? 'all' : 'Ditugaskan'"
                     :class="{'ring-2 ring-amber-500': statusFilter === 'Ditugaskan'}"
                     class="cursor-pointer bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between group hover:-translate-y-0.5">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-300">Baru Ditugaskan</span>
                        <div class="p-2 sm:p-2.5 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border border-amber-200/50 dark:border-amber-800/40 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-baseline justify-between">
                            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">{{ $totalDitugaskan }}</p>
                            <span class="text-xs font-semibold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/40 px-2 py-0.5 rounded-full border border-amber-200 dark:border-amber-800/60">
                                Butuh Respon
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Belum dikerjakan</p>
                    </div>
                </div>

                <!-- 2. Sedang Dikerjakan -->
                <div @click="statusFilter = statusFilter === 'Dikerjakan' ? 'all' : 'Dikerjakan'"
                     :class="{'ring-2 ring-blue-500': statusFilter === 'Dikerjakan'}"
                     class="cursor-pointer bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between group hover:-translate-y-0.5">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-300">Sedang Dikerjakan</span>
                        <div class="p-2 sm:p-2.5 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-200/50 dark:border-blue-800/40 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-baseline justify-between">
                            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">{{ $totalDikerjakan }}</p>
                            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/40 px-2 py-0.5 rounded-full border border-blue-200 dark:border-blue-800/60">
                                In Progress
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Dalam proses perbaikan</p>
                    </div>
                </div>

                <!-- 3. Selesai Bulan Ini -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between group hover:-translate-y-0.5">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-300">Selesai Bulan Ini</span>
                        <div class="p-2 sm:p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-800/40 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-baseline justify-between">
                            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">{{ $totalSelesaiBulanIni }}</p>
                            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/40 px-2 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800/60">
                                {{ now()->translatedFormat('M Y') }}
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Pencapaian performa</p>
                    </div>
                </div>

                <!-- 4. Total Selesai Riwayat -->
                <a href="{{ route('teknisi.riwayat') }}" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between group hover:-translate-y-0.5">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-300">Total Riwayat Selesai</span>
                        <div class="p-2 sm:p-2.5 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 border border-purple-200/50 dark:border-purple-800/40 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-baseline justify-between">
                            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">{{ $totalSelesaiSemua }}</p>
                            <span class="text-xs font-semibold text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/40 px-2 py-0.5 rounded-full border border-purple-200 dark:border-purple-800/60 inline-flex items-center gap-1 group-hover:bg-purple-100 dark:group-hover:bg-purple-900/60 transition-colors">
                                Arsip &rarr;
                            </span>
                        </div>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Keseluruhan pekerjaan</p>
                    </div>
                </a>
            </div>

            <!-- Main Work Hub: Penugasan Aktif -->
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 rounded-2xl shadow-sm overflow-hidden">
                <!-- Section Header & Quick Search Controls -->
                <div class="p-5 sm:p-6 border-b border-slate-200/80 dark:border-slate-700/80 space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                <span>Daftar Penugasan Aktif</span>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                                    {{ $totalAktif }} Tugas
                                </span>
                            </h3>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                                Kendala yang saat ini membutuhkan tindakan perbaikan Anda di lapangan.
                            </p>
                        </div>

                        <!-- Status Filter Tabs -->
                        <div class="flex items-center gap-1.5 p-1 bg-slate-100 dark:bg-slate-900/80 border border-transparent dark:border-slate-700/70 rounded-xl self-start sm:self-auto overflow-x-auto max-w-full">
                            <button type="button"
                                    @click="statusFilter = 'all'"
                                    :class="statusFilter === 'all' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-xs font-bold border border-slate-200/60 dark:border-slate-700' : 'text-slate-600 dark:text-slate-400 font-medium hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/50 dark:hover:bg-slate-800/50'"
                                    class="px-3 py-1.5 rounded-lg text-xs transition-all whitespace-nowrap">
                                Semua ({{ $totalAktif }})
                            </button>
                            <button type="button"
                                    @click="statusFilter = 'Ditugaskan'"
                                    :class="statusFilter === 'Ditugaskan' ? 'bg-white dark:bg-slate-800 text-amber-600 dark:text-amber-400 shadow-xs font-bold border border-slate-200/60 dark:border-slate-700' : 'text-slate-600 dark:text-slate-400 font-medium hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/50 dark:hover:bg-slate-800/50'"
                                    class="px-3 py-1.5 rounded-lg text-xs transition-all whitespace-nowrap flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Baru ({{ $totalDitugaskan }})
                            </button>
                            <button type="button"
                                    @click="statusFilter = 'Dikerjakan'"
                                    :class="statusFilter === 'Dikerjakan' ? 'bg-white dark:bg-slate-800 text-blue-600 dark:text-blue-400 shadow-xs font-bold border border-slate-200/60 dark:border-slate-700' : 'text-slate-600 dark:text-slate-400 font-medium hover:text-slate-900 dark:hover:text-white hover:bg-slate-200/50 dark:hover:bg-slate-800/50'"
                                    class="px-3 py-1.5 rounded-lg text-xs transition-all whitespace-nowrap flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                Dikerjakan ({{ $totalDikerjakan }})
                            </button>
                        </div>
                    </div>

                    <!-- Search Input -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text"
                               x-model="search"
                               placeholder="Cari tugas berdasarkan judul kerusakan, lokasi, atau nama pelapor..."
                               class="w-full pl-10 pr-9 py-2.5 text-xs sm:text-sm rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900/60 dark:text-gray-100 placeholder-slate-400 dark:placeholder-slate-500 focus:border-amber-500 focus:ring-amber-500 dark:focus:border-amber-500 dark:focus:ring-amber-500 transition-colors shadow-xs">
                        <button type="button"
                                x-show="search.length > 0"
                                @click="search = ''"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>

                <!-- =============================================================== -->
                <!-- DUAL VIEW: MOBILE JOB CARDS (Visible on sm & below: block md:hidden) -->
                <!-- =============================================================== -->
                <div class="block md:hidden p-4 space-y-4 bg-slate-50/60 dark:bg-slate-900/40">
                    @forelse($tugasAktif as $tugas)
                    @php
                        $pelaporNama = $tugas->laporan->pelapor->nama_lengkap ?? $tugas->laporan->pelapor->name ?? 'Pelapor';
                        $rawPhone = $tugas->laporan->pelapor->no_telepon ?? '';
                        $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                        if (str_starts_with($cleanPhone, '0')) {
                            $waPhone = '62' . substr($cleanPhone, 1);
                        } else {
                            $waPhone = $cleanPhone;
                        }
                        $waText = urlencode("Halo {$pelaporNama}, saya teknisi FRC terkait laporan kerusakan: '{$tugas->laporan->judul}' di {$tugas->laporan->lokasi}.");
                        $waUrl = !empty($cleanPhone) ? "https://wa.me/{$waPhone}?text={$waText}" : '';
                        $telUrl = !empty($cleanPhone) ? "tel:{$cleanPhone}" : '';
                        $waktuRelatif = $tugas->assigned_at ? $tugas->assigned_at->diffForHumans() : $tugas->created_at->diffForHumans();
                        $searchHaystack = strtolower($tugas->laporan->judul . ' ' . $tugas->laporan->lokasi . ' ' . $pelaporNama);
                    @endphp

                    <div x-show="matchesFilter('{{ $tugas->status_tugas }}', '{{ addslashes($searchHaystack) }}')"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700/80 p-4 shadow-sm space-y-3.5">

                        <!-- Card Top: Status & Relative Time -->
                        <div class="flex items-center justify-between gap-2">
                            @if($tugas->status_tugas === 'Ditugaskan')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold uppercase bg-amber-50 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Baru Ditugaskan
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold uppercase bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60">
                                    <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Sedang Dikerjakan
                                </span>
                            @endif

                            <div class="text-[11px] text-slate-400 dark:text-slate-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $waktuRelatif }}</span>
                            </div>
                        </div>

                        <!-- Card Body: Judul & Lokasi -->
                        <div>
                            <h4 class="text-base font-bold text-slate-900 dark:text-white leading-snug">
                                {{ $tugas->laporan->judul }}
                            </h4>
                            <div class="mt-1.5 text-xs text-slate-600 dark:text-slate-300 flex items-center gap-1.5 font-medium">
                                <svg class="w-3.5 h-3.5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $tugas->laporan->lokasi }}</span>
                            </div>
                        </div>

                        <!-- Pelapor Information & Direct WhatsApp / Call -->
                        <div class="bg-slate-50 dark:bg-slate-900/60 rounded-xl p-2.5 border border-slate-200/70 dark:border-slate-700/60 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-7 h-7 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($pelaporNama, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate">{{ $pelaporNama }}</p>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400">Pelapor Fasilitas</p>
                                </div>
                            </div>

                            @if($waUrl)
                            <div class="flex items-center gap-1 flex-shrink-0">
                                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-semibold shadow-xs transition" title="Hubungi via WhatsApp">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                    </svg>
                                    <span>WA</span>
                                </a>
                                @if($telUrl)
                                <a href="{{ $telUrl }}" class="p-1.5 rounded-lg bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-300 dark:hover:bg-slate-600 transition" title="Telepon">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </a>
                                @endif
                            </div>
                            @else
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 italic">No kontak -</span>
                            @endif
                        </div>

                        <!-- Admin Instruction Snippet if available -->
                        @if(!empty($tugas->instruksi))
                        <div class="p-2.5 rounded-lg bg-amber-50/70 dark:bg-amber-950/30 border border-amber-200/80 dark:border-amber-800/60 text-xs">
                            <span class="font-bold text-amber-800 dark:text-amber-300 text-[10px] uppercase tracking-wider block mb-0.5">Instruksi Admin:</span>
                            <p class="text-amber-900 dark:text-amber-200 italic line-clamp-2">"{{ $tugas->instruksi }}"</p>
                        </div>
                        @endif

                        <!-- Card Action Buttons (Mobile-first large tap targets: min 44px height) -->
                        <div class="pt-1 flex items-center gap-2">
                            <button type="button"
                                    @click="openPreview(
                                        {{ $tugas->id }},
                                        {{ json_encode($tugas->laporan->judul) }},
                                        {{ json_encode($tugas->laporan->lokasi) }},
                                        {{ json_encode($tugas->laporan->deskripsi) }},
                                        {{ json_encode($tugas->instruksi ?? 'Tidak ada instruksi khusus.') }},
                                        {{ json_encode($tugas->laporan->foto_sebelum ? asset('storage/' . $tugas->laporan->foto_sebelum) : '') }},
                                        {{ json_encode($pelaporNama) }},
                                        {{ json_encode($rawPhone) }},
                                        {{ json_encode($waUrl) }},
                                        {{ json_encode($tugas->status_tugas) }},
                                        {{ json_encode($waktuRelatif) }},
                                        {{ json_encode(route('teknisi.tugas.mulai', $tugas->id)) }},
                                        {{ json_encode(route('teknisi.tugas.show', $tugas->id)) }}
                                    )"
                                    class="flex-1 min-h-[44px] inline-flex items-center justify-center gap-1.5 px-3 py-2.5 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-semibold rounded-xl text-xs transition border border-slate-200 dark:border-slate-600 shadow-xs">
                                <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Preview</span>
                            </button>

                            @if($tugas->status_tugas === 'Ditugaskan')
                                <form action="{{ route('teknisi.tugas.mulai', $tugas->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                            class="w-full min-h-[44px] inline-flex items-center justify-center gap-1.5 px-3 py-2.5 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white font-bold rounded-xl text-xs transition shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Mulai Kerjakan</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('teknisi.tugas.show', $tugas->id) }}"
                                   class="flex-1 min-h-[44px] inline-flex items-center justify-center gap-1.5 px-3 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold rounded-xl text-xs transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Form Selesai</span>
                                </a>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="py-12 px-4 text-center bg-white dark:bg-slate-800 rounded-xl border border-dashed border-slate-200 dark:border-slate-700">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 mb-3">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">Tidak Ada Tugas Aktif</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Semua pekerjaan telah rampung! Nikmati waktu istirahat Anda.</p>
                    </div>
                    @endforelse
                </div>

                <!-- =============================================================== -->
                <!-- DUAL VIEW: DESKTOP TABLE (Visible on md & above: hidden md:block) -->
                <!-- =============================================================== -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full whitespace-nowrap text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 dark:bg-slate-900/60 text-slate-500 dark:text-slate-400 text-xs font-semibold uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">
                                <th class="py-3.5 px-4">No</th>
                                <th class="py-3.5 px-4">Judul Kerusakan & Lokasi</th>
                                <th class="py-3.5 px-4">Pelapor & Kontak</th>
                                <th class="py-3.5 px-4">Waktu Masuk</th>
                                <th class="py-3.5 px-4 text-center">Status</th>
                                <th class="py-3.5 px-4 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-200 dark:divide-slate-700/80">
                            @forelse($tugasAktif as $index => $tugas)
                            @php
                                $pelaporNama = $tugas->laporan->pelapor->nama_lengkap ?? $tugas->laporan->pelapor->name ?? 'Pelapor';
                                $rawPhone = $tugas->laporan->pelapor->no_telepon ?? '';
                                $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                                if (str_starts_with($cleanPhone, '0')) {
                                    $waPhone = '62' . substr($cleanPhone, 1);
                                } else {
                                    $waPhone = $cleanPhone;
                                }
                                $waText = urlencode("Halo {$pelaporNama}, saya teknisi FRC terkait laporan kerusakan: '{$tugas->laporan->judul}' di {$tugas->laporan->lokasi}.");
                                $waUrl = !empty($cleanPhone) ? "https://wa.me/{$waPhone}?text={$waText}" : '';
                                $telUrl = !empty($cleanPhone) ? "tel:{$cleanPhone}" : '';
                                $waktuRelatif = $tugas->assigned_at ? $tugas->assigned_at->diffForHumans() : $tugas->created_at->diffForHumans();
                                $searchHaystack = strtolower($tugas->laporan->judul . ' ' . $tugas->laporan->lokasi . ' ' . $pelaporNama);
                            @endphp

                            <tr x-show="matchesFilter('{{ $tugas->status_tugas }}', '{{ addslashes($searchHaystack) }}')"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors duration-150">
                                
                                <td class="py-4 px-4 text-xs font-semibold text-slate-500 dark:text-slate-400">
                                    {{ $index + 1 }}
                                </td>

                                <td class="py-4 px-4 whitespace-normal min-w-[240px]">
                                    <div class="font-bold text-slate-900 dark:text-slate-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                        {{ $tugas->laporan->judul }}
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1.5 font-medium">
                                        <svg class="w-3.5 h-3.5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span>{{ $tugas->laporan->lokasi }}</span>
                                    </div>
                                </td>

                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($pelaporNama, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $pelaporNama }}</div>
                                            @if($waUrl)
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                                                   class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-0.5">
                                                    <span>Chat WA</span>
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                </a>
                                                @if($telUrl)
                                                <span class="text-slate-300 dark:text-slate-600">•</span>
                                                <a href="{{ $telUrl }}" class="text-[11px] text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                                                    {{ $rawPhone }}
                                                </a>
                                                @endif
                                            </div>
                                            @else
                                            <span class="text-[11px] text-slate-400 dark:text-slate-500 italic">Tanpa nomor kontak</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-4 text-xs text-slate-600 dark:text-slate-400">
                                    <div class="font-medium text-slate-800 dark:text-slate-200">{{ $waktuRelatif }}</div>
                                    <div class="text-[11px] text-slate-400 dark:text-slate-500">
                                        {{ $tugas->assigned_at ? $tugas->assigned_at->format('d M, H:i') : $tugas->created_at->format('d M, H:i') }}
                                    </div>
                                </td>

                                <td class="py-4 px-4 text-center">
                                    @if($tugas->status_tugas === 'Ditugaskan')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase bg-amber-50 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60 shadow-xs">
                                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                            Ditugaskan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60 shadow-xs">
                                            <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Dikerjakan
                                        </span>
                                    @endif
                                </td>

                                <td class="py-4 px-4 text-right">
                                    <div class="inline-flex items-center justify-end gap-2">
                                        <!-- Preview Button -->
                                        <button type="button"
                                            @click="openPreview(
                                                {{ $tugas->id }},
                                                {{ json_encode($tugas->laporan->judul) }},
                                                {{ json_encode($tugas->laporan->lokasi) }},
                                                {{ json_encode($tugas->laporan->deskripsi) }},
                                                {{ json_encode($tugas->instruksi ?? 'Tidak ada instruksi khusus.') }},
                                                {{ json_encode($tugas->laporan->foto_sebelum ? asset('storage/' . $tugas->laporan->foto_sebelum) : '') }},
                                                {{ json_encode($pelaporNama) }},
                                                {{ json_encode($rawPhone) }},
                                                {{ json_encode($waUrl) }},
                                                {{ json_encode($tugas->status_tugas) }},
                                                {{ json_encode($waktuRelatif) }},
                                                {{ json_encode(route('teknisi.tugas.mulai', $tugas->id)) }},
                                                {{ json_encode(route('teknisi.tugas.show', $tugas->id)) }}
                                            )"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold rounded-xl text-xs transition shadow-xs" title="Preview Detail Penugasan">
                                            <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <span>Detail</span>
                                        </button>

                                        <!-- Direct Status Update or Execution -->
                                        @if($tugas->status_tugas === 'Ditugaskan')
                                            <form action="{{ route('teknisi.tugas.mulai', $tugas->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white font-bold rounded-xl text-xs transition shadow-xs" title="Tandai Anda sedang menuju ke lokasi / mulai perbaikan">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>Mulai Kerja</span>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('teknisi.tugas.show', $tugas->id) }}"
                                               class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold rounded-xl text-xs transition shadow-xs" title="Buka formulir pelaporan hasil perbaikan">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Form Selesai</span>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 px-4 text-center">
                                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 mb-3">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Tidak ada tugas aktif saat ini</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Seluruh kendala fasilitas telah terselesaikan dengan baik.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- =============================================================== -->
            <!-- ENHANCED PREVIEW MODAL (With Reporter Contact, Direct Actions) -->
            <!-- =============================================================== -->
            <div x-show="previewOpen" style="display: none;"
                 class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/70 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4"
                 x-transition:enter="ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">

                <div @click.away="previewOpen = false"
                     class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden transform transition-all border border-slate-200 dark:border-slate-700"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95">

                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50">
                        <div class="flex items-center gap-2.5">
                            <div class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white">
                                    Detail Informasi Penugasan
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400" x-text="'Waktu Penugasan: ' + p_waktu"></p>
                            </div>
                        </div>

                        <button @click="previewOpen = false" class="text-slate-400 dark:text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-5 max-h-[75vh] overflow-y-auto space-y-5" style="scrollbar-width: thin;">

                        <!-- Title & Location -->
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider"
                                      :class="p_status === 'Ditugaskan' ? 'bg-amber-100 dark:bg-amber-900/40 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60' : 'bg-blue-100 dark:bg-blue-900/40 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60'"
                                      x-text="p_status"></span>
                            </div>
                            <h4 class="text-xl font-extrabold text-slate-900 dark:text-white leading-tight" x-text="p_judul"></h4>
                            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 flex items-center gap-1.5 font-medium">
                                <svg class="w-4 h-4 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span x-text="p_lokasi"></span>
                            </p>
                        </div>

                        <!-- Reporter Contact Card -->
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-700/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-bold text-sm flex items-center justify-center flex-shrink-0">
                                    <span x-text="p_pelapor_nama.charAt(0).toUpperCase()"></span>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-400 dark:text-slate-400 uppercase font-semibold">Pelapor Fasilitas</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white" x-text="p_pelapor_nama"></p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400" x-text="p_pelapor_telp ? p_pelapor_telp : 'Tidak mencantumkan nomor telepon'"></p>
                                </div>
                            </div>

                            <template x-if="p_pelapor_wa">
                                <div class="flex items-center gap-2">
                                    <a :href="p_pelapor_wa" target="_blank" rel="noopener noreferrer"
                                       class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-xs transition shadow-xs">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                        </svg>
                                        <span>Hubungi WhatsApp</span>
                                    </a>
                                </div>
                            </template>
                        </div>

                        <!-- Admin Instruction Box -->
                        <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 rounded-xl p-4">
                            <p class="text-xs font-bold text-amber-800 dark:text-amber-300 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Instruksi Khusus Admin
                            </p>
                            <p class="text-sm text-amber-900 dark:text-amber-200 italic font-medium leading-relaxed" x-text="p_instruksi"></p>
                        </div>

                        <!-- Description & Initial Photo -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Deskripsi Kerusakan</p>
                                <div class="bg-slate-50 dark:bg-slate-900/50 p-3.5 rounded-xl border border-slate-200/80 dark:border-slate-700/80">
                                    <p class="text-sm text-slate-800 dark:text-slate-200 leading-relaxed whitespace-pre-line" x-text="p_deskripsi"></p>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Foto Kondisi Awal</p>

                                <template x-if="p_foto">
                                    <div class="relative group cursor-zoom-in" @click="window.open(p_foto, '_blank')">
                                        <img :src="p_foto" class="w-full h-44 object-cover rounded-xl border border-slate-200 dark:border-slate-700 shadow-xs group-hover:opacity-95 transition">
                                        <div class="absolute inset-0 bg-slate-900/30 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center text-white text-xs font-semibold gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                            Klik untuk Memperbesar
                                        </div>
                                    </div>
                                </template>

                                <template x-if="!p_foto">
                                    <div class="w-full h-44 bg-slate-50 dark:bg-slate-900/50 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl flex flex-col items-center justify-center text-slate-400 dark:text-slate-500 p-4 text-center">
                                        <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-xs font-medium">Pelapor tidak melampirkan foto</span>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/60 border-t border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-3">
                        <button type="button" @click="previewOpen = false" class="w-full sm:w-auto px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 rounded-xl font-bold text-xs hover:bg-slate-50 dark:hover:bg-slate-600 transition shadow-xs">
                            Tutup Preview
                        </button>

                        <div class="w-full sm:w-auto flex items-center gap-2">
                            <template x-if="p_status === 'Ditugaskan'">
                                <form :action="p_start_route" method="POST" class="w-full sm:w-auto">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center justify-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Mulai Kerjakan Sekarang</span>
                                    </button>
                                </form>
                            </template>

                            <a :href="p_show_route" class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-xs shadow-sm transition flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Buka Halaman Form</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>