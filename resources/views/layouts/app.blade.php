<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Pelaporan FRC') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-frc.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="antialiased bg-slate-50 text-slate-800">
    <div class="min-h-screen">

        <nav x-data="{ open: false }" class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                                <img src="{{ asset('images/logo-frc.png') }}" alt="Logo" class="h-12 w-auto">

                                <span class="font-bold text-xl tracking-tight text-indigo-700 hidden md:block">
                                    FRC<span class="text-slate-800">Report</span>
                                </span>
                            </a>
                        </div>

                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                Dashboard
                            </x-nav-link>

                            @if(Auth::user()->role === 'Pelapor')
                            <x-nav-link href="{{ route('pelapor.laporan.index') }}" :active="request()->routeIs('pelapor.laporan.*')">
                                Laporan Saya
                            </x-nav-link>
                            @elseif(Auth::user()->role === 'Admin')
                            <x-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs(['admin.laporan.index', 'admin.laporan.create'])">
                                Laporan Saya
                            </x-nav-link>
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                Kelola User
                            </x-nav-link>
                            <x-nav-link :href="route('admin.penugasan.index')" :active="request()->routeIs('admin.penugasan.*')">
                                Penugasan
                            </x-nav-link>
                            <x-nav-link :href="route('admin.laporan.selesai')" :active="request()->routeIs('admin.laporan.selesai')">
                                Laporan Selesai
                            </x-nav-link>
                            <x-nav-link :href="route('admin.utilitas.index')" :active="request()->routeIs('admin.utilitas.*')">
                                Utilitas
                            </x-nav-link>
                            @elseif(Auth::user()->role === 'Teknisi')
                            <x-nav-link :href="route('teknisi.dashboard')" :active="request()->routeIs('teknisi.dashboard') || request()->routeIs('teknisi.tugas.*')">
                                Tugas Aktif
                            </x-nav-link>
                            <x-nav-link :href="route('teknisi.riwayat')" :active="request()->routeIs('teknisi.riwayat')">
                                Riwayat Pekerjaan
                            </x-nav-link>
                            @elseif(Auth::user()->role === 'KepalaFRC')
                            <x-nav-link :href="route('kepala.laporan.index')" :active="request()->routeIs('kepala.laporan.*')">
                                Rekap Laporan
                            </x-nav-link>
                            <x-nav-link :href="route('kepala.utilitas.index')" :active="request()->routeIs('kepala.utilitas.*')">
                                Rekap Utilitas
                            </x-nav-link>
                            <x-nav-link :href="route('kepala.kinerja.index')" :active="request()->routeIs('kepala.kinerja.*')">
                                Kinerja Teknisi
                            </x-nav-link>
                            @endif
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ms-6">

                        <div class="relative" x-data="{ notifOpen: false }">
                            <button @click="notifOpen = !notifOpen; if(notifOpen) markAsRead()" class="relative p-2 text-gray-400 hover:text-gray-500 mr-3 focus:outline-none transition-colors">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                                <span id="notif-badge" class="hidden absolute top-1 right-1 items-center justify-center px-2 py-1 text-[10px] font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-rose-600 rounded-full border-2 border-white shadow-sm">0</span>
                            </button>

                            <div x-show="notifOpen" @click.away="notifOpen = false" style="display: none;"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-xl shadow-lg py-1 z-50 overflow-hidden">

                                <div class="px-4 py-3 border-b border-gray-100 bg-slate-50 flex justify-between items-center">
                                    <span class="text-sm font-bold text-slate-800">Notifikasi Baru</span>
                                </div>

                                <div class="max-h-72 overflow-y-auto" id="notif-list-container">
                                    <div class="px-4 py-6 text-sm text-gray-500 text-center flex flex-col items-center">
                                        <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                        Belum ada notifikasi baru.
                                    </div>
                                </div>

                                <div class="px-4 py-2 border-t border-gray-100 text-center bg-gray-50">
                                    <a href="#" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">Lihat Semua Notifikasi</a>
                                </div>
                            </div>
                        </div>

                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-600 bg-white hover:text-gray-900 hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150 shadow-sm border-gray-200">
                                    <div>{{ Auth::user()->nama_lengkap }} <span class="text-xs text-gray-400 ml-1">({{ Auth::user()->role }})</span></div>
                                    <div class="ms-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();" class="text-rose-600 hover:bg-rose-50">
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-gray-200">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-responsive-nav-link>

                    @if(Auth::user()->role === 'Pelapor')
                    <x-responsive-nav-link :href="route('pelapor.laporan.index')" :active="request()->routeIs('pelapor.laporan.*')">
                        Laporan Saya
                    </x-responsive-nav-link>
                    @elseif(Auth::user()->role === 'Admin')
                    <x-responsive-nav-link :href="route('admin.laporan.index')" :active="request()->routeIs(['admin.laporan.index', 'admin.laporan.create'])">
                        Laporan Saya
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        Kelola User
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.penugasan.index')" :active="request()->routeIs('admin.penugasan.*')">
                        Penugasan
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.laporan.selesai')" :active="request()->routeIs('admin.laporan.selesai')">
                        Laporan Selesai
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.utilitas.index')" :active="request()->routeIs('admin.utilitas.*')">
                        Utilitas
                    </x-responsive-nav-link>
                    @elseif(Auth::user()->role === 'Teknisi')
                    <x-responsive-nav-link :href="route('teknisi.dashboard')" :active="request()->routeIs('teknisi.dashboard') || request()->routeIs('teknisi.tugas.*')">
                        Tugas Aktif
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('teknisi.riwayat')" :active="request()->routeIs('teknisi.riwayat')">
                        Riwayat Pekerjaan
                    </x-responsive-nav-link>
                    @elseif(Auth::user()->role === 'KepalaFRC')
                    <x-responsive-nav-link :href="route('kepala.laporan.index')" :active="request()->routeIs('kepala.laporan.*')">
                        Rekap Laporan
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('kepala.utilitas.index')" :active="request()->routeIs('kepala.utilitas.*')">
                        Rekap Utilitas
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('kepala.kinerja.index')" :active="request()->routeIs('kepala.kinerja.*')">
                        Kinerja Teknisi
                    </x-responsive-nav-link>
                    @endif
                </div>

                <div class="pt-4 pb-1 border-t border-gray-200 bg-gray-50">
                    <div class="px-4">
                        <div class="font-medium text-base text-indigo-900">{{ Auth::user()->nama_lengkap }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <x-responsive-nav-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();" class="text-rose-600 font-semibold">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        @isset($header)
        <header class="bg-slate-50 pt-8 pb-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 border-b border-gray-200 pb-4">
                {{ $header }}
            </div>
        </header>
        @endisset

        <main class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="mb-6">
                    @if (session('success'))
                    <div class="p-4 border-l-4 border-emerald-500 bg-emerald-50 text-emerald-800 rounded-md shadow-sm text-sm font-medium">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="p-4 border-l-4 border-rose-500 bg-rose-50 text-rose-800 rounded-md shadow-sm text-sm font-medium">
                        {{ session('error') }}
                    </div>
                    @endif
                </div>

                {{ $slot }}

            </div>
        </main>

    </div>

    <script>
        // 1. Fungsi untuk Mengecek Notifikasi Baru
        function checkNotifications() {
            fetch('/api/notifications/unread')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notif-badge');
                    const listContainer = document.getElementById('notif-list-container');

                    if (data.count > 0) {
                        // Update Angka Merah
                        badge.innerText = data.count;
                        badge.classList.remove('hidden');

                        // Cetak List Notifikasi (Gunakan OR "||" untuk mencegah error jika nama kolom berbeda)
                        if (data.latest) {
                            const judul = data.latest.judul || data.latest.title || data.latest.type || 'Pemberitahuan Baru';
                            const pesan = data.latest.pesan || data.latest.message || data.latest.data || 'Ada pembaruan pada aktivitas Anda.';

                            listContainer.innerHTML = `
                            <a href="#" class="block px-4 py-3 hover:bg-slate-50 border-b border-gray-50 transition-colors">
                                <p class="text-sm font-semibold text-gray-800">${judul}</p>
                                <p class="text-xs text-gray-600 mt-1 line-clamp-2">${pesan}</p>
                                <p class="text-[10px] text-gray-400 mt-2 font-medium uppercase tracking-wider">Belum dibaca</p>
                            </a>
                        `;
                        }
                    }
                })
                .catch(err => console.error('Gagal mengambil data notifikasi:', err));
        }

        // 2. Fungsi untuk Menandai Telah Dibaca (Saat lonceng diklik)
        function markAsRead() {
            const badge = document.getElementById('notif-badge');

            // Cek apakah ada angka notifikasi merah yang muncul
            if (!badge.classList.contains('hidden')) {

                // A. Sembunyikan angka merah secara instan (UX yang baik agar terasa cepat)
                badge.classList.add('hidden');
                badge.innerText = '0';

                // B. Kirim laporan ke database secara diam-diam (background)
                fetch('/api/notifications/mark-read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            // Ambil token CSRF dari meta tag Laravel di atas
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => console.log('Notifikasi berhasil ditandai sudah dibaca.'))
                    .catch(err => console.error('Gagal memproses mark-as-read:', err));
            }
        }

        // Jalankan pengecekan setiap 30 detik
        setInterval(checkNotifications, 30000);
        // Jalankan langsung saat halaman baru dimuat
        checkNotifications();
    </script>
</body>

</html>