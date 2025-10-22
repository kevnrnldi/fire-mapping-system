<div>
    <div class="container mx-auto px-4 py-3">
        <h1 class="text-3xl font-bold text-gray-800  mb-6 ">Manajemen <span class="text-yellow-500">Artikel Edukasi</span> </h1>

        @if (session()->has('success'))
            <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

         @if (session()->has('error'))
            <div class="bg-red-100 border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Area Aksi: Pencarian dan Tombol Tambah --}}
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari judul atau isi artikel..." class="block w-full sm:max-w-xs rounded-lg p-2 shadow-lg ring-1 ring-gray-200 border-gray-500  focus:border-indigo-500 focus:ring-indigo-500  ">
            <button wire:click="create()" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md transform-colors duration-300 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Tambah Artikel Baru
            </button>
        </div>

        {{-- Tabel Daftar Artikel --}}
        <div class="bg-white  shadow-md rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 ">
                <thead class="bg-red-600 ">
                    <tr >
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Foto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Deskripsi</th>
                        <th class="px-16 py-3 text-left  text-xs font-medium text-white uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 ">
                    @forelse ($articles as $article)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 ">{{ Str::limit($article->title, 50) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 ">
                                @if ($article->image)
                                    <img src="{{ asset('storage/' . $article->image) }}" alt="Foto Artikel" class="h-12 w-12 object-cover rounded">
                                @else
                                    <span>-</span>
                                @endif
                            </td>
                            <td class="px-6 my-4 whitespace-nowrap text-sm text-gray-500 ">
                                @if ($article->content)
                                    <p>{{ Str::limit($article->content, 20) }}</p>
                                @else
                                    <span>-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="edit({{ $article->id }})" class="font-medium text-white bg-green-600 hover:text-gray-800 transform-colors duration-300 hover:bg-green-600 py-2 px-4 mb-1  rounded-lg">Edit</button>
                                <button wire:click="confirmDelete({{ $article->id }})" class="font-medium bg-red-600 text-white hover:text-gray-800 rounded-lg transform-colors duration-300 hover:bg-red-600 py-2 px-2">Hapus</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500 ">Tidak ada artikel edukasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-2 bg-red-600 ">{{ $articles->links() }}</div>
        </div>

        {{-- MODAL UNTUK CREATE & UPDATE ARTIKEL --}}
        @if ($isModalOpen)
        <div class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0 shadow-xl ring-1 ring-gray-200">
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white  rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit.prevent="store">
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
                        <div class="bg-white  px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-yellow-600 ">{{ $id ? 'Edit Artikel' : 'Tambah Artikel Baru' }}</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 ">Judul Artikel</label>
                                    <input type="text" id="title" wire:model.defer="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-lg ring-1 p-2 ring-gray-300 focus:border-blue-500 focus:ring-blue-500  ">
                                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="content" class="block text-sm font-medium text-gray-700 ">Isi Artikel</label>
                                    <textarea id="content" wire:model.defer="content" rows="8" class="mt-1 block w-full rounded-md border-gray-300 shadow-lg ring-1 p-2 ring-gray-300 focus:border-blue-500 focus:ring-blue-500  "></textarea>
                                    @error('content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="image" class="block text-sm font-medium text-gray-700 ">Foto Artikel</label>
                                    <input type="file" id="image" accept="image/*" wire:model="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full  file:border-0 file:text-sm file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200">
                                    @if ($image)
                                        <img src="{{ $image->temporaryUrl() }}" class="mt-2 h-24 w-auto rounded">
                                    @elseif ($currentImage)
                                        <p class="mt-2 text-sm text-gray-500 ">Gambar saat ini:</p>
                                        <img src="{{ asset('storage/' . $currentImage) }}" class="h-24 w-auto rounded">
                                    @endif
                                    @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                {{-- <div>
                                    <label for="video" class="block text-sm font-medium text-gray-700 ">URL Video YouTube (Opsional)</label>
                                    <input type="text" id="video" wire:model.defer="video" placeholder="Contoh: https://www.youtube.com/watch?v=..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500  ">
                                    @error('video') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                                    @if ($video && $this->getYoutubeId($video))
                                        <div class="mt-2 aspect-video w-full">
                                            <iframe class="w-full h-full rounded-lg" src="https://www.youtube.com/embed/{{ $this->getYoutubeId($video) }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </div>
                                    @endif
                                </div> --}}
                            </div>
                        </div>
                        <div class="bg-gray-50  px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-4">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Simpan</button>
                            <button type="button" wire:click="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

        {{-- DIALOG KONFIRMASI DELETE --}}
        @if ($isConfirmingDelete)
        <div class="fixed z-10 inset-0 overflow-y-auto">
             <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white  rounded-lg text-left overflow-hidden shadow-xl ring-1 ring-gray-200 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white  px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6  text-red-600 font-bold">Hapus Artikel</h3>
                        <p class="mt-2 text-sm text-gray-600 ">Anda yakin ingin menghapus artikel ini? Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="bg-gray-50  px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                        <button type="button" wire:click="delete()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Hapus</button>
                        <button type="button" wire:click="closeConfirmModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3">Batal</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>