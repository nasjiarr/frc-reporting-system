<x-app-layout>
    {{-- Header Slot: Breadcrumbs, Title & Subtitle --}}
    <x-slot name="header">
        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-3 overflow-x-auto no-scrollbar whitespace-nowrap py-0.5" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors inline-flex items-center gap-1.5 font-medium flex-shrink-0">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Dashboard</span>
            </a>
            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-800 dark:text-gray-200 font-semibold flex-shrink-0" aria-current="page">
                Pengaturan Profil
            </span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                    Pengaturan Akun & Profil
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Kelola informasi data diri, keamanan kata sandi, dan preferensi akun Anda di sistem FRC.
                </p>
            </div>
        </div>
    </x-slot>

    @php
        $role = $user->role;
        $roleStyles = [
            'Admin' => [
                'label' => 'Administrator',
                'badge' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800',
                'dot' => 'bg-indigo-500',
                'avatar' => 'from-indigo-600 to-indigo-700 text-white',
            ],
            'Teknisi' => [
                'label' => 'Teknisi Lapangan',
                'badge' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300 border border-amber-200 dark:border-amber-800',
                'dot' => 'bg-amber-500',
                'avatar' => 'from-amber-600 to-amber-700 text-white',
            ],
            'Pelapor' => [
                'label' => 'Staf Pelapor',
                'badge' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
                'dot' => 'bg-blue-500',
                'avatar' => 'from-blue-600 to-blue-700 text-white',
            ],
            'KepalaFRC' => [
                'label' => 'Kepala FRC',
                'badge' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800',
                'dot' => 'bg-emerald-500',
                'avatar' => 'from-emerald-600 to-emerald-700 text-white',
            ],
        ];
        $currentRole = $roleStyles[$role] ?? [
            'label' => $role,
            'badge' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700',
            'dot' => 'bg-gray-500',
            'avatar' => 'from-gray-600 to-gray-700 text-white',
        ];

        $initials = collect(explode(' ', $user->nama_lengkap ?? $user->name))
            ->filter()
            ->take(2)
            ->map(fn($part) => strtoupper(substr($part, 0, 1)))
            ->implode('');
        if (empty($initials)) { $initials = 'U'; }
    @endphp

    <div class="space-y-6 pb-12">
        <!-- Toast / Alert Notifikasi Sukses -->
        @if (session('status') === 'profile-updated')
        <div x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-300 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold">Pembaruan Berhasil</h4>
                    <p class="text-xs text-emerald-700 dark:text-emerald-300">Informasi profil akun Anda telah berhasil disimpan.</p>
                </div>
            </div>
            <button @click="show = false" class="text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 p-1.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        @endif

        @if (session('status') === 'password-updated')
        <div x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-300 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="text-xs sm:text-sm font-bold">Kata Sandi Diperbarui</h4>
                    <p class="text-xs text-emerald-700 dark:text-emerald-300">Kredensial keamanan kata sandi akun Anda berhasil diperbarui.</p>
                </div>
            </div>
            <button @click="show = false" class="text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 p-1.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        @endif

        <!-- 1. Hero Profile Card: Ringkasan Pengguna -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-5 sm:p-6 transition-colors">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                <div class="flex items-center gap-4 sm:gap-5 min-w-0">
                    <!-- Avatar Badge -->
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-tr {{ $currentRole['avatar'] }} flex items-center justify-center font-extrabold text-xl sm:text-2xl shadow-md shrink-0">
                        {{ $initials }}
                    </div>
                    <!-- User Details -->
                    <div class="min-w-0">
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h2 class="text-lg sm:text-xl lg:text-2xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight truncate">
                                {{ $user->nama_lengkap ?? $user->name }}
                            </h2>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $currentRole['badge'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $currentRole['dot'] }}"></span>
                                {{ $currentRole['label'] }}
                            </span>
                            @if($user->is_active ?? true)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Akun Aktif
                            </span>
                            @endif
                        </div>
                        <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1.5">
                            <span class="inline-flex items-center gap-1.5 truncate">
                                <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ $user->email }}</span>
                            </span>
                            @if($user->no_telepon)
                            <span class="inline-flex items-center gap-1.5 truncate">
                                <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span>{{ $user->no_telepon }}</span>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Registration Meta Info -->
                <div class="sm:text-right shrink-0 pt-3 sm:pt-0 border-t sm:border-t-0 border-gray-100 dark:border-gray-700/60">
                    <span class="text-xs text-gray-400 dark:text-gray-500 block uppercase font-mono tracking-wider">Tanggal Terdaftar</span>
                    <span class="text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 mt-0.5 block">
                        {{ $user->created_at ? $user->created_at->isoFormat('D MMMM Y') : 'Terdaftar di Sistem' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 2. Form Grid (Informasi Profil & Keamanan Password) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Kolom 1: Update Profil -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between transition-colors">
                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Kolom 2: Update Kata Sandi -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden flex flex-col justify-between transition-colors">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- 3. Zona Berbahaya: Hapus Akun -->
        <div class="bg-white dark:bg-gray-800 border border-rose-200 dark:border-rose-900/60 rounded-2xl shadow-sm overflow-hidden transition-colors">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>