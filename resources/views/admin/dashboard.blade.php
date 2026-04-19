<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">Dashboard Admin</h2>
        <p class="text-sm text-gray-600 mt-1">Pusat kendali operasional pelaporan dan penugasan teknisi FRC.</p>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex items-center hover:shadow-md transition-shadow">
            <div class="flex-shrink-0 p-3 bg-blue-50 text-blue-600 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Laporan Baru</h3>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['laporan_baru'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex items-center hover:shadow-md transition-shadow">
            <div class="flex-shrink-0 p-3 bg-amber-50 text-amber-600 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Penugasan Aktif</h3>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['tugas_aktif'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex items-center hover:shadow-md transition-shadow">
            <div class="flex-shrink-0 p-3 bg-emerald-50 text-emerald-600 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Selesai (Bulan Ini)</h3>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['selesai_bulan_ini'] }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex items-center hover:shadow-md transition-shadow">
            <div class="flex-shrink-0 p-3 bg-violet-50 text-violet-600 rounded-lg">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pengguna Aktif</h3>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['pengguna_aktif'] }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Perlu Ditindaklanjuti
                </h3>
                <a href="{{ route('admin.penugasan.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Lihat Semua &rarr;</a>
            </div>

            <div class="p-0 flex-1">
                <ul class="divide-y divide-gray-200">
                    @forelse($laporanPerluTindakLanjut as $lap)
                    <li class="px-6 py-4 hover:bg-slate-50 transition">
                        <div class="flex items-center justify-between">
                            <div class="pr-4">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $lap->judul }}</p>
                                <p class="text-xs text-gray-500 mt-1"><span class="font-medium text-gray-700">{{ $lap->pelapor->nama_lengkap ?? 'User' }}</span> &bull; {{ $lap->lokasi }}</p>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800 uppercase tracking-widest">Baru</span>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="px-6 py-12 text-center flex flex-col items-center justify-center">
                        <svg class="w-10 h-10 text-emerald-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-gray-500 font-medium">Luar Biasa! Tidak ada laporan menumpuk.</p>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Penugasan Aktif Lapangan
                </h3>
            </div>

            <div class="p-0 flex-1">
                <ul class="divide-y divide-gray-200">
                    @forelse($penugasanAktif as $tugas)
                    <li class="px-6 py-4 hover:bg-slate-50 transition">
                        <div class="flex items-center justify-between">
                            <div class="pr-4">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $tugas->laporan?->judul ?? 'Laporan telah dihapus' }}</p>
                                <div class="flex items-center mt-1 text-xs text-gray-500 gap-2">
                                    <span class="inline-flex items-center gap-1 font-medium text-indigo-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $tugas->teknisi->nama_lengkap ?? 'Teknisi' }}
                                    </span>
                                    <span>&bull; Ditugaskan {{ $tugas->assigned_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="shrink-0 ml-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest
                                        {{ $tugas->status_tugas == 'Dikerjakan' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $tugas->status_tugas }}
                                </span>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="px-6 py-12 text-center flex flex-col items-center justify-center">
                        <svg class="w-10 h-10 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <p class="text-sm text-gray-500 font-medium">Belum ada penugasan aktif ke teknisi saat ini.</p>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>
</x-app-layout>