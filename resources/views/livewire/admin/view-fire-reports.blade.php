<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-3">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Laporan <span class="text-yellow-500">Kebakaran</span></h1>

    {{-- Notifikasi Sukses --}}
    @if (session()->has('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    {{-- Notifikasi Error/Hapus --}}
     @if (session()->has('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Area Pencarian dan Filter --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        {{-- Input Search --}}
        <div class="md:col-span-1 relative">
             <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
            </div>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Cari nama, kontak, lokasi..."
                class="block w-full rounded-lg border-gray-300 shadow-lg ring-1 ring-gray-200 pl-10 p-2.5 focus:border-indigo-500 focus:ring-indigo-500"
            >
        </div>
        {{-- Filter Status Laporan --}}
        <div>
            <select wire:model.live="filterReportStatus" class="block w-full shadow-lg ring-1 ring-gray-200  rounded-lg border-gray-300  p-2.5 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Status Laporan</option>
                <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                <option value="Diterima">Diterima</option>
            </select>
        </div>
        {{-- Filter Status Kebakaran --}}
        <div>
            <select wire:model.live="filterFireStatus" class="block w-full rounded-lg border-gray-300 shadow-lg ring-1 ring-gray-200   p-2.5 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua Status Kebakaran</option>
                <option value="Sedang Terjadi">Sedang Terjadi</option>
                <option value="Dalam Penanganan">Dalam Penanganan</option>
                <option value="Selesai">Selesai</option>
            </select>
        </div>
    </div>

    {{-- Daftar Laporan dalam Format Kartu Ringkas --}}
    <div class="space-y-4">
        @forelse ($reports as $report)
            <div class="bg-white shadow-md rounded-lg p-4 transition hover:shadow-lg flex flex-col sm:flex-row justify-between items-start gap-4">
                
                {{-- Info Utama (Kiri) --}}
                <div class="flex-1 min-w-0"> {{-- min-w-0 penting untuk text wrap --}}
                    <p class="font-semibold text-gray-900 truncate">{{ $report->name ?? 'Tanpa Nama' }}</p>
                    <p class="text-sm text-gray-600 truncate">{{ $report->location ?? 'Lokasi tidak tersedia' }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $report->created_at->diffForHumans() }}</p>
                </div>

                {{-- Aksi (Kanan) --}}
                <div class="flex-shrink-0 flex flex-col sm:flex-row items-end sm:items-center gap-2 w-full sm:w-auto">
                     {{-- Tombol Detail --}}
                     <a href="{{ route('admin.laporan.show', $report->id) }}"
                        class="group relative inline-flex items-center justify-center overflow-hidden rounded-md 
                                bg-gray-600 px-3 py-1.5 text-xs font-medium text-white 
                                hover:bg-gray-700 
                                w-full sm:w-auto text-center 
                                transition duration-300 ease-in-out"> 
                            
                            {{-- Initial Text ("Detail") --}}
                            <span class="translate-x-0 opacity-100 transition duration-300 ease-in-out group-hover:-translate-x-[150%] group-hover:opacity-0">
                                Detail
                            </span>
                            
                            {{-- Icon revealed on Hover --}}
                            <span class="absolute inset-0 flex items-center justify-center translate-x-[150%] opacity-0 transition duration-300 ease-in-out group-hover:translate-x-0 group-hover:opacity-100">
                                {{-- Arrow SVG Icon (Adjust size with h-4 w-4) --}}
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4">
                                    <path d="M8.14645 3.14645C8.34171 2.95118 8.65829 2.95118 8.85355 3.14645L12.8536 7.14645C13.0488 7.34171 13.0488 7.65829 12.8536 7.85355L8.85355 11.8536C8.65829 12.0488 8.34171 12.0488 8.14645 11.8536C7.95118 11.6583 7.95118 11.3417 8.14645 11.1464L11.2929 8H2.5C2.22386 8 2 7.77614 2 7.5C2 7.22386 2.22386 7 2.5 7H11.2929L8.14645 3.85355C7.95118 3.65829 7.95118 3.34171 8.14645 3.14645Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                        </a>
                    
                    {{-- Tombol Aksi Terima/Tolak atau Tampilan Status --}}
                    @if ($report->report_status == 'Menunggu Verifikasi')
                        <button wire:click="updateReportStatus({{ $report->id }}, 'Diterima')" class="px-3 py-1 text-xs font-semibold text-white bg-green-600 rounded-md hover:bg-green-700 w-full sm:w-auto">
                            Terima
                        </button>
                        <button wire:click="confirmDelete({{ $report->id }})" class="px-3 py-1 text-xs font-semibold text-white bg-red-600 rounded-md hover:bg-red-700 w-full sm:w-auto">
                            Tolak
                        </button>
                    @else
                         <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $report->report_status == 'Diterima' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $report->report_status }}
                         </span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-10 bg-white rounded-lg shadow-md">
                <p class="text-gray-500">Tidak ada laporan yang cocok dengan filter.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $reports->links() }}
    </div>

    {{-- Konfirmasi Hapus --}}
    @if ($isConfirmingDelete)
        <div class="fixed z-30 inset-0 overflow-y-auto" aria-labelledby="modal-title-delete" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class=" transition-opacity" wire:click="closeConfirmModal()" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                     <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                         <div class="sm:flex sm:items-start">
                             <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                 <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                             </div>
                             <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                 <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title-delete">Tolak & Hapus Laporan</h3>
                                 <div class="mt-2">
                                     <p class="text-sm text-gray-500">Anda yakin ingin menolak dan menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.</p>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                         <button wire:click="deleteReport()" wire:loading.attr="disabled" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                            Ya, Hapus
                         </button>
                         <button wire:click="closeConfirmModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                            Batal
                         </button>
                     </div>
                </div>
            </div>
        </div>
    @endif
</div>




