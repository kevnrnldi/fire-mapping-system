<div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold text-red-600 mb-6 text-center">Edukasi & Artikel Kebakaran</h1>

    {{-- Area Pencarian --}}
   <div class="mb-8 flex justify-center">
    
    {{-- 1. Tambahkan div pembungkus dengan class `relative` --}}
    <div class="relative w-full sm:max-w-xl">
        
        {{-- 2. Tambahkan ikon SVG dengan posisi absolut --}}
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
            </svg>
        </div>

        {{-- 3. Tambahkan padding kiri pada input --}}
        <input 
            type="text" 
            wire:model.live.debounce.300ms="search" 
            placeholder="Cari artikel edukasi..." 
            class="block w-full rounded-lg p-3 pl-10 border-gray-400 border bg-white shadow-md focus:border-red-500 focus:ring-red-500">
    </div>
    
</div>
 


    {{-- Grid Artikel --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($articles as $article)
            <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                @if ($article->image)
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                        Tidak Ada Media
                    </div>
                @endif
                <div class="p-5">
                    <h2 class="font-bold text-xl text-gray-900 mb-2 truncate">{{ $article->title }}</h2>
                    <p class="text-gray-700 text-sm mb-4">{{ Str::limit($article->content, 100) }}</p>
                    <a href="{{ route('edukasi.show', $article->id) }}" class="inline-block rounded   py-2.5 text-sm font-medium text-white transition ">
                        <button class="group relative inline-flex h-12 items-center justify-center overflow-hidden rounded-md border border-neutral-200  bg-red-600 px-6 font-medium hover:bg-red-700 hover:text-gray-200  text-white transition-all duration-100 [box-shadow:5px_5px_rgb(82_82_82)] active:translate-x-[3px] active:translate-y-[3px] active:[box-shadow:0px_0px_rgb(82_82_82)]">Lihat Selengkapnya</button>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-10 bg-gray-100 rounded-lg ">
                <p class="text-gray-500">Tidak ada artikel yang cocok dengan pencarian "{{ $search }}".</p>
            </div>
        @endforelse
    </div>

    

    {{-- Paginasi --}}
    <div class="mt-8">
        {{ $articles->links() }}
    </div>
</div>