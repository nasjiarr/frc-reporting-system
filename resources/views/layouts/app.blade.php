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

    <!-- Script Inisialisasi Dark Mode (Mencegah kedipan terang ke gelap) -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="antialiased bg-slate-50 dark:bg-gray-900 text-slate-800 dark:text-gray-100 transition-colors duration-200">
    <div class="min-h-screen bg-slate-50 dark:bg-gray-900 flex" x-data="{
        sidebarOpen: false,
        darkMode: localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        toggleTheme() {
            this.darkMode = !this.darkMode;
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            }
        }
    }">
        @php
            $role = Auth::user()->role;
            $roleBadgeConfig = [
                'Admin' => [
                    'label' => 'Administrator',
                    'badge' => 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-300 ring-1 ring-indigo-500/20 dark:ring-indigo-500/30',
                    'dot' => 'bg-indigo-500 dark:bg-indigo-400 shadow-[0_0_8px_rgba(129,140,248,0.6)]',
                    'avatar' => 'from-indigo-600 to-indigo-700 text-white',
                ],
                'Teknisi' => [
                    'label' => 'Teknisi Lapangan',
                    'badge' => 'bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-300 ring-1 ring-amber-500/20 dark:ring-amber-500/30',
                    'dot' => 'bg-amber-500 dark:bg-amber-400 shadow-[0_0_8px_rgba(251,191,36,0.6)]',
                    'avatar' => 'from-amber-600 to-amber-700 text-white',
                ],
                'Pelapor' => [
                    'label' => 'Pelapor / Staff',
                    'badge' => 'bg-sky-50 dark:bg-sky-500/10 text-sky-700 dark:text-sky-300 ring-1 ring-sky-500/20 dark:ring-sky-500/30',
                    'dot' => 'bg-sky-500 dark:bg-sky-400 shadow-[0_0_8px_rgba(56,189,248,0.6)]',
                    'avatar' => 'from-sky-600 to-sky-700 text-white',
                ],
                'KepalaFRC' => [
                    'label' => 'Kepala FRC',
                    'badge' => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-300 ring-1 ring-emerald-500/20 dark:ring-emerald-500/30',
                    'dot' => 'bg-emerald-500 dark:bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.6)]',
                    'avatar' => 'from-emerald-600 to-emerald-700 text-white',
                ],
            ];
            $currentRoleConfig = $roleBadgeConfig[$role] ?? [
                'label' => $role,
                'badge' => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 ring-1 ring-slate-200 dark:ring-slate-700',
                'dot' => 'bg-slate-400',
                'avatar' => 'from-slate-700 to-slate-800 text-white',
            ];
        @endphp

        <!-- Sidebar Navigation (Modern Clean SaaS, Adaptive Dark/Light) -->
        <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800/80 flex flex-col shadow-2xl lg:shadow-none transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
               :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

            <!-- Sidebar Header & Branding -->
            <div class="h-20 flex items-center justify-between px-5 border-b border-slate-200/80 dark:border-slate-800/80 bg-white/80 dark:bg-slate-900/60 backdrop-blur-sm">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3.5 group">
                    <div class="flex items-center justify-center h-11 w-11 rounded-xl bg-slate-50 dark:bg-white p-2 shadow-sm border border-slate-200 dark:border-transparent group-hover:scale-105 group-hover:ring-2 group-hover:ring-indigo-500/40 transition-all duration-200">
                        <img src="{{ asset('images/logo-frc.png') }}" alt="Logo FRC" class="h-7 w-auto object-contain">
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-1.5 leading-none">
                            <span class="font-bold text-base tracking-tight text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-200 transition-colors">
                                FRC<span class="text-indigo-600 dark:text-indigo-400">Report</span>
                            </span>
                        </div>
                        <div class="mt-1">
                            <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-[10px] font-semibold tracking-wide uppercase {{ $currentRoleConfig['badge'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $currentRoleConfig['dot'] }}"></span>
                                {{ $currentRoleConfig['label'] }}
                            </span>
                        </div>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 focus:outline-none transition-colors" title="Tutup Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Scrollable Navigation Items -->
            <nav class="flex-1 overflow-y-auto px-4 py-5 space-y-6" style="scrollbar-width: thin;">
                @if(Auth::user()->role === 'Admin')
                    <!-- Section: OVERVIEW -->
                    <div class="space-y-1">
                        <div class="px-3 pb-1 text-[11px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase font-mono">
                            OVERVIEW
                        </div>
                        @php $active = request()->routeIs(['admin.dashboard', 'dashboard']); @endphp
                        <a href="{{ route('admin.dashboard') }}"
                           class="group flex items-center gap-3 px-3 py-2 text-sm rounded-xl transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 font-semibold ring-1 ring-indigo-500/20 dark:ring-indigo-500/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-medium' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-150 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </div>

                    <!-- Section: MANAJEMEN TIKET -->
                    <div class="space-y-1">
                        <div class="px-3 pb-1 text-[11px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase font-mono">
                            MANAJEMEN TIKET
                        </div>
                        @php $active = request()->routeIs('admin.penugasan.*'); @endphp
                        <a href="{{ route('admin.penugasan.index') }}"
                           class="group flex items-center gap-3 px-3 py-2 text-sm rounded-xl transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 font-semibold ring-1 ring-indigo-500/20 dark:ring-indigo-500/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-medium' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-150 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span>Penugasan</span>
                        </a>

                        @php $active = request()->routeIs(['admin.laporan.index', 'admin.laporan.create', 'admin.laporan.show']); @endphp
                        <a href="{{ route('admin.laporan.index') }}"
                           class="group flex items-center gap-3 px-3 py-2 text-sm rounded-xl transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 font-semibold ring-1 ring-indigo-500/20 dark:ring-indigo-500/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-medium' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-150 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Laporan Saya</span>
                        </a>

                        @php $active = request()->routeIs('admin.laporan.selesai'); @endphp
                        <a href="{{ route('admin.laporan.selesai') }}"
                           class="group flex items-center gap-3 px-3 py-2 text-sm rounded-xl transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 font-semibold ring-1 ring-indigo-500/20 dark:ring-indigo-500/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-medium' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-150 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Laporan Selesai</span>
                        </a>
                    </div>

                    <!-- Section: MONITORING & UTILITAS -->
                    <div class="space-y-1">
                        <div class="px-3 pb-1 text-[11px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase font-mono">
                            MONITORING & UTILITAS
                        </div>
                        @php $active = request()->routeIs('admin.utilitas.*'); @endphp
                        <a href="{{ route('admin.utilitas.index') }}"
                           class="group flex items-center gap-3 px-3 py-2 text-sm rounded-xl transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 font-semibold ring-1 ring-indigo-500/20 dark:ring-indigo-500/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-medium' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-150 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Utilitas Gedung</span>
                        </a>
                    </div>

                    <!-- Section: PENGATURAN -->
                    <div class="space-y-1">
                        <div class="px-3 pb-1 text-[11px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase font-mono">
                            PENGATURAN
                        </div>
                        @php $active = request()->routeIs('admin.users.*'); @endphp
                        <a href="{{ route('admin.users.index') }}"
                           class="group flex items-center gap-3 px-3 py-2 text-sm rounded-xl transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 font-semibold ring-1 ring-indigo-500/20 dark:ring-indigo-500/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-medium' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-150 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            <span>Kelola User</span>
                        </a>
                    </div>
                @elseif(Auth::user()->role === 'Pelapor')
                    <!-- Section: OVERVIEW -->
                    <div class="space-y-1">
                        <div class="px-3 pb-1 text-[11px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase font-mono">
                            OVERVIEW
                        </div>
                        @php $active = request()->routeIs(['pelapor.dashboard', 'dashboard']); @endphp
                        <a href="{{ route('pelapor.dashboard') }}"
                           class="group flex items-center gap-3 px-3 py-2 text-sm rounded-xl transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 font-semibold ring-1 ring-indigo-500/20 dark:ring-indigo-500/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-medium' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-150 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </div>

                    <!-- Section: PELAPORAN KERUSAKAN -->
                    <div class="space-y-1">
                        <div class="px-3 pb-1 text-[11px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase font-mono">
                            PELAPORAN KERUSAKAN
                        </div>
                        @php $active = request()->routeIs('pelapor.laporan.create'); @endphp
                        <a href="{{ route('pelapor.laporan.create') }}"
                           class="group flex items-center gap-3 px-3 py-2 text-sm rounded-xl transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 font-semibold ring-1 ring-indigo-500/20 dark:ring-indigo-500/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-medium' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-150 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Buat Laporan Baru</span>
                        </a>

                        @php $active = request()->routeIs(['pelapor.laporan.index', 'pelapor.laporan.show']); @endphp
                        <a href="{{ route('pelapor.laporan.index') }}"
                           class="group flex items-center gap-3 px-3 py-2 text-sm rounded-xl transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 font-semibold ring-1 ring-indigo-500/20 dark:ring-indigo-500/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-medium' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-150 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Laporan Saya</span>
                        </a>
                    </div>
                @elseif(Auth::user()->role === 'Teknisi')
                    <!-- Section: OVERVIEW -->
                    <div class="space-y-1">
                        <div class="px-3 pb-1 text-[11px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase font-mono">
                            OVERVIEW
                        </div>
                        @php $active = request()->routeIs(['teknisi.dashboard', 'dashboard']); @endphp
                        <a href="{{ route('teknisi.dashboard') }}"
                           class="group flex items-center gap-3 px-3 py-2 text-sm rounded-xl transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 font-semibold ring-1 ring-indigo-500/20 dark:ring-indigo-500/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-medium' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-150 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </div>

                    <!-- Section: PENUGASAN KERJA -->
                    <div class="space-y-1">
                        <div class="px-3 pb-1 text-[11px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase font-mono">
                            PENUGASAN KERJA
                        </div>
                        @php $active = request()->routeIs('teknisi.tugas-aktif') || request()->routeIs('teknisi.tugas.*'); @endphp
                        <a href="{{ route('teknisi.tugas-aktif') }}"
                           class="group flex items-center gap-3 px-3 py-2 text-sm rounded-xl transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 font-semibold ring-1 ring-indigo-500/20 dark:ring-indigo-500/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-medium' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-150 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.03a4.5 4.5 0 004.486-6.32l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.32 4.486c.158.58.134 1.193-.03 1.743l-1.42 1.42" />
                            </svg>
                            <span>Tugas Aktif</span>
                        </a>

                        @php $active = request()->routeIs('teknisi.riwayat'); @endphp
                        <a href="{{ route('teknisi.riwayat') }}"
                           class="group flex items-center gap-3 px-3 py-2 text-sm rounded-xl transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 font-semibold ring-1 ring-indigo-500/20 dark:ring-indigo-500/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-medium' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-150 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Riwayat Pekerjaan</span>
                        </a>
                    </div>
                @elseif(Auth::user()->role === 'KepalaFRC')
                    <!-- Section: OVERVIEW -->
                    <div class="space-y-1">
                        <div class="px-3 pb-1 text-[11px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase font-mono">
                            OVERVIEW
                        </div>
                        @php $active = request()->routeIs(['kepala.dashboard', 'dashboard']); @endphp
                        <a href="{{ route('kepala.dashboard') }}"
                           class="group flex items-center gap-3 px-3 py-2 text-sm rounded-xl transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 font-semibold ring-1 ring-indigo-500/20 dark:ring-indigo-500/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-medium' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-150 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </div>

                    <!-- Section: MONITORING & LAPORAN -->
                    <div class="space-y-1">
                        <div class="px-3 pb-1 text-[11px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase font-mono">
                            MONITORING & LAPORAN
                        </div>
                        @php $active = request()->routeIs(['kepala.laporan.index', 'kepala.laporan.show']); @endphp
                        <a href="{{ route('kepala.laporan.index') }}"
                           class="group flex items-center gap-3 px-3 py-2 text-sm rounded-xl transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 font-semibold ring-1 ring-indigo-500/20 dark:ring-indigo-500/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-medium' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-150 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span>Rekap Seluruh Laporan</span>
                        </a>

                        @php $active = request()->routeIs('kepala.laporan_saya.*'); @endphp
                        <a href="{{ route('kepala.laporan_saya.index') }}"
                           class="group flex items-center gap-3 px-3 py-2 text-sm rounded-xl transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 font-semibold ring-1 ring-indigo-500/20 dark:ring-indigo-500/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-medium' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-150 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span>Laporan Saya</span>
                        </a>

                        @php $active = request()->routeIs('kepala.laporan.create'); @endphp
                        <a href="{{ route('kepala.laporan.create') }}"
                           class="group flex items-center gap-3 px-3 py-2 text-sm rounded-xl transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 font-semibold ring-1 ring-indigo-500/20 dark:ring-indigo-500/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-medium' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-150 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Buat Laporan</span>
                        </a>
                    </div>

                    <!-- Section: UTILITAS & EVALUASI -->
                    <div class="space-y-1">
                        <div class="px-3 pb-1 text-[11px] font-semibold tracking-wider text-slate-400 dark:text-slate-500 uppercase font-mono">
                            UTILITAS & EVALUASI
                        </div>
                        @php $active = request()->routeIs('kepala.utilitas.*'); @endphp
                        <a href="{{ route('kepala.utilitas.index') }}"
                           class="group flex items-center gap-3 px-3 py-2 text-sm rounded-xl transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 font-semibold ring-1 ring-indigo-500/20 dark:ring-indigo-500/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-medium' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-150 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Rekap Utilitas</span>
                        </a>

                        @php $active = request()->routeIs('kepala.kinerja.*'); @endphp
                        <a href="{{ route('kepala.kinerja.index') }}"
                           class="group flex items-center gap-3 px-3 py-2 text-sm rounded-xl transition-all duration-150 {{ $active ? 'bg-indigo-50 dark:bg-indigo-600/15 text-indigo-700 dark:text-indigo-300 font-semibold ring-1 ring-indigo-500/20 dark:ring-indigo-500/30 shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800/60 font-medium' }}">
                            <svg class="w-5 h-5 flex-shrink-0 transition-colors duration-150 {{ $active ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                            <span>Kinerja Teknisi</span>
                        </a>
                    </div>
                @endif
            </nav>

            <!-- Sidebar Footer: Mini User Profile Card -->
            <div class="p-3.5 border-t border-slate-200/80 dark:border-slate-800/80 bg-slate-50/60 dark:bg-slate-950/40">
                <div class="p-2 rounded-xl bg-white dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-800/90 flex items-center justify-between gap-2.5 shadow-sm">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 min-w-0 flex-1 group" title="Pengaturan Profil Akun">
                        <div class="relative flex-shrink-0">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold shadow-inner bg-gradient-to-tr {{ $currentRoleConfig['avatar'] }}">
                                {{ strtoupper(substr(Auth::user()->nama_lengkap ?? Auth::user()->name ?? 'U', 0, 2)) }}
                            </div>
                            <span class="absolute -bottom-0.5 -right-0.5 w-2 h-2 rounded-full ring-2 ring-white dark:ring-slate-900 {{ $currentRoleConfig['dot'] }}"></span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-200 truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">
                                {{ Auth::user()->nama_lengkap ?? Auth::user()->name }}
                            </p>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 truncate">
                                {{ Auth::user()->email }}
                            </p>
                        </div>
                    </a>

                    <div class="flex items-center gap-0.5 flex-shrink-0">
                        <!-- Theme Toggle Quick Button -->
                        <button @click="toggleTheme()" type="button" class="p-1.5 text-slate-400 hover:text-amber-500 dark:hover:text-amber-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors" :title="darkMode ? 'Ubah ke Mode Terang' : 'Ubah ke Mode Gelap'">
                            <svg x-show="darkMode" style="display: none;" class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <svg x-show="!darkMode" class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>

                        <!-- Logout Quick Button -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-lg transition-colors" title="Keluar / Log Out">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Overlay for mobile drawer -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-slate-950/70 backdrop-blur-sm lg:hidden" style="display: none;"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 transition-colors duration-200">
                <div class="flex items-center justify-between px-4 py-3">
                    <!-- Mobile menu button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 focus:text-gray-500 dark:focus:text-gray-400 transition-colors">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': sidebarOpen, 'inline-flex': !sidebarOpen}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !sidebarOpen, 'inline-flex': sidebarOpen}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Notification Bell and Profile -->
                    <div class="flex items-center ml-auto space-x-2 sm:space-x-3 relative">
                        <div x-data="{ notifOpen: false }">
                            <button @click="notifOpen = !notifOpen; if(notifOpen) markAsRead()" class="relative p-2 text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-gray-700 rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-200">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                                {{-- Badge angka --}}
                                <span id="notif-badge" class="hidden absolute -top-0.5 -right-0.5 flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white bg-rose-500 border-2 border-white dark:border-gray-800 rounded-full shadow-sm">0</span>
                                {{-- Ping animation --}}
                                <span id="notif-ping" class="hidden absolute -top-0.5 -right-0.5 flex h-[18px] w-[18px]">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                </span>
                            </button>

                            <div x-show="notifOpen" @click.away="notifOpen = false" style="display: none;"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                                class="absolute right-0 top-full mt-3 w-[92vw] sm:w-96 max-w-[420px] bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-2xl z-50 overflow-hidden transform origin-top-right">

                                {{-- Header --}}
                                <div class="px-5 py-3.5 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-gray-800 dark:to-gray-800 flex justify-between items-center">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">Notifikasi</h3>
                                    </div>
                                    <span id="notif-header-count" class="px-2.5 py-1 text-[10px] font-bold bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 rounded-full tracking-wide uppercase">0 Baru</span>
                                </div>

                                {{-- Notification list --}}
                                <div class="max-h-[420px] overflow-y-auto" id="notif-list-container" style="scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent;">
                                    <div class="px-5 py-10 text-center flex flex-col items-center justify-center">
                                        <div class="w-14 h-14 bg-gray-50 dark:bg-gray-700/50 rounded-2xl flex items-center justify-center mb-4">
                                            <svg class="w-7 h-7 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                        </div>
                                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Tidak ada notifikasi baru</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 max-w-[200px] leading-relaxed">Semua pemberitahuan Anda sudah terbaca.</p>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Profile Dropdown -->
                        <div>
                            <x-dropdown align="right" width="48" contentClasses="py-1 bg-white dark:bg-gray-800">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 dark:text-gray-300 bg-white dark:bg-gray-800 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none transition ease-in-out duration-150 shadow-sm border border-gray-200 dark:border-gray-700">
                                        <div>{{ Auth::user()->nama_lengkap }} <span class="text-xs text-gray-400 dark:text-gray-500 ml-1">({{ Auth::user()->role }})</span></div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')" class="text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:bg-gray-100 dark:focus:bg-gray-800">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            {{ __('Profile') }}
                                        </div>
                                    </x-dropdown-link>

                                    <!-- Theme Toggle -->
                                    <button @click="toggleTheme()" type="button" class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
                                        <div class="flex items-center">
                                            <svg x-show="darkMode" style="display: none;" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            <svg x-show="!darkMode" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                            </svg>
                                            <span x-text="darkMode ? 'Mode Terang' : 'Mode Gelap'"></span>
                                        </div>
                                    </button>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();" class="text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-gray-800">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg>
                                                {{ __('Log Out') }}
                                            </div>
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto py-2">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @isset($header)
                    <header class="bg-slate-50 dark:bg-gray-900 pt-6 pb-4 mb-8 transition-colors duration-200">
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                            {{ $header }}
                        </div>
                    </header>
                    @endisset

                    <div class="mb-6 space-y-3">
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

                        @if ($errors->any())
                        <div class="p-4 border-l-4 border-rose-500 bg-rose-50 text-rose-800 rounded-md shadow-sm text-sm">
                            <div class="font-bold mb-1">Terdapat kesalahan pada input data:</div>
                            <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <script>
        /**
         * Menentukan ikon & warna berdasarkan judul notifikasi
         */
        function getNotifStyle(judul) {
            const j = (judul || '').toLowerCase();

            if (j.includes('ditolak') || j.includes('tolak')) {
                return {
                    bg: 'bg-rose-100 dark:bg-rose-900/40',
                    text: 'text-rose-600 dark:text-rose-400',
                    dot: 'bg-rose-500',
                    icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                };
            }
            if (j.includes('selesai') || j.includes('perbaikan')) {
                return {
                    bg: 'bg-emerald-100 dark:bg-emerald-900/40',
                    text: 'text-emerald-600 dark:text-emerald-400',
                    dot: 'bg-emerald-500',
                    icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                };
            }
            if (j.includes('tugas') || j.includes('ditugaskan') || j.includes('diproses')) {
                return {
                    bg: 'bg-blue-100 dark:bg-blue-900/40',
                    text: 'text-blue-600 dark:text-blue-400',
                    dot: 'bg-blue-500',
                    icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>'
                };
            }
            if (j.includes('kerusakan baru') || j.includes('laporan baru')) {
                return {
                    bg: 'bg-amber-100 dark:bg-amber-900/40',
                    text: 'text-amber-600 dark:text-amber-400',
                    dot: 'bg-amber-500',
                    icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>'
                };
            }
            if (j.includes('update status') || j.includes('status laporan')) {
                return {
                    bg: 'bg-violet-100 dark:bg-violet-900/40',
                    text: 'text-violet-600 dark:text-violet-400',
                    dot: 'bg-violet-500',
                    icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>'
                };
            }
            // Default
            return {
                bg: 'bg-indigo-100 dark:bg-indigo-900/40',
                text: 'text-indigo-600 dark:text-indigo-400',
                dot: 'bg-indigo-500',
                icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
            };
        }

        /**
         * Format waktu relatif (misal: "5 menit lalu")
         */
        function timeAgo(dateString) {
            const now = new Date();
            const date = new Date(dateString);
            const diffMs = now - date;
            const diffSec = Math.floor(diffMs / 1000);
            const diffMin = Math.floor(diffSec / 60);
            const diffHour = Math.floor(diffMin / 60);
            const diffDay = Math.floor(diffHour / 24);

            if (diffSec < 60) return 'Baru saja';
            if (diffMin < 60) return `${diffMin} menit lalu`;
            if (diffHour < 24) return `${diffHour} jam lalu`;
            if (diffDay === 1) return 'Kemarin';
            if (diffDay < 7) return `${diffDay} hari lalu`;
            if (diffDay < 30) return `${Math.floor(diffDay / 7)} minggu lalu`;
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        }

        /**
         * Escape HTML untuk keamanan XSS
         */
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.appendChild(document.createTextNode(text));
            return div.innerHTML;
        }

        /**
         * Mengecek notifikasi baru dari server
         */
        function checkNotifications() {
            fetch('/api/notifications/unread')
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notif-badge');
                    const ping = document.getElementById('notif-ping');
                    const listContainer = document.getElementById('notif-list-container');
                    const headerCount = document.getElementById('notif-header-count');

                    // 1. Update Badge & Header (Berdasarkan jumlah yang BELUM DIBACA)
                    if (data.count > 0) {
                        const displayCount = data.count > 9 ? '9+' : data.count;
                        badge.innerText = displayCount;
                        badge.classList.remove('hidden');
                        ping.classList.remove('hidden');

                        if (headerCount) {
                            headerCount.innerText = `${data.count} Baru`;
                        }
                    } else {
                        badge.classList.add('hidden');
                        badge.innerText = '0';
                        ping.classList.add('hidden');

                        if (headerCount) {
                            headerCount.innerText = '0 Baru';
                        }
                    }

                    // 2. Render List Notifikasi (Menampilkan yang belum DAN sudah dibaca)
                    if (data.items && data.items.length > 0) {
                        let html = '';
                        data.items.forEach((notif, index) => {
                            const judul = escapeHtml(notif.judul || 'Pemberitahuan');
                            const pesan = escapeHtml(notif.pesan || 'Ada pembaruan pada aktivitas Anda.');
                            const waktu = timeAgo(notif.created_at);
                            const style = getNotifStyle(notif.judul);
                            const isLast = index === data.items.length - 1;
                            
                            // Hanya tampilkan titik penanda jika belum dibaca
                            const dotDisplay = notif.is_read ? 'style="display: none;"' : '';

                            html += `
                            <div class="px-4 py-3.5 hover:bg-slate-50 dark:hover:bg-gray-700/50 transition-colors duration-150 cursor-default ${!isLast ? 'border-b border-gray-100 dark:border-gray-700/50' : ''}">
                                <div class="flex gap-3">
                                    <div class="flex-shrink-0 mt-0.5">
                                        <div class="w-9 h-9 ${style.bg} rounded-xl flex items-center justify-center">
                                            <svg class="w-4 h-4 ${style.text}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                ${style.icon}
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-2">
                                            <p class="text-[13px] font-semibold text-gray-900 dark:text-gray-100 leading-tight">${judul}</p>
                                            <span class="flex-shrink-0 w-2 h-2 mt-1.5 rounded-full ${style.dot} notif-unread-dot" ${dotDisplay}></span>
                                        </div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 leading-relaxed line-clamp-2">${pesan}</p>
                                        <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500 mt-1.5 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            ${waktu}
                                        </p>
                                    </div>
                                </div>
                            </div>`;
                        });
                        listContainer.innerHTML = html;
                    } else {
                        // Tampilkan state kosong jika benar-benar tidak ada riwayat notifikasi
                        listContainer.innerHTML = `
                        <div class="px-5 py-10 text-center flex flex-col items-center justify-center">
                            <div class="w-14 h-14 bg-gray-50 dark:bg-gray-700/50 rounded-2xl flex items-center justify-center mb-4">
                                <svg class="w-7 h-7 text-gray-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">Belum ada notifikasi</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 max-w-[200px] leading-relaxed">Pemberitahuan aktivitas Anda akan muncul di sini.</p>
                        </div>`;
                    }
                })
                .catch(err => console.error('Gagal mengambil data notifikasi:', err));
        }

        /**
         * Menandai semua notifikasi sebagai telah dibaca
         */
        function markAsRead() {
            const badge = document.getElementById('notif-badge');
            const ping = document.getElementById('notif-ping');

            if (!badge.classList.contains('hidden')) {
                // Sembunyikan badge & ping secara instan
                badge.classList.add('hidden');
                badge.innerText = '0';
                ping.classList.add('hidden');

                // Sembunyikan dot indikator pada setiap notifikasi
                document.querySelectorAll('.notif-unread-dot').forEach(dot => {
                    dot.style.display = 'none';
                });

                // Update header count
                const headerCount = document.getElementById('notif-header-count');
                if (headerCount) {
                    headerCount.innerText = '0 Baru';
                }

                // Kirim ke server
                fetch('/api/notifications/mark-read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
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