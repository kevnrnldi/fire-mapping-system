

<div class="">
 
    <div class="w-full max-w-lg rounded-lg bg-gray-200 p-10 shadow-md ">
        
        {{-- Judul Form --}}
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-900 "><span class="text-yellow-600">Admin</span>Login</h1>
            <p class="mt-2 text-sm text-gray-600 ">Selamat datang kembali! Silakan masuk.</p>
        </div>

        {{-- Form Login --}}
        <form class="mt-5 space-y-5" wire:submit.prevent="login">

            {{-- Input Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-900 ">Email</label>
                <div class="mt-2">
                    <input 
                        id="email" 
                        wire:model.defer="email"
                        type="email" 
                        autocomplete="email" 
                        required
                        class="block w-full p-2 bg-white rounded-md border-gray-300 shadow-lg focus:border-blue-500 focus:ring-blue-500    sm:text-sm"
                        placeholder="you@example.com"
                    >
                </div>
                @error('email') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Input Password --}}
          <div>
                <label for="password" class="block text-sm font-medium text-gray-900">Password</label>
                
                <div x-data="{ show: false }" class="relative mt-2">
                    
                    <input 
                        id="password" 
                        wire:model.defer="password"
                        :type="show ? 'text' : 'password'"
                        autocomplete="current-password" 
                        required
                        class="block w-full rounded-md border-gray-300 bg-white p-2 pr-10 shadow-lg focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        placeholder="*****"
                    >

                    <button 
                        type="button" 
                        @click="show = !show" 
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                        
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>

                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>

                @error('password') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input 
                        id="remember" 
                        wire:model.defer="remember"
                        type="checkbox" 
                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500  "
                    >
                    <label for="remember" class="ml-2 block text-sm text-gray-900 ">Ingat saya</label>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div>
                <button 
                    type="submit"
                    class="flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    <span wire:loading.remove>Masuk</span>
                    <span wire:loading>Memproses...</span>
                </button>
                
            </div>
        </form>
        <a href="{{ route('beranda') }}"><button type="button" class="text-white   focus:ring-4  bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-4 w-full py-2.5 text-center me-2 mt-3">Kembali</button>
    </a>

        
    </div>
      
</div>


