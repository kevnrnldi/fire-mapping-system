<div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">Pengaturan Akun Admin</h1>

        {{-- Pesan Sukses --}}
        @if (session()->has('message'))
            <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        {{-- Area Aksi: Pencarian dan Tombol Tambah --}}
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
            <input type="text" wire:model.debounce.300ms="search" placeholder="Cari nama atau email..." class="block w-full sm:max-w-xs pl-10 pr-3 py-2 border border-gray-300 rounded-md ...">
            <button wire:click="create()" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md ...">
                Tambah Admin
            </button>
        </div>

        {{-- Tabel Daftar Admin --}}
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 ...">Nama</th>
                        <th class="px-6 py-3 ...">Email</th>
                        <th class="px-6 py-3 ...">Tanggal Gabung</th>
                        <th class="px-6 py-3 ...">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-6 py-4 ...">{{ $user->name }}</td>
                            <td class="px-6 py-4 ...">{{ $user->email }}</td>
                            <td class="px-6 py-4 ...">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 ...">
                                <button wire:click="edit({{ $user->id }})" class="font-medium text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                <button wire:click="confirmDelete({{ $user->id }})" class="font-medium text-red-600 hover:text-red-900">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-4 text-center ...">Tidak ada data admin.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 bg-gray-50 dark:bg-gray-700">{{ $users->links() }}</div>
        </div>

        {{-- MODAL UNTUK CREATE & UPDATE --}}
        @if ($isModalOpen)
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    <form>
                        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $userId ? 'Edit Akun' : 'Tambah Akun Admin' }}</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="name" class="block ...">Nama</label>
                                    <input type="text" id="name" wire:model.defer="name" class="mt-1 block w-full ...">
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="email" class="block ...">Email</label>
                                    <input type="email" id="email" wire:model.defer="email" class="mt-1 block w-full ...">
                                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="password" class="block ...">Password</label>
                                    <input type="password" id="password" wire:model.defer="password" class="mt-1 block w-full ...">
                                    @if ($userId) <small class="text-gray-500">Kosongkan jika tidak ingin mengubah password.</small> @endif
                                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block ...">Konfirmasi Password</label>
                                    <input type="password" id="password_confirmation" wire:model.defer="password_confirmation" class="mt-1 block w-full ...">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" wire:click.prevent="store()" class="... bg-blue-600 hover:bg-blue-700">Simpan</button>
                            <button type="button" wire:click="closeModal()" class="... bg-white hover:bg-gray-50">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        {{-- DIALOG KONFIRMASI DELETE --}}
        @if ($isConfirmingDelete)
        <div class="fixed z-10 inset-0 overflow-y-auto">
             <div class="flex items-center justify-center min-h-screen">
                <div class="fixed inset-0 transition-opacity" wire:click="closeConfirmModal()"><div class="absolute inset-0 bg-gray-600 opacity-75"></div></div>
                <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Hapus Akun</h3>
                        <p class="mt-2 text-sm text-gray-500">Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="delete()" class="... bg-red-600 hover:bg-red-700">Hapus</button>
                        <button type="button" wire:click="closeConfirmModal()" class="... bg-white hover:bg-gray-50">Batal</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>