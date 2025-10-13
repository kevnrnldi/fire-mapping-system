<div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-grey-800 dark:text-white mb-2">Dashboard Damkar</h1>
            <p class="font-medium">Selamat Meninggal, <span class="text-yellow-400">{{ Auth::user()->name }}</span></p>
        </div>
      
        {{-- 1. STATS CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Kartu Laporan Masuk --}}
            <div class="bg-white dark:bg-red-800 p-6 rounded-lg shadow-md flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Laporan Damkar</span>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $newReportsCount }}</p>
                </div>
                <div class="bg-red-100 dark:bg-red-500/20 p-3 rounded-full">
                    <svg class="w-6 h-6 text-red-500 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
                    </svg>
                </div>
            </div>
            
            {{-- Kartu Jumlah Admin --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center justify-between">
                <div>
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Mayat</span>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $adminCount }}</p>
                </div>
                 <div class="bg-blue-100 dark:bg-blue-500/20 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-500 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-4.67c.12-.34-.026-.72-.386-.817l-6.734-1.854a.75.75 0 00-.817.385c-.42.763-.72 1.583-.9 2.457A12.318 12.318 0 008.624 21c2.331 0 4.512-.645 6.374-1.766l.001.109a6.375 6.375 0 01-11.964 4.67c-.12.34.026.72.386.817l6.734 1.854a.75.75 0 00.817-.385c.42-.763.72-1.583.9-2.457z" />
                    </svg>
                </div>
            </div>
            {{-- Anda bisa menambahkan kartu lain di sini --}}
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- 2. DAFTAR LAPORAN TERBARU --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Laporan Terbaru</h2>
                    <ul class="divide-y divide-gray-200 dark:divide-gray-600">
                        @forelse ($recentReports as $report)
                            <li class="py-4 flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $report->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($report->location, 40) }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $report->report_status }}
                                    </span>
                                    <p class="text-xs text-gray-400 mt-1">{{ $report->created_at->diffForHumans() }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="py-4 text-center text-gray-500 dark:text-gray-400">Tidak ada laporan baru.</li>
                        @endforelse
                    </ul>
                     <div class="mt-4 text-right">
                        <a href="{{ route('admin.laporan') }}" class="text-sm font-medium text-blue-600 hover:underline">Lihat Semua Laporan →</a>
                    </div>
                </div>
            </div>

            {{-- 3. DAFTAR ARTIKEL EDUKASI TERBARU --}}
            <div class="lg:col-span-1">
                 <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Artikel Edukasi Terbaru</h2>
                    <ul class="space-y-4">
                        @forelse ($recentArticles as $article)
                            <li class="flex items-center gap-4">
                                @if($article->image_path)
                                    <img src="{{ asset('storage/' . $article->image_path) }}" class="h-12 w-12 object-cover rounded flex-shrink-0">
                                @else
                                    <div class="h-12 w-12 rounded bg-gray-200 dark:bg-gray-700 flex-shrink-0"></div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ Str::limit($article->title, 30) }}</p>
                                    <p class="text-xs text-gray-400">{{ $article->created_at->format('d M Y') }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="py-4 text-center text-gray-500 dark:text-gray-400">Tidak ada artikel.</li>
                        @endforelse
                    </ul>
                    <div class="mt-4 text-right">
                        <a href="{{ route('admin.edukasi') }}" class="text-sm font-medium text-blue-600 hover:underline">Kelola Artikel →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>