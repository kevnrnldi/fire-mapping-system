<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
</head>
<body class="bg-gray-100 ">
  {{-- Layout utama --}}
   <div class="flex h-screen">
   
        <div 
            id="sidebar" 
            class="fixed inset-y-0 left-0 z-30 flex h-screen w-64 flex-col justify-between border-e  transition-transform duration-300 ease-in-out -translate-x-full  bg-red-800 md:relative md:translate-x-0"
        >
            <div>
                <div class="flex items-center gap-2 px-4 py-4">
                    <img src="https://i0.wp.com/satpolpp.ngawikab.go.id/wp-content/uploads/2023/09/cropped-LOGO-PEMADAM-KEBAKARAN.png?ssl=1" class="size-10 rounded-lg" alt="Logo">
                    <span class="text-base font-semibold text-white ">pantauKebakaran.id</span>
                </div>
                <div class="border-t border-white ">
                    <div class="px-4 py-4">
                        <ul class="space-y-1">

                            {{-- ... Halaman Dashboard ... --}}
                            <li>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 rounded-lg text-sm font-medium px-4 py-2
                              @if(request()->routeIs('admin.dashboard'))
                                'bg-blue-50 text-yellow-500 '
                              @else
                              ' text-white hover:bg-gray-50 hover:text-gray-700 '
                               @endif
                              )
                           ">
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                <span class="text-sm font-medium">Beranda</span>
                              </a>
                            </li>

                            {{-- Halaman Laporan --}}
                            <li class="mt-3">
                            <a href="{{ route('admin.laporan') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium 
                               @if(request()->routeIs('admin.laporan'))
                                'bg-blue-50  text-yellow-500 '
                               @else
                              ' text-white hover:bg-gray-50 hover:text-gray-700  '
                               @endif
                               )
                            ">
                              <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                              </svg>
                              <span class="text-sm font-medium">Laporan</span>
                            </a>
                          </li>

                           {{-- Halaman Pemetaan --}}
                            <li class="mt-3">
                            <a href="{{ route('admin.map') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium 
                               @if(request()->routeIs('admin.map'))
                                'bg-blue-50   text-yellow-500 '
                               @else
                              ' text-white hover:bg-gray-50 hover:text-gray-700 '
                               @endif
                               )
                            ">
                              <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z"  />
                              </svg>
                              <span class="text-sm font-medium">Pemetaan</span>
                            </a>
                          </li>

                          {{-- Halaman Edukasi --}}
                          <li class="mt-3">
                            <a href="{{ route('admin.edukasi') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium 
                               @if(request()->routeIs('admin.edukasi'))
                                'bg-blue-50   text-yellow-500 '
                               @else
                              ' text-white hover:bg-gray-50 hover:text-gray-700 '
                               @endif
                               )
                            ">
                              <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"  d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"  />
                              </svg>
                              <span class="text-sm font-medium">Edukasi</span>
                            </a>
                          </li>

                           {{-- Halaman Pengaturan --}}
                            <li class="mt-3" x-data='{ open: false }'>
                              {{-- Tombol option dropdown --}}
                              <div
                                  @click="open = !open"
                                  class="flex cursor-pointer items-center justify-between gap-3 rounded-lg px-4 py-2
                                  
                                  {{-- Logika untuk highlight menu utama jika salah satu submenu aktif --}}
                                  @if(request()->routeIs('admin.akun' , 'admin.change-password'))
                                      'bg-blue-50   text-yellow-500 '   
                                  @else
                                      ' text-white hover:bg-gray-50 hover:text-gray-700 '
                                  @endif
                                  ">

                            {{-- Bagian Menu Pengaturan --}}
                            <div  class="flex items-center gap-3">
                              <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"  />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                              </svg>
                              <span class="text-sm font-medium">Pengaturan</span>
                            </div>

                             {{-- Ikon panah yang berputar saat dropdown terbuka --}}
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="size-4 shrink-0 transition duration-300"
                                    :class="{ 'rotate-180': open }"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                              </div>
                            
                              <ul x-show='open' class='mt-3 space-y-1 px-4' x-transition>
                                  <li>
                                      <a
                                          href="{{ route('admin.akun') }}"
                                          class="block rounded-lg px-4 py-2 text-sm font-medium
                                          @if(request()->routeIs('admin.akun'))
                                              'bg-blue-50   text-yellow-500 '
                                          @else
                                              ' text-white hover:bg-gray-50 hover:text-gray-700 '
                                          @endif
                                          ">
                                          Pengaturan Akun</a>
                                  </li>
                                  <li>
                                      <a
                                          href="{{ route('admin.change-password') }}"
                                          class="block rounded-lg px-4 py-2 text-sm font-medium
                                          @if(request()->routeIs('admin.change-password'))
                                              'bg-blue-50   text-yellow-500 '
                                          @else
                                              ' text-white hover:bg-gray-50 hover:text-gray-700 '
                                          @endif
                                          ">
                                          Reset Password</a>
                                  </li>
                              </ul>
                           
                          </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            {{-- Logout --}}
            <div class="sticky inset-x-0 bottom-0 border-t border-white  p-2  bg-gray-500">
                <a href="{{ route('login') }}" class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium text-white hover:bg-gray-50 hover:text-gray-700 ">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    <span class="text-sm font-medium">Keluar</span>
                </a>
            </div>
        </div> 

        {{-- Sidebar backdrop --}}
        <div id="sidebar-backdrop" class="fixed inset-0 z-20 hidden bg-black/50 md:hidden"></div> {{-- ID ditambahkan --}}

        <div class="flex flex-1 flex-col ">
            <header class="flex h-16 shrink-0 items-center justify-between border-b  p-4  bg-red-700 md:justify-end sticky top-0 z-10">
                <button id="hamburger-button" class="text-white hover:text-yellow-500 border border-white p-2 rounded-md ring-2  focus:outline-none focus:ring-2 focus:ring-inset  hover:border-yellow-500 transition-colors duration-300 shadow-xs md:hidden"> {{-- ID ditambahkan --}}
                    <span class="sr-only">Buka menu</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                
                <div class="flex items-center">
                    <button type="button" class="overflow-hidden rounded-full bg-blue-600">
                      <div class="flex items-center gap-1 px-4 ">
                        <p class="text-sm text-white font-mono font-bold">{{ Auth::user()->name }}</p>
                        <img src="https://static.vecteezy.com/system/resources/thumbnails/009/636/683/small_2x/admin-3d-illustration-icon-png.png" alt="User Profile" class="size-10 object-cover" />
                      </div>
                     </button>
                </div>
            </header>

            {{-- Halaman Utama --}}
            <main class="flex-1 px-6  md:p-8 overflow-y-auto">
              @if(isset($slot))
                 {{ $slot }} 
                 @else 
                 @yield('content')
                 @endif
            </main>
        </div>
    </div>




    
    {{-- SCRIPT UNTUK FUNGSI SIDEBAR --}}
<script>
         document.addEventListener('DOMContentLoaded', () => {
            const hamburgerButton = document.getElementById('hamburger-button');
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');

            const toggleSidebar = () => {
                sidebar.classList.toggle('-translate-x-full');
                sidebar.classList.toggle('translate-x-0');
                backdrop.classList.toggle('hidden');
            };

            if (hamburgerButton) {
                hamburgerButton.addEventListener('click', toggleSidebar);
            }

            if (backdrop) {
                backdrop.addEventListener('click', toggleSidebar);
            }
        });
</script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    

 @livewireScripts
 @stack('scripts')
 {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}
</body>
</html>

   