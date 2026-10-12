<x-app-layout>
    {{-- Header Slot: Breadcrumbs, Title, Subtitle, & Add User Button --}}
    <x-slot name="header">
        <!-- Breadcrumb Navigation -->
        <nav class="flex items-center gap-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-3 overflow-x-auto no-scrollbar whitespace-nowrap py-0.5" aria-label="Breadcrumb">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors inline-flex items-center gap-1.5 font-medium flex-shrink-0">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Dashboard</span>
            </a>
            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-800 dark:text-gray-200 font-semibold flex-shrink-0" aria-current="page">
                Kelola Pengguna
            </span>
        </nav>

        <div>
            <h1 class="text-xl sm:text-2xl lg:text-3xl font-extrabold text-gray-900 dark:text-gray-100 tracking-tight">
                Manajemen Pengguna
            </h1>
            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                Kelola hak akses peran staf, pantau beban kerja teknisi lapangan, dan kendalikan status akses akun FRC.
            </p>
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
        class="space-y-6 pb-12">

        <!-- 4 KPI Summary Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4">
            <!-- 1. Total Pengguna -->
            <a href="{{ route('admin.users.index') }}" 
                class="group bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors uppercase tracking-wider">Total Pengguna</span>
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $stats['total'] }}</p>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                            Semua Akun
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-between truncate">
                        <span>{{ $stats['total'] }} Akun Terdaftar &bull; {{ $stats['admin_total'] }} Admin</span>
                        <span class="text-indigo-600 dark:text-indigo-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </p>
                </div>
            </a>

            <!-- 2. Akun Aktif -->
            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['status' => 'aktif', 'page' => 1])) }}" 
                class="group bg-white dark:bg-gray-800 rounded-2xl border {{ request('status') === 'aktif' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-gray-200 dark:border-gray-700' }} hover:border-emerald-400 dark:hover:border-emerald-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors uppercase tracking-wider">Akun Aktif</span>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $stats['aktif'] }}</p>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                            Status Aktif
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-between truncate">
                        <span>{{ $stats['nonaktif'] }} akun dinonaktifkan</span>
                        <span class="text-indigo-600 dark:text-indigo-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </p>
                </div>
            </a>

            <!-- 3. Teknisi Lapangan -->
            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['role' => 'Teknisi', 'page' => 1])) }}" 
                class="group bg-white dark:bg-gray-800 rounded-2xl border {{ request('role') === 'Teknisi' ? 'border-amber-500 ring-2 ring-amber-500/20' : 'border-gray-200 dark:border-gray-700' }} hover:border-amber-400 dark:hover:border-amber-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors uppercase tracking-wider">Teknisi FRC</span>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $stats['teknisi_total'] }}</p>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                            {{ $stats['teknisi_ready'] }} Siaga
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-between truncate">
                        <span>{{ $stats['teknisi_ready'] }} Siaga (0 tugas aktif)</span>
                        <span class="text-indigo-600 dark:text-indigo-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </p>
                </div>
            </a>

            <!-- 4. Staf Pelapor -->
            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['role' => 'Pelapor', 'page' => 1])) }}" 
                class="group bg-white dark:bg-gray-800 rounded-2xl border {{ request('role') === 'Pelapor' ? 'border-blue-500 ring-2 ring-blue-500/20' : 'border-gray-200 dark:border-gray-700' }} hover:border-blue-400 dark:hover:border-blue-500 p-4 sm:p-5 shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between hover:-translate-y-0.5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors uppercase tracking-wider">Staf Pelapor</span>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <div>
                    <div class="flex items-baseline justify-between gap-2">
                        <p class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $stats['pelapor_total'] }}</p>
                        <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                            Staf / Tenant
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 flex items-center justify-between truncate">
                        <span>Tenant & Pengguna Gedung</span>
                        <span class="text-indigo-600 dark:text-indigo-400 group-hover:translate-x-0.5 transition-transform">&rarr;</span>
                    </p>
                </div>
            </a>
        </div>

        <!-- Filter & Search Bar Card -->
        <div class="p-4 sm:p-5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm transition-colors">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 sm:gap-4 items-end">
                <!-- Search Input -->
                <div class="sm:col-span-2 lg:col-span-5">
                    <label for="search" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Pencarian Pengguna</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau no. telepon..."
                            class="block w-full rounded-xl pl-10 pr-3.5 py-2.5 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors placeholder:text-gray-400 dark:placeholder:text-gray-500">
                    </div>
                </div>

                <!-- Role Filter -->
                <div class="lg:col-span-3">
                    <label for="role" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Hak Akses (Role)</span>
                    </label>
                    <select name="role" id="role" class="block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                        <option value="">Semua Role</option>
                        <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                        <option value="KepalaFRC" {{ request('role') == 'KepalaFRC' ? 'selected' : '' }}>Kepala FRC</option>
                        <option value="Teknisi" {{ request('role') == 'Teknisi' ? 'selected' : '' }}>Teknisi</option>
                        <option value="Pelapor" {{ request('role') == 'Pelapor' ? 'selected' : '' }}>Pelapor</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="lg:col-span-3">
                    <label for="status" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span>Status Akun</span>
                    </label>
                    <select name="status" id="status" class="block w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Hanya Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Hanya Nonaktif</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="sm:col-span-2 lg:col-span-1 flex items-center gap-2">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gray-900 dark:bg-gray-700 hover:bg-black dark:hover:bg-gray-600 text-white rounded-xl font-semibold text-xs shadow-sm transition-all duration-150 gap-1.5" title="Terapkan Filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span class="lg:hidden">Cari</span>
                    </button>

                    @if(request()->hasAny(['search', 'role', 'status']))
                    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center px-3 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-xl text-xs font-semibold transition" title="Reset Filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="sr-only">Reset</span>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Main User List Card -->
        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden transition-colors">
            <!-- Header Table Bar -->
            <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gray-50/75 dark:bg-gray-900/40">
                <div class="flex items-center gap-2">
                    <h2 class="text-sm sm:text-base font-bold text-gray-900 dark:text-gray-100">Daftar Pengguna FRC</h2>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold">
                        {{ $users->total() }} Data
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        Menampilkan {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna
                    </span>
                    <button type="button" @click="userModalOpen = true" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs rounded-xl shadow-sm transition gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Tambah User</span>
                    </button>
                </div>
            </div>

            <!-- Mobile Card List (< md) -->
            <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700/80">
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
                <div class="p-4 sm:p-5 space-y-3 hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors {{ !$user->is_active ? 'opacity-70' : '' }}">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-xs shrink-0 shadow-xs
                                {{ $user->role === 'Admin' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/60 dark:text-indigo-300' : '' }}
                                {{ $user->role === 'Teknisi' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/60 dark:text-amber-300' : '' }}
                                {{ $user->role === 'Pelapor' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/60 dark:text-blue-300' : '' }}
                                {{ $user->role === 'KepalaFRC' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/60 dark:text-emerald-300' : '' }}">
                                {{ $initials }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-gray-900 dark:text-gray-100 text-sm flex items-center gap-1.5 truncate">
                                    <span>{{ $user->nama_lengkap }}</span>
                                    @if(auth()->id() === $user->id)
                                    <span class="text-[9px] font-bold bg-indigo-100 dark:bg-indigo-900/60 text-indigo-700 dark:text-indigo-300 px-1.5 py-0.2 rounded-full">
                                        Anda
                                    </span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</div>
                            </div>
                        </div>

                        <!-- Status Pill -->
                        @if($user->is_active)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Aktif
                        </span>
                        @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-rose-100 dark:bg-rose-900/50 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                            Nonaktif
                        </span>
                        @endif
                    </div>

                    <!-- Role & Workload Pill -->
                    <div class="flex flex-wrap items-center gap-2 pt-1 text-xs">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                            {{ $user->role == 'Admin' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800' : '' }}
                            {{ $user->role == 'Teknisi' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 border border-amber-200 dark:border-amber-800' : '' }}
                            {{ $user->role == 'Pelapor' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border border-blue-200 dark:border-blue-800' : '' }}
                            {{ $user->role == 'KepalaFRC' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : '' }}">
                            {{ $user->role }}
                        </span>

                        @if($user->role === 'Teknisi')
                            @if(($user->tugas_aktif_count ?? 0) > 0)
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/40 px-2 py-0.5 rounded-md border border-amber-200 dark:border-amber-800/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                {{ $user->tugas_aktif_count }} Tugas Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-md border border-emerald-200 dark:border-emerald-800/80">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Siaga (0 Tugas)
                            </span>
                            @endif
                        @endif

                        @if($waClean)
                        <a href="https://wa.me/{{ $waClean }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-xs text-emerald-600 dark:text-emerald-400 font-medium ml-auto hover:underline">
                            <span>WA: {{ $user->no_telepon }}</span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-2 border-t border-gray-100 dark:border-gray-700/80 flex items-center justify-end gap-2">
                        <button type="button"
                            @click="openEditModal({
                                id: {{ $user->id }},
                                nama_lengkap: '{{ addslashes($user->nama_lengkap) }}',
                                email: '{{ addslashes($user->email) }}',
                                no_telepon: '{{ addslashes($user->no_telepon) }}',
                                role: '{{ $user->role }}'
                            }, '{{ route('admin.users.update', $user->id) }}')"
                            class="flex-1 inline-flex items-center justify-center py-2 px-3 text-xs font-semibold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 rounded-xl border border-indigo-200 dark:border-indigo-800 transition">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <span>Edit Data</span>
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
                            class="flex-1 inline-flex items-center justify-center py-2 px-3 text-xs font-semibold rounded-xl border transition
                                {{ $user->is_active 
                                    ? 'text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/30 hover:bg-rose-100 dark:hover:bg-rose-900/50 border-rose-200 dark:border-rose-800' 
                                    : 'text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 border-emerald-200 dark:border-emerald-800' }}">
                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                        @endif
                    </div>
                </div>
                @empty
                <div class="px-6 py-12 text-center">
                    <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 flex items-center justify-center mx-auto mb-3">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100">Tidak ada data pengguna</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                        @if(request()->hasAny(['search', 'role', 'status']))
                        Kriteria pencarian Anda tidak menemukan hasil. Coba sesuaikan filter atau kata kunci.
                        @else
                        Belum ada pengguna yang terdaftar di dalam sistem.
                        @endif
                    </p>
                    <button type="button" @click="userModalOpen = true" class="mt-4 inline-flex items-center px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Tambah User Baru</span>
                    </button>
                </div>
                @endforelse
            </div>

            <!-- Desktop & Tablet Table (>= md) -->
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 bg-gray-50/75 dark:bg-gray-900/40 text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <th scope="col" class="py-3.5 px-4 sm:px-6">Pengguna</th>
                            <th scope="col" class="py-3.5 px-4">Kontak & WhatsApp</th>
                            <th scope="col" class="py-3.5 px-4">Peran & Beban Kerja</th>
                            <th scope="col" class="py-3.5 px-4 text-center">Status Akun</th>
                            <th scope="col" class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/80 text-xs sm:text-sm">
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
                        <tr class="hover:bg-gray-50/70 dark:hover:bg-gray-700/40 transition-colors {{ !$user->is_active ? 'bg-gray-50/40 dark:bg-gray-800/40 opacity-70' : '' }}">
                            <!-- Pengguna Info -->
                            <td class="py-4 px-4 sm:px-6 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-sm shrink-0 shadow-xs
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
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                            Bergabung: {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Kontak & WhatsApp -->
                            <td class="py-4 px-4 whitespace-nowrap">
                                <div class="text-xs sm:text-sm text-gray-900 dark:text-gray-200 font-medium">{{ $user->email }}</div>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $user->no_telepon ?: '-' }}</span>
                                    @if($waClean)
                                    <a href="https://wa.me/{{ $waClean }}" target="_blank" rel="noopener noreferrer" title="Hubungi via WhatsApp" class="inline-flex items-center text-[11px] text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-semibold bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-md border border-emerald-200 dark:border-emerald-800/80 transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.086s1.011.477 1.184.564.289.13.332.203c.043.072.043.419-.101.824z" />
                                        </svg>
                                        Chat WA
                                    </a>
                                    @endif
                                </div>
                            </td>

                            <!-- Peran & Beban Kerja -->
                            <td class="py-4 px-4 whitespace-nowrap">
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
                                        <span class="inline-flex items-center gap-1 text-[11px] font-medium text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/40 px-2 py-0.5 rounded-md border border-amber-200 dark:border-amber-800/80 w-max">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            {{ $user->tugas_aktif_count }} Tugas Aktif
                                        </span>
                                        @else
                                        <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-md border border-emerald-200 dark:border-emerald-800/80 w-max">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Siaga (0 Tugas)
                                        </span>
                                        @endif
                                    @endif
                                </div>
                            </td>

                            <!-- Status Akun -->
                            <td class="py-4 px-4 whitespace-nowrap text-center">
                                @if($user->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                                @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 dark:bg-rose-900/30 dark:text-rose-300 border border-rose-200 dark:border-rose-800/60">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Nonaktif
                                </span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-4 sm:px-6 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button"
                                        @click="openEditModal({
                                            id: {{ $user->id }},
                                            nama_lengkap: '{{ addslashes($user->nama_lengkap) }}',
                                            email: '{{ addslashes($user->email) }}',
                                            no_telepon: '{{ addslashes($user->no_telepon) }}',
                                            role: '{{ $user->role }}'
                                        }, '{{ route('admin.users.update', $user->id) }}')"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 rounded-xl border border-indigo-200 dark:border-indigo-800 transition shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        <span>Edit</span>
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
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-xl border transition shadow-sm
                                            {{ $user->is_active 
                                                ? 'text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/30 hover:bg-rose-100 dark:hover:bg-rose-900/50 border-rose-200 dark:border-rose-800' 
                                                : 'text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 border-emerald-200 dark:border-emerald-800' }}">
                                        @if($user->is_active)
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                        </svg>
                                        <span>Nonaktifkan</span>
                                        @else
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Aktifkan</span>
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
                            <td colspan="5" class="px-6 py-12 text-center bg-gray-50/50 dark:bg-gray-800/30">
                                <div class="max-w-xs mx-auto">
                                    <div class="w-14 h-14 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 flex items-center justify-center mx-auto mb-3">
                                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                    <a href="{{ route('admin.users.index') }}" class="mt-3 inline-flex items-center px-3.5 py-2 text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/30 rounded-xl border border-indigo-200 dark:border-indigo-800 transition">
                                        Reset Filter Pencarian
                                    </a>
                                    @else
                                    <button type="button" @click="userModalOpen = true" class="mt-3 inline-flex items-center px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <span>Tambah User Baru</span>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/30">
                {{ $users->links() }}
            </div>
            @endif
        </div>

        <!-- ========================================== -->
        <!-- MODAL 1: REGISTRASI USER BARU              -->
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
                
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/75 dark:bg-gray-900/40 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 tracking-tight">Tambah User Baru</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Registrasi Pengguna Baru - Daftarkan akun staf, teknisi, atau pelapor baru FRC</p>
                        </div>
                    </div>
                    <button type="button" @click="userModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 p-1.5 rounded-lg transition-colors">
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
                            <label for="create_nama_lengkap" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input id="create_nama_lengkap" name="nama_lengkap" type="text" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm" :value="old('form_action') === 'create' ? old('nama_lengkap') : ''" placeholder="contoh: Ahmad Ridwan" required autofocus />
                            <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-1" />
                        </div>

                        <!-- Email & No Telepon -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="create_email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                    Alamat Email <span class="text-rose-500">*</span>
                                </label>
                                <input id="create_email" name="email" type="email" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm" :value="old('form_action') === 'create' ? old('email') : ''" placeholder="nama@domain.com" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>
                            <div>
                                <label for="create_no_telepon" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                    No. Telepon / WA <span class="text-rose-500">*</span>
                                </label>
                                <input id="create_no_telepon" name="no_telepon" type="text" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm" :value="old('form_action') === 'create' ? old('no_telepon') : ''" placeholder="contoh: 081234567890" required />
                                <x-input-error :messages="$errors->get('no_telepon')" class="mt-1" />
                            </div>
                        </div>

                        <!-- Role -->
                        <div>
                            <label for="create_role" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                Hak Akses (Role) <span class="text-rose-500">*</span>
                            </label>
                            <select id="create_role" name="role" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm" required>
                                <option value="Pelapor" {{ (old('form_action') === 'create' && old('role') === 'Pelapor') ? 'selected' : '' }}>Pelapor (Tenant / Pengguna Fasilitas)</option>
                                <option value="Teknisi" {{ (old('form_action') === 'create' && old('role') === 'Teknisi') ? 'selected' : '' }}>Teknisi (Pelaksana Perbaikan Lapangan)</option>
                                <option value="Admin" {{ (old('form_action') === 'create' && old('role') === 'Admin') ? 'selected' : '' }}>Admin (Pengelola Operasional FRC)</option>
                                <option value="KepalaFRC" {{ (old('form_action') === 'create' && old('role') === 'KepalaFRC') ? 'selected' : '' }}>Kepala FRC (Pengawas & Laporan Eksekutif)</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-1" />
                        </div>

                        <!-- Password Awal -->
                        <div>
                            <label for="create_password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                Password Awal <span class="text-rose-500">*</span>
                            </label>
                            <input id="create_password" name="password" type="password" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm" placeholder="Minimal 8 karakter" required />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pengguna dapat mengganti password ini setelah login pertama kali.</p>
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50/75 dark:bg-gray-900/40 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-2">
                        <button type="button" @click="userModalOpen = false" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            Batal
                        </button>
                        <button type="submit" onclick="this.disabled=true; this.form.submit(); this.innerHTML='Menyimpan...';" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                            Simpan Pengguna
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL 2: EDIT PENGGUNA                     -->
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
                
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/75 dark:bg-gray-900/40 flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-gray-100 tracking-tight">Edit Data Pengguna</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Perbarui informasi profil dan hak akses akun</p>
                        </div>
                    </div>
                    <button type="button" @click="editModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 p-1.5 rounded-lg transition-colors">
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
                        <div x-show="editUser.is_current_admin" class="rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 p-3 text-xs text-amber-800 dark:text-amber-300 flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <span class="font-bold">Perhatian Akun Anda:</span> Anda sedang menyunting akun Anda sendiri. Role dikunci sebagai <span class="font-semibold underline">Admin</span> untuk mencegah penguncian akun secara tidak disengaja.
                            </div>
                        </div>

                        <!-- Nama Lengkap -->
                        <div>
                            <label for="edit_nama_lengkap" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input id="edit_nama_lengkap" x-model="editUser.nama_lengkap" name="nama_lengkap" type="text" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm" required />
                            <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-1" />
                        </div>

                        <!-- Email & No Telepon -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="edit_email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                    Alamat Email <span class="text-rose-500">*</span>
                                </label>
                                <input id="edit_email" x-model="editUser.email" name="email" type="email" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>
                            <div>
                                <label for="edit_no_telepon" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                    No. Telepon / WA <span class="text-rose-500">*</span>
                                </label>
                                <input id="edit_no_telepon" x-model="editUser.no_telepon" name="no_telepon" type="text" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm" required />
                                <x-input-error :messages="$errors->get('no_telepon')" class="mt-1" />
                            </div>
                        </div>

                        <!-- Role / Hak Akses -->
                        <div>
                            <label for="edit_role" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                Hak Akses (Role) <span class="text-rose-500">*</span>
                            </label>
                            
                            <template x-if="editUser.is_current_admin">
                                <div>
                                    <input type="hidden" name="role" value="Admin">
                                    <div class="px-3 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs sm:text-sm font-semibold flex items-center justify-between">
                                        <span>Admin (Terkunci)</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">Role Anda tidak dapat diturunkan</span>
                                    </div>
                                </div>
                            </template>

                            <template x-if="!editUser.is_current_admin">
                                <select id="edit_role" x-model="editUser.role" name="role" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 transition-colors shadow-sm" required>
                                    <option value="Pelapor">Pelapor</option>
                                    <option value="Teknisi">Teknisi</option>
                                    <option value="Admin">Admin</option>
                                    <option value="KepalaFRC">Kepala FRC</option>
                                </select>
                            </template>
                            <x-input-error :messages="$errors->get('role')" class="mt-1" />
                        </div>

                        <!-- Ganti Password (Opsional) -->
                        <div class="pt-3 border-t border-gray-100 dark:border-gray-700/80">
                            <label for="edit_password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1.5">
                                Ganti Password (Opsional)
                            </label>
                            <input id="edit_password" name="password" type="password" placeholder="Kosongkan jika tidak ingin mengubah password" class="w-full rounded-xl border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-xs sm:text-sm py-2.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 placeholder-gray-400 dark:placeholder-gray-500 transition-colors shadow-sm" />
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kosongkan kolom ini jika tidak ingin mengubah password akun pengguna.</p>
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50/75 dark:bg-gray-900/40 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-2">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            Batal
                        </button>
                        <button type="submit" onclick="this.disabled=true; this.form.submit(); this.innerHTML='Memperbarui...';" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                            Perbarui Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL 3: KONFIRMASI STATUS (AKTIF/NONAKTIF)-->
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
                        <div class="w-14 h-14 rounded-full bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto mb-4 ring-8 ring-rose-50 dark:ring-rose-950/30">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </template>
                    <template x-if="!toggleUser.is_active">
                        <div class="w-14 h-14 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mx-auto mb-4 ring-8 ring-emerald-50 dark:ring-emerald-950/30">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </template>

                    <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100" x-text="toggleUser.is_active ? 'Nonaktifkan Pengguna?' : 'Aktifkan Pengguna?'"></h3>
                    
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mt-2">
                        Anda akan mengubah status akun <span class="font-bold text-gray-900 dark:text-gray-100" x-text="toggleUser.nama"></span> menjadi <span class="font-bold" :class="toggleUser.is_active ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400'" x-text="toggleUser.is_active ? 'Nonaktif' : 'Aktif'"></span>.
                    </p>

                    <template x-if="toggleUser.is_active">
                        <div class="mt-4 p-3.5 rounded-xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 text-xs text-rose-800 dark:text-rose-200 text-left space-y-1">
                            <p class="font-bold">Konsekuensi Penonaktifan:</p>
                            <p>&bull; Pengguna tidak akan dapat login ke aplikasi FRC.</p>
                            <p>&bull; Akun tidak dapat ditugaskan untuk laporan kerusakan baru.</p>
                            <template x-if="toggleUser.role === 'Teknisi' && toggleUser.tugas_aktif > 0">
                                <p class="font-bold text-rose-700 dark:text-rose-400 mt-1.5">
                                    ⚠️ Perhatian: Teknisi ini saat ini memiliki <span x-text="toggleUser.tugas_aktif"></span> tugas aktif di lapangan!
                                </p>
                            </template>
                        </div>
                    </template>

                    <template x-if="!toggleUser.is_active">
                        <div class="mt-4 p-3.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/50 text-xs text-emerald-800 dark:text-emerald-200 text-left">
                            <p>Pengguna akan dapat login kembali dan menerima delegasi tugas sesuai perannya.</p>
                        </div>
                    </template>
                </div>

                <div class="px-6 py-4 bg-gray-50/75 dark:bg-gray-900/40 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-2">
                    <button type="button" @click="confirmToggleModalOpen = false" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-xs font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                        Batal
                    </button>
                    
                    <form :action="toggleUrl" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                            class="inline-flex items-center px-4 py-2 font-semibold text-xs rounded-xl shadow-sm transition"
                            :class="toggleUser.is_active ? 'bg-rose-600 hover:bg-rose-700 text-white' : 'bg-emerald-600 hover:bg-emerald-700 text-white'"
                            x-text="toggleUser.is_active ? 'Ya, Nonaktifkan Akun' : 'Ya, Aktifkan Akun'">
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>