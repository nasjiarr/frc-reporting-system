<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">Form Pelaporan Kerusakan</h2>
        <p class="text-sm text-gray-600 mt-1">Harap isi detail kerusakan secara spesifik agar teknisi dapat menangani dengan akurat.</p>
    </x-slot>

    <div class="max-w-4xl">
        <div class="bg-white border border-gray-200 shadow-sm rounded-lg overflow-hidden">
            <form action="{{ route('admin.laporan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="p-6 space-y-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium leading-6 text-gray-900">Kerusakan <span class="text-rose-500">*</span></label>
                        <div class="mt-2">
                            <input type="text" name="judul" id="judul" value="{{ old('judul') }}" placeholder="Contoh: AC Bocor, Lampu Mati, Pipa Pecah"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                        </div>
                        @error('judul')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="lokasi" class="block text-sm font-medium leading-6 text-gray-900">Lokasi / Ruangan <span class="text-rose-500">*</span></label>
                        <div class="mt-2">
                            <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Gedung A, Lantai 2, Ruang Dosen"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                        </div>
                        @error('lokasi')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-medium leading-6 text-gray-900">Deskripsi Detail <span class="text-rose-500">*</span></label>
                        <p class="text-xs text-gray-500 mb-2">Ceritakan apa yang terjadi, sejak kapan, dan dampak dari kerusakan tersebut.</p>
                        <div class="mt-2">
                            <textarea id="deskripsi" name="deskripsi" rows="5"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>{{ old('deskripsi') }}</textarea>
                        </div>
                        @error('deskripsi')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="foto_sebelum" class="block text-sm font-medium leading-6 text-gray-900">Foto Bukti Kerusakan <span class="text-rose-500">*</span></label>
                        <p class="text-xs text-gray-500 mb-2">Format yang diizinkan: JPG, JPEG, PNG. Ukuran maksimal: 2 MB.</p>
                        <div class="mt-2 flex items-center justify-center w-full">
                            <label for="foto_sebelum" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk unggah</span> atau seret file ke sini</p>
                                </div>
                                <input id="foto_sebelum" name="foto_sebelum" type="file" class="hidden" accept=".jpg,.jpeg,.png" required />
                            </label>
                        </div>
                        <p id="file-name" class="mt-2 text-sm text-indigo-600 font-medium"></p>
                        @error('foto_sebelum')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-x-4">
                    <a href="{{ route('admin.laporan.index') }}" class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700 transition">Batal</a>

                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('foto_sebelum').addEventListener('change', function(e) {
            var fileName = e.target.files[0] ? e.target.files[0].name : '';
            document.getElementById('file-name').textContent = fileName ? 'File dipilih: ' + fileName : '';
        });
    </script>
</x-app-layout>