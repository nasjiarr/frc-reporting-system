<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">Laporan Kerja Teknisi</h2>
        <p class="text-sm text-gray-600 mt-1">Detail penugasan: <span class="font-semibold">{{ $tugas->laporan->judul }}</span></p>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">

        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-6">
                <h3 class="font-bold text-gray-900 border-b border-gray-200 pb-3 mb-4">Detail Penugasan</h3>

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

                @if($tugas->status_tugas === 'Selesai' || $tugas->hasilPerbaikan)

                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-50 text-emerald-500 mb-6 border-8 border-emerald-50">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Pekerjaan Telah Selesai</h3>
                    <p class="text-gray-500 mt-2 max-w-md mx-auto">Terima kasih. Anda telah mensubmit laporan hasil perbaikan untuk tugas ini. Data telah diteruskan ke Admin dan Pelapor.</p>

                    <div class="mt-8">
                        <a href="{{ route('teknisi.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Dashboard
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
                            <textarea name="tindakan" rows="3" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Misal: Telah dilakukan penggantian kapasitor AC dan pengisian freon..." required></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900">Material / Suku Cadang yang Digunakan</label>
                            <input type="text" name="material_digunakan" class="mt-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Contoh: 1 buah Kapasitor 30uF, Kabel 2m" />
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada penggantian suku cadang.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-4 rounded-lg border border-slate-200">
                            <div>
                                <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Foto Kondisi Sebelum</label>
                                <input type="file" name="foto_sebelum" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-white file:text-indigo-600 file:shadow-sm file:ring-1 file:ring-inset file:ring-gray-300 hover:file:bg-gray-50 cursor-pointer" accept="image/*" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium leading-6 text-gray-900 mb-2">Foto Kondisi Sesudah</label>
                                <input type="file" name="foto_sesudah" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-white file:text-emerald-600 file:shadow-sm file:ring-1 file:ring-inset file:ring-gray-300 hover:file:bg-gray-50 cursor-pointer" accept="image/*" />
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-x-4">
                        <a href="{{ route('teknisi.dashboard') }}" class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700 transition">Batal</a>

                        <button type="submit"
                            onclick="this.disabled=true; this.form.submit(); this.innerHTML='Mengirim Data...';"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Submit Laporan Kerja
                        </button>
                    </div>
                </form>

                @endif

            </div>
        </div>

    </div>
</x-app-layout>