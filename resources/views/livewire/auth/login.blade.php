<div class="flex min-h-screen flex-col items-center justify-center">
    <div class="w-full max-w-md rounded-lg bg-white p-8 shadow-md dark:bg-gray-800">
        
        {{-- Judul Form --}}
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Login</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Selamat datang kembali! Silakan masuk.</p>
        </div>

        {{-- Form Login --}}
        <form class="mt-5 space-y-5" wire:submit.prevent="login">

            {{-- Input Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <div class="mt-2">
                    <input 
                        id="email" 
                        wire:model.defer="email"
                        type="email" 
                        autocomplete="email" 
                        required
                        class="block w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                        placeholder="you@example.com"
                    >
                </div>
                @error('email') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Input Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                <div class="mt-2">
                    <input 
                        id="password" 
                        wire:model.defer="password"
                        type="password" 
                        autocomplete="current-password" 
                        required
                        class="block w-full p-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                        placeholder="********"
                    >
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
                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700"
                    >
                    <label for="remember" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Ingat saya</label>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div>
                <button 
                    type="submit"
                    class="flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    <span wire:loading.remove>Masuk</span>
                    <span wire:loading>Memproses...</span>
                </button>
            </div>
        </form>
    </div>
</div>