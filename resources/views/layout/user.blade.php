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
    
    
    
    {{-- Menambahkan font dari Google Fonts (opsional, untuk tampilan lebih baik) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    {{-- Memuat style Livewire --}}
    @livewireStyles
</head>
<body class="bg-gray-100  antialiased">

    <div class="flex flex-col h-screen">
        {{-- HEADER --}}
       <header x-data="{ open: false }" class="bg-white shadow-lg z-20 relative ">
            <div class="container-fluid  px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between  h-16">
                    {{-- Logo dan Judul --}}
                    <div class="flex items-center">
                        <a href="{{ route('beranda') }}" class="ml-3 overflow-hidden  w-full h-48">
                            <img class="w-full h-full object-cover"  src="{{ asset('assets/img/photo.png') }}" alt="Logo">
                        </a>
                    </div>

                    {{-- Menu Kanan (bisa untuk login, dll) --}}
                    <div class="flex items-center">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600  hover:text-gray-900 ">
                             <button type="button" class="focus:outline-none text-white bg-red-600 hover:bg-red-800 transition-colors shadow-lg shadow-red-500/50 duration-300 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-3  ">LOGIN</button>
                        </a>

                        {{-- Hamburger Menu --}}
                        <button @click="open = !open" class="ml-4 p-2 ring-2 rounded-md  text-gray-400 hover:text-red-600 transition-colors duration-300  focus:outline-none focus:ring-2 shadow-xs focus:ring-inset  lg:hidden">
                            <span class="sr-only">Buka menu utama</span>
                            {{-- Ikon Hamburger (garis tiga) --}}
                            <svg x-show="!open" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                            </svg>
                            {{-- Ikon 'X' (tutup) --}}
                            <svg x-show="open" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="open" 
                 x-transition
                 class="lg:hidden" 
                 id="mobile-menu"
                 style="display: none;">
                <nav class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="{{ route('beranda') }}" 
                            @class([
                                    'block   text-medium  transition-all duration-300  hover:bg-gray-200 border-b border-gray-500  font-medium',
                                    'text-red-600 px-3 py-3' => request()->routeIs('beranda'), // Aktif
                                    'text-gray-900 px-4 py-4 hover:p-3' => !request()->routeIs('beranda'), // Tidak Aktif
                            ])>BERANDA</a>
                    <a href="{{ route('laporan') }}"  @class([
                                    'block   text-medium   transition-all duration-300  hover:bg-gray-200 border-b border-gray-500  font-medium',
                                    'text-red-600 px-3 py-3' => request()->routeIs('laporan'), // Aktif
                                    'text-gray-900 px-4 py-4 hover:p-3' => !request()->routeIs('laporan'), // Tidak Aktif
                            ])>LAPORKAN</a>
                    <a href="{{ route('edukasi') }}"  @class([
                                    'block   text-medium  transition-all duration-300  hover:bg-gray-200 border-b border-gray-500  font-medium',
                                    'text-red-600 px-3 py-3' => request()->routeIs('edukasi'), // Aktif
                                    'text-gray-900 px-4 py-4 hover:p-3' => !request()->routeIs('edukasi'), // Tidak Aktif
                            ])>EDUKASI</a>
                    
                    {{-- Dropdown Legenda (versi mobile) --}}
                    <div x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false" class="relative border-b border-gray-500">
    
                    <button 
                        @click="dropdownOpen = !dropdownOpen" 
                        class="flex items-center justify-between w-full text-left px-4 py-3 text-base font-medium text-gray-900 hover:bg-gray-200 focus:outline-none transition-colors duration-300 ease-in-out"
                        :class="{ 'text-red-600 bg-gray-100': dropdownOpen }">
                        
                        <span>LEGENDA</span>
                        
                        <svg 
                            class="w-5 h-5 ml-1 transform transition-transform duration-300" 
                            :class="{ 'rotate-180': dropdownOpen }" 
                            fill="currentColor" 
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    
                    <div 
                        x-show="dropdownOpen" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                        class="mt-1 pl-4"
                        style="display: none;">
                        
                        <div class="px-4 py-2 text-sm text-gray-600 space-y-3">
                        <p class="text-xs">
                            Legenda ini menjelaskan ikon yang digunakan di dalam peta untuk menandai titik lokasi.
                        </p>
                        
                        <ul class="space-y-2.5">
                            
                            <li class="flex items-center">
                                <img src="{{ asset('assets/img/fire.png') }}" 
                                    alt="Ikon Kebakaran" 
                                    class="w-6 h-6 mr-2 flex-shrink-0">
                                <div>
                                    <span class="font-semibold text-gray-800">Area Kebakaran</span>
                                    <p class="text-xs text-gray-500">Menandakan titik lokasi terjadinya kebakaran yang dilaporkan.</p>
                                </div>
                            </li>
                            
                            <li class="flex items-center">
                                <img src="{{ asset('assets/img/fire-station.png') }}" 
                                    alt="Ikon Pos Pemadam" 
                                    class="w-6 h-6 mr-2 flex-shrink-0">
                                <div>
                                    <span class="font-semibold text-gray-800">Pos Pemadam</span>
                                    <p class="text-xs text-gray-500">Menandakan lokasi pos pemadam kebakaran terdekat.</p>
                                </div>
                            </li>
                            
                        </ul>
                    </div>
                        </p>
                    </div>

                </div>

                    {{-- Dropdown Tentang (versi mobile) --}}
                    <div x-data="{ dropdownOpen: false }" @click.outside="dropdownOpen = false" class="relative ">
    
                    <button 
                        @click="dropdownOpen = !dropdownOpen" 
                        class="flex items-center justify-between w-full text-left px-4 py-3 text-base font-medium text-gray-900 hover:bg-gray-200 focus:outline-none transition-colors duration-300 ease-in-out"
                        :class="{ 'text-red-600 bg-gray-100': dropdownOpen }">
                        
                        <span>TENTANG</span>
                        
                        <svg 
                            class="w-5 h-5 ml-1 transform transition-transform duration-300" 
                            :class="{ 'rotate-180': dropdownOpen }" 
                            fill="currentColor" 
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
    
                    <div 
                        x-show="dropdownOpen" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                        class="mt-1 pl-4"
                        style="display: none;">
                        
                        <div class="container mx-auto px-4 py-3">
                            <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
                                
                                <h1 class="text-3xl font-bold text-gray-800 mb-2">
                                    Tentang PantauKebakaran.id
                                </h1>
                                <p class="text-lg text-gray-600 mb-6">
                                    Platform Informasi dan Pelaporan Kebakaran
                                </p>

                                <hr class="my-6">

                                <div class="prose prose-lg max-w-none text-gray-700">
                                    <p>
                                        <strong>PantauKebakaran.id</strong> adalah sebuah sistem informasi geografis (SIG) berbasis web yang dirancang untuk memantau, melaporkan, dan menyediakan informasi terkait insiden kebakaran.
                                    </p>
                                    
                                    <h2 class="text-2xl font-semibold text-gray-800 mt-6">Misi Kami</h2>
                                    <p>
                                        Misi kami adalah menyediakan platform yang cepat, akurat, dan mudah diakses bagi masyarakat dan petugas pemadam kebakaran. Kami bertujuan untuk:
                                    </p>
                                    <ul>
                                        <li>
                                            <strong>Mempercepat Respon:</strong> Memberikan data lokasi yang akurat kepada petugas agar dapat merespon laporan kebakaran dengan lebih cepat.
                                        </li>
                                        <li>
                                            <strong>Menginformasikan Publik:</strong> Menyajikan informasi terkini mengenai titik api atau area terdampak kebakaran kepada masyarakat.
                                        </li>
                                        <li>
                                            <strong>Sentralisasi Data:</strong> Menjadi pusat data historis kebakaran yang dapat digunakan untuk analisis dan pencegahan di masa depan.
                                        </li>
                                    </ul>

                                    <h2 class="text-2xl font-semibold text-gray-800 mt-8">Fitur Utama</h2>
                                    <ul>
                                        <li>
                                            <strong>Peta Interaktif:</strong> Visualisasi data kebakaran dan pos pemadam dalam peta yang dinamis dan mudah digunakan.
                                        </li>
                                        <li>
                                            <strong>Pelaporan Publik:</strong> Memungkinkan masyarakat untuk melaporkan kejadian kebakaran baru langsung melalui peta interaktif.
                                        </li>
                                        <li>
                                            <strong>Daftar Informasi Real-time:</strong> Menampilkan daftar laporan yang masuk secara kronologis.
                                        </li>
                                        <li>
                                            <strong>Legenda Dinamis:</strong> Penjelasan ikon yang jelas untuk membedakan antara titik api dan lokasi pos pemadam.
                                        </li>
                                    </ul>

                                   
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                </nav>
            </div>
        </header>

        {{-- MAIN CONTENT & SIDEBAR --}}
        <div class="flex flex-1 overflow-hidden">
            {{-- Bagian Konten Utama (menggantikan peta) --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
                @if(isset($slot))
                    {{ $slot }}
                 @else                 
                     @yield('content')
                @endif
            </main>

            {{-- Sidebar Kanan --}}
            <aside class="w-full sm:w-80 bg-white shadow-xl/20 overflow-y-auto flex-shrink-0 hidden lg:block mt-3">
                <div class="">
                    
                    {{-- Menu Sidebar --}}
                    <nav class="flex flex-col">

                        {{-- Item 1: BERANDA --}}
                        <div class="border-b border-gray-500">
                            <a href="{{ route('beranda') }}" 
                            @class([
                                    'block transition-all duration-300 ease-in-out hover:bg-gray-200',
                                    'text-red-600 py-3 px-3 font-medium' => request()->routeIs('beranda'), // Aktif
                                    'text-gray-900 px-4 py-4 hover:py-3 font-medium' => !request()->routeIs('beranda'), // Tidak Aktif
                            ])>
                            BERANDA
                            </a>
                        </div>

                        {{-- Item 2: LAPORKAN --}}
                        <div class="border-b border-gray-500">
                            <a href="{{ route('laporan') }}"
                            @class([
                                    'block transition-all duration-300 ease-in-out hover:bg-gray-200',
                                    'text-red-600 py-3 px-3 font-medium' => request()->routeIs('laporan'),
                                    'text-gray-900 px-4 py-4 hover:py-3 font-medium' => !request()->routeIs('laporan'),
                            ])>
                                LAPORKAN 
                            </a>
                        </div>

                        {{-- Item 3: EDUKASI --}}
                        <div class="border-b border-gray-500">
                            <a href="{{ route('edukasi') }}" 
                            @class([
                                    'block transition-all duration-300 ease-in-out hover:bg-gray-200',
                                    'text-red-600 py-3 px-3 font-medium' => request()->routeIs('edukasi'),
                                    'text-gray-900 px-4 py-4 hover:py-3 font-medium' => !request()->routeIs('edukasi'),
                            ])>
                                EDUKASI
                            </a>
                        </div>

                        {{-- Item 4: LEGENDA (Dropdown) --}}
                        <div x-data="{ open: false }" @click.outside="open = false" class="border-b border-gray-500">
    
                            <button 
                                @click="open = !open" 
                                class="flex items-center justify-between w-full text-left px-4 py-4 font-medium text-gray-900 hover:bg-gray-200 focus:outline-none transition-all duration-300 ease-in-out"
                                :class="{ 'text-red-600 bg-gray-100': open }">
                                
                                <span>LEGENDA</span>
                                
                                <svg 
                                    class="w-4 h-4 ml-1 transform transition-transform duration-300" 
                                    :class="{'rotate-180': open}" 
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div 
                                x-show="open" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform -translate-y-2"
                                class="w-full origin-top-left bg-white" 
                                style="display: none;">
                                
                                <div class="p-4">
                                   <div class="px-4 py-2 text-sm text-gray-600 space-y-3">
                                <p class="text-xs">
                                    Legenda ini menjelaskan ikon yang digunakan di dalam peta untuk menandai titik lokasi.
                                </p>
                                <ul class="space-y-2.5">
                                    
                                    <li class="flex items-center">
                                        <img src="{{ asset('assets/img/fire.png') }}" 
                                            alt="Ikon Kebakaran" 
                                            class="w-6 h-6 mr-2 flex-shrink-0">
                                        <div>
                                            <span class="font-semibold text-gray-800">Area Kebakaran</span>
                                            <p class="text-xs text-gray-500">Menandakan titik lokasi terjadinya kebakaran yang dilaporkan.</p>
                                        </div>
                                    </li>
                                    
                                    <li class="flex items-center">
                                        <img src="{{ asset('assets/img/fire-station.png') }}" 
                                            alt="Ikon Pos Pemadam" 
                                            class="w-6 h-6 mr-2 flex-shrink-0">
                                        <div>
                                            <span class="font-semibold text-gray-800">Pos Pemadam</span>
                                            <p class="text-xs text-gray-500">Menandakan lokasi pos pemadam kebakaran terdekat.</p>
                                        </div>
                                    </li>
                                    
                                </ul>
                            </div>
                                </div>
                            </div>
                        </div>

                        {{-- Item 5: TENTANG (Dropdown) --}}
                        <div x-data="{ open: false }" @click.outside="open = false" class="border-b border-gray-500">
    
                            <button 
                                @click="open = !open" 
                                class="flex items-center justify-between w-full text-left px-4 py-4 font-medium text-gray-900 hover:bg-gray-200 focus:outline-none transition-all duration-300 ease-in-out"
                                :class="{ 'text-red-600 bg-gray-100': open }">
                                
                                <span>TENTANG</span>
                                
                                <svg 
                                    class="w-4 h-4 ml-1 transform transition-transform duration-300" 
                                    :class="{'rotate-180': open}" 
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div 
                                x-show="open" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform -translate-y-2"
                                class="w-full origin-top-left bg-white" 
                                style="display: none;">
                                
                                <div class="container mx-auto px-4 py-3">
                                <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
                                    
                                    <h1 class="text-xl font-bold text-gray-800 mb-2">
                                        Tentang PantauKebakaran.id
                                    </h1>
                                    <p class="text-lg text-gray-600 mb-6">
                                        Platform Informasi dan Pelaporan Kebakaran
                                    </p>

                                    <hr class="my-6">

                                    <div class="prose prose-lg max-w-none text-gray-700">
                                        <p>
                                            <strong>PantauKebakaran.id</strong> adalah sebuah sistem informasi geografis (SIG) berbasis web yang dirancang untuk memantau, melaporkan, dan menyediakan informasi terkait insiden kebakaran.
                                        </p>
                                        
                                        <h2 class="text-2xl font-semibold text-gray-800 mt-6">Misi Kami</h2>
                                        <p>
                                            Misi kami adalah menyediakan platform yang cepat, akurat, dan mudah diakses bagi masyarakat dan petugas pemadam kebakaran. Kami bertujuan untuk:
                                        </p>
                                        <ul>
                                            <li>
                                                <strong>Mempercepat Respon:</strong> Memberikan data lokasi yang akurat kepada petugas agar dapat merespon laporan kebakaran dengan lebih cepat.
                                            </li>
                                            <li>
                                                <strong>Menginformasikan Publik:</strong> Menyajikan informasi terkini mengenai titik api atau area terdampak kebakaran kepada masyarakat.
                                            </li>
                                            <li>
                                                <strong>Sentralisasi Data:</strong> Menjadi pusat data historis kebakaran yang dapat digunakan untuk analisis dan pencegahan di masa depan.
                                            </li>
                                        </ul>

                                        <h2 class="text-2xl font-semibold text-gray-800 mt-8">Fitur Utama</h2>
                                        <ul>
                                            <li>
                                                <strong>Peta Interaktif:</strong> Visualisasi data kebakaran dan pos pemadam dalam peta yang dinamis dan mudah digunakan.
                                            </li>
                                            <li>
                                                <strong>Pelaporan Publik:</strong> Memungkinkan masyarakat untuk melaporkan kejadian kebakaran baru langsung melalui peta.
                                            </li>
                                            <li>
                                                <strong>Daftar Informasi Real-time:</strong> Menampilkan daftar laporan yang masuk secara kronologis.
                                            </li>
                                            <li>
                                                <strong>Legenda Dinamis:</strong> Penjelasan ikon yang jelas untuk membedakan antara titik api dan lokasi pos pemadam.
                                            </li>
                                        </ul>

                                    </div>

                                </div>
                            </div>
                            </div>
                        </div>
                        
                    </nav>
                </div>
            </aside>
        </div>
    </div>


    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
    {{-- Memuat script Livewire --}}
    @livewireScripts
    @stack('scripts')
     

</body>
</html>