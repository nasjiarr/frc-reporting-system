<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800">Manajemen Utilitas Gedung</h2>
        <p class="text-sm text-gray-600 mt-1">Pilih kategori utilitas untuk mengelola data dan melihat statistik.</p>
    </x-slot>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($jenisUtilitas as $item)
        <a href="{{ route('admin.utilitas.show', $item['slug']) }}" class="group bg-white p-6 rounded-2xl border border-gray-200 shadow-sm hover:shadow-md hover:border-{{ $item['warna'] }}-400 transition-all">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-xl bg-{{ $item['warna'] }}-100 text-{{ $item['warna'] }}-600 group-hover:scale-110 transition-transform">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
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