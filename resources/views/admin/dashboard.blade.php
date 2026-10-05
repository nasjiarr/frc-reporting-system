<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 dark:text-gray-100 tracking-tight leading-tight">
                    Dashboard Admin
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    Pusat kendali operasional fasilitas, monitoring utilitas, dan penugasan teknisi FRC.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 text-xs font-semibold border border-indigo-200/80 dark:border-indigo-800/60 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    Admin Operasional
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-2" x-data="{ 
        previewOpen: false, 
        assignModalOpen: false, 
        tolakModalOpen: false,
        selectedLaporanId: '', 
        selectedLaporanJudul: '',
        tolakLaporanId: '',
        tolakJudul: '',
        
        p_id: null,
        p_judul: '', 
        p_pelapor: '',
        p_kontak: '',
        p_lokasi: '', 
        p_deskripsi: '', 
        p_foto: '',
        p_waktu: '',
        
        openPreview(id, judul, pelapor, kontak, lokasi, deskripsi, foto, waktu) {
            this.p_id = id;
            this.p_judul = judul;
            this.p_pelapor = pelapor;
            this.p_kontak = kontak;
            this.p_lokasi = lokasi;
            this.p_deskripsi = deskripsi;
            this.p_foto = foto;
            this.p_waktu = waktu;
            this.previewOpen = true;
        },

        openAssignModal(id = '', judul = '') {
            this.selectedLaporanId = id;
            this.selectedLaporanJudul = judul;
            this.previewOpen = false;
            this.assignModalOpen = true;
        },

        openTolakModal(id, judul) {
            this.tolakLaporanId = id;
            this.tolakJudul = judul;
            this.previewOpen = false;
            this.tolakModalOpen = true;
        }
    }">
        <div class="space-y-6">

            <!-- Hero Section / Greeting Banner -->
            <div class="relative overflow-hidden bg-gradient-to-br from-indigo-500/10 via-white to-blue-50/40 dark:from-gray-800 dark:via-gray-800 dark:to-indigo-950/30 rounded-2xl border border-indigo-200/60 dark:border-gray-700 p-5 sm:p-7 shadow-sm">
                <div class="absolute -right-8 -bottom-8 w-48 h-48 bg-indigo-500/10 dark:bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                    <div class="space-y-1.5 max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-md bg-white dark:bg-gray-700/70 text-gray-700 dark:text-gray-200 text-xs font-medium border border-gray-200/80 dark:border-gray-600 shadow-sm">
                            <svg class="w-3.5 h-3.5 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            Halo, {{ auth()->user()->nama_lengkap ?? auth()->user()->name }}! 👋
                        </h1>
                        <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                            @if($stats['laporan_baru'] > 0)
                                Terdapat <strong class="text-rose-600 dark:text-rose-400 font-bold">{{ $stats['laporan_baru'] }} laporan baru</strong> yang menanti respon penugasan teknisi dan <strong class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $stats['tugas_aktif'] }} tugas lapangan</strong> sedang berlangsung.
                            @else
                                Seluruh laporan baru telah didelegasikan! Saat ini terdapat <strong class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $stats['tugas_aktif'] }} penugasan aktif</strong> yang sedang ditangani oleh tim teknisi di lapangan.
                            @endif
                        </p>
                    </div>

                    <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
                        <a href="{{ route('admin.laporan.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-sm transition gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Buat Laporan</span>
                        </a>
                        <a href="{{ route('admin.penugasan.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-semibold rounded-xl border border-gray-200 dark:border-gray-600 shadow-sm transition gap-2">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <span>Kelola Penugasan</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Utilitas Status Banner -->
            @if($bln_ini_belum_isi)
            <div class="overflow-hidden bg-amber-50/90 dark:bg-amber-950/30 border-l-4 border-amber-500 rounded-xl p-4 sm:p-5 shadow-sm border border-amber-200/80 dark:border-amber-800/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 shrink-0">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-amber-900 dark:text-amber-200">Peringatan Pencatatan Utilitas</h4>
                        <p class="text-xs text-amber-700 dark:text-amber-300 mt-0.5">
                            Data utilitas gedung untuk periode bulan <strong>{{ now()->translatedFormat('F Y') }}</strong> belum dicatat.
                        </p>
                    </div>
                </div>
                <a href="{{ route('admin.utilitas.create') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg text-xs font-bold transition shadow-sm whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Catat Sekarang
                </a>
            </div>
            @else
            <div class="overflow-hidden bg-gray-50/80 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-xs shadow-sm">
                <div class="flex items-center gap-2.5 text-gray-700 dark:text-gray-300">
                    <span class="inline-flex p-1 rounded-md bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <span>Pencatatan utilitas periode <strong>{{ now()->translatedFormat('F Y') }}</strong> telah tersimpan ({{ $utilitasCount }} jenis utilitas).</span>
                </div>
                <a href="{{ route('admin.utilitas.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 inline-flex items-center gap-1">
                    Buka Rekap Utilitas &rarr;
                </a>
            </div>
            @endif

            <!-- Clickable KPI Stat Cards (4 Cards Grid) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5">
                
                <!-- 1. Laporan Baru -->
                <a href="{{ route('admin.penugasan.index') }}" class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-rose-400 dark:hover:border-rose-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-300 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">Laporan Baru</span>
                        <div class="p-2 sm:p-2.5 rounded-xl bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 border border-rose-200/50 dark:border-rose-800/40 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-baseline justify-between">
                            <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">{{ $stats['laporan_baru'] }}</p>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full border {{ $stats['laporan_baru'] > 0 ? 'bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800/60' : 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60' }}">
                                {{ $stats['laporan_baru'] > 0 ? 'Perlu Respon' : 'Nihil' }}
                            </span>
                        </div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 flex items-center justify-between">
                            <span>Menunggu teknisi</span>
                            <span class="text-indigo-600 dark:text-indigo-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </p>
                    </div>
                </a>

                <!-- 2. Penugasan Aktif -->
                <a href="{{ route('admin.penugasan.index') }}" class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-amber-400 dark:hover:border-amber-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-300 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">Penugasan Aktif</span>
                        <div class="p-2 sm:p-2.5 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 border border-amber-200/50 dark:border-amber-800/40 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-baseline justify-between">
                            <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">{{ $stats['tugas_aktif'] }}</p>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full border bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800/60">
                                Di Lapangan
                            </span>
                        </div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 flex items-center justify-between">
                            <span>Sedang ditangani</span>
                            <span class="text-indigo-600 dark:text-indigo-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </p>
                    </div>
                </a>

                <!-- 3. Selesai (Bulan Ini) -->
                <a href="{{ route('admin.laporan.selesai') }}" class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-emerald-400 dark:hover:border-emerald-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-300 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Selesai (Bulan Ini)</span>
                        <div class="p-2 sm:p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-800/40 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-baseline justify-between">
                            <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">{{ $stats['selesai_bulan_ini'] }}</p>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full border bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800/60">
                                {{ now()->translatedFormat('M Y') }}
                            </span>
                        </div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 flex items-center justify-between">
                            <span>Laporan teratasi</span>
                            <span class="text-indigo-600 dark:text-indigo-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </p>
                    </div>
                </a>

                <!-- 4. Pengguna Aktif -->
                <a href="{{ route('admin.users.index') }}" class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-violet-400 dark:hover:border-violet-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-300 group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">Pengguna Aktif</span>
                        <div class="p-2 sm:p-2.5 rounded-xl bg-violet-50 dark:bg-violet-900/30 text-violet-600 dark:text-violet-400 border border-violet-200/50 dark:border-violet-800/40 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-baseline justify-between">
                            <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">{{ $stats['pengguna_aktif'] }}</p>
                            <span class="text-[11px] font-bold px-2 py-0.5 rounded-full border bg-violet-50 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300 border-violet-200 dark:border-violet-800/60">
                                Akun Aktif
                            </span>
                        </div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 flex items-center justify-between">
                            <span>Kelola pengguna</span>
                            <span class="text-indigo-600 dark:text-indigo-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </p>
                    </div>
                </a>

            </div>

            <!-- Quick Action Bar (Pintas Operasional Admin) -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-4 sm:p-5 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3.5 px-1">
                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Aksi Cepat Operasional
                    </h3>
                    <span class="text-[11px] text-gray-400 dark:text-gray-500">Pintasan menu yang sering digunakan</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-3">
                    <a href="{{ route('admin.utilitas.create') }}" class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-indigo-50/70 dark:bg-gray-700/40 dark:hover:bg-gray-700/80 border border-gray-200/80 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-300 transition group shadow-2xs">
                        <div class="p-2 rounded-lg bg-white dark:bg-gray-800 text-amber-500 dark:text-amber-400 border border-gray-200/60 dark:border-gray-700 shadow-xs group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-xs font-bold leading-tight text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">Catat Utilitas</span>
                            <span class="block text-[10px] text-gray-500 dark:text-gray-400">Listrik & Air</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.laporan.create') }}" class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-indigo-50/70 dark:bg-gray-700/40 dark:hover:bg-gray-700/80 border border-gray-200/80 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-300 transition group shadow-2xs">
                        <div class="p-2 rounded-lg bg-white dark:bg-gray-800 text-indigo-500 dark:text-indigo-400 border border-gray-200/60 dark:border-gray-700 shadow-xs group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-xs font-bold leading-tight text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">Buat Laporan</span>
                            <span class="block text-[10px] text-gray-500 dark:text-gray-400">Kerusakan Baru</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-indigo-50/70 dark:bg-gray-700/40 dark:hover:bg-gray-700/80 border border-gray-200/80 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-300 transition group shadow-2xs">
                        <div class="p-2 rounded-lg bg-white dark:bg-gray-800 text-violet-500 dark:text-violet-400 border border-gray-200/60 dark:border-gray-700 shadow-xs group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-xs font-bold leading-tight text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">Kelola Akun</span>
                            <span class="block text-[10px] text-gray-500 dark:text-gray-400">Teknisi & Pelapor</span>
                        </div>
                    </a>

                    <a href="{{ route('admin.laporan.export_all') }}" class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-indigo-50/70 dark:bg-gray-700/40 dark:hover:bg-gray-700/80 border border-gray-200/80 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:text-indigo-600 dark:hover:text-indigo-300 transition group shadow-2xs">
                        <div class="p-2 rounded-lg bg-white dark:bg-gray-800 text-emerald-500 dark:text-emerald-400 border border-gray-200/60 dark:border-gray-700 shadow-xs group-hover:scale-105 transition-transform">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="text-left">
                            <span class="block text-xs font-bold leading-tight text-gray-800 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">Ekspor PDF</span>
                            <span class="block text-[10px] text-gray-500 dark:text-gray-400">Rekap Laporan</span>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Two-Column Operations Section -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                <!-- 1. Perlu Ditindaklanjuti (Laporan Baru) -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden flex flex-col">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/50 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500"></span>
                            </span>
                            <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                Perlu Ditindaklanjuti
                                <span class="text-xs px-2 py-0.5 rounded-full font-bold bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-300">
                                    {{ $stats['laporan_baru'] }}
                                </span>
                            </h3>
                        </div>
                        <a href="{{ route('admin.penugasan.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 inline-flex items-center gap-1 group">
                            <span>Lihat Semua</span>
                            <span class="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </a>
                    </div>

                    <div class="p-0 flex-1">
                        <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($laporanPerluTindakLanjut as $lap)
                            <li class="p-4 sm:p-5 hover:bg-gray-50/80 dark:hover:bg-gray-700/40 transition">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="pr-2 space-y-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="text-sm font-bold text-gray-900 dark:text-gray-100 line-clamp-1">{{ $lap->judul }}</p>
                                            
                                            <!-- SLA / Waktu Badge -->
                                            @if($lap->created_at->diffInHours() >= 24)
                                             <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                &gt; 24 Jam
                                            </span>
                                            @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60">
                                                Baru
                                            </span>
                                            @endif
                                        </div>

                                        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1.5 flex-wrap">
                                            <span class="font-medium text-gray-700 dark:text-gray-300">{{ $lap->pelapor->nama_lengkap ?? 'User Pelapor' }}</span>
                                            <span>&bull;</span>
                                            <span class="inline-flex items-center gap-1">
                                                <svg class="w-3 h-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                                {{ $lap->lokasi }}
                                            </span>
                                            <span>&bull;</span>
                                            <span class="text-gray-400 dark:text-gray-500">{{ $lap->created_at->diffForHumans() }}</span>
                                        </p>
                                    </div>

                                    <!-- Quick Action Buttons -->
                                    <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                                        <button type="button"
                                            @click="openPreview(
                                                {{ $lap->id }},
                                                {{ json_encode($lap->judul) }},
                                                {{ json_encode($lap->pelapor->nama_lengkap ?? 'User Pelapor') }},
                                                {{ json_encode($lap->pelapor->no_telepon ?? '-') }},
                                                {{ json_encode($lap->lokasi) }},
                                                {{ json_encode($lap->deskripsi) }},
                                                {{ json_encode($lap->foto_sebelum ? asset('storage/' . $lap->foto_sebelum) : '') }},
                                                {{ json_encode($lap->created_at->translatedFormat('d M Y, H:i')) }}
                                            )"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600 shadow-sm transition">
                                            <svg class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <span>Pratinjau</span>
                                        </button>

                                        <button type="button"
                                            @click="openAssignModal('{{ $lap->id }}', {{ json_encode($lap->judul) }})"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                            </svg>
                                            <span>Tugaskan</span>
                                        </button>
                                    </div>
                                </div>
                            </li>
                            @empty
                            <li class="px-6 py-12 text-center flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">Luar Biasa! Tidak ada laporan baru menumpuk.</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Semua laporan kerusakan yang masuk telah berhasil didelegasikan ke teknisi.</p>
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- 2. Penugasan Aktif Lapangan -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden flex flex-col">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/50 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                            </span>
                            <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                Penugasan Aktif Lapangan
                                <span class="text-xs px-2 py-0.5 rounded-full font-bold bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300">
                                    {{ $stats['tugas_aktif'] }}
                                </span>
                            </h3>
                        </div>
                        <a href="{{ route('admin.penugasan.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 inline-flex items-center gap-1 group">
                            <span>Lihat Semua</span>
                            <span class="group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                        </a>
                    </div>

                    <div class="p-0 flex-1">
                        <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($penugasanAktif as $tugas)
                            <li class="p-4 sm:p-5 hover:bg-gray-50/80 dark:hover:bg-gray-700/40 transition">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="pr-2 space-y-1">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <a href="{{ route('admin.laporan.show', $tugas->laporan_id) }}" class="text-sm font-bold text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition line-clamp-1">
                                                {{ $tugas->laporan?->judul ?? 'Laporan telah dihapus' }}
                                            </a>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                                {{ $tugas->status_tugas == 'Dikerjakan' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600' }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $tugas->status_tugas == 'Dikerjakan' ? 'bg-amber-500 animate-pulse' : 'bg-gray-400 dark:bg-gray-500' }} mr-1.5"></span>
                                                {{ $tugas->status_tugas }}
                                            </span>
                                        </div>
                                        <div class="flex items-center text-xs text-gray-500 dark:text-gray-400 gap-2 flex-wrap">
                                            <span class="inline-flex items-center gap-1 font-semibold text-indigo-600 dark:text-indigo-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                {{ $tugas->teknisi->nama_lengkap ?? 'Teknisi' }}
                                            </span>
                                            <span>&bull;</span>
                                            <span>{{ $tugas->laporan?->lokasi ?? '-' }}</span>
                                            <span>&bull;</span>
                                            <span>Ditugaskan {{ $tugas->assigned_at?->diffForHumans() ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="shrink-0 self-end sm:self-center">
                                        <a href="{{ route('admin.laporan.show', $tugas->laporan_id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600 shadow-sm transition">
                                            <span>Detail</span>
                                            <svg class="w-3 h-3 text-gray-400 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            @empty
                            <li class="px-6 py-12 text-center flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-700/50 text-gray-400 dark:text-gray-500 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">Belum ada penugasan aktif ke teknisi saat ini.</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Gunakan tombol "Tugaskan" pada panel kiri untuk mendelegasikan perbaikan.</p>
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

            </div>

            <!-- Status Beban Kerja Tim Teknisi (Technician Workload Radar) -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 sm:p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                    <div>
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Ketersediaan &amp; Beban Kerja Teknisi
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            Pantau kesiapan personil teknisi lapangan sebelum mendelegasikan laporan perbaikan baru.
                        </p>
                    </div>
                    <a href="{{ route('admin.users.index', ['role' => 'Teknisi']) }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 inline-flex items-center gap-1">
                        Kelola Akun Teknisi &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @forelse($teknisiList as $tek)
                    <div class="p-4 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/40 flex flex-col justify-between space-y-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-center gap-2.5">
                                <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 font-bold text-xs flex items-center justify-center shrink-0">
                                    {{ strtoupper(substr($tek->nama_lengkap ?? 'T', 0, 2)) }}
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-xs sm:text-sm font-bold text-gray-900 dark:text-gray-100 truncate">{{ $tek->nama_lengkap }}</p>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 truncate">{{ $tek->no_telepon ?? 'Tanpa No. Telp' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-gray-200/80 dark:border-gray-700 flex items-center justify-between text-xs">
                            <span class="text-gray-500 dark:text-gray-400">Status Tugas:</span>
                            @if($tek->tugas_aktif_count == 0)
                            <span class="inline-flex items-center gap-1 font-bold text-emerald-600 dark:text-emerald-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Tersedia (0 Tugas)
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 font-bold text-amber-600 dark:text-amber-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                {{ $tek->tugas_aktif_count }} Tugas Aktif
                            </span>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-8 text-center text-xs text-gray-500 dark:text-gray-400">
                        Belum ada data teknisi yang terdaftar dan aktif. Silakan tambahkan user dengan role Teknisi melalui menu Kelola Pengguna.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- MODAL 1: Pratinjau Laporan -->
        <div x-show="previewOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-gray-950/75 dark:bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
            <div @click.away="previewOpen = false" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-xl w-full overflow-hidden transform transition-all border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50/80 dark:bg-gray-900/60">
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Pratinjau Data Laporan
                    </h3>
                    <button @click="previewOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="px-6 py-5 space-y-4 max-h-[75vh] overflow-y-auto">
                    <div>
                        <span class="text-[11px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider">Judul Kerusakan</span>
                        <h4 class="text-base font-bold text-gray-900 dark:text-gray-100 mt-0.5" x-text="p_judul"></h4>
                    </div>

                    <div class="grid grid-cols-2 gap-3 p-3.5 rounded-xl bg-gray-50 dark:bg-gray-900/60 border border-gray-200/60 dark:border-gray-700/60 text-xs">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 block">Pelapor:</span>
                            <span class="font-bold text-gray-800 dark:text-gray-200" x-text="p_pelapor"></span>
                            <span class="text-gray-400 dark:text-gray-500 block text-[10px]" x-text="'Telp: ' + p_kontak"></span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400 block">Lokasi:</span>
                            <span class="font-bold text-gray-800 dark:text-gray-200" x-text="p_lokasi"></span>
                            <span class="text-gray-400 dark:text-gray-500 block text-[10px]" x-text="p_waktu"></span>
                        </div>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 block mb-1">Deskripsi Kerusakan:</span>
                        <div class="p-3.5 rounded-xl bg-gray-50 dark:bg-gray-900/60 text-xs text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed border border-gray-200/60 dark:border-gray-700/60" x-text="p_deskripsi"></div>
                    </div>

                    <div>
                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 block mb-1">Foto Bukti Kerusakan:</span>
                        <template x-if="p_foto">
                            <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900">
                                <img :src="p_foto" alt="Bukti Kerusakan" class="w-full h-56 object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                        </template>
                        <template x-if="!p_foto">
                            <div class="p-6 text-center rounded-xl border border-dashed border-gray-300 dark:border-gray-700 text-gray-400 dark:text-gray-500 text-xs">
                                Tidak ada foto bukti yang dilampirkan oleh pelapor.
                            </div>
                        </template>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50/80 dark:bg-gray-900/60 border-t border-gray-200 dark:border-gray-700 flex flex-wrap justify-between items-center gap-2">
                    <button type="button" @click="openTolakModal(p_id, p_judul)" class="px-3.5 py-2 text-rose-600 hover:text-rose-700 dark:text-rose-400 text-xs font-bold hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-xl transition">
                        Tolak Laporan
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="previewOpen = false" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 shadow-sm transition">
                            Tutup
                        </button>
                        <button type="button" @click="openAssignModal(p_id, p_judul)" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            <span>Tugaskan Teknisi</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL 2: Tugaskan Teknisi -->
        <div x-show="assignModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-gray-950/75 dark:bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
            <div @click.away="assignModalOpen = false" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden transform transition-all border border-gray-200 dark:border-gray-700">
                <form :action="'{{ route('admin.penugasan.store', 999) }}'.replace('999', selectedLaporanId)" method="POST">
                    @csrf
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50/80 dark:bg-gray-900/60">
                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Penugasan Teknisi Lapangan
                        </h3>
                        <button type="button" @click="assignModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1.5 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider mb-1">
                                Laporan yang Ditugaskan <span class="text-rose-500">*</span>
                            </label>
                            <select name="laporan_id" x-model="selectedLaporanId" required class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-xs shadow-sm">
                                <option value="" class="bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">-- Pilih Laporan Kerusakan --</option>
                                @foreach($laporanBaruList as $lap)
                                <option value="{{ $lap->id }}" class="bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">{{ $lap->judul }} &bull; {{ $lap->lokasi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider mb-1">
                                Pilih Teknisi Lapangan <span class="text-rose-500">*</span>
                            </label>
                            <select name="teknisi_id" required class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-xs shadow-sm">
                                <option value="" class="bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">-- Pilih Teknisi --</option>
                                @foreach($teknisiList as $tek)
                                <option value="{{ $tek->id }}" class="bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    {{ $tek->nama_lengkap }} ({{ $tek->tugas_aktif_count == 0 ? 'Tersedia - 0 tugas' : $tek->tugas_aktif_count . ' tugas aktif' }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider mb-1">
                                Instruksi Khusus (Opsional)
                            </label>
                            <textarea name="instruksi" rows="3" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-xs placeholder-gray-400 dark:placeholder-gray-400 shadow-sm" placeholder="Misal: Harap bawa peralatan las dan koordinasi dengan kepala lab..."></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50/80 dark:bg-gray-900/60 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-2">
                        <button type="button" @click="assignModalOpen = false" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 shadow-sm transition">
                            Batal
                        </button>
                        <button type="submit" onclick="this.disabled=true; this.form.submit(); this.innerHTML='Menyimpan...';" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-bold shadow-sm hover:bg-indigo-700 transition">
                            Simpan & Tugaskan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL 3: Tolak Laporan -->
        <div x-show="tolakModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-gray-950/75 dark:bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
            <div @click.away="tolakModalOpen = false" class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all border border-gray-200 dark:border-gray-700">
                <form :action="'{{ route('admin.penugasan.tolak', 999) }}'.replace('999', tolakLaporanId)" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50/80 dark:bg-gray-900/60">
                        <h3 class="text-base font-bold text-rose-600 dark:text-rose-400 flex items-center gap-2">
                            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            Tolak Laporan Kerusakan
                        </h3>
                        <button type="button" @click="tolakModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1.5 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="px-6 py-5 space-y-4">
                        <div class="p-3 bg-rose-50 dark:bg-rose-900/20 rounded-xl border border-rose-200/80 dark:border-rose-800/50 text-xs text-rose-800 dark:text-rose-300">
                            Anda akan menolak laporan: <strong x-text="tolakJudul" class="font-bold"></strong>. Pelapor akan menerima notifikasi beserta alasan berikut.
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider mb-1">
                                Alasan Penolakan <span class="text-rose-500">*</span>
                            </label>
                            <textarea name="alasan_penolakan" rows="3" required class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:border-rose-500 focus:ring-rose-500 text-xs placeholder-gray-400 dark:placeholder-gray-400 shadow-sm" placeholder="Tuliskan alasan penolakan secara jelas..."></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50/80 dark:bg-gray-900/60 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-2">
                        <button type="button" @click="tolakModalOpen = false" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 shadow-sm transition">
                            Batal
                        </button>
                        <button type="submit" onclick="this.disabled=true; this.form.submit(); this.innerHTML='Menolak...';" class="px-4 py-2 bg-rose-600 text-white rounded-xl text-xs font-bold shadow-sm hover:bg-rose-700 transition">
                            Konfirmasi Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>