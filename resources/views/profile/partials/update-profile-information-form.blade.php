<section class="h-full flex flex-col justify-between">
    <!-- Header Bagian Profil -->
    <div class="px-5 sm:px-6 py-4 sm:py-5 border-b border-gray-100 dark:border-gray-700/80 bg-gray-50/75 dark:bg-gray-900/40 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h2 class="text-sm sm:text-base font-bold text-gray-900 dark:text-gray-100">
                    Informasi Profil
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Perbarui nama lengkap, nomor WhatsApp, dan alamat email Anda.
                </p>
            </div>
        </div>
    </div>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="p-5 sm:p-6 flex-1 flex flex-col justify-between space-y-5">
        @csrf
        @method('patch')

        <div class="space-y-4">
            <!-- Nama Lengkap -->
            <div>
                <label for="nama_lengkap" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                    Nama Lengkap <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input id="nama_lengkap" name="nama_lengkap" type="text" 
                        class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm" 
                        value="{{ old('nama_lengkap', $user->nama_lengkap) }}" 
                        required autofocus autocomplete="name" placeholder="Masukkan nama lengkap Anda" />
                </div>
                <x-input-error class="mt-1" :messages="$errors->get('nama_lengkap')" />
            </div>

            <!-- No. Telepon / WhatsApp -->
            <div>
                <label for="no_telepon" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                    No. Telepon / WhatsApp <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input id="no_telepon" name="no_telepon" type="text" 
                        class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm" 
                        value="{{ old('no_telepon', $user->no_telepon) }}" 
                        required placeholder="contoh: 081234567890" />
                </div>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Nomor ini digunakan untuk koordinasi pelaporan dan notifikasi tugas FRC.</p>
                <x-input-error class="mt-1" :messages="$errors->get('no_telepon')" />
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                    Alamat Email <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input id="email" name="email" type="email" 
                        class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm" 
                        value="{{ old('email', $user->email) }}" 
                        required autocomplete="username" placeholder="nama@domain.com" />
                </div>
                <x-input-error class="mt-1" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2.5 p-3 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-xs text-amber-800 dark:text-amber-200 space-y-1">
                    <p class="font-semibold">Alamat email Anda belum diverifikasi.</p>
                    <button form="send-verification" class="underline font-semibold hover:text-amber-900 dark:hover:text-amber-100 transition-colors">
                        Klik di sini untuk mengirim ulang email verifikasi.
                    </button>

                    @if (session('status') === 'verification-link-sent')
                    <p class="mt-1 font-bold text-emerald-600 dark:text-emerald-400">
                        Tautan verifikasi baru telah dikirimkan ke alamat email Anda.
                    </p>
                    @endif
                </div>
                @endif
            </div>

            <!-- Role (Read Only Display) -->
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                    Hak Akses / Peran Akun
                </label>
                <div class="flex items-center justify-between p-2.5 sm:p-3 rounded-xl bg-gray-50 dark:bg-gray-900/60 border border-gray-200 dark:border-gray-700 text-xs text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span class="font-bold text-gray-800 dark:text-gray-200">{{ $user->role }}</span>
                    </div>
                    <span class="text-[11px] text-gray-400 italic">Dikelola oleh Administrator</span>
                </div>
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="pt-4 border-t border-gray-100 dark:border-gray-700/80 flex items-center justify-end gap-3">
            <button type="submit" onclick="this.disabled=true; this.form.submit(); this.innerHTML='Menyimpan...';" class="inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white rounded-xl font-semibold text-xs shadow-sm hover:shadow transition-all duration-150 gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
                <span>Simpan Perubahan Profil</span>
            </button>
        </div>
    </form>
</section>