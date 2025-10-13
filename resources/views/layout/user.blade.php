<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- Ganti judul default jika halaman memiliki judul sendiri --}}
    <title>{{ $title ?? 'Sistem Pemantauan Kebakaran' }}</title>

    {{-- Memuat Vite untuk CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Memuat style Livewire --}}
    @livewireStyles
    
    {{-- Menambahkan font dari Google Fonts (opsional, untuk tampilan lebih baik) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 antialiased">

    <div class="flex flex-col h-screen">
        {{-- HEADER --}}
        <header class="bg-white dark:bg-gray-800 shadow-md z-10">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    {{-- Logo dan Judul --}}
                    <div class="flex items-center">
                        <a href="/">
                            {{-- Ganti dengan logo Anda --}}
                            <img class="h-8 w-auto" src="https://placehold.co/100x100/f87171/ffffff?text=SPK" alt="Logo">
                        </a>
                        <a href="/" class="ml-3 text-xl font-bold text-gray-800 dark:text-white">
                            Sistem Pemantauan Kebakaran
                        </a>
                    </div>

                    {{-- Menu Kanan (bisa untuk login, dll) --}}
                    <div class="flex items-center">
                        <a href="#" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Login Admin</a>
                    </div>
                </div>
            </div>
        </header>

        {{-- MAIN CONTENT & SIDEBAR --}}
        <div class="flex flex-1 overflow-hidden">
            {{-- Bagian Konten Utama (menggantikan peta) --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                {{-- Di sinilah konten dari setiap halaman akan dimasukkan --}}
                {{ $slot }}
            </main>

            {{-- Sidebar Kanan --}}
            <aside class="w-full sm:w-80 bg-white dark:bg-gray-800 shadow-lg overflow-y-auto flex-shrink-0">
                <div class="p-6">
                    {{-- Search Bar --}}
                    <div class="relative mb-6">
                        <input type="text" placeholder="Cari..." class="w-full pl-4 pr-10 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                    
                    {{-- Menu Sidebar --}}
                    <nav class="flex flex-col space-y-2">
                        <a href="#" class="block px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            Laporankan 
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                           Artikel dan Edukasi
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            Notifikasi
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            Tentang
                        </a>
                    </nav>
                </div>
            </aside>
        </div>
    </div>

    {{-- Memuat script Livewire --}}
    @livewireScripts
</body>
</html>