<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">Manajemen Pengguna (User)</h2>
        <p class="text-sm text-gray-600 mt-1">Kelola hak akses, tambah, perbarui, atau nonaktifkan data staf dan teknisi FRC.</p>
    </x-slot>

    <div x-data="{ 
            userModalOpen: false, 
            editModalOpen: false, 
            editUrl: '', 
            editUser: { nama_lengkap: '', email: '', no_telepon: '', role: '' } 
        }" class="space-y-6">

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden p-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">

                <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-end gap-3 w-full md:w-auto">
                    <div class="w-full md:w-56">
                        <label class="block text-sm font-medium leading-6 text-gray-900 mb-1">Filter Hak Akses (Role)</label>
                        <select name="role" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" onchange="this.form.submit()">
                            <option value="">Semua Role</option>
                            <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="KepalaFRC" {{ request('role') == 'KepalaFRC' ? 'selected' : '' }}>Kepala FRC</option>
                            <option value="Teknisi" {{ request('role') == 'Teknisi' ? 'selected' : '' }}>Teknisi</option>
                            <option value="Pelapor" {{ request('role') == 'Pelapor' ? 'selected' : '' }}>Pelapor</option>
                        </select>
                    </div>
                </form>

                <button @click="userModalOpen = true" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 w-full md:w-auto justify-center h-9 mt-6 md:mt-0">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah User
                </button>
            </div>

            <div class="overflow-x-auto border border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Informasi Pengguna</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role / Hak Akses</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors duration-150 {{ !$user->is_active ? 'opacity-60 bg-gray-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900 flex items-center">
                                    {{ $user->nama_lengkap }}

                                    @if(auth()->id() === $user->id)
                                    <span class="ml-2 text-[10px] font-bold bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full">Anda</span>
                                    @endif

                                    @if($user->is_active)
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">Aktif</span>
                                    @else
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700">Nonaktif</span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-500">{{ $user->email }} &bull; {{ $user->no_telepon }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $user->role == 'Admin' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                    {{ $user->role == 'Teknisi' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $user->role == 'Pelapor' ? 'bg-slate-100 text-slate-800' : '' }}
                                    {{ $user->role == 'KepalaFRC' ? 'bg-emerald-100 text-emerald-800' : '' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button type="button"
                                    @click="editModalOpen = true; 
                                            editUrl = '{{ route('admin.users.update', $user->id) }}'; 
                                            editUser = { 
                                                nama_lengkap: '{{ addslashes($user->nama_lengkap) }}', 
                                                email: '{{ addslashes($user->email) }}', 
                                                no_telepon: '{{ addslashes($user->no_telepon) }}', 
                                                role: '{{ $user->role }}' 
                                            };"
                                    class="text-indigo-600 hover:text-indigo-900 font-semibold transition-colors">
                                    Edit
                                </button>

                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline-block ml-4">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengubah status akses pengguna ini?')"
                                        class="font-semibold transition-colors {{ $user->is_active ? 'text-rose-600 hover:text-rose-900' : 'text-emerald-600 hover:text-emerald-900' }}">
                                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                                @endif

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <h3 class="text-sm font-medium text-gray-900">Belum ada data user</h3>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($users, 'hasPages') && $users->hasPages())
            <div class="mt-4">
                {{ $users->links() }}
            </div>
            @endif
        </div>

        <div x-show="userModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center backdrop-blur-sm">
            <div @click.away="userModalOpen = false" class="bg-white rounded-xl shadow-xl max-w-lg w-full mx-4 overflow-hidden" x-transition>
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900 tracking-tight">Registrasi User Baru</h3>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900">Nama Lengkap</label>
                            <input name="nama_lengkap" type="text" class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                                <input name="email" type="email" class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium leading-6 text-gray-900">No. Telepon / WA</label>
                                <input name="no_telepon" type="text" class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900">Hak Akses (Role)</label>
                            <select name="role" class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                                <option value="Pelapor">Pelapor</option>
                                <option value="Teknisi">Teknisi</option>
                                <option value="Admin">Admin</option>
                                <option value="KepalaFRC">Kepala FRC</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900">Password Awal</label>
                            <input name="password" type="password" class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                        <button type="button" @click="userModalOpen = false" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-show="editModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-50 flex items-center justify-center backdrop-blur-sm">
            <div @click.away="editModalOpen = false" class="bg-white rounded-xl shadow-xl max-w-lg w-full mx-4 overflow-hidden" x-transition>
                <div class="px-6 py-4 border-b border-gray-200 bg-indigo-50">
                    <h3 class="text-lg font-bold text-indigo-900 tracking-tight">Edit Data Pengguna</h3>
                </div>
                <form :action="editUrl" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900">Nama Lengkap</label>
                            <input x-model="editUser.nama_lengkap" name="nama_lengkap" type="text" class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium leading-6 text-gray-900">Email</label>
                                <input x-model="editUser.email" name="email" type="email" class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium leading-6 text-gray-900">No. Telepon / WA</label>
                                <input x-model="editUser.no_telepon" name="no_telepon" type="text" class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900">Hak Akses (Role)</label>
                            <select x-model="editUser.role" name="role" class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" required>
                                <option value="Pelapor">Pelapor</option>
                                <option value="Teknisi">Teknisi</option>
                                <option value="Admin">Admin</option>
                                <option value="KepalaFRC">Kepala FRC</option>
                            </select>
                        </div>
                        <div class="pt-2 border-t border-gray-100">
                            <label class="block text-sm font-medium leading-6 text-gray-900">Ganti Password <span class="text-xs text-gray-400 font-normal">(Kosongkan jika tidak ingin mengubah)</span></label>
                            <input name="password" type="password" placeholder="••••••••" class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">Perbarui Data</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>