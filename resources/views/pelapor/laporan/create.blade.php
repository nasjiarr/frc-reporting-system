<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 tracking-tight">Form Pelaporan Kerusakan</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Harap isi detail kerusakan secara spesifik agar teknisi dapat menangani dengan akurat.</p>
    </x-slot>

    <div class="max-w-4xl">
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm rounded-lg overflow-hidden">
            <form action="{{ route('pelapor.laporan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="p-6 space-y-6">
                    <div>
                        <label for="judul" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Kerusakan <span class="text-rose-500 dark:text-rose-400">*</span></label>
                        <div class="mt-2">
                            <input type="text" name="judul" id="judul" value="{{ old('judul') }}" placeholder="Contoh: AC Bocor, Lampu Mati, Pipa Pecah"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                        </div>
                        @error('judul')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="lokasi" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Lokasi / Ruangan <span class="text-rose-500 dark:text-rose-400">*</span></label>
                        <div class="mt-2">
                            <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Gedung A, Lantai 2, Ruang Dosen"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                        </div>
                        @error('lokasi')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Deskripsi Detail <span class="text-rose-500 dark:text-rose-400">*</span></label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Ceritakan apa yang terjadi, sejak kapan, dan dampak dari kerusakan tersebut.</p>
                        <div class="mt-2">
                            <textarea id="deskripsi" name="deskripsi" rows="5"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 dark:bg-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>{{ old('deskripsi') }}</textarea>
                        </div>
                        @error('deskripsi')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="foto_sebelum" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-200">Foto Bukti Kerusakan <span class="text-rose-500 dark:text-rose-400">*</span></label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Format yang diizinkan: JPG, PNG. Ukuran maksimal: 2 MB.</p>
                        <div class="mt-2">
                            <input type="file" name="foto_sebelum" id="foto_sebelum" accept=".jpg,.jpeg,.png"
                                class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 file:dark:bg-indigo-900/50 file:dark:text-indigo-300 hover:file:dark:bg-indigo-900/70 transition-colors" required>
                        </div>
                        @error('foto_sebelum')
                        <p class="mt-1 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>



                <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-end gap-x-4">
                    <a href="{{ route('pelapor.laporan.index') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-200 hover:text-gray-700 dark:hover:text-gray-400 transition">Batal</a>

                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Kirim Laporan
                    </button>
                </div>
            </form>
            <script>
                // Script sederhana untuk menampilkan nama file yang dipilih
                document.getElementById('foto_sebelum').addEventListener('change', function(e) {
                    let fileName = e.target.files[0] ? e.target.files[0].name : '';
                    document.getElementById('file-name-preview').textContent = fileName ? 'File dipilih: ' + fileName : '';
                });
            </script>
        </div>
    </div>
</x-app-layout>