<div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <h1 class="text-3xl font-bold text-gray-900  mb-6">Pengaturan Akun <span class="text-yellow-500">Admin</span></h1>

        {{-- Pesan Sukses --}}
        @if (session()->has('message'))
            <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        {{-- Area Aksi: Pencarian dan Tombol Tambah --}}
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau email..." class="block w-full sm:max-w-xs pl-5 pr-3 py-2 border border-gray-400 rounded-md ...">
            <button wire:click="create()" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-800 transform-colors duration-300 text-white rounded-md ...">
                Tambah Admin
            </button>
        </div>

        {{-- Tabel Daftar Admin --}}
        <div class="bg-white  shadow-lg ring-1 ring-gray-200 rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-300 ">
                <thead class="bg-red-600 text-white">
                    <tr>
                        <th class="px-6 py-3 ...">Nama</th>
                        <th class="px-6 py-3 ...">Email</th>
                        <th class="px-6 py-3 ...">Tanggal Dibuat</th>
                        <th class="px-6 py-3 ...">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300 text-center ">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-6 py-4 ...">{{ $user->name }}</td>
                            <td class="px-6 py-4 ...">{{ $user->email }}</td>
                            <td class="px-6 py-4 ...">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4  gap-2...">
                                <button wire:click="edit({{ $user->id }})" class="font-medium text-white bg-green-600 hover:text-gray-800 transform-colors duration-300 hover:bg-green-600 py-2 px-4 mb-1  rounded-lg ">Edit</button>
                                <button wire:click="confirmDelete({{ $user->id }})" class="font-medium bg-red-600 text-white hover:text-gray-800 rounded-lg transform-colors duration-300 hover:bg-red-600 py-2 px-2">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-4 text-center ...">Tidak ada data admin.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-1 bg-red-600 ">{{ $users->links() }}</div>
        </div>

        {{-- MODAL UNTUK CREATE & UPDATE --}}
        
        @if ($isModalOpen)
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white  rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                   
                    <form>
                         @if ($errors->any())
                            <div class="mb-4 rounded-lg  bg-red-100 border border-red-400 mt-3 mx-2 p-2">
                                {{-- ... Tampilan notifikasi gagal ... --}}
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        {{-- Akan muncul pesan: "File yang diunggah harus berupa gambar (contoh: JPG, PNG)." --}}
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="px-4 pt-3 pb-4 sm:pb-4 ">
                            <h3 class="text-lg font-bold text-yellow-500 ">{{ $userId ? 'Edit Akun' : 'Tambah Akun Admin' }}</h3>
                            <div class="mt-2  space-y-4">
                                <div>
                                    <label for="name" class="block ...">Nama</label>
                                    <input type="text" id="name" wire:model.defer="name" class="mt-1 ring-1 ring-gray-200 shadow-lg block p-2 rounded-md w-full ...">
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="email" class="block ...">Email</label> 
                                    <input type="email" id="email" wire:model.defer="email" class="mt-1 ring-1 ring-gray-200 shadow-lg p-2 block rounded-md w-full ...">
                                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="password" class="block ...">Password</label>
                                    <input type="password" id="password" wire:model.defer="password" class="mt-1 ring-1 ring-gray-200 p-2 shadow-lg rounded-md block w-full ...">
                                    @if ($userId) <small class="text-gray-500">Kosongkan jika tidak ingin mengubah password.</small> @endif
                                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="password_confirmation" class=" block ...">Konfirmasi Password</label>
                                    <input type="password" id="password_confirmation" wire:model.defer="password_confirmation" class="mt-1 p-2 block rounded-md ring-1 ring-gray-200 shadow-lg w-full ...">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50  px-4 py-3 gap-2 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" wire:click.prevent="store()" class="... bg-blue-600 rounded-md p-2 text-white hover:bg-blue-700 transform-colors duration-300">Simpan</button>
                            <button type="button" wire:click="closeModal()" class="... bg-white hover:bg-gray-400 hover:text-white transform-colors duration-300 p-2 rounded-md ">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        {{-- DIALOG KONFIRMASI DELETE --}}
        @if ($isConfirmingDelete)
        <div class="fixed z-10 inset-0 overflow-y-auto shadow-lg ring-1 ring-gray-200">
             <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white  rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-yellow-600 ">Hapus Akun</h3>
                        <p class="mt-2 text-sm text-gray-600">Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="bg-gray-200  px-4 py-3 gap-2 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="delete()" class="... bg-red-600 p-2 text-white transform-colors rounded-md duration-300 hover:bg-red-700">Hapus</button>
                        <button type="button" wire:click="closeConfirmModal()" class="... bg-gray-200 hover:bg-white hover:text-gray-600 rounded-md transform-colors duration-300 p-2">Batal</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>