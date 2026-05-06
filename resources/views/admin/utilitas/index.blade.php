<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800">Manajemen Utilitas Gedung</h2>
        <p class="text-sm text-gray-600 mt-1">Pilih kategori utilitas untuk mengelola data dan melihat statistik.</p>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($jenisUtilitas as $item)
        <a href="{{ route('admin.utilitas.show', $item['slug']) }}" class="group bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md hover:border-{{ $item['warna'] }}-400 transition-all">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-{{ $item['warna'] }}-100 text-{{ $item['warna'] }}-600 transform transition-all duration-300 ease-out group-hover:scale-110 group-hover:-translate-y-1 group-hover:rotate-12">
                    <svg class="w-8 h-8 drop-shadow-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @switch($item['icon'])
                        @case('droplet')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"></path>
                        @break
                        @case('cloud-rain')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 14v6"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v6"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 16v6"></path>
                        @break
                        @case('arrow-up-down')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v18"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16 17-4 4-4-4"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 7 4-4 4 4"></path>
                        @break
                        @case('wind')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.7 7.7a2.5 2.5 0 1 1 1.8 4.3H2"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.6 4.6A2 2 0 1 1 11 8H2"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.6 19.4A2 2 0 1 0 14 16H2"></path>
                        @break
                        @case('lightbulb')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.9 1.2 1.5 1.5 2.5"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 18h6"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 22h4"></path>
                        @break
                        @default
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        @endswitch
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">{{ $item['nama'] }}</h3>
                    <p class="text-xs text-gray-500">Klik untuk detail & kelola</p>
                </div>
            </div>
        </a>
        @endforeach
    </div>
</x-app-layout>