<section class="p-5 sm:p-6 space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div>
                <h2 class="text-sm sm:text-base font-bold text-gray-900 dark:text-gray-100">
                    Zona Berbahaya: Hapus Akun Pengguna
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Penghapusan akun bersifat permanen dan tidak dapat dibatalkan.
                </p>
            </div>
        </div>

        <button type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center justify-center px-4 py-2.5 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white rounded-xl font-semibold text-xs shadow-sm hover:shadow transition-all duration-150 gap-2 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            <span>Hapus Akun</span>
        </button>
    </div>

    <div class="p-4 rounded-xl bg-rose-50/70 dark:bg-rose-950/20 border border-rose-200/80 dark:border-rose-900/40 text-xs text-rose-800 dark:text-rose-300">
        <p class="leading-relaxed">
            Setelah akun Anda dihapus, seluruh data profil dan hak akses Anda di sistem FRC akan dihapus secara permanen. Harap pastikan kembali sebelum melakukan tindakan ini.
        </p>
    </div>

    <!-- Modal Konfirmasi Hapus Akun -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-2xl">
            @csrf
            @method('delete')

            <div class="text-center sm:text-left flex flex-col sm:flex-row items-center sm:items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100">
                        Apakah Anda yakin ingin menghapus akun Anda?
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Setelah akun Anda dihapus, semua sumber daya dan data di dalamnya akan dihapus secara permanen. Masukkan kata sandi akun Anda untuk mengonfirmasi penghapusan.
                    </p>
                </div>
            </div>

            <div class="mt-5">
                <label for="password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                    Kata Sandi Konfirmasi <span class="text-rose-500">*</span>
                </label>
                <input id="password" name="password" type="password" 
                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 focus:border-rose-500 focus:ring-rose-500 transition-colors shadow-sm" 
                    placeholder="Masukkan kata sandi akun Anda" required />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5" />
            </div>

            <div class="mt-6 flex justify-end gap-2.5">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                    Ya, Hapus Akun Permanen
                </button>
            </div>
        </form>
    </x-modal>
</section>