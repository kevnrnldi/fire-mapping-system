<div>
    {{-- Container utama dengan tata letak dua kolom --}}
    <div class="grid grid-cols-1 gap-x-16 gap-y-8 lg:grid-cols-5">
        
        {{-- Kolom Kiri: Judul dan Deskripsi --}}
        <div class="lg:col-span-2">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                 <span class="text-yellow-400">Ganti Password</span> 
            </h2>
            <p class="mt-2 text-sm text-gray-500 dark:text-white">
                Perbarui password akun Anda. Untuk keamanan, pastikan Anda tidak menggunakan password ini di tempat lain.
            </p>
        </div>

        {{-- Kolom Kanan: Form --}}
        <div class="lg:col-span-3">
            <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <form wire:submit.prevent="updatePassword">
                    {{-- Bagian Isi Form --}}
                    <div class="p-6">
                        <div class="space-y-6">
                            {{-- Success Message --}}
                            @if (session('status'))
                                <div class="rounded-md bg-green-50 p-4 dark:bg-green-900/50">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-green-700 dark:text-green-300">
                                                {{ session('status') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Current Password --}}
                            <div>
                                <label for="currentPassword" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Password Saat Ini
                                </label>
                                <input 
                                    id="currentPassword" 
                                    wire:model.defer="currentPassword"
                                    type="password" 
                                    required
                                    class="mt-1 block w-full px-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                >
                                @error('currentPassword') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- New Password --}}
                            <div>
                                <label for="newPassword" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Password Baru
                                </label>
                                <input 
                                    id="newPassword" 
                                    wire:model.defer="newPassword"
                                    type="password" 
                                    required
                                    class="mt-1 block w-full px-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                >
                                <p class="mt-2 text-xs text-gray-500">Minimal 8 karakter.</p>
                                @error('newPassword') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- Confirm New Password --}}
                            <div>
                                <label for="newPassword_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Konfirmasi Password Baru
                                </label>
                                <input 
                                    id="newPassword_confirmation" 
                                    wire:model.defer="newPassword_confirmation"
                                    type="password" 
                                    required
                                    class="mt-1 block px-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-x-3 rounded-b-lg border-t border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-700 dark:bg-gray-800/50">
                      <div>
                            {{-- Pop-up notifikasi --}}
                            <div 
                                x-data="{ show: false }"
                                x-show="show"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform translate-y-4"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-300"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform translate-y-4"
                                @password-updated.window="() => { show = true; setTimeout(() => show = false, 3000) }"
                                style="display: none;"
                                class="fixed bottom-5 right-5 z-50 rounded-md bg-green-500 px-4 py-3 text-white shadow-lg"
                            >
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="font-medium">Password berhasil diperbarui!</p>
                                </div>
                            </div>
                            
                                <button 
                                    type="submit"
                                    class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700"
                                 
                                >
                                    <span >
                                        Simpan Password
                                    </span>
                                </button>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>