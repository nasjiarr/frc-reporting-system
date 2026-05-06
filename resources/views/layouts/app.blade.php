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
    <div class="min-h-screen bg-slate-50 flex" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 z-50 w-64 bg-indigo-600 shadow-xl transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0" :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-16 px-4 border-b border-indigo-500">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <span class="flex items-center justify-center h-12 w-12 rounded-full bg-white p-2 shadow-sm">
                            <img src="{{ asset('images/logo-frc.png') }}" alt="Logo" class="h-8 w-auto">
                        </span>
                        <span class="font-bold text-lg tracking-tight text-white">
                            FRC<span class="text-blue-100">Report</span>
                        </span>
                    </a>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 px-4 py-6 space-y-2 text-white">
                    @if(Auth::user()->role !== 'Teknisi')
                    <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs(['dashboard', 'admin.dashboard', 'pelapor.dashboard', 'kepala.dashboard']) ? 'bg-indigo-700 text-white border-r-2 border-white' : 'text-white hover:bg-indigo-500 hover:text-white' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                        Dashboard
                    </a>
                    @endif

                    @if(Auth::user()->role === 'Pelapor')
                    <a href="{{ route('pelapor.laporan.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('pelapor.laporan.*') ? 'bg-indigo-700 text-white border-r-2 border-white' : 'text-white hover:bg-indigo-500 hover:text-white' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Laporan Saya
                    </a>
                    @elseif(Auth::user()->role === 'Admin')
                    <a href="{{ route('admin.laporan.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs(['admin.laporan.index', 'admin.laporan.create']) ? 'bg-indigo-700 text-white border-r-2 border-white' : 'text-white hover:bg-indigo-500 hover:text-white' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Laporan Saya
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-indigo-700 text-white border-r-2 border-white' : 'text-white hover:bg-indigo-500 hover:text-white' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Kelola User
                    </a>
                    <a href="{{ route('admin.penugasan.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.penugasan.*') ? 'bg-indigo-700 text-white border-r-2 border-white' : 'text-white hover:bg-indigo-500 hover:text-white' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Penugasan
                    </a>
                    <a href="{{ route('admin.laporan.selesai') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.laporan.selesai') ? 'bg-indigo-700 text-white border-r-2 border-white' : 'text-white hover:bg-indigo-500 hover:text-white' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Laporan Selesai
                    </a>
                    <a href="{{ route('admin.utilitas.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.utilitas.*') ? 'bg-indigo-700 text-white border-r-2 border-white' : 'text-white hover:bg-indigo-500 hover:text-white' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Utilitas
                    </a>
                    @elseif(Auth::user()->role === 'Teknisi')
                    <a href="{{ route('teknisi.dashboard') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('teknisi.dashboard') || request()->routeIs('teknisi.tugas.*') ? 'bg-indigo-700 text-white border-r-2 border-white' : 'text-white hover:bg-indigo-500 hover:text-white' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Tugas Aktif
                    </a>
                    <a href="{{ route('teknisi.riwayat') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('teknisi.riwayat') ? 'bg-indigo-700 text-white border-r-2 border-white' : 'text-white hover:bg-indigo-500 hover:text-white' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Riwayat Pekerjaan
                    </a>
                    @elseif(Auth::user()->role === 'KepalaFRC')
                    <a href="{{ route('kepala.laporan.index') }}" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('kepala.laporan.*') ? 'bg-indigo-700 text-white border-r-2 border-white' : 'text-white hover:bg-indigo-500 hover:text-white' }} transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Rekap Laporan
                    </a>
                    @endif
                </nav>
            </div>
        </div>

        <!-- Overlay for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden" style="display: none;"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-4 py-3">
                    <!-- Mobile menu button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': sidebarOpen, 'inline-flex': !sidebarOpen}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !sidebarOpen, 'inline-flex': sidebarOpen}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Notification Bell and Profile -->
                    <div class="flex items-center ml-auto space-x-3">
                        <div class="relative" x-data="{ notifOpen: false }">
                            <button @click="notifOpen = !notifOpen; if(notifOpen) markAsRead()" class="relative p-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                                <span id="notif-badge" class="hidden absolute top-0 right-0 flex items-center justify-center w-4 h-4 text-[10px] font-bold text-white bg-rose-500 border-2 border-white rounded-full shadow-sm">0</span>
                            </button>

                            <div x-show="notifOpen" @click.away="notifOpen = false" style="display: none;"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                class="absolute right-0 mt-3 w-80 bg-white border border-gray-100 rounded-2xl shadow-xl z-50 overflow-hidden transform origin-top-right">

                                <div class="px-4 py-3 border-b border-gray-100 bg-white flex justify-between items-center">
                                    <h3 class="text-sm font-bold text-gray-900">Notifikasi Baru</h3>
                                    <span class="px-2 py-0.5 text-[10px] font-semibold bg-indigo-50 text-indigo-600 rounded-full">Pembaruan</span>
                                </div>

                                <div class="max-h-80 overflow-y-auto divide-y divide-gray-50" id="notif-list-container">
                                    <div class="px-4 py-8 text-center flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900">Belum ada notifikasi</p>
                                        <p class="text-xs text-gray-500 mt-1">Anda sudah membaca semuanya.</p>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <!-- Profile Dropdown -->
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
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto py-2">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @isset($header)
                    <header class="bg-slate-50 pt-6 pb-4 mb-8">
                        <div class="border-b border-gray-200 pb-4">
                            {{ $header }}
                        </div>
                    </header>
                    @endisset

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
                            const waktu = data.latest.created_at || 'Baru saja';

                            listContainer.innerHTML = `
                            <a href="#" class="block px-4 py-4 hover:bg-slate-50 transition-colors group relative">
                                <div class="flex gap-3">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 truncate">${judul}</p>
                                        <p class="text-xs text-gray-600 mt-1 line-clamp-2 leading-relaxed">${pesan}</p>
                                        <p class="text-[10px] font-medium text-gray-400 mt-2 flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                            ${waktu}
                                        </p>
                                    </div>
                                </div>
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