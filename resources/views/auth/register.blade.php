<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-semibold text-gray-900 tracking-tight">Daftar Akun Baru</h2>
        <p class="text-sm text-gray-500 mt-2">Daftarkan akun pelapor untuk menyampaikan laporan fasilitas</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <label for="nama_lengkap" class="block text-sm font-medium leading-6 text-gray-900">Nama Lengkap</label>
            <div class="mt-1">
                <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required autofocus autocomplete="name"
                    class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all duration-150"
                    placeholder="Contoh: Rahayu Pratama">
            </div>
            @error('nama_lengkap')
            <p class="mt-1.5 text-sm text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- No Telepon -->
        <div>
            <label for="no_telepon" class="block text-sm font-medium leading-6 text-gray-900">No. Telepon / WhatsApp</label>
            <div class="mt-1">
                <input id="no_telepon" type="tel" name="no_telepon" value="{{ old('no_telepon') }}" required autocomplete="tel"
                    class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all duration-150"
                    placeholder="Contoh: 081234567890">
            </div>
            @error('no_telepon')
            <p class="mt-1.5 text-sm text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Alamat Email -->
        <div>
            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Alamat Email</label>
            <div class="mt-1">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all duration-150"
                    placeholder="nama@email.com">
            </div>
            @error('email')
            <p class="mt-1.5 text-sm text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Kata Sandi</label>
            <div class="mt-1">
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all duration-150"
                    placeholder="Minimal 8 karakter">
            </div>
            @error('password')
            <p class="mt-1.5 text-sm text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Konfirmasi Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium leading-6 text-gray-900">Konfirmasi Kata Sandi</label>
            <div class="mt-1">
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all duration-150"
                    placeholder="Ulangi kata sandi">
            </div>
            @error('password_confirmation')
            <p class="mt-1.5 text-sm text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="flex w-full justify-center items-center rounded-md bg-indigo-600 px-3 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
                Daftar Akun
            </button>
        </div>

        <div class="text-center text-sm text-gray-500 pt-2">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                Masuk di sini
            </a>
        </div>
    </form>
</x-guest-layout>
