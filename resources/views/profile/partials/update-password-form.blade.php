<section class="h-full flex flex-col justify-between" x-data="{
    showCurrent: false,
    showNew: false,
    showConfirm: false
}">
    <!-- Header Bagian Kata Sandi -->
    <div class="px-5 sm:px-6 py-4 sm:py-5 border-b border-gray-100 dark:border-gray-700/80 bg-gray-50/75 dark:bg-gray-900/40 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-100 dark:bg-amber-900/50 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div>
                <h2 class="text-sm sm:text-base font-bold text-gray-900 dark:text-gray-100">
                    Keamanan & Kata Sandi
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Pastikan akun Anda menggunakan kombinasi sandi yang kuat.
                </p>
            </div>
        </div>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="p-5 sm:p-6 flex-1 flex flex-col justify-between space-y-5">
        @csrf
        @method('put')

        <div class="space-y-4">
            <!-- Password Saat Ini -->
            <div>
                <label for="update_password_current_password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                    Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input id="update_password_current_password" name="current_password" 
                        :type="showCurrent ? 'text' : 'password'" 
                        class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 pr-10 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm" 
                        autocomplete="current-password" placeholder="Masukkan kata sandi lama Anda" required />
                    <button type="button" @click="showCurrent = !showCurrent" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg x-show="!showCurrent" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="showCurrent" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
            </div>

            <!-- Password Baru -->
            <div>
                <label for="update_password_password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                    Kata Sandi Baru <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input id="update_password_password" name="password" 
                        :type="showNew ? 'text' : 'password'" 
                        class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 pr-10 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm" 
                        autocomplete="new-password" placeholder="Minimal 8 karakter baru" required />
                    <button type="button" @click="showNew = !showNew" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg x-show="!showNew" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="showNew" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
            </div>

            <!-- Konfirmasi Password Baru -->
            <div>
                <label for="update_password_password_confirmation" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                    Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                </label>
                <div class="relative">
                    <input id="update_password_password_confirmation" name="password_confirmation" 
                        :type="showConfirm ? 'text' : 'password'" 
                        class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 pr-10 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm" 
                        autocomplete="new-password" placeholder="Ulangi kata sandi baru Anda" required />
                    <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <svg x-show="!showConfirm" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="showConfirm" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
            </div>

            <!-- Petunjuk Keamanan -->
            <div class="p-3 rounded-xl bg-gray-50 dark:bg-gray-900/40 border border-gray-200/80 dark:border-gray-700/60 text-xs text-gray-500 dark:text-gray-400 space-y-1">
                <span class="font-bold text-gray-700 dark:text-gray-300 block">Saran Kata Sandi Kuat:</span>
                <p>&bull; Minimal panjang 8 karakter</p>
                <p>&bull; Gunakan kombinasi huruf besar, angka, dan karakter khusus</p>
            </div>
        </div>

        <!-- Tombol Simpan Password -->
        <div class="pt-4 border-t border-gray-100 dark:border-gray-700/80 flex items-center justify-end gap-3">
            <button type="submit" onclick="this.disabled=true; this.form.submit(); this.innerHTML='Memperbarui...';" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-900 hover:bg-black dark:bg-gray-700 dark:hover:bg-gray-600 text-white rounded-xl font-semibold text-xs shadow-sm hover:shadow transition-all duration-150 gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
                <span>Perbarui Kata Sandi</span>
            </button>
        </div>
    </form>
</section>