<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('teknisi.dashboard') }}" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition" title="Kembali ke Dashboard">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-gray-100 tracking-tight leading-tight">
                        {{ $tugas->status_tugas === 'Selesai' ? 'Arsip Pekerjaan Teknisi' : 'Laporan Kerja Teknisi' }}
                    </h2>
                </div>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Detail penugasan: <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $tugas->laporan->judul }}</span></p>
            </div>
            <div class="flex items-center gap-2">
                @if($tugas->status_tugas === 'Ditugaskan')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase bg-amber-50 dark:bg-amber-900/40 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60 shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        Baru Ditugaskan
                    </span>
                @elseif($tugas->status_tugas === 'Dikerjakan')
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60 shadow-xs">
                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Sedang Dikerjakan
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase bg-emerald-50 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 shadow-xs">
                        <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Selesai
                    </span>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-2">
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
                <button type="button" @click="$el.closest('div').remove()" class="text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-300 p-1">
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
                <button type="button" @click="$el.closest('div').remove()" class="text-blue-500 hover:text-blue-700 dark:hover:text-blue-300 p-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            @endif

            @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider mb-1">Terjadi kesalahan pada input:</p>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Quick Start Banner if Still 'Ditugaskan' -->
            @if($tugas->status_tugas === 'Ditugaskan')
            <div class="p-4 sm:p-5 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start sm:items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-amber-100 dark:bg-amber-900/60 text-amber-700 dark:text-amber-300 flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-amber-900 dark:text-amber-200">Tugas Baru Masuk (Belum Dimulai)</h4>
                        <p class="text-xs text-amber-700 dark:text-amber-300 mt-0.5">
                            Klik <strong>"Mulai Pengerjaan"</strong> untuk memberitahu pelapor dan admin bahwa Anda sedang menuju lokasi perbaikan.
                        </p>
                    </div>
                </div>
                <form action="{{ route('teknisi.tugas.mulai', $tugas->id) }}" method="POST" class="flex-shrink-0">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto min-h-[44px] inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white font-bold rounded-xl text-xs transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Mulai Pengerjaan Sekarang</span>
                    </button>
                </form>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left Column: Informasi Laporan & Pelapor -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 shadow-sm rounded-2xl p-5 sm:p-6 space-y-5">
                        <div class="border-b border-slate-100 dark:border-slate-700/80 pb-3 flex items-center justify-between">
                            <h3 class="font-bold text-slate-900 dark:text-slate-100 text-base">Informasi Laporan</h3>
                            <span class="text-xs text-slate-400">
                                {{ $tugas->assigned_at ? $tugas->assigned_at->diffForHumans() : $tugas->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <!-- Admin Instruction Box -->
                        <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 rounded-xl p-4">
                            <p class="text-[11px] text-amber-800 dark:text-amber-300 font-bold uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Instruksi Khusus Admin
                            </p>
                            <p class="text-xs sm:text-sm text-amber-900 dark:text-amber-200 italic font-medium leading-relaxed">
                                "{{ $tugas->instruksi ?? 'Lakukan perbaikan sesuai standar operasional.' }}"
                            </p>
                        </div>

                        <!-- Pelapor Contact Box with Direct WA / Call -->
                        @php
                            $pelaporNama = $tugas->laporan->pelapor->nama_lengkap ?? $tugas->laporan->pelapor->name ?? 'Pelapor';
                            $rawPhone = $tugas->laporan->pelapor->no_telepon ?? '';
                            $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);
                            if (str_starts_with($cleanPhone, '0')) {
                                $waPhone = '62' . substr($cleanPhone, 1);
                            } else {
                                $waPhone = $cleanPhone;
                            }
                            $waText = urlencode("Halo {$pelaporNama}, saya teknisi FRC terkait laporan Anda: '{$tugas->laporan->judul}' di {$tugas->laporan->lokasi}.");
                            $waUrl = !empty($cleanPhone) ? "https://wa.me/{$waPhone}?text={$waText}" : null;
                            $telUrl = !empty($cleanPhone) ? "tel:{$cleanPhone}" : null;
                        @endphp

                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-700/80 space-y-2.5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 font-bold text-sm flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($pelaporNama, 0, 1)) }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] text-slate-400 uppercase font-semibold">Pelapor Kerusakan</p>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ $pelaporNama }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $rawPhone ?: 'Tidak ada no. telepon' }}</p>
                                </div>
                            </div>

                            @if($waUrl)
                            <div class="pt-1 flex items-center gap-2">
                                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                                   class="flex-1 min-h-[38px] inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-xs transition shadow-xs">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                    </svg>
                                    <span>Chat WA</span>
                                </a>
                                @if($telUrl)
                                <a href="{{ $telUrl }}" class="min-h-[38px] px-3 py-2 rounded-xl bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-300 dark:hover:bg-slate-600 transition flex items-center justify-center" title="Telepon Pelapor">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </a>
                                @endif
                            </div>
                            @endif
                        </div>

                        <!-- Location & Complaint Details -->
                        <div class="space-y-4 pt-1">
                            <div>
                                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Lokasi Kerusakan</p>
                                <p class="font-bold text-slate-900 dark:text-slate-100 text-sm mt-1 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $tugas->laporan->lokasi }}</span>
                                </p>
                            </div>

                            <div>
                                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Deskripsi Keluhan Pelapor</p>
                                <div class="bg-slate-50 dark:bg-slate-900/50 p-3 rounded-xl border border-slate-200/80 dark:border-slate-700/80 mt-1">
                                    <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line">{{ $tugas->laporan->deskripsi }}</p>
                                </div>
                            </div>

                            <div>
                                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Foto Kondisi Awal</p>
                                @if($tugas->laporan->foto_sebelum)
                                <div class="relative rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-xs group cursor-zoom-in" onclick="window.open(this.querySelector('img').src)">
                                    <img src="{{ asset('storage/' . $tugas->laporan->foto_sebelum) }}"
                                         alt="Foto Kerusakan"
                                         class="w-full h-44 object-cover group-hover:opacity-95 transition">
                                    <div class="absolute inset-0 bg-slate-900/30 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center text-white text-xs font-semibold gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                        Klik untuk Memperbesar
                                    </div>
                                </div>
                                @else
                                <div class="bg-slate-50 dark:bg-slate-900/50 border border-dashed border-slate-200 dark:border-slate-700 rounded-xl p-4 text-center">
                                    <p class="text-xs text-slate-400 dark:text-slate-500 italic">Pelapor tidak melampirkan foto</p>
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right Column: Form Pelaporan Hasil Perbaikan / Arsip -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700/80 shadow-sm rounded-2xl overflow-hidden">

                        @if($tugas->status_tugas === 'Selesai' && $tugas->hasilPerbaikan)

                        <!-- Mode Arsip Selesai -->
                        <div class="bg-emerald-50 dark:bg-emerald-950/40 px-6 py-4 border-b border-emerald-100 dark:border-emerald-800/50 flex items-center justify-between">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-emerald-900 dark:text-emerald-300 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Pekerjaan Telah Selesai
                                </h3>
                                <p class="text-xs text-emerald-700 dark:text-emerald-400 mt-0.5">Laporan dikirim pada: {{ $tugas->hasilPerbaikan->selesai_pada ? $tugas->hasilPerbaikan->selesai_pada->format('d M Y, H:i') : '-' }}</p>
                            </div>
                        </div>

                        <div class="p-6 space-y-6">
                            <div>
                                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Tindakan Perbaikan yang Dilakukan</p>
                                <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-200/80 dark:border-slate-700/80">
                                    <p class="text-sm text-slate-800 dark:text-slate-200 leading-relaxed whitespace-pre-line">{{ $tugas->hasilPerbaikan->tindakan }}</p>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Material / Suku Cadang yang Digunakan</p>
                                <div class="bg-slate-50 dark:bg-slate-900/50 p-3.5 rounded-xl border border-slate-200/80 dark:border-slate-700/80">
                                    <p class="text-sm text-slate-800 dark:text-slate-200 font-medium">{{ $tugas->hasilPerbaikan?->material_digunakan ?? 'Tidak ada penggantian suku cadang' }}</p>
                                </div>
                            </div>

                            <div>
                                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Bukti Foto Hasil Perbaikan (Sesudah)</p>
                                @if($tugas->hasilPerbaikan->foto_sesudah)
                                <img src="{{ asset('storage/' . $tugas->hasilPerbaikan->foto_sesudah) }}"
                                     class="w-full max-h-80 object-cover rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm cursor-zoom-in hover:opacity-95 transition"
                                     onclick="window.open(this.src)">
                                @else
                                <div class="bg-slate-50 dark:bg-slate-900/50 border border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-8 text-center">
                                    <p class="text-sm text-slate-400 dark:text-slate-500 italic">Tidak ada foto bukti perbaikan</p>
                                </div>
                                @endif
                            </div>

                            <div class="pt-4 flex items-center justify-between flex-wrap gap-3">
                                <a href="{{ route('teknisi.riwayat.export_pdf', $tugas->id) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/80 text-rose-700 dark:text-rose-300 rounded-xl font-semibold text-xs hover:bg-rose-100 dark:hover:bg-rose-900/60 transition shadow-xs">
                                    <svg class="w-4 h-4 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span>Unduh Berita Acara (PDF)</span>
                                </a>
                                <a href="{{ route('teknisi.riwayat') }}" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl font-bold text-xs text-slate-700 dark:text-slate-200 uppercase tracking-wider hover:bg-slate-50 dark:hover:bg-slate-600 transition shadow-xs">
                                    Kembali ke Riwayat
                                </a>
                            </div>
                        </div>

                        @else

                        <!-- Mode Form Input Hasil Perbaikan -->
                        <form action="{{ route('teknisi.tugas.update', $tugas->id) }}" method="POST" enctype="multipart/form-data"
                              x-data="{
                                  photoPreview: null,
                                  isSubmitting: false,
                                  previewFile(event) {
                                      const file = event.target.files[0];
                                      if (file) {
                                          const reader = new FileReader();
                                          reader.onload = (e) => {
                                              this.photoPreview = e.target.result;
                                          };
                                          reader.readAsDataURL(file);
                                      } else {
                                          this.photoPreview = null;
                                      }
                                  }
                              }"
                              @submit="isSubmitting = true">
                            @csrf
                            <div class="p-6 border-b border-slate-200/80 dark:border-slate-700/80 bg-slate-50/70 dark:bg-slate-900/50">
                                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Formulir Hasil Perbaikan</h3>
                                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">Laporkan tindakan teknis yang Anda lakukan beserta bukti foto hasil perbaikan.</p>
                            </div>

                            <div class="p-6 space-y-6">
                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-slate-900 dark:text-slate-100 mb-1.5">
                                        Tindakan yang Dilakukan <span class="text-rose-500">*</span>
                                    </label>
                                    <textarea name="tindakan" rows="4"
                                              placeholder="Jelaskan secara rinci tindakan perbaikan yang telah dilaksanakan..."
                                              class="block w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white shadow-xs focus:border-indigo-600 focus:ring-indigo-600 text-sm leading-relaxed"
                                              required>{{ old('tindakan') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-slate-900 dark:text-slate-100 mb-1.5">
                                        Material / Suku Cadang yang Digunakan
                                    </label>
                                    <input type="text" name="material_digunakan"
                                           value="{{ old('material_digunakan') }}"
                                           placeholder="Contoh: MCB 16A Schneider (1 pcs), Seal Tape (1 roll), atau kosongkan jika tidak ada"
                                           class="block w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900/60 dark:text-white shadow-xs focus:border-indigo-600 focus:ring-indigo-600 text-sm" />
                                </div>

                                <div class="bg-slate-50/80 dark:bg-slate-900/50 p-4 sm:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 space-y-3">
                                    <div>
                                        <label class="block text-xs sm:text-sm font-bold text-slate-900 dark:text-slate-100">
                                            Foto Bukti Hasil Perbaikan (Sesudah) <span class="text-rose-500">*</span>
                                        </label>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Ambil foto kondisi fisik setelah diperbaiki (Format: JPG, PNG, maks 2MB).</p>
                                    </div>

                                    <input type="file" name="foto_sesudah"
                                           @change="previewFile($event)"
                                           class="block w-full text-xs sm:text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 dark:file:bg-indigo-900/40 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/60 cursor-pointer"
                                           accept="image/*" required />

                                    <!-- Live Image Preview -->
                                    <template x-if="photoPreview">
                                        <div class="mt-3 relative rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 w-full sm:w-64 h-44 shadow-xs">
                                            <img :src="photoPreview" class="w-full h-full object-cover">
                                            <div class="absolute bottom-0 inset-x-0 bg-slate-900/60 text-white text-[10px] py-1 px-2 text-center font-medium">
                                                Pratinjau Foto
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <div class="bg-slate-50/70 dark:bg-slate-900/50 px-6 py-4 border-t border-slate-200/80 dark:border-slate-700/80 flex flex-col sm:flex-row items-center justify-between gap-3">
                                <a href="{{ route('teknisi.dashboard') }}" class="w-full sm:w-auto px-4 py-2.5 text-center text-xs font-bold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition">
                                    &larr; Kembali ke Dashboard
                                </a>

                                <button type="submit"
                                        :disabled="isSubmitting"
                                        class="w-full sm:w-auto min-h-[44px] inline-flex items-center justify-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 disabled:opacity-50 text-white font-bold rounded-xl text-xs uppercase tracking-wider transition shadow-sm gap-2">
                                    <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <svg x-show="isSubmitting" style="display: none;" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="isSubmitting ? 'Mengirim Laporan...' : 'Kirim Laporan Kerja Selesai'"></span>
                                </button>
                            </div>
                        </form>
                        @endif

                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>