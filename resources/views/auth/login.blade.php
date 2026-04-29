<x-guest-layout>
    <div class="mb-8 text-center">
        <h2 class="text-2xl font-semibold text-gray-900 tracking-tight">Selamat Datang</h2>
        <p class="text-sm text-gray-500 mt-2">Masuk ke akun Anda untuk melanjutkan</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Alamat Email</label>
            <div class="mt-2">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all duration-150"
                    placeholder="nama@email.com">
            </div>
            @error('email')
            <p class="mt-2 text-sm text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Kata Sandi</label>
            </div>
            <div class="mt-2">
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all duration-150"
                    placeholder="••••••••">
            </div>
            @error('password')
            <p class="mt-2 text-sm text-rose-600 font-medium">{{ $message }}</p>
            @enderror
        </div>


        <div class="pt-2">
            <button type="submit" class="flex w-full justify-center items-center rounded-md bg-indigo-600 px-3 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                Masuk
            </button>
        </div>
    </form>
</x-guest-layout>