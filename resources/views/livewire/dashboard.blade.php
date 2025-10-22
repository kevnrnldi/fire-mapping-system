<div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-grey-800 e mb-2">Dashboard Admin</h1>
            <p class="font-medium">Selamat Datang, <span class="text-yellow-600">{{ Auth::user()->name }}</span></p>
        </div>
      
        {{-- 1. STATS CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Kartu Laporan Masuk --}}
            <div class=" p-6 rounded-lg shadow-lg flex items-center bg-red-700  justify-between">
                <div>
                    <span class="text-sm font-medium text-white ">Laporan Masuk</span>
                    <p class="text-3xl font-bold text-white ">{{ $newReportsCount }}</p>
                </div>
                <div class="bg-red-100  p-3 rounded-full border border-red-700">
                    <svg class="w-6 h-6 text-red-700 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
                    </svg>
                </div>
            </div>
            
            {{-- Kartu Jumlah Admin --}}
            <div class="bg-green-700  p-6 rounded-lg shadow-md flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-white">Jumlah Admin</span>
                    <p class="text-3xl font-bold text-white ">{{ $adminCount }}</p>
                </div>
                 <div class="bg-green-100 border border-green-700 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-700 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                    </svg>
                </div>
            </div>
            {{-- Anda bisa menambahkan kartu lain di sini --}}
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- 2. DAFTAR LAPORAN TERBARU --}}
            <div class="lg:col-span-2">
                <div class="bg-white border border-gray-200  p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-bold text-gray-900  mb-4">Laporan Terbaru</h2>
                    <ul class="divide-y divide-gray-200 ">
                        @forelse ($recentReports as $report)
                            <li class="py-4 flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-gray-900 ">{{ $report->name }}</p>
                                    <p class="text-sm text-gray-700 ">{{ Str::limit($report->location, 40) }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $report->report_status }}
                                    </span>
                                    <p class="text-xs text-gray-600 mt-1">{{ $report->created_at->diffForHumans() }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="py-4 text-center text-gray-500 ">Tidak ada laporan baru.</li>
                        @endforelse
                    </ul>
                     <div class="mt-4 text-right">
                        <a href="{{ route('admin.laporan') }}" 
                        class="text-sm font-medium leading-tight text-blue-600 no-underline"> <span class="
                                bg-no-repeat
                                bg-[linear-gradient(currentColor,currentColor)]
                                bg-[position:0%_100%]
                                bg-[length:0%_2px]
                                transition-all
                                duration-500
                                ease-in-out
                                hover:bg-[length:100%_2px]
                            ">
                                Lihat Semua Laporan →
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- 3. DAFTAR ARTIKEL EDUKASI TERBARU --}}
            <div class="lg:col-span-1">
                 <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-bold text-gray-900  mb-4">Artikel Edukasi Terbaru</h2>
                    <ul class="divide-y divide-gray-200">
                        @forelse ($recentArticles as $article)
                            <li class="flex items-center  gap-4 py-4">
                                @if($article->image)
                                    <img src="{{ asset('storage/' . $article->image) }}" class="h-12 w-12 object-cover rounded flex-shrink-0">
                                @else
                                    <div class="h-12 w-12 rounded bg-gray-200  flex-shrink-0"></div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900 ">{{ Str::limit($article->title, 30) }}</p>
                                    <p class="text-xs text-gray-600">{{ $article->created_at->format('d M Y') }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="py-4 text-center text-gray-500 ">Tidak ada artikel.</li>
                        @endforelse
                    </ul>
                    <div class="mt-4 text-right">
                        <a href="{{ route('admin.edukasi') }}" class="text-sm font-medium text-blue-600 no-underline">
                            <span class="bg-no-repeat
                                bg-[linear-gradient(currentColor,currentColor)]
                                bg-[position:0%_100%]
                                bg-[length:0%_2px]
                                transition-all
                                duration-500
                                ease-in-out
                                hover:bg-[length:100%_2px]">
                                 Kelola Artikel →
                            </span>
                           </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>