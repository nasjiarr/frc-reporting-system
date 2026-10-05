<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <nav class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400 mb-1.5">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Dashboard</a>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300 font-medium">Pengguna</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-indigo-600 dark:text-indigo-400 font-semibold">Manajemen User</span>
                </nav>
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 tracking-tight">Manajemen Pengguna</h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                        {{ $stats['total'] }} Akun Terdaftar
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola hak akses peran staf, pantau beban kerja teknisi lapangan, dan kendalikan status akses akun FRC.</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" 
                    @click="$dispatch('open-user-modal'); window.dispatchEvent(new CustomEvent('open-user-modal'))" 
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white font-semibold text-sm rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah User Baru
                </button>
            </div>
        </div>
    </x-slot>

    <div x-data="{ 
            userModalOpen: {{ old('form_action') === 'create' ? 'true' : 'false' }}, 
            editModalOpen: {{ old('form_action') === 'edit' ? 'true' : 'false' }}, 
            confirmToggleModalOpen: false,
            editUrl: '{{ old('edit_url', '') }}', 
            editUser: { 
                id: '{{ old('edit_user_id', '') }}',
                nama_lengkap: '{{ addslashes(old('nama_lengkap', '')) }}', 
                email: '{{ addslashes(old('email', '')) }}', 
                no_telepon: '{{ addslashes(old('no_telepon', '')) }}', 
                role: '{{ old('role', '') }}',
                is_current_admin: {{ old('edit_user_id') == auth()->id() ? 'true' : 'false' }}
            },
            toggleUser: {
                id: '',
                nama: '',
                is_active: true,
                role: '',
                tugas_aktif: 0
            },
            toggleUrl: '',
            openEditModal(user, url) {
                this.editUrl = url;
                this.editUser = {
                    id: user.id,
                    nama_lengkap: user.nama_lengkap,
                    email: user.email,
                    no_telepon: user.no_telepon,
                    role: user.role,
                    is_current_admin: user.id === {{ auth()->id() }}
                };
                this.editModalOpen = true;
            },
            openToggleModal(user, url) {
                this.toggleUrl = url;
                this.toggleUser = {
                    id: user.id,
                    nama: user.nama_lengkap,
                    is_active: user.is_active,
                    role: user.role,
                    tugas_aktif: user.tugas_aktif_count || 0
                };
                this.confirmToggleModalOpen = true;
            }
        }" 
        @open-user-modal.window="userModalOpen = true"
        class="space-y-6">

        <!-- Flash Alerts -->
        @if(session('success'))
        <div class="rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/80 p-4 text-sm text-emerald-800 dark:text-emerald-200 flex items-start gap-3 shadow-sm">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="font-medium">{{ session('success') }}</div>
        </div>
        @endif

        @if(session('error'))
        <div class="rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/80 p-4 text-sm text-rose-800 dark:text-rose-200 flex items-start gap-3 shadow-sm">
            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="font-medium">{{ session('error') }}</div>
        </div>
        @endif

        <!-- 4 KPI Summary Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Pengguna -->
            <a href="{{ route('admin.users.index') }}" class="group block p-4 sm:p-5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm hover:border-indigo-400 dark:hover:border-indigo-500 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Pengguna</span>
                    <div class="p-2 rounded-lg bg-indigo-50 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">{{ $stats['total'] }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Akun</span>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 truncate">
                    {{ $stats['admin_total'] }} Admin &bull; {{ $stats['pelapor_total'] }} Pelapor
                </p>
            </a>

            <!-- Akun Aktif -->
            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['status' => 'aktif', 'page' => 1])) }}" class="group block p-4 sm:p-5 bg-white dark:bg-gray-800 border {{ request('status') === 'aktif' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-gray-200 dark:border-gray-700' }} rounded-xl shadow-sm hover:border-emerald-400 dark:hover:border-emerald-500 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Akun Aktif</span>
                    <div class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-extrabold text-emerald-600 dark:text-emerald-400 tracking-tight">{{ $stats['aktif'] }}</span>
                    <span class="text-xs text-emerald-700/80 dark:text-emerald-400/80 font-medium">User</span>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 truncate">
                    {{ $stats['nonaktif'] }} akun dinonaktifkan
                </p>
            </a>

            <!-- Teknisi Lapangan -->
            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['role' => 'Teknisi', 'page' => 1])) }}" class="group block p-4 sm:p-5 bg-white dark:bg-gray-800 border {{ request('role') === 'Teknisi' ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-gray-200 dark:border-gray-700' }} rounded-xl shadow-sm hover:border-amber-400 dark:hover:border-amber-500 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Teknisi FRC</span>
                    <div class="p-2 rounded-lg bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-extrabold text-amber-600 dark:text-amber-400 tracking-tight">{{ $stats['teknisi_total'] }}</span>
                    <span class="text-xs text-amber-700/80 dark:text-amber-400/80 font-medium">Teknisi</span>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 truncate">
                    <span class="text-emerald-600 dark:text-emerald-400 font-semibold">{{ $stats['teknisi_ready'] }} Siaga</span> (0 tugas aktif)
                </p>
            </a>

            <!-- Staf Pelapor -->
            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['role' => 'Pelapor', 'page' => 1])) }}" class="group block p-4 sm:p-5 bg-white dark:bg-gray-800 border {{ request('role') === 'Pelapor' ? 'border-blue-500 ring-2 ring-blue-500/20' : 'border-gray-200 dark:border-gray-700' }} rounded-xl shadow-sm hover:border-blue-400 dark:hover:border-blue-500 hover:shadow-md transition-all duration-200">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Staf Pelapor</span>
                    <div class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <div class="mt-3 flex items-baseline gap-2">
                    <span class="text-2xl sm:text-3xl font-extrabold text-blue-600 dark:text-blue-400 tracking-tight">{{ $stats['pelapor_total'] }}</span>
                    <span class="text-xs text-blue-700/80 dark:text-blue-400/80 font-medium">Staf</span>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 truncate">
                    Tenant & Pengguna Gedung
                </p>
            </a>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm p-4 sm:p-5">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
                <!-- Search Input -->
                <div class="lg:col-span-5">
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Pencarian Pengguna</label>
                    <div class="relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau no. telepon..." class="block w-full pl-9 pr-3 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    </div>
                </div>

                <!-- Role Filter -->
                <div class="lg:col-span-3">
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Hak Akses (Role)</label>
                    <select name="role" class="block w-full py-2 px-3 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="">Semua Role</option>
                        <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="KepalaFRC" {{ request('role') == 'KepalaFRC' ? 'selected' : '' }}>Kepala FRC</option>
                        <option value="Teknisi" {{ request('role') == 'Teknisi' ? 'selected' : '' }}>Teknisi</option>
                        <option value="Pelapor" {{ request('role') == 'Pelapor' ? 'selected' : '' }}>Pelapor</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="lg:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">Status Akun</label>
                    <select name="status" class="block w-full py-2 px-3 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Hanya Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Hanya Nonaktif</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="lg:col-span-2 flex items-center gap-2">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 dark:hover:bg-gray-600 text-white font-semibold text-sm rounded-lg shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>

                    @if(request()->hasAny(['search', 'role', 'status']))
                    <a href="{{ route('admin.users.index') }}" title="Reset Filter" class="p-2 text-gray-500 hover:text-rose-600 dark:text-gray-400 dark:hover:text-rose-400 rounded-lg border border-gray-300 dark:border-gray-600 hover:border-rose-300 dark:hover:border-rose-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Main User List Card -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm overflow-hidden">
            <!-- Header Table Bar -->
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gray-50/50 dark:bg-gray-900/50">
                <div class="flex items-center gap-2">
                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">Daftar Pengguna FRC</h3>
                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold">
                        {{ $users->total() }} Data
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna
                    </span>
                    <button type="button" @click="userModalOpen = true" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white font-semibold text-xs rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah User
                    </button>
                </div>
            </div>

            <!-- Desktop & Tablet Table (Hidden on Mobile) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/40">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pengguna</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kontak & WhatsApp</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Peran & Beban Kerja</th>
                            <th scope="col" class="px-6 py-3.5 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status Akun</th>
                            <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @forelse($users as $user)
                        @php
                            $initials = collect(explode(' ', $user->nama_lengkap))
                                ->filter()
                                ->take(2)
                                ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                                ->implode('');
                            if (empty($initials)) { $initials = 'U'; }

                            $waClean = preg_replace('/[^0-9]/', '', $user->no_telepon ?? '');
                            if (str_starts_with($waClean, '0')) {
                                $waClean = '62' . substr($waClean, 1);
                            }
                        @endphp
                        <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/40 transition-colors {{ !$user->is_active ? 'bg-gray-50/50 dark:bg-gray-800/40 opacity-75' : '' }}">
                            <!-- Pengguna Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shrink-0
                                        {{ $user->role === 'Admin' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/60 dark:text-indigo-300 ring-2 ring-indigo-200 dark:ring-indigo-800' : '' }}
                                        {{ $user->role === 'Teknisi' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/60 dark:text-amber-300 ring-2 ring-amber-200 dark:ring-amber-800' : '' }}
                                        {{ $user->role === 'Pelapor' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/60 dark:text-blue-300 ring-2 ring-blue-200 dark:ring-blue-800' : '' }}
                                        {{ $user->role === 'KepalaFRC' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300 ring-2 ring-emerald-200 dark:ring-emerald-800' : '' }}">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-gray-900 dark:text-gray-100 text-sm">{{ $user->nama_lengkap }}</span>
                                            @if(auth()->id() === $user->id)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                                Anda
                                            </span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-1.5">
                                            <span>Bergabung: {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Kontak & WhatsApp -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-200 font-medium">{{ $user->email }}</div>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $user->no_telepon ?: '-' }}</span>
                                    @if($waClean)
                                    <a href="https://wa.me/{{ $waClean }}" target="_blank" rel="noopener noreferrer" title="Hubungi via WhatsApp" class="inline-flex items-center text-xs text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-medium bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-800/80 transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.203c.043.072.043.419-.101.824z" />
                                        </svg>
                                        Chat WA
                                    </a>
                                    @endif
                                </div>
                            </td>

                            <!-- Peran & Beban Kerja -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col gap-1.5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold w-max
                                        {{ $user->role == 'Admin' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800' : '' }}
                                        {{ $user->role == 'Teknisi' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 border border-amber-200 dark:border-amber-800' : '' }}
                                        {{ $user->role == 'Pelapor' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border border-blue-200 dark:border-blue-800' : '' }}
                                        {{ $user->role == 'KepalaFRC' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : '' }}">
                                        {{ $user->role }}
                                    </span>

                                    <!-- Indikator Beban Kerja untuk Teknisi -->
                                    @if($user->role === 'Teknisi')
                                        @if(($user->tugas_aktif_count ?? 0) > 0)
                                        <span class="inline-flex items-center text-[11px] font-medium text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/40 px-2 py-0.5 rounded border border-amber-200 dark:border-amber-800/80 w-max">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>
                                            {{ $user->tugas_aktif_count }} Tugas Aktif
                                        </span>
                                        @else
                                        <span class="inline-flex items-center text-[11px] font-medium text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-800/80 w-max">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                            Siaga (0 Tugas)
                                        </span>
                                        @endif
                                    @endif
                                </div>
                            </td>

                            <!-- Status Akun -->
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($user->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                    Aktif
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                    Nonaktif
                                </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                        @click="openEditModal({
                                            id: {{ $user->id }},
                                            nama_lengkap: '{{ addslashes($user->nama_lengkap) }}',
                                            email: '{{ addslashes($user->email) }}',
                                            no_telepon: '{{ addslashes($user->no_telepon) }}',
                                            role: '{{ $user->role }}'
                                        }, '{{ route('admin.users.update', $user->id) }}')"
                                        class="inline-flex items-center px-2.5 py-1.5 text-xs font-semibold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 rounded-md border border-indigo-200 dark:border-indigo-800 transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>

                                    @if(auth()->id() !== $user->id)
                                    <button type="button"
                                        @click="openToggleModal({
                                            id: {{ $user->id }},
                                            nama_lengkap: '{{ addslashes($user->nama_lengkap) }}',
                                            is_active: {{ $user->is_active ? 'true' : 'false' }},
                                            role: '{{ $user->role }}',
                                            tugas_aktif_count: {{ $user->tugas_aktif_count ?? 0 }}
                                        }, '{{ route('admin.users.toggle-status', $user->id) }}')"
                                        class="inline-flex items-center px-2.5 py-1.5 text-xs font-semibold rounded-md border transition-colors
                                            {{ $user->is_active 
                                                ? 'text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/30 hover:bg-rose-100 dark:hover:bg-rose-900/50 border-rose-200 dark:border-rose-800' 
                                                : 'text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 border-emerald-200 dark:border-emerald-800' }}">
                                        @if($user->is_active)
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                        Nonaktifkan
                                        @else
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Aktifkan
                                        @endif
                                    </button>
                                    @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs text-gray-400 dark:text-gray-500 font-normal italic">
                                        Akun Anda
                                    </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="max-w-xs mx-auto">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700/60 text-gray-400 dark:text-gray-500 flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-gray-100">Tidak ada data pengguna</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        @if(request()->hasAny(['search', 'role', 'status']))
                                        Kriteria pencarian Anda tidak menemukan hasil.
                                        @else
                                        Belum ada pengguna yang terdaftar di dalam sistem.
                                        @endif
                                    </p>
                                    @if(request()->hasAny(['search', 'role', 'status']))
                                    <a href="{{ route('admin.users.index') }}" class="mt-3 inline-flex items-center px-3 py-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg border border-indigo-200 dark:border-indigo-800 transition-colors">
                                        Reset Filter Pencarian
                                    </a>
                                    @else
                                    <button type="button" @click="userModalOpen = true" class="mt-3 inline-flex items-center px-3 py-1.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Tambah User Baru
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card Stack (Visible only on mobile devices) -->
            <div class="md:hidden divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($users as $user)
                @php
                    $initials = collect(explode(' ', $user->nama_lengkap))
                        ->filter()
                        ->take(2)
                        ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                        ->implode('');
                    if (empty($initials)) { $initials = 'U'; }

                    $waClean = preg_replace('/[^0-9]/', '', $user->no_telepon ?? '');
                    if (str_starts_with($waClean, '0')) {
                        $waClean = '62' . substr($waClean, 1);
                    }
                @endphp
                <div class="p-4 space-y-3 {{ !$user->is_active ? 'bg-gray-50/60 dark:bg-gray-800/40 opacity-75' : '' }}">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs shrink-0
                                {{ $user->role === 'Admin' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/60 dark:text-indigo-300' : '' }}
                                {{ $user->role === 'Teknisi' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/60 dark:text-amber-300' : '' }}
                                {{ $user->role === 'Pelapor' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/60 dark:text-blue-300' : '' }}
                                {{ $user->role === 'KepalaFRC' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300' : '' }}">
                                {{ $initials }}
                            </div>
                            <div>
                                <div class="font-bold text-gray-900 dark:text-gray-100 text-sm flex items-center gap-1.5">
                                    {{ $user->nama_lengkap }}
                                    @if(auth()->id() === $user->id)
                                    <span class="text-[9px] font-bold bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 px-1.5 py-0.2 rounded-full">
                                        Anda
                                    </span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                            </div>
                        </div>

                        <!-- Status Pill -->
                        @if($user->is_active)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                            Aktif
                        </span>
                        @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                            Nonaktif
                        </span>
                        @endif
                    </div>

                    <!-- Role & Workload Pill -->
                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                            {{ $user->role == 'Admin' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300' : '' }}
                            {{ $user->role == 'Teknisi' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300' : '' }}
                            {{ $user->role == 'Pelapor' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' : '' }}
                            {{ $user->role == 'KepalaFRC' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' : '' }}">
                            {{ $user->role }}
                        </span>

                        @if($user->role === 'Teknisi')
                            @if(($user->tugas_aktif_count ?? 0) > 0)
                            <span class="text-[11px] font-medium text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/40 px-2 py-0.5 rounded border border-amber-200 dark:border-amber-800/80">
                                {{ $user->tugas_aktif_count }} Tugas Aktif
                            </span>
                            @else
                            <span class="text-[11px] font-medium text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-800/80">
                                Siaga (0 Tugas)
                            </span>
                            @endif
                        @endif

                        @if($waClean)
                        <a href="https://wa.me/{{ $waClean }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-xs text-emerald-600 dark:text-emerald-400 font-medium ml-auto">
                            WA: {{ $user->no_telepon }} &rarr;
                        </a>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-2 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-end gap-2">
                        <button type="button"
                            @click="openEditModal({
                                id: {{ $user->id }},
                                nama_lengkap: '{{ addslashes($user->nama_lengkap) }}',
                                email: '{{ addslashes($user->email) }}',
                                no_telepon: '{{ addslashes($user->no_telepon) }}',
                                role: '{{ $user->role }}'
                            }, '{{ route('admin.users.update', $user->id) }}')"
                            class="flex-1 py-1.5 px-3 text-center text-xs font-semibold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg border border-indigo-200 dark:border-indigo-800">
                            Edit Data
                        </button>

                        @if(auth()->id() !== $user->id)
                        <button type="button"
                            @click="openToggleModal({
                                id: {{ $user->id }},
                                nama_lengkap: '{{ addslashes($user->nama_lengkap) }}',
                                is_active: {{ $user->is_active ? 'true' : 'false' }},
                                role: '{{ $user->role }}',
                                tugas_aktif_count: {{ $user->tugas_aktif_count ?? 0 }}
                            }, '{{ route('admin.users.toggle-status', $user->id) }}')"
                            class="flex-1 py-1.5 px-3 text-center text-xs font-semibold rounded-lg border
                                {{ $user->is_active 
                                    ? 'text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/30 border-rose-200 dark:border-rose-800' 
                                    : 'text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/30 border-emerald-200 dark:border-emerald-800' }}">
                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-8 text-center">
                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Tidak ada pengguna yang cocok</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Coba sesuaikan filter atau kata kunci pencarian Anda.</p>
                    <button type="button" @click="userModalOpen = true" class="mt-3 inline-flex items-center px-3 py-1.5 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition-colors">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah User Baru
                    </button>
                </div>
                @endforelse
            </div>

            <!-- Real Pagination -->
            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/50">
                {{ $users->links() }}
            </div>
            @endif
        </div>

        <!-- ========================================== -->
        <!-- MODAL REGISTRASI USER BARU                 -->
        <!-- ========================================== -->
        <div x-show="userModalOpen" style="display: none;" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-gray-950/75 dark:bg-black/80 flex items-center justify-center backdrop-blur-sm p-4">
            
            <div @click.away="userModalOpen = false" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden">
                
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 tracking-tight">Registrasi Pengguna Baru</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Daftarkan akun staf, teknisi, atau pelapor baru FRC</p>
                    </div>
                    <button type="button" @click="userModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="form_action" value="create">

                    <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                        <!-- Nama Lengkap -->
                        <div>
                            <x-input-label for="create_nama_lengkap" value="Nama Lengkap" />
                            <x-text-input id="create_nama_lengkap" name="nama_lengkap" type="text" class="mt-1 block w-full" :value="old('form_action') === 'create' ? old('nama_lengkap') : ''" placeholder="contoh: Ahmad Ridwan" required autofocus />
                            <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-1" />
                        </div>

                        <!-- Email & No Telepon -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="create_email" value="Alamat Email" />
                                <x-text-input id="create_email" name="email" type="email" class="mt-1 block w-full" :value="old('form_action') === 'create' ? old('email') : ''" placeholder="nama@domain.com" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="create_no_telepon" value="No. Telepon / WA" />
                                <x-text-input id="create_no_telepon" name="no_telepon" type="text" class="mt-1 block w-full" :value="old('form_action') === 'create' ? old('no_telepon') : ''" placeholder="contoh: 081234567890" required />
                                <x-input-error :messages="$errors->get('no_telepon')" class="mt-1" />
                            </div>
                        </div>

                        <!-- Role -->
                        <div>
                            <x-input-label for="create_role" value="Hak Akses (Role)" />
                            <select id="create_role" name="role" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <option value="Pelapor" {{ (old('form_action') === 'create' && old('role') === 'Pelapor') ? 'selected' : '' }}>Pelapor (Tenant / Pengguna Fasilitas)</option>
                                <option value="Teknisi" {{ (old('form_action') === 'create' && old('role') === 'Teknisi') ? 'selected' : '' }}>Teknisi (Pelaksana Perbaikan Lapangan)</option>
                                <option value="Admin" {{ (old('form_action') === 'create' && old('role') === 'Admin') ? 'selected' : '' }}>Admin (Pengelola Operasional FRC)</option>
                                <option value="KepalaFRC" {{ (old('form_action') === 'create' && old('role') === 'KepalaFRC') ? 'selected' : '' }}>Kepala FRC (Pengawas & Laporan Eksekutif)</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-1" />
                        </div>

                        <!-- Password Awal -->
                        <div>
                            <x-input-label for="create_password" value="Password Awal" />
                            <x-text-input id="create_password" name="password" type="password" class="mt-1 block w-full" placeholder="Minimal 8 karakter" required />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pengguna dapat mengganti password ini setelah login pertama kali.</p>
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                        <x-secondary-button type="button" @click="userModalOpen = false">
                            Batal
                        </x-secondary-button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white font-semibold text-xs uppercase tracking-widest rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                            Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL EDIT PENGGUNA                        -->
        <!-- ========================================== -->
        <div x-show="editModalOpen" style="display: none;" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-gray-950/75 dark:bg-black/80 flex items-center justify-center backdrop-blur-sm p-4">
            
            <div @click.away="editModalOpen = false" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden">
                
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 tracking-tight">Edit Data Pengguna</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Perbarui informasi profil dan hak akses pengguna</p>
                    </div>
                    <button type="button" @click="editModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form :action="editUrl" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="form_action" value="edit">
                    <input type="hidden" name="edit_url" :value="editUrl">
                    <input type="hidden" name="edit_user_id" :value="editUser.id">

                    <div class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
                        <!-- Warning jika mengedit akun sendiri -->
                        <div x-show="editUser.is_current_admin" class="rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/80 p-3 text-xs text-amber-800 dark:text-amber-300 flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <span class="font-bold">Perhatian Akun Anda:</span> Anda sedang menyunting akun Anda sendiri. Role dikunci sebagai <span class="font-semibold underline">Admin</span> untuk mencegah penguncian akun secara tidak disengaja.
                            </div>
                        </div>

                        <!-- Nama Lengkap -->
                        <div>
                            <x-input-label for="edit_nama_lengkap" value="Nama Lengkap" />
                            <x-text-input id="edit_nama_lengkap" x-model="editUser.nama_lengkap" name="nama_lengkap" type="text" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-1" />
                        </div>

                        <!-- Email & No Telepon -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="edit_email" value="Alamat Email" />
                                <x-text-input id="edit_email" x-model="editUser.email" name="email" type="email" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>
                            <div>
                                <x-input-label for="edit_no_telepon" value="No. Telepon / WA" />
                                <x-text-input id="edit_no_telepon" x-model="editUser.no_telepon" name="no_telepon" type="text" class="mt-1 block w-full" required />
                                <x-input-error :messages="$errors->get('no_telepon')" class="mt-1" />
                            </div>
                        </div>

                        <!-- Role / Hak Akses -->
                        <div>
                            <x-input-label for="edit_role" value="Hak Akses (Role)" />
                            
                            <template x-if="editUser.is_current_admin">
                                <div>
                                    <input type="hidden" name="role" value="Admin">
                                    <div class="mt-1 px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700/60 text-gray-700 dark:text-gray-300 text-sm font-semibold flex items-center justify-between">
                                        <span>Admin (Terkunci)</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">Role Anda tidak dapat diturunkan</span>
                                    </div>
                                </div>
                            </template>

                            <template x-if="!editUser.is_current_admin">
                                <select id="edit_role" x-model="editUser.role" name="role" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                    <option value="Pelapor">Pelapor</option>
                                    <option value="Teknisi">Teknisi</option>
                                    <option value="Admin">Admin</option>
                                    <option value="KepalaFRC">Kepala FRC</option>
                                </select>
                            </template>
                            <x-input-error :messages="$errors->get('role')" class="mt-1" />
                        </div>

                        <!-- Ganti Password (Opsional) -->
                        <div class="pt-3 border-t border-gray-100 dark:border-gray-700">
                            <x-input-label for="edit_password" value="Ganti Password" />
                            <x-text-input id="edit_password" name="password" type="password" placeholder="••••••••" class="mt-1 block w-full" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kosongkan kolom ini jika tidak ingin mengubah password akun.</p>
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                        <x-secondary-button type="button" @click="editModalOpen = false">
                            Batal
                        </x-secondary-button>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white font-semibold text-xs uppercase tracking-widest rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all">
                            Perbarui Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL KONFIRMASI STATUS (AKTIF/NONAKTIF)   -->
        <!-- ========================================== -->
        <div x-show="confirmToggleModalOpen" style="display: none;" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto bg-gray-950/75 dark:bg-black/80 flex items-center justify-center backdrop-blur-sm p-4">
            
            <div @click.away="confirmToggleModalOpen = false" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">
                
                <div class="p-6 text-center">
                    <template x-if="toggleUser.is_active">
                        <div class="w-12 h-12 rounded-full bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto mb-4 ring-8 ring-rose-50 dark:ring-rose-950/30">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </template>
                    <template x-if="!toggleUser.is_active">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-4 ring-8 ring-emerald-50 dark:ring-emerald-950/30">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </template>

                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100" x-text="toggleUser.is_active ? 'Nonaktifkan Pengguna?' : 'Aktifkan Pengguna?'"></h3>
                    
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">
                        Anda akan mengubah status akun <span class="font-bold text-gray-900 dark:text-gray-100" x-text="toggleUser.nama"></span> menjadi <span class="font-bold" :class="toggleUser.is_active ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400'" x-text="toggleUser.is_active ? 'Nonaktif' : 'Aktif'"></span>.
                    </p>

                    <template x-if="toggleUser.is_active">
                        <div class="mt-3 p-3 rounded-lg bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-900/60 text-xs text-rose-800 dark:text-rose-300 text-left space-y-1">
                            <p class="font-semibold">Konsekuensi Penonaktifan:</p>
                            <p>&bull; Pengguna tidak akan dapat login ke aplikasi FRC.</p>
                            <p>&bull; Akun tidak dapat ditugaskan untuk laporan kerusakan baru.</p>
                            <template x-if="toggleUser.role === 'Teknisi' && toggleUser.tugas_aktif > 0">
                                <p class="font-bold text-rose-700 dark:text-rose-400 mt-1">
                                    ⚠️ Perhatian: Teknisi ini saat ini memiliki <span x-text="toggleUser.tugas_aktif"></span> tugas aktif di lapangan!
                                </p>
                            </template>
                        </div>
                    </template>

                    <template x-if="!toggleUser.is_active">
                        <div class="mt-3 p-3 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/60 text-xs text-emerald-800 dark:text-emerald-300 text-left">
                            <p>Pengguna akan dapat login kembali dan menerima delegasi tugas sesuai perannya.</p>
                        </div>
                    </template>
                </div>

                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
                    <x-secondary-button type="button" @click="confirmToggleModalOpen = false">
                        Batal
                    </x-secondary-button>
                    
                    <form :action="toggleUrl" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                            class="inline-flex items-center px-4 py-2 font-semibold text-xs uppercase tracking-widest text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all"
                            :class="toggleUser.is_active ? 'bg-rose-600 hover:bg-rose-700 focus:ring-rose-500' : 'bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500'"
                            x-text="toggleUser.is_active ? 'Ya, Nonaktifkan Akun' : 'Ya, Aktifkan Akun'">
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>