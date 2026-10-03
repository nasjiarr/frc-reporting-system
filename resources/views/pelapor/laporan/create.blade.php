<x-app-layout>
    <x-slot name="header">
        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-3" aria-label="Breadcrumb">
            <a href="{{ route('pelapor.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors inline-flex items-center gap-1.5 font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Dashboard</span>
            </a>
            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('pelapor.laporan.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-medium">
                Laporan Saya
            </a>
            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-800 dark:text-gray-200 font-semibold" aria-current="page">Buat Laporan</span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">Form Pelaporan Kerusakan</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Harap isi detail kerusakan secara spesifik agar teknisi dapat menangani kendala dengan cepat dan akurat.</p>
            </div>
            <a href="{{ route('pelapor.laporan.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali ke Laporan Saya</span>
            </a>
        </div>
    </x-slot>

    <!-- Main Container: Responsive 2-Column Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start pb-12">
        <!-- Left Column: Core Form (lg:col-span-8) -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm rounded-2xl overflow-hidden transition-colors">
                <!-- Card Header -->
                <div class="p-6 sm:p-7 border-b border-gray-100 dark:border-gray-700/80 bg-gradient-to-r from-gray-50/80 via-indigo-50/20 to-transparent dark:from-gray-800 dark:via-gray-800 dark:to-gray-800/80">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Informasi Kendala & Sarana</h3>
                            <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Pastikan informasi diisi dengan jelas dan akurat.</p>
                        </div>
                    </div>
                </div>

                <!-- Form Element -->
                <form action="{{ route('pelapor.laporan.store') }}" method="POST" enctype="multipart/form-data" id="form-laporan">
                    @csrf

                    <div class="p-6 sm:p-8 space-y-6">
                        <!-- Field 1: Kerusakan & Suggestion Chips -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="judul" class="block text-sm font-semibold text-gray-900 dark:text-gray-200">
                                    Kerusakan / Kendala Fasilitas <span class="text-rose-500 dark:text-rose-400">*</span>
                                </label>
                                <span class="text-xs text-gray-400 dark:text-gray-500">Pilih rekomendasi atau ketik sendiri</span>
                            </div>

                            <!-- Suggestion Chips Container -->
                            <div class="mb-3">
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <span>Pilihan Cepat Masalah Umum:</span>
                                </p>
                                <div class="flex flex-wrap gap-2" id="suggestion-chips">
                                    <button type="button" data-chip="AC Tidak Dingin / Bocor" class="suggestion-chip inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-100 dark:bg-gray-700/80 text-slate-700 dark:text-gray-300 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-900/40 dark:hover:text-indigo-300 border border-slate-200/80 dark:border-gray-600 transition-all cursor-pointer">
                                        <span>❄️ AC Tidak Dingin / Bocor</span>
                                    </button>
                                    <button type="button" data-chip="Lampu / Kelistrikan Mati" class="suggestion-chip inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-100 dark:bg-gray-700/80 text-slate-700 dark:text-gray-300 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-900/40 dark:hover:text-indigo-300 border border-slate-200/80 dark:border-gray-600 transition-all cursor-pointer">
                                        <span>💡 Lampu / Kelistrikan Mati</span>
                                    </button>
                                    <button type="button" data-chip="Kran / Pipa Bocor" class="suggestion-chip inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-100 dark:bg-gray-700/80 text-slate-700 dark:text-gray-300 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-900/40 dark:hover:text-indigo-300 border border-slate-200/80 dark:border-gray-600 transition-all cursor-pointer">
                                        <span>🚰 Kran / Pipa Bocor</span>
                                    </button>
                                    <button type="button" data-chip="Proyektor / Audio Rusak" class="suggestion-chip inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-100 dark:bg-gray-700/80 text-slate-700 dark:text-gray-300 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-900/40 dark:hover:text-indigo-300 border border-slate-200/80 dark:border-gray-600 transition-all cursor-pointer">
                                        <span>📽️ Proyektor / Audio Rusak</span>
                                    </button>
                                    <button type="button" data-chip="Pintu / Kunci Rusak" class="suggestion-chip inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-slate-100 dark:bg-gray-700/80 text-slate-700 dark:text-gray-300 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-900/40 dark:hover:text-indigo-300 border border-slate-200/80 dark:border-gray-600 transition-all cursor-pointer">
                                        <span>🚪 Pintu / Kunci Rusak</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Judul Input Field -->
                            <div class="relative">
                                <input type="text" name="judul" id="judul" value="{{ old('judul') }}"
                                    placeholder="Contoh: AC Bocor, Lampu Mati, Pipa Pecah"
                                    class="block w-full rounded-xl border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 dark:bg-gray-700/90 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-3.5 placeholder:text-gray-400 dark:placeholder:text-gray-500 transition"
                                    required>
                            </div>
                            @error('judul')
                            <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-400 flex items-center gap-1">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                            @enderror
                        </div>

                        <!-- Field 2: Lokasi / Ruangan -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="lokasi" class="block text-sm font-semibold text-gray-900 dark:text-gray-200">
                                    Lokasi / Ruangan Gedung FRC <span class="text-rose-500 dark:text-rose-400">*</span>
                                </label>
                                <span class="text-xs text-gray-400 dark:text-gray-500">Pilih dari daftar atau ketik baru</span>
                            </div>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <input list="daftar_lokasi" name="lokasi" id="lokasi" value="{{ old('lokasi') }}"
                                    placeholder="Pilih atau ketik nama ruangan (Contoh: Hall, Wood pellet production...)"
                                    class="block w-full rounded-xl pl-10 pr-4 py-2.5 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 dark:bg-gray-700/90 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm placeholder:text-gray-400 dark:placeholder:text-gray-500 transition"
                                    autocomplete="off" required>

                                <datalist id="daftar_lokasi">
                                    @if(isset($daftarRuangan))
                                        @foreach($daftarRuangan as $ruangan)
                                            <option value="{{ $ruangan }}">
                                        @endforeach
                                    @endif
                                </datalist>
                            </div>
                            @error('lokasi')
                            <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-400 flex items-center gap-1">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                            @enderror
                        </div>

                        <!-- Field 3: Deskripsi Detail & Character Counter -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="deskripsi" class="block text-sm font-semibold text-gray-900 dark:text-gray-200">
                                    Deskripsi Detail Kerusakan <span class="text-rose-500 dark:text-rose-400">*</span>
                                </label>
                                <span id="char-counter" class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    <span id="char-count">0</span> karakter
                                </span>
                            </div>
                            <p id="deskripsi-hint" class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                Ceritakan kondisi fisik saat ini, sejak kapan terjadi, dan dampak kendala terhadap kegiatan gedung.
                            </p>
                            <div class="relative">
                                <textarea id="deskripsi" name="deskripsi" rows="5"
                                    aria-describedby="char-counter deskripsi-hint"
                                    placeholder="Contoh deskripsi:&#10;- Kondisi fisik: AC di sisi utara ruangan meneteskan air cukup deras dan hembusan angin terasa gerah.&#10;- Sejak kapan: Terjadi sejak pukul 09:00 pagi hari ini.&#10;- Dampak: Lantai di bawahnya basah dan licin, mengganggu jalannya kegiatan laboratorium."
                                    class="block w-full rounded-xl border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 dark:bg-gray-700/90 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3.5 placeholder:text-gray-400 dark:placeholder:text-gray-500 transition leading-relaxed"
                                    required>{{ old('deskripsi') }}</textarea>
                            </div>
                            <div class="mt-1.5 flex items-center justify-between text-xs">
                                <span class="text-gray-400 dark:text-gray-500">Disarankan minimal 20 karakter agar teknisi cepat memahami persoalan.</span>
                            </div>
                            @error('deskripsi')
                            <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-400 flex items-center gap-1">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                            @enderror
                        </div>

                        <!-- Field 4: Dropzone & Live Image Preview -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="foto_sebelum" class="block text-sm font-semibold text-gray-900 dark:text-gray-200">
                                    Foto Bukti Kerusakan <span class="text-rose-500 dark:text-rose-400">*</span>
                                </label>
                                <span class="text-xs text-gray-400 dark:text-gray-500">Maks. 2 MB (JPG, PNG)</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2.5">
                                Sertakan foto objek yang mengalami kerusakan untuk mempermudah identifikasi dan persiapan peralatan teknisi.
                            </p>

                            <!-- Dropzone & Live Preview Container -->
                            <div id="dropzone-container" class="relative">
                                <!-- Empty Dropzone State (Clickable & Drag-Drop Target) -->
                                <label id="dropzone-empty" for="foto_sebelum"
                                    class="border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-indigo-500 dark:hover:border-indigo-400 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 focus-within:border-indigo-500 rounded-2xl p-6 sm:p-8 text-center bg-gray-50/60 dark:bg-gray-800/60 hover:bg-indigo-50/20 dark:hover:bg-indigo-950/20 transition-all duration-200 cursor-pointer flex flex-col items-center justify-center group">
                                    <input type="file" name="foto_sebelum" id="foto_sebelum"
                                        accept="image/jpeg,image/png,image/jpg" capture="environment"
                                        class="sr-only" required>

                                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-3 group-hover:scale-110 group-hover:bg-indigo-100 dark:group-hover:bg-indigo-900/60 transition-all pointer-events-none">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 mb-1 pointer-events-none">
                                        <span class="text-indigo-600 dark:text-indigo-400 hover:underline">Klik untuk memilih file</span> atau seret foto ke sini
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 max-w-sm pointer-events-none">
                                        Mendukung tangkapan kamera langsung di smartphone atau unggah file foto dari perangkat.
                                    </p>
                                    <div class="mt-3.5 inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-200/60 dark:bg-gray-700/60 text-[11px] font-medium text-gray-600 dark:text-gray-300 pointer-events-none">
                                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Otomatis dioptimasi kompresi jika ukuran besar</span>
                                    </div>
                                </label>

                                <!-- Live Preview State (Hidden by default, shown upon file selection) -->
                                <div id="dropzone-preview" class="hidden border border-gray-200 dark:border-gray-700 rounded-2xl p-4 bg-gray-50/70 dark:bg-gray-800/80 transition-all">
                                    <div class="flex flex-col sm:flex-row items-center gap-4">
                                        <div class="relative w-full sm:w-44 h-40 rounded-xl overflow-hidden bg-black/5 dark:bg-black/20 flex-shrink-0 border border-gray-200 dark:border-gray-700">
                                            <img id="image-preview" src="#" alt="Preview Foto Kerusakan" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex-1 w-full flex flex-col justify-between">
                                            <div>
                                                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 mb-2">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    <span>Foto Terpilih</span>
                                                </div>
                                                <h4 id="preview-filename" class="text-sm font-bold text-gray-900 dark:text-gray-100 truncate max-w-xs sm:max-w-md">nama_foto.jpg</h4>
                                                <p id="preview-filesize" class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">0 KB</p>
                                            </div>

                                            <div class="flex items-center gap-2.5 mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                                                <button type="button" id="btn-change-photo" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 transition shadow-sm">
                                                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                    <span>Ganti Foto</span>
                                                </button>
                                                <button type="button" id="btn-remove-photo" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-semibold rounded-lg bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900/60 hover:bg-rose-100 dark:hover:bg-rose-900/60 transition shadow-sm">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    <span>Hapus Foto</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Compression Info Container (Harmonized with image-compressor.js) -->
                            <div id="compress-info-foto_sebelum" class="mt-2 text-xs font-medium transition-all duration-200"></div>

                            @error('foto_sebelum')
                            <p class="mt-1.5 text-xs text-rose-600 dark:text-rose-400 flex items-center gap-1">
                                <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Actions Bar with Double-Submit Protection -->
                    <div class="bg-gray-50/80 dark:bg-gray-900/50 px-6 sm:px-8 py-4 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <a href="{{ route('pelapor.laporan.index') }}"
                            class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span>Batal & Kembali</span>
                        </a>

                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                            <button type="submit" id="btn-submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white shadow-md shadow-indigo-500/20 hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 gap-2">
                                <svg id="btn-submit-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                <span id="btn-submit-text">Kirim Laporan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Column: Sidebar Helper Cards (lg:col-span-4) -->
        <div class="lg:col-span-4 space-y-6 lg:sticky lg:top-4">
            <!-- Helper Card 1: Photo Guidelines (Dos & Don'ts) -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 sm:p-6 shadow-sm">
                <div class="flex items-center gap-2.5 mb-4 pb-3 border-b border-gray-100 dark:border-gray-700">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">Panduan Foto Bukti</h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400">Tips pengambilan foto yang efektif</p>
                    </div>
                </div>

                <div class="space-y-4 text-xs">
                    <div>
                        <p class="font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5 mb-2">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span>Disarankan (Do)</span>
                        </p>
                        <ul class="space-y-1.5 text-gray-600 dark:text-gray-300 pl-5 list-disc leading-relaxed">
                            <li>Pencahayaan terang dan titik fokus jelas ke kendala.</li>
                            <li>Perlihatkan objek kerusakan dan letak sekitarnya.</li>
                            <li>Gunakan sudut pandang landscape bila area kerusakan cukup luas.</li>
                        </ul>
                    </div>

                    <div class="pt-3 border-t border-gray-100 dark:border-gray-700/60">
                        <p class="font-semibold text-rose-600 dark:text-rose-400 flex items-center gap-1.5 mb-2">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            <span>Hindari (Don't)</span>
                        </p>
                        <ul class="space-y-1.5 text-gray-600 dark:text-gray-300 pl-5 list-disc leading-relaxed">
                            <li>Foto yang terlalu buram (blur) atau terlalu gelap gulita.</li>
                            <li>Foto terlalu dekat (extreme close-up) tanpa konteks ruang.</li>
                            <li>Mengunggah screenshot tanpa kejelasan kerusakan fisik.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Helper Card 2: Reporting Flow Overview -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 sm:p-6 shadow-sm">
                <div class="flex items-center gap-2.5 mb-4 pb-3 border-b border-gray-100 dark:border-gray-700">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">Alur Penanganan Laporan</h3>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400">Tahapan proses dari pelapor ke teknisi</p>
                    </div>
                </div>

                <ol class="relative border-l border-indigo-200 dark:border-indigo-900/60 ml-3 space-y-4 text-xs">
                    <li class="pl-5 relative">
                        <span class="absolute -left-2 top-0.5 w-4 h-4 rounded-full bg-indigo-600 text-white flex items-center justify-center text-[10px] font-bold shadow-sm">1</span>
                        <p class="font-bold text-gray-900 dark:text-gray-100">Laporan Terkirim (Baru)</p>
                        <p class="text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed">Admin FRC menerima notifikasi otomatis dan memverifikasi data kendala fasilitas.</p>
                    </li>
                    <li class="pl-5 relative">
                        <span class="absolute -left-2 top-0.5 w-4 h-4 rounded-full bg-blue-500 text-white flex items-center justify-center text-[10px] font-bold shadow-sm">2</span>
                        <p class="font-bold text-gray-900 dark:text-gray-100">Penugasan Teknisi (Diproses)</p>
                        <p class="text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed">Teknisi lapangan ditugaskan dan segera menuju lokasi membawa peralatan yang sesuai.</p>
                    </li>
                    <li class="pl-5 relative">
                        <span class="absolute -left-2 top-0.5 w-4 h-4 rounded-full bg-amber-500 text-white flex items-center justify-center text-[10px] font-bold shadow-sm">3</span>
                        <p class="font-bold text-gray-900 dark:text-gray-100">Pengerjaan di Lapangan</p>
                        <p class="text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed">Teknisi memperbaiki kerusakan dan mengunggah foto bukti hasil perbaikan.</p>
                    </li>
                    <li class="pl-5 relative">
                        <span class="absolute -left-2 top-0.5 w-4 h-4 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold shadow-sm">4</span>
                        <p class="font-bold text-gray-900 dark:text-gray-100">Konfirmasi Selesai</p>
                        <p class="text-gray-500 dark:text-gray-400 mt-0.5 leading-relaxed">Status laporan diperbarui ke 'Selesai' dan Anda dapat memeriksa hasil pengerjaan.</p>
                    </li>
                </ol>
            </div>

            <!-- Helper Card 3: Maintenance Emergency Contact -->
            <div class="bg-gradient-to-br from-amber-500/10 via-amber-500/5 to-transparent dark:from-amber-950/40 dark:via-amber-950/20 border border-amber-200 dark:border-amber-900/60 rounded-2xl p-5 sm:p-6 shadow-sm">
                <div class="flex items-center gap-2.5 mb-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/60 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-amber-900 dark:text-amber-200">Kontak Darurat Fasilitas</h3>
                        <p class="text-[11px] text-amber-700/80 dark:text-amber-400/80">Kondisi mendesak & membahayakan</p>
                    </div>
                </div>
                <p class="text-xs text-amber-800 dark:text-amber-300 leading-relaxed mb-3.5">
                    Jika terjadi kendala darurat seperti korsleting berbau terbakar, kebocoran pipa utama berisiko banjir, atau lift macet, hubungi segera:
                </p>
                <div class="space-y-2.5 text-xs">
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-white/90 dark:bg-gray-800/90 border border-amber-200/80 dark:border-amber-800/80 shadow-sm">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Pos Keamanan FRC:</span>
                        <span class="font-bold text-indigo-600 dark:text-indigo-400">Ext. 110 / 112</span>
                    </div>
                    <div class="flex items-center justify-between p-2.5 rounded-xl bg-white/90 dark:bg-gray-800/90 border border-amber-200/80 dark:border-amber-800/80 shadow-sm">
                        <span class="font-medium text-gray-700 dark:text-gray-300">Helpdesk Sarpras:</span>
                        <span class="font-bold text-indigo-600 dark:text-indigo-400">+62 811-2856-789</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interactive Features JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Suggestion Chips Functionality
            const chips = document.querySelectorAll('.suggestion-chip');
            const judulInput = document.getElementById('judul');

            chips.forEach(function(chip) {
                chip.addEventListener('click', function() {
                    const text = this.getAttribute('data-chip');
                    if (text && judulInput) {
                        judulInput.value = text;
                        judulInput.focus();

                        // Highlight clicked chip
                        chips.forEach(function(c) {
                            c.classList.remove('ring-2', 'ring-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/60', 'text-indigo-700', 'dark:text-indigo-300');
                        });
                        this.classList.add('ring-2', 'ring-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/60', 'text-indigo-700', 'dark:text-indigo-300');
                    }
                });
            });

            if (judulInput) {
                judulInput.addEventListener('input', function() {
                    const currentVal = this.value.trim();
                    chips.forEach(function(c) {
                        if (c.getAttribute('data-chip') === currentVal) {
                            c.classList.add('ring-2', 'ring-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/60', 'text-indigo-700', 'dark:text-indigo-300');
                        } else {
                            c.classList.remove('ring-2', 'ring-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/60', 'text-indigo-700', 'dark:text-indigo-300');
                        }
                    });
                });
            }

            // 2. Character Counter Functionality
            const deskripsiInput = document.getElementById('deskripsi');
            const charCount = document.getElementById('char-count');

            function updateCharCount() {
                if (deskripsiInput && charCount) {
                    const length = deskripsiInput.value.length;
                    charCount.textContent = length;
                    if (length >= 20) {
                        charCount.classList.remove('text-gray-500', 'dark:text-gray-400');
                        charCount.classList.add('text-emerald-600', 'dark:text-emerald-400');
                    } else {
                        charCount.classList.remove('text-emerald-600', 'dark:text-emerald-400');
                        charCount.classList.add('text-gray-500', 'dark:text-gray-400');
                    }
                }
            }

            if (deskripsiInput) {
                deskripsiInput.addEventListener('input', updateCharCount);
                updateCharCount();
            }

            // 3. Dropzone & Live Image Preview Functionality
            const fileInput = document.getElementById('foto_sebelum');
            const dropzoneEmpty = document.getElementById('dropzone-empty');
            const dropzonePreview = document.getElementById('dropzone-preview');
            const imagePreview = document.getElementById('image-preview');
            const previewFilename = document.getElementById('preview-filename');
            const previewFilesize = document.getElementById('preview-filesize');
            const btnChangePhoto = document.getElementById('btn-change-photo');
            const btnRemovePhoto = document.getElementById('btn-remove-photo');
            const compressInfo = document.getElementById('compress-info-foto_sebelum');
            let currentPreviewUrl = null;

            function formatBytes(bytes) {
                if (!bytes || bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            function displayImagePreview(file) {
                if (!file || !file.type.startsWith('image/')) return;

                if (currentPreviewUrl) {
                    URL.revokeObjectURL(currentPreviewUrl);
                }
                currentPreviewUrl = URL.createObjectURL(file);

                if (imagePreview) {
                    imagePreview.src = currentPreviewUrl;
                }
                if (previewFilename) {
                    previewFilename.textContent = file.name;
                }
                if (previewFilesize) {
                    previewFilesize.textContent = formatBytes(file.size);
                }
                if (dropzoneEmpty) dropzoneEmpty.classList.add('hidden');
                if (dropzonePreview) dropzonePreview.classList.remove('hidden');
            }

            function clearImageSelection() {
                if (currentPreviewUrl) {
                    URL.revokeObjectURL(currentPreviewUrl);
                    currentPreviewUrl = null;
                }
                if (fileInput) {
                    fileInput.value = '';
                }
                if (imagePreview) {
                    imagePreview.src = '#';
                }
                if (previewFilename) {
                    previewFilename.textContent = '';
                }
                if (previewFilesize) {
                    previewFilesize.textContent = '';
                }
                if (dropzonePreview) dropzonePreview.classList.add('hidden');
                if (dropzoneEmpty) dropzoneEmpty.classList.remove('hidden');
                if (compressInfo) {
                    compressInfo.innerHTML = '';
                }
            }

            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    const file = this.files && this.files[0];
                    if (file) {
                        displayImagePreview(file);
                    } else {
                        clearImageSelection();
                    }
                });

                fileInput.addEventListener('image:compressed', function(e) {
                    if (e.detail && e.detail.compressedSize && previewFilesize) {
                        previewFilesize.textContent = formatBytes(e.detail.compressedSize) + ' (Terkompresi)';
                    }
                });
            }

            if (btnChangePhoto && fileInput) {
                btnChangePhoto.addEventListener('click', function(e) {
                    e.preventDefault();
                    fileInput.click();
                });
            }

            if (btnRemovePhoto) {
                btnRemovePhoto.addEventListener('click', function(e) {
                    e.preventDefault();
                    clearImageSelection();
                });
            }

            // Drag and drop event listeners on dropzone-empty
            if (dropzoneEmpty && fileInput) {
                ['dragenter', 'dragover'].forEach(function(eventName) {
                    dropzoneEmpty.addEventListener(eventName, function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        dropzoneEmpty.classList.add('border-indigo-500', 'bg-indigo-50/40', 'dark:bg-indigo-950/40');
                    }, false);
                });

                ['dragleave', 'drop'].forEach(function(eventName) {
                    dropzoneEmpty.addEventListener(eventName, function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        dropzoneEmpty.classList.remove('border-indigo-500', 'bg-indigo-50/40', 'dark:bg-indigo-950/40');
                    }, false);
                });

                dropzoneEmpty.addEventListener('drop', function(e) {
                    const dt = e.dataTransfer;
                    const files = dt ? dt.files : null;
                    if (files && files.length > 0) {
                        const file = files[0];
                        if (!file.type.startsWith('image/')) {
                            alert('Hanya file foto (JPG, PNG) yang diperbolehkan.');
                            return;
                        }

                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        fileInput.files = dataTransfer.files;

                        displayImagePreview(file);

                        // Dispatch change event to trigger client-side image compression
                        fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }, false);
            }

            // 4. Double-Submit Prevention & Loading State
            const form = document.getElementById('form-laporan');
            const submitBtn = document.getElementById('btn-submit');
            let isSubmitting = false;

            if (form && submitBtn) {
                form.addEventListener('submit', function(e) {
                    // Check native form validation first
                    if (!form.checkValidity()) {
                        return; // Let browser trigger validation messages
                    }

                    if (isSubmitting) {
                        e.preventDefault();
                        return false;
                    }

                    isSubmitting = true;
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
                    setTimeout(function() {
                        submitBtn.disabled = true;
                    }, 0);
                    submitBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Mengirim Laporan...</span>
                    `;
                });
            }
        });
    </script>
</x-app-layout>