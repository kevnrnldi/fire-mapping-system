<div>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">Laporan Kebakaran Masuk</h1>

        @if (session()->has('message'))
            <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif

        {{-- Area Pencarian dan Filter --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="md:col-span-1">
                <input 
                    type="text" 
                    wire:model.debounce.300ms="search" 
                    placeholder="Cari nama, kontak, atau lokasi..."
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                >
            </div>
            <div>
                <select wire:model="filterReportStatus" class="block w-full rounded-lg ...">
                    <option value="">Filter Status Laporan...</option>
                    <option value="Baru">Menunggu Verifikasi</option>
                    <option value="Diterima">Diterima</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
            </div>
            <div>
                <select wire:model="filterFireStatus" class="block w-full rounded-lg ...">
                    <option value="">Filter Status Kebakaran...</option>
                    <option value="Menunggu Verifikasi">Sedang Terjadi</option>
                    <option value="Dalam Penanganan">Dalam Penanganan</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>
        </div>

        {{-- Daftar Laporan dalam Format Kartu --}}
        <div class="space-y-4">
            @forelse ($reports as $report)
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 sm:p-6 transition hover:shadow-lg">
                    <div class="flex flex-col sm:flex-row justify-between sm:items-start">
                        {{-- Info Utama --}}
                        <div class="flex-1">
                            <div class="flex items-center gap-4">
                                @if ($report->photo ?? $report->photo_path)
                                    <a href="{{ asset('storage/' . ($report->photo ?? $report->photo_path)) }}" target="_blank">
                                        <img src="{{ asset('storage/' . ($report->photo ?? $report->photo_path)) }}" alt="Foto Laporan" class="h-16 w-16 object-cover rounded-md flex-shrink-0">
                                    </a>
                                @endif
                                <div>
                                    <p class="font-semibold text-lg text-gray-900 dark:text-white">{{ $report->name ?? $report->reporter_name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $report->contact ?? $report->reporter_phone }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $report->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>

                            <div class="mt-4 space-y-2">
                                <p class="text-sm text-gray-700 dark:text-gray-300"><strong class="font-medium">Lokasi:</strong> {{ $report->location ?? $report->location_address }}</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300"><strong class="font-medium">Deskripsi:</strong> {{ Str::limit($report->description, 150) }}</p>
                            </div>
                        </div>

                        {{-- Aksi dan Status di Sebelah Kanan --}}
                        <div class="mt-4 sm:mt-0 sm:ml-6 flex-shrink-0">
                            {{-- Status Laporan (Verifikasi) --}}
                            <div class="flex items-center justify-end space-x-2">
                                @if ($report->report_status == 'Baru' || $report->report_status == 'Menunggu Verifikasi')
                                    <button wire:click="updateReportStatus({{ $report->id }}, 'Diterima')" class="px-3 py-1 text-xs font-semibold text-white bg-green-600 rounded-full hover:bg-green-700">Terima</button>
                                    <button wire:click="updateReportStatus({{ $report->id }}, 'Ditolak')" class="px-3 py-1 text-xs font-semibold text-white bg-red-600 rounded-full hover:bg-red-700">Tolak</button>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $report->report_status == 'Diterima' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $report->report_status }}
                                    </span>
                                @endif
                            </div>

                            {{-- Status Kebakaran (Penanganan) --}}
                            <div class="mt-3 text-right">
                                @if ($report->report_status == 'Diterima')
                                    <label class="text-xs font-medium text-gray-500">Status Kebakaran:</label>
                                    <select wire:change="updateFireStatus({{ $report->id }}, $event.target.value)" class="mt-1 w-full rounded-md text-xs border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="Dalam Penanganan" @if($report->fire_status == 'Dalam Penanganan') selected @endif>Dalam Penanganan</option>
                                        <option value="Selesai" @if($report->fire_status == 'Selesai') selected @endif>Selesai</option>
                                    </select>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">{{ $report->fire_status }}</span>
                                @endif
                            </div>
                            
                            {{-- Tombol Lihat di Peta --}}
                            @if ($report->latitude && $report->longitude)
                                <div class="mt-3 text-right">
                                    <a href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}" target="_blank" class="text-sm text-blue-600 hover:underline">
                                        Lihat di Peta →
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                    <p class="text-gray-500">Tidak ada laporan yang cocok dengan filter.</p>
                </div>
            @endforelse
        </div>

        {{-- Paginasi --}}
        <div class="mt-6">
            {{ $reports->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    let mapInstance = null; // Variabel untuk menyimpan instance peta

    // Dengarkan event dari backend
    document.addEventListener('livewire:initialized', () => {
        @this.on('showReportModal', (event) => {
            const lat = event.latitude;
            const lng = event.longitude;

            // Hancurkan instance peta sebelumnya jika ada, untuk mencegah duplikasi
            if (mapInstance) {
                mapInstance.remove();
            }

            // Inisialisasi peta di dalam modal
            mapInstance = L.map('report-map').setView([lat, lng], 16);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(mapInstance);

            L.marker([lat, lng]).addTo(mapInstance)
                .bindPopup('Lokasi yang dilaporkan.')
                .openPopup();
            
            // Perbaiki masalah render peta di dalam modal yang tersembunyi
            setTimeout(function() {
                mapInstance.invalidateSize();
            }, 100);
        });
    });
</script>
@endpush