<x-app-layout>
    {{-- Header Slot --}}
    <x-slot name="header">
        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                Riwayat Pekerjaan Selesai
            </h1>
            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                Arsip rekam jejak hasil perbaikan fasilitas gedung FRC yang telah berhasil Anda selesaikan.
            </p>
        </div>
    </x-slot>

    <div class="space-y-6 pb-12" x-data="{
        previewOpen: false,
        photoZoomOpen: false,
        photoZoomSrc: '',
        photoZoomTitle: '',
        
        search: '{{ request('search', '') }}',
        activeFilterPeriode: '{{ request('periode', 'all') }}',

        p_id: null,
        p_judul: '',
        p_lokasi: '',
        p_deskripsi: '',
        p_instruksi: '',
        p_tindakan: '',
        p_material: '',
        p_foto_sebelum: '',
        p_foto_sesudah: '',
        p_pelapor_nama: '',
        p_pelapor_telp: '',
        p_pelapor_wa: '',
        p_waktu_selesai: '',
        p_waktu_tugas: '',
        p_durasi: '',
        p_export_url: '',
        p_detail_url: '',

        openPreview(id, judul, lokasi, deskripsi, instruksi, tindakan, material, fotoSebelum, fotoSesudah, pelaporNama, pelaporTelp, pelaporWa, waktuSelesai, waktuTugas, durasi, exportUrl, detailUrl) {
            this.p_id = id;
            this.p_judul = judul;
            this.p_lokasi = lokasi;
            this.p_deskripsi = deskripsi;
            this.p_instruksi = instruksi;
            this.p_tindakan = tindakan;
            this.p_material = material;
            this.p_foto_sebelum = fotoSebelum;
            this.p_foto_sesudah = fotoSesudah;
            this.p_pelapor_nama = pelaporNama;
            this.p_pelapor_telp = pelaporTelp;
            this.p_pelapor_wa = pelaporWa;
            this.p_waktu_selesai = waktuSelesai;
            this.p_waktu_tugas = waktuTugas;
            this.p_durasi = durasi;
            this.p_export_url = exportUrl;
            this.p_detail_url = detailUrl;
            this.previewOpen = true;
        },

        zoomPhoto(src, title) {
            this.photoZoomSrc = src;
            this.photoZoomTitle = title;
            this.photoZoomOpen = true;
        },

        matchesSearch(text) {
            const q = this.search.toLowerCase().trim();
            if (!q) return true;
            return text.toLowerCase().includes(q);
        }
    }">
        {{-- Flash Notification --}}
        @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-1.5 rounded-xl bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <span class="text-xs sm:text-sm font-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" @click="$el.closest('div').remove()" class="text-emerald-500 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        @endif

        @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <div class="p-1.5 rounded-xl bg-rose-100 dark:bg-rose-900/60 text-rose-600 dark:text-rose-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <span class="text-xs sm:text-sm font-semibold">{{ session('error') }}</span>
            </div>
            <button type="button" @click="$el.closest('div').remove()" class="text-rose-500 hover:text-rose-700 dark:text-rose-400 dark:hover:text-rose-300 p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        @endif

        <!-- 1. KPI Summary Cards (4 Cards Grid) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
            <!-- 1. Total Selesai Sepanjang Waktu -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-emerald-400 dark:hover:border-emerald-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5 group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors uppercase tracking-wider">Total Riwayat Selesai</span>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $stats['total'] }}</p>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                            Semua
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-between truncate">
                        <span>Pekerjaan tuntas & terverifikasi</span>
                        <span class="text-emerald-600 dark:text-emerald-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </p>
                </div>
            </div>

            <!-- 2. Selesai Bulan Ini -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-blue-400 dark:hover:border-blue-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5 group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors uppercase tracking-wider">Selesai Bulan Ini</span>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $stats['bulan_ini'] }}</p>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                            {{ now()->translatedFormat('M Y') }}
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-between truncate">
                        <span>Pencapaian bulan berjalan</span>
                        <span class="text-blue-600 dark:text-blue-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </p>
                </div>
            </div>

            <!-- 3. Selesai Minggu Ini -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-amber-400 dark:hover:border-amber-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5 group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors uppercase tracking-wider">Selesai Pekan Ini</span>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $stats['minggu_ini'] }}</p>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                            7 Hari
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-between truncate">
                        <span>Produktivitas pekan ini</span>
                        <span class="text-amber-600 dark:text-amber-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </p>
                </div>
            </div>

            <!-- 4. Selesai Hari Ini -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-purple-400 dark:hover:border-purple-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5 group">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors uppercase tracking-wider">Selesai Hari Ini</span>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $stats['hari_ini'] }}</p>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300">
                            Hari Ini
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-between truncate">
                        <span>Dituntaskan hari ini</span>
                        <span class="text-purple-600 dark:text-purple-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- 2. Main Work Hub: Riwayat Pekerjaan -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden transition-colors">
            <!-- Header Kontrol & Formulir Pencarian / Filter -->
            <div class="p-5 sm:p-6 border-b border-gray-200 dark:border-gray-700 space-y-4 bg-gray-50/75 dark:bg-gray-900/40">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                <span>Daftar Riwayat Perbaikan</span>
                                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                    {{ $riwayat->total() }} Data
                                </span>
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Pantau rincian tindakan teknis, penggantian material, dan dokumentasi foto sebelum & sesudah.
                            </p>
                        </div>
                    </div>

                    <!-- Periode Filter Pills (Form Submit Link) -->
                    <div class="flex items-center gap-1.5 p-1 bg-gray-100 dark:bg-gray-900/80 border border-gray-200/60 dark:border-gray-700/70 rounded-xl overflow-x-auto max-w-full">
                        <a href="{{ route('teknisi.riwayat', array_merge(request()->except(['page', 'periode']), [])) }}"
                           class="px-3 py-1.5 rounded-lg text-xs transition-all whitespace-nowrap {{ !request('periode') || request('periode') === 'all' ? 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-xs font-bold border border-gray-200/60 dark:border-gray-700' : 'text-gray-600 dark:text-gray-400 font-medium hover:text-gray-900 dark:hover:text-white hover:bg-gray-200/50 dark:hover:bg-gray-800/50' }}">
                            Semua Waktu
                        </a>
                        <a href="{{ route('teknisi.riwayat', array_merge(request()->except(['page']), ['periode' => 'bulan_ini'])) }}"
                           class="px-3 py-1.5 rounded-lg text-xs transition-all whitespace-nowrap {{ request('periode') === 'bulan_ini' ? 'bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 shadow-xs font-bold border border-gray-200/60 dark:border-gray-700' : 'text-gray-600 dark:text-gray-400 font-medium hover:text-gray-900 dark:hover:text-white hover:bg-gray-200/50 dark:hover:bg-gray-800/50' }}">
                            Bulan Ini
                        </a>
                        <a href="{{ route('teknisi.riwayat', array_merge(request()->except(['page']), ['periode' => 'bulan_lalu'])) }}"
                           class="px-3 py-1.5 rounded-lg text-xs transition-all whitespace-nowrap {{ request('periode') === 'bulan_lalu' ? 'bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 shadow-xs font-bold border border-gray-200/60 dark:border-gray-700' : 'text-gray-600 dark:text-gray-400 font-medium hover:text-gray-900 dark:hover:text-white hover:bg-gray-200/50 dark:hover:bg-gray-800/50' }}">
                            Bulan Lalu
                        </a>
                        <a href="{{ route('teknisi.riwayat', array_merge(request()->except(['page']), ['periode' => 'tahun_ini'])) }}"
                           class="px-3 py-1.5 rounded-lg text-xs transition-all whitespace-nowrap {{ request('periode') === 'tahun_ini' ? 'bg-white dark:bg-gray-800 text-purple-600 dark:text-purple-400 shadow-xs font-bold border border-gray-200/60 dark:border-gray-700' : 'text-gray-600 dark:text-gray-400 font-medium hover:text-gray-900 dark:hover:text-white hover:bg-gray-200/50 dark:hover:bg-gray-800/50' }}">
                            Tahun Ini
                        </a>
                    </div>
                </div>

                <!-- Search Input Form -->
                <form method="GET" action="{{ route('teknisi.riwayat') }}" class="relative">
                    @if(request('periode'))
                        <input type="hidden" name="periode" value="{{ request('periode') }}">
                    @endif

                    <div class="relative flex items-center">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text"
                               name="search"
                               x-model="search"
                               placeholder="Cari riwayat (judul kerusakan, lokasi, nama pelapor, tindakan, atau material)..."
                               class="w-full pl-10 pr-24 py-2.5 text-xs sm:text-sm rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900/60 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-emerald-500 focus:ring-emerald-500 dark:focus:border-emerald-500 dark:focus:ring-emerald-500 transition-colors shadow-xs">

                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 gap-1.5">
                            <button type="button"
                                    x-show="search.length > 0"
                                    @click="search = ''; $el.closest('form').submit()"
                                    class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-200 p-1 rounded-lg"
                                    title="Hapus pencarian">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <button type="submit"
                                    class="px-3 py-1 bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 dark:hover:bg-gray-600 text-white rounded-lg text-xs font-semibold transition">
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- DUAL VIEW 1: MOBILE JOB ARCHIVE CARDS (< md) -->
            <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700/80">
                @forelse($riwayat as $data)
                @php
                    $pelaporNama = $data->laporan->pelapor->nama_lengkap ?? $data->laporan->pelapor->name ?? 'Pelapor';
                    $rawPhone = $data->laporan->pelapor->no_telepon ?? '';
                    $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                    $waPhone = str_starts_with($cleanPhone, '0') ? '62' . substr($cleanPhone, 1) : $cleanPhone;
                    $waText = urlencode("Halo {$pelaporNama}, saya teknisi FRC terkait riwayat perbaikan: '{$data->laporan->judul}' di {$data->laporan->lokasi}.");
                    $waUrl = !empty($cleanPhone) ? "https://wa.me/{$waPhone}?text={$waText}" : '';
                    $telUrl = !empty($cleanPhone) ? "tel:{$cleanPhone}" : '';

                    $waktuSelesai = $data->hasilPerbaikan?->selesai_pada ?? $data->updated_at;
                    $waktuTugas = $data->assigned_at ?? $data->created_at;

                    $durasiText = '-';
                    if ($waktuTugas && $waktuSelesai) {
                        $durasiText = $waktuTugas->diffForHumans($waktuSelesai, [
                            'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE,
                            'parts' => 2,
                        ]);
                    }

                    $tindakanText = $data->hasilPerbaikan?->tindakan ?? 'Tindakan telah diselesaikan.';
                    $materialText = $data->hasilPerbaikan?->material ?? $data->hasilPerbaikan?->material_digunakan ?? 'Tidak ada material/suku cadang tambahan.';
                    
                    $fotoSebelumUrl = $data->laporan->foto_sebelum ? asset('storage/' . $data->laporan->foto_sebelum) : '';
                    $fotoSesudahUrl = $data->hasilPerbaikan?->foto_sesudah ? asset('storage/' . $data->hasilPerbaikan->foto_sesudah) : '';

                    $searchHaystack = strtolower($data->laporan->judul . ' ' . $data->laporan->lokasi . ' ' . $pelaporNama . ' ' . $tindakanText . ' ' . $materialText);
                @endphp

                <div x-show="matchesSearch('{{ addslashes($searchHaystack) }}')"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="p-4 sm:p-5 space-y-4 hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">

                    <!-- Card Top: Status & Completion Date -->
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Selesai & Terverifikasi
                        </span>

                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $waktuSelesai ? $waktuSelesai->translatedFormat('d M Y, H:i') : '-' }} WIB</span>
                        </div>
                    </div>

                    <!-- Card Title & Location -->
                    <div>
                        <h4 class="font-bold text-sm sm:text-base text-gray-900 dark:text-gray-100 leading-snug">
                            {{ $data->laporan->judul }}
                        </h4>
                        <div class="mt-1 text-xs text-gray-600 dark:text-gray-300 flex items-center gap-1.5 font-medium">
                            <svg class="w-3.5 h-3.5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $data->laporan->lokasi }}</span>
                        </div>
                    </div>

                    <!-- Duration Pill & Reporter Contact -->
                    <div class="p-3 rounded-xl bg-gray-50/80 dark:bg-gray-900/50 border border-gray-200/70 dark:border-gray-700/60 flex items-center justify-between gap-3 text-xs">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                {{ strtoupper(substr($pelaporNama, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 dark:text-gray-200">{{ $pelaporNama }}</p>
                                <p class="text-[11px] text-gray-400 dark:text-gray-400">Durasi: <span class="font-semibold text-emerald-600 dark:text-emerald-400">{{ $durasiText }}</span></p>
                            </div>
                        </div>

                        @if(!empty($waUrl))
                        <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                           class="min-h-[38px] px-2.5 py-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 flex items-center gap-1.5 font-semibold text-xs hover:bg-emerald-100 dark:hover:bg-emerald-900/60 transition shadow-xs"
                           title="Hubungi Pelapor via WA">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                            <span>WA</span>
                        </a>
                        @endif
                    </div>

                    <!-- Action Taken & Material Badges -->
                    <div class="space-y-2 text-xs">
                        <div class="bg-gray-50/80 dark:bg-gray-900/40 p-3 rounded-xl border border-gray-200/80 dark:border-gray-700/80">
                            <span class="font-bold text-gray-700 dark:text-gray-300 block mb-1">Tindakan:</span>
                            <p class="text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed">{{ $tindakanText }}</p>
                        </div>

                        @if($data->hasilPerbaikan?->material_digunakan)
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/60 font-semibold text-[11px]">
                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span class="line-clamp-1">Material: {{ $data->hasilPerbaikan->material_digunakan }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Before & After Photo Preview Thumbnails -->
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-rose-600 dark:text-rose-400 block mb-1">Foto Sebelum</span>
                            @if(!empty($fotoSebelumUrl))
                            <div class="relative rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 group cursor-zoom-in aspect-video bg-gray-100 dark:bg-gray-900"
                                 @click="zoomPhoto('{{ $fotoSebelumUrl }}', 'Foto Kondisi Sebelum - {{ addslashes($data->laporan->judul) }}')">
                                <img src="{{ $fotoSebelumUrl }}" alt="Sebelum" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                <div class="absolute inset-0 bg-gray-900/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[10px] font-bold">
                                    Perbesar
                                </div>
                            </div>
                            @else
                            <div class="rounded-xl border border-dashed border-gray-200 dark:border-gray-700 aspect-video flex items-center justify-center text-[11px] text-gray-400 dark:text-gray-500 italic bg-gray-50 dark:bg-gray-900/40">
                                Tanpa Foto
                            </div>
                            @endif
                        </div>

                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 block mb-1">Foto Sesudah</span>
                            @if(!empty($fotoSesudahUrl))
                            <div class="relative rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 group cursor-zoom-in aspect-video bg-gray-100 dark:bg-gray-900"
                                 @click="zoomPhoto('{{ $fotoSesudahUrl }}', 'Foto Hasil Sesudah - {{ addslashes($data->laporan->judul) }}')">
                                <img src="{{ $fotoSesudahUrl }}" alt="Sesudah" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                <div class="absolute inset-0 bg-gray-900/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-[10px] font-bold">
                                    Perbesar
                                </div>
                            </div>
                            @else
                            <div class="rounded-xl border border-dashed border-gray-200 dark:border-gray-700 aspect-video flex items-center justify-center text-[11px] text-gray-400 dark:text-gray-500 italic bg-gray-50 dark:bg-gray-900/40">
                                Tanpa Foto
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Action Buttons -->
                    <div class="pt-2 flex items-center gap-2 border-t border-gray-100 dark:border-gray-700/60">
                        <button type="button"
                                @click="openPreview(
                                    {{ $data->id }},
                                    '{{ addslashes($data->laporan->judul) }}',
                                    '{{ addslashes($data->laporan->lokasi) }}',
                                    '{{ addslashes($data->laporan->deskripsi) }}',
                                    '{{ addslashes($data->instruksi ?? '-') }}',
                                    '{{ addslashes($tindakanText) }}',
                                    '{{ addslashes($materialText) }}',
                                    '{{ $fotoSebelumUrl }}',
                                    '{{ $fotoSesudahUrl }}',
                                    '{{ addslashes($pelaporNama) }}',
                                    '{{ addslashes($rawPhone) }}',
                                    '{{ $waUrl }}',
                                    '{{ $waktuSelesai ? $waktuSelesai->translatedFormat('d F Y, H:i') : '-' }}',
                                    '{{ $waktuTugas ? $waktuTugas->translatedFormat('d F Y, H:i') : '-' }}',
                                    '{{ $durasiText }}',
                                    '{{ route('teknisi.riwayat.export_pdf', $data->id) }}',
                                    '{{ route('teknisi.tugas.show', $data->id) }}'
                                )"
                                class="min-h-[44px] flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 text-xs font-bold transition">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span>Pratinjau</span>
                        </button>

                        <a href="{{ route('teknisi.riwayat.export_pdf', $data->id) }}"
                           class="min-h-[44px] px-3.5 py-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/70 text-xs font-bold transition flex items-center justify-center gap-1.5"
                           title="Unduh Berita Acara PDF">
                            <svg class="w-4 h-4 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>PDF</span>
                        </a>

                        <a href="{{ route('teknisi.tugas.show', $data->id) }}"
                           class="min-h-[44px] px-3 py-2.5 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600 text-xs font-bold transition flex items-center justify-center"
                           title="Buka Halaman Detail">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @empty
                <div class="py-12 text-center bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700/80 p-6">
                    <div class="w-14 h-14 mx-auto rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 flex items-center justify-center mb-3">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white">Tidak Ada Riwayat Ditemukan</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                        @if(request('search') || request('periode'))
                            Tidak ada arsip perbaikan yang cocok dengan filter atau kata kunci pencarian Anda.
                        @else
                            Anda belum memiliki arsip pekerjaan selesai. Tugas yang telah Anda perbaiki akan tercatat di sini.
                        @endif
                    </p>
                    @if(request('search') || request('periode'))
                    <div class="mt-4">
                        <a href="{{ route('teknisi.riwayat') }}" class="inline-flex items-center px-3.5 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-semibold transition">
                            Reset Semua Filter
                        </a>
                    </div>
                    @endif
                </div>
                @endforelse
            </div>

            <!-- DUAL VIEW 2: DESKTOP DATA TABLE (>= md) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50/75 dark:bg-gray-900/50 text-gray-600 dark:text-gray-400 text-xs font-bold uppercase tracking-wider">
                            <th class="py-3.5 px-5">Waktu Selesai & Durasi</th>
                            <th class="py-3.5 px-5">Laporan & Lokasi</th>
                            <th class="py-3.5 px-5">Pelapor</th>
                            <th class="py-3.5 px-5">Dokumentasi & Tindakan</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/80 text-gray-700 dark:text-gray-300 text-sm">
                        @forelse($riwayat as $data)
                        @php
                            $pelaporNama = $data->laporan->pelapor->nama_lengkap ?? $data->laporan->pelapor->name ?? 'Pelapor';
                            $rawPhone = $data->laporan->pelapor->no_telepon ?? '';
                            $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                            $waPhone = str_starts_with($cleanPhone, '0') ? '62' . substr($cleanPhone, 1) : $cleanPhone;
                            $waText = urlencode("Halo {$pelaporNama}, saya teknisi FRC terkait riwayat perbaikan: '{$data->laporan->judul}' di {$data->laporan->lokasi}.");
                            $waUrl = !empty($cleanPhone) ? "https://wa.me/{$waPhone}?text={$waText}" : '';

                            $waktuSelesai = $data->hasilPerbaikan?->selesai_pada ?? $data->updated_at;
                            $waktuTugas = $data->assigned_at ?? $data->created_at;

                            $durasiText = '-';
                            if ($waktuTugas && $waktuSelesai) {
                                $durasiText = $waktuTugas->diffForHumans($waktuSelesai, [
                                    'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE,
                                    'parts' => 2,
                                ]);
                            }

                            $tindakanText = $data->hasilPerbaikan?->tindakan ?? 'Tindakan telah diselesaikan.';
                            $materialText = $data->hasilPerbaikan?->material ?? $data->hasilPerbaikan?->material_digunakan ?? 'Tidak ada material/suku cadang tambahan.';
                            
                            $fotoSebelumUrl = $data->laporan->foto_sebelum ? asset('storage/' . $data->laporan->foto_sebelum) : '';
                            $fotoSesudahUrl = $data->hasilPerbaikan?->foto_sesudah ? asset('storage/' . $data->hasilPerbaikan->foto_sesudah) : '';

                            $searchHaystack = strtolower($data->laporan->judul . ' ' . $data->laporan->lokasi . ' ' . $pelaporNama . ' ' . $tindakanText . ' ' . $materialText);
                        @endphp

                        <tr x-show="matchesSearch('{{ addslashes($searchHaystack) }}')"
                            class="hover:bg-gray-50/60 dark:hover:bg-gray-700/30 transition-colors duration-150">

                            <!-- 1. Waktu Selesai & Durasi -->
                            <td class="py-4 px-5 whitespace-nowrap align-top">
                                <div class="flex items-center gap-1.5 font-bold text-gray-900 dark:text-gray-100 text-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>{{ $waktuSelesai ? $waktuSelesai->translatedFormat('d M Y') : '-' }}</span>
                                </div>
                                <div class="text-[11px] text-gray-400 dark:text-gray-500 pl-3 mt-0.5">
                                    Pukul {{ $waktuSelesai ? $waktuSelesai->translatedFormat('H:i') : '-' }} WIB
                                </div>
                                <div class="mt-2 pl-3">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60" title="Waktu sejak ditugaskan hingga selesai">
                                        <span>⏱️ {{ $durasiText }}</span>
                                    </span>
                                </div>
                            </td>

                            <!-- 2. Laporan & Lokasi -->
                            <td class="py-4 px-5 align-top max-w-xs">
                                <div class="font-bold text-gray-900 dark:text-gray-100 text-sm leading-tight hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    {{ $data->laporan->judul }}
                                </div>
                                <div class="mt-1 flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 font-medium">
                                    <svg class="w-3.5 h-3.5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="truncate">{{ $data->laporan->lokasi }}</span>
                                </div>
                                <p class="mt-1.5 text-xs text-gray-400 dark:text-gray-500 line-clamp-1">
                                    {{ $data->laporan->deskripsi }}
                                </p>
                            </td>

                            <!-- 3. Pelapor -->
                            <td class="py-4 px-5 align-top whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                        {{ strtoupper(substr($pelaporNama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-900 dark:text-gray-100 block text-xs">{{ $pelaporNama }}</span>
                                        <span class="text-[11px] text-gray-400 dark:text-gray-500 block">{{ $rawPhone ? $rawPhone : 'Tanpa kontak' }}</span>
                                    </div>
                                    @if(!empty($waUrl))
                                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                                       class="p-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 transition"
                                       title="Chat WhatsApp Pelapor">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                        </svg>
                                    </a>
                                    @endif
                                </div>
                            </td>

                            <!-- 4. Dokumentasi & Tindakan -->
                            <td class="py-4 px-5 align-top max-w-sm">
                                <div class="flex items-start gap-3">
                                    <div class="flex items-center gap-1.5 flex-shrink-0">
                                        @if(!empty($fotoSebelumUrl))
                                        <div class="relative w-12 h-12 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 cursor-zoom-in group"
                                             @click="zoomPhoto('{{ $fotoSebelumUrl }}', 'Foto Kondisi Sebelum - {{ addslashes($data->laporan->judul) }}')">
                                            <img src="{{ $fotoSebelumUrl }}" alt="Sebelum" class="w-full h-full object-cover group-hover:scale-110 transition duration-150">
                                            <span class="absolute bottom-0 inset-x-0 bg-rose-600/80 text-[8px] text-white text-center font-bold">SEBELUM</span>
                                        </div>
                                        @endif

                                        @if(!empty($fotoSesudahUrl))
                                        <div class="relative w-12 h-12 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 cursor-zoom-in group"
                                             @click="zoomPhoto('{{ $fotoSesudahUrl }}', 'Foto Hasil Sesudah - {{ addslashes($data->laporan->judul) }}')">
                                            <img src="{{ $fotoSesudahUrl }}" alt="Sesudah" class="w-full h-full object-cover group-hover:scale-110 transition duration-150">
                                            <span class="absolute bottom-0 inset-x-0 bg-emerald-600/80 text-[8px] text-white text-center font-bold">SESUDAH</span>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="space-y-1 min-w-0">
                                        <p class="text-xs text-gray-700 dark:text-gray-300 line-clamp-2 leading-relaxed">
                                            {{ $tindakanText }}
                                        </p>
                                        @if($data->hasilPerbaikan?->material_digunakan)
                                        <span class="inline-block text-[11px] text-indigo-600 dark:text-indigo-400 font-semibold truncate max-w-xs">
                                            📦 {{ $data->hasilPerbaikan->material_digunakan }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- 5. Aksi -->
                            <td class="py-4 px-5 align-top whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button"
                                            @click="openPreview(
                                                {{ $data->id }},
                                                '{{ addslashes($data->laporan->judul) }}',
                                                '{{ addslashes($data->laporan->lokasi) }}',
                                                '{{ addslashes($data->laporan->deskripsi) }}',
                                                '{{ addslashes($data->instruksi ?? '-') }}',
                                                '{{ addslashes($tindakanText) }}',
                                                '{{ addslashes($materialText) }}',
                                                '{{ $fotoSebelumUrl }}',
                                                '{{ $fotoSesudahUrl }}',
                                                '{{ addslashes($pelaporNama) }}',
                                                '{{ addslashes($rawPhone) }}',
                                                '{{ $waUrl }}',
                                                '{{ $waktuSelesai ? $waktuSelesai->translatedFormat('d F Y, H:i') : '-' }}',
                                                '{{ $waktuTugas ? $waktuTugas->translatedFormat('d F Y, H:i') : '-' }}',
                                                '{{ $durasiText }}',
                                                '{{ route('teknisi.riwayat.export_pdf', $data->id) }}',
                                                '{{ route('teknisi.tugas.show', $data->id) }}'
                                            )"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-xl text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-600 transition shadow-xs"
                                            title="Pratinjau Hasil Perbaikan">
                                        <svg class="w-3.5 h-3.5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span>Detail</span>
                                    </button>

                                    <a href="{{ route('teknisi.riwayat.export_pdf', $data->id) }}"
                                       class="p-1.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800/70 transition shadow-xs"
                                       title="Unduh Berita Acara PDF">
                                        <svg class="w-4 h-4 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>

                                    <a href="{{ route('teknisi.tugas.show', $data->id) }}"
                                       class="p-1.5 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600 transition shadow-xs"
                                       title="Buka Halaman Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-14 px-5 text-center">
                                <div class="max-w-xs mx-auto">
                                    <div class="w-16 h-16 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">Tidak Ada Riwayat Pekerjaan</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        @if(request('search') || request('periode'))
                                            Tidak ada arsip perbaikan yang cocok dengan filter atau kata kunci pencarian Anda.
                                        @else
                                            Pekerjaan perbaikan yang Anda selesaikan di lapangan akan otomatis tercatat di sini.
                                        @endif
                                    </p>
                                    @if(request('search') || request('periode'))
                                    <div class="mt-4">
                                        <a href="{{ route('teknisi.riwayat') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                                            Reset Filter & Pencarian
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @if($riwayat->hasPages())
            <div class="bg-gray-50/75 dark:bg-gray-900/40 px-5 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $riwayat->links() }}
            </div>
            @endif
        </div>

        <!-- 3. Enhanced Preview Modal (Alpine.js) -->
        <div x-show="previewOpen" style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/70 backdrop-blur-sm flex items-center justify-center p-3 sm:p-4"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div @click.away="previewOpen = false"
                 class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden transform transition-all border border-gray-200 dark:border-gray-700"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95">

                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/75 dark:bg-gray-900/50">
                    <div class="flex items-center gap-2.5">
                        <div class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white">
                                Dokumentasi Hasil Perbaikan
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="'Waktu Selesai: ' + p_waktu_selesai + ' WIB'"></p>
                        </div>
                    </div>

                    <button @click="previewOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="px-6 py-5 max-h-[75vh] overflow-y-auto space-y-5" style="scrollbar-width: thin;">
                    <!-- Title, Location & Status -->
                    <div class="space-y-1.5">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                Selesai & Terverifikasi
                            </span>
                            <span class="text-xs text-gray-400 dark:text-gray-400" x-text="'Durasi Pengerjaan: ' + p_durasi"></span>
                        </div>
                        <h4 class="text-xl font-extrabold text-gray-900 dark:text-white leading-tight" x-text="p_judul"></h4>
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 flex items-center gap-1.5 font-medium">
                            <svg class="w-4 h-4 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span x-text="p_lokasi"></span>
                        </p>
                    </div>

                    <!-- Reporter Card -->
                    <div class="p-3.5 rounded-xl bg-gray-50 dark:bg-gray-900/60 border border-gray-200/80 dark:border-gray-700/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-bold text-sm flex items-center justify-center flex-shrink-0">
                                <span x-text="p_pelapor_nama.charAt(0).toUpperCase()"></span>
                            </div>
                            <div>
                                <p class="text-[11px] text-gray-400 dark:text-gray-400 uppercase font-semibold">Pelapor Fasilitas</p>
                                <p class="text-sm font-bold text-gray-900 dark:text-white" x-text="p_pelapor_nama"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400" x-text="p_pelapor_telp ? p_pelapor_telp : 'Tidak mencantumkan nomor telepon'"></p>
                            </div>
                        </div>

                        <template x-if="p_pelapor_wa">
                            <div class="flex items-center gap-2">
                                <a :href="p_pelapor_wa" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-xs transition shadow-xs">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                    </svg>
                                    <span>Hubungi WA</span>
                                </a>
                            </div>
                        </template>
                    </div>

                    <!-- Tindakan Perbaikan (Highlight Box) -->
                    <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/60 rounded-xl p-4">
                        <p class="text-xs font-bold text-emerald-800 dark:text-emerald-300 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Tindakan Perbaikan yang Dilakukan
                        </p>
                        <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-line font-medium" x-text="p_tindakan"></p>
                    </div>

                    <!-- Material yang Digunakan -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-200/80 dark:border-gray-700/80">
                        <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            Material / Suku Cadang yang Digunakan
                        </p>
                        <p class="text-sm text-gray-800 dark:text-gray-200" x-text="p_material ? p_material : 'Tidak ada penggantian suku cadang'"></p>
                    </div>

                    <!-- Original Description & Admin Instruction -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-3.5 rounded-xl border border-gray-200/80 dark:border-gray-700/80">
                            <p class="font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Keluhan Pelapor</p>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line" x-text="p_deskripsi"></p>
                        </div>

                        <div class="bg-amber-50/60 dark:bg-amber-950/20 p-3.5 rounded-xl border border-amber-200/70 dark:border-amber-800/50">
                            <p class="font-bold text-amber-800 dark:text-amber-400 uppercase tracking-wider mb-1">Instruksi Awal Admin</p>
                            <p class="text-gray-700 dark:text-gray-300 italic leading-relaxed" x-text="p_instruksi"></p>
                        </div>
                    </div>

                    <!-- Side-by-Side Photos (Sebelum vs Sesudah) -->
                    <div>
                        <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Dokumentasi Foto Perbaikan</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Foto Sebelum -->
                            <div>
                                <span class="text-xs font-bold text-rose-600 dark:text-rose-400 block mb-1.5">Kondisi Sebelum Perbaikan</span>
                                <template x-if="p_foto_sebelum">
                                    <div class="relative group cursor-zoom-in rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-xs aspect-video bg-gray-100 dark:bg-gray-900"
                                         @click="zoomPhoto(p_foto_sebelum, 'Foto Kondisi Sebelum - ' + p_judul)">
                                        <img :src="p_foto_sebelum" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                        <div class="absolute inset-0 bg-gray-900/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-semibold gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                            Klik untuk Memperbesar
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!p_foto_sebelum">
                                    <div class="rounded-xl border border-dashed border-gray-200 dark:border-gray-700 aspect-video flex items-center justify-center text-xs text-gray-400 dark:text-gray-500 italic bg-gray-50 dark:bg-gray-900/40">
                                        Tidak ada foto awal dari pelapor
                                    </div>
                                </template>
                            </div>

                            <!-- Foto Sesudah -->
                            <div>
                                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 block mb-1.5">Kondisi Hasil Sesudah</span>
                                <template x-if="p_foto_sesudah">
                                    <div class="relative group cursor-zoom-in rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-xs aspect-video bg-gray-100 dark:bg-gray-900"
                                         @click="zoomPhoto(p_foto_sesudah, 'Foto Hasil Sesudah - ' + p_judul)">
                                        <img :src="p_foto_sesudah" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                        <div class="absolute inset-0 bg-gray-900/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-semibold gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                            Klik untuk Memperbesar
                                        </div>
                                    </div>
                                </template>
                                <template x-if="!p_foto_sesudah">
                                    <div class="rounded-xl border border-dashed border-gray-200 dark:border-gray-700 aspect-video flex items-center justify-center text-xs text-gray-400 dark:text-gray-500 italic bg-gray-50 dark:bg-gray-900/40">
                                        Tidak ada foto hasil perbaikan
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-gray-50/75 dark:bg-gray-900/50">
                    <a :href="p_export_url"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 text-xs font-bold transition shadow-xs">
                        <svg class="w-4 h-4 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Unduh Berita Acara (PDF)</span>
                    </a>

                    <div class="flex items-center gap-2 justify-end">
                        <button type="button" @click="previewOpen = false"
                                class="px-4 py-2.5 rounded-xl bg-white dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 text-xs font-semibold transition">
                            Tutup
                        </button>
                        <a :href="p_detail_url"
                           class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition shadow-xs">
                            <span>Buka Halaman Lengkap</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Photo Zoom Lightbox Modal -->
        <div x-show="photoZoomOpen" style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto bg-gray-950/80 backdrop-blur-md flex items-center justify-center p-3 sm:p-6"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            <div @click.away="photoZoomOpen = false"
                 class="relative max-w-4xl w-full bg-gray-900 rounded-2xl overflow-hidden shadow-2xl border border-gray-800 flex flex-col items-center">
                <div class="w-full px-5 py-3.5 flex items-center justify-between border-b border-gray-800 text-white bg-gray-900/90">
                    <span class="text-xs sm:text-sm font-bold truncate pr-4" x-text="photoZoomTitle"></span>
                    <div class="flex items-center gap-2">
                        <a :href="photoZoomSrc" target="_blank" class="p-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white transition" title="Buka di tab baru">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                        <button @click="photoZoomOpen = false" class="p-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                </div>
                <div class="p-3 sm:p-5 flex items-center justify-center max-h-[80vh] overflow-auto">
                    <img :src="photoZoomSrc" class="max-w-full max-h-[72vh] object-contain rounded-lg shadow-md">
                </div>
            </div>
        </div>

    </div>
</x-app-layout>