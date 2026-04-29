<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">
            {{ $tugas->status_tugas === 'Selesai' ? 'Arsip Pekerjaan Teknisi' : 'Laporan Kerja Teknisi' }}
        </h2>
        <p class="text-sm text-gray-600 mt-1">Detail penugasan: <span class="font-semibold">{{ $tugas->laporan->judul }}</span></p>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-6">
                <h3 class="font-bold text-gray-900 border-b border-gray-200 pb-3 mb-4">Informasi Laporan</h3>

                <div class="bg-indigo-50 border border-indigo-100 rounded-md p-4 mb-5">
                    <p class="text-xs text-indigo-800 font-bold uppercase tracking-wider mb-1">Instruksi Khusus Admin</p>
                    <p class="text-sm text-indigo-900 italic font-medium">"{{ $tugas->instruksi ?? 'Lakukan perbaikan sesuai standar operasional.' }}"</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Lokasi Kerusakan</p>
                        <p class="font-semibold text-gray-900 mt-1">{{ $tugas->laporan->lokasi }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Deskripsi Kerusakan dari Pelapor</p>
                        <p class="text-sm text-gray-700 mt-1 leading-relaxed">{{ $tugas->laporan->deskripsi }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase mb-2">Foto Kondisi Awal</p>
                        @if($tugas->laporan->foto_sebelum)
                        <div class="relative rounded-md overflow-hidden border border-gray-200 shadow-sm">
                            <img src="{{ asset('storage/' . $tugas->laporan->foto_sebelum) }}"
                                alt="Foto Kerusakan"
                                class="w-full h-40 object-cover cursor-zoom-in hover:opacity-90 transition"
                                onclick="window.open(this.src)">
                        </div>
                        @else
                        <div class="bg-gray-50 border border-dashed border-gray-300 rounded-md p-4 text-center">
                            <p class="text-xs text-gray-400 italic">Pelapor tidak melampirkan foto</p>
                        </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-1">Status Penugasan</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $tugas->status_tugas == 'Selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $tugas->status_tugas }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">

                @if($tugas->status_tugas === 'Selesai' && $tugas->hasilPerbaikan)

                <div class="bg-emerald-50 px-6 py-4 border-b border-emerald-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-emerald-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pekerjaan Selesai
                        </h3>
                        <p class="text-xs text-emerald-700 mt-0.5">Laporan dikirim pada: {{ $tugas->hasilPerbaikan->selesai_pada->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <div class="p-6 space-y-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Tindakan Perbaikan yang Anda Lakukan</p>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <p class="text-sm text-gray-800 leading-relaxed">{{ $tugas->hasilPerbaikan->tindakan }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Material / Suku Cadang yang Digunakan</p>
                        <p class="text-sm text-gray-800 font-medium">{{ $tugas->hasilPerbaikan->material ?? 'Tidak ada penggantian suku cadang' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Bukti Foto Hasil Perbaikan (Sesudah)</p>
                        @if($tugas->hasilPerbaikan->foto_sesudah)
                        <img src="{{ asset('storage/' . $tugas->hasilPerbaikan->foto_sesudah) }}"
                            class="w-full max-h-80 object-cover rounded-lg border border-gray-200 shadow-sm cursor-zoom-in hover:opacity-95 transition"
                            onclick="window.open(this.src)">
                        @else
                        <div class="bg-gray-50 border border-dashed border-gray-300 rounded-md p-8 text-center">
                            <p class="text-sm text-gray-400 italic">Tidak ada foto bukti perbaikan</p>
                        </div>
                        @endif
                    </div>

                    <div class="pt-4 flex justify-end">
                        <a href="{{ route('teknisi.riwayat') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">
                            Kembali ke Riwayat
                        </a>
                    </div>
                </div>

                @else

                <form action="{{ route('teknisi.tugas.update', $tugas->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-900">Form Hasil Perbaikan</h3>
                        <p class="text-sm text-gray-500">Laporkan tindakan yang Anda lakukan beserta bukti foto.</p>
                    </div>

                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900">Tindakan yang Dilakukan <span class="text-rose-500">*</span></label>
                            <textarea name="tindakan" rows="3" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900">Material / Suku Cadang yang Digunakan</label>
                            <input type="text" name="material_digunakan" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                        </div>

                        <div class="bg-slate-50 p-4 rounded-lg border border-slate-200">
                            <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Foto Hasil Perbaikan (Sesudah) <span class="text-rose-500">*</span></label>
                            <input type="file" name="foto_sesudah" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-white file:text-emerald-600 file:shadow-sm file:ring-1 file:ring-inset file:ring-gray-300 hover:file:bg-gray-50 cursor-pointer" accept="image/*" required />
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-x-4">
                        <button type="submit" onclick="this.disabled=true; this.form.submit(); this.innerHTML='Mengirim...';" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                            Submit Laporan Kerja
                        </button>
                    </div>
                </form>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>