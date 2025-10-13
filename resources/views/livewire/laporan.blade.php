<div class="max-w-xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
    @if ($reportSubmitted)
        <div class="text-center p-8">
            <h3 class="text-2xl font-bold text-green-600 dark:text-green-400">✅ Laporan Terkirim!</h3>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Terima kasih, laporan Anda telah kami terima.</p>
        </div>
    @else
        <form wire:submit.prevent="submitReport">
            <h2 class="text-2xl font-bold text-red-600 dark:text-red-400 mb-6">Laporan Kebakaran Darurat</h2>
            
            {{-- Bagian Peta --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tandai Lokasi di Peta <span class="text-red-500">*</span></label>
                <div id="map" wire:ignore class="mt-2 rounded-lg" style="height: 300px; z-index: 0;"></div>
                <button type="button" id="detect-location" class="mt-2 text-sm text-blue-600 hover:underline">Deteksi Lokasi Saya</button>
                @error('latitude') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            {{-- Hidden inputs untuk menyimpan koordinat --}}
            <input type="hidden" id="latitude" wire:model.defer="latitude">
            <input type="hidden" id="longitude" wire:model.defer="longitude">

            <div class="space-y-4 mt-4">
                {{-- Alamat terisi otomatis --}}
                <div>
                    <label for="location" class="block ...">Alamat Lengkap Kejadian <span class="text-red-500">*</span></label>
                    <textarea id="location" wire:model.defer="location" rows="3" class="mt-1 block w-full ..."></textarea>
                    @error('location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                {{-- Form lainnya (nama, kontak, dll) --}}
                <div>
                    <label for="name" class="block ...">Nama Anda <span class="text-red-500">*</span></label>
                    <input type="text" id="name" wire:model.defer="name" class="mt-1 block w-full ...">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="contact" class="block ...">No. Telepon Aktif <span class="text-red-500">*</span></label>
                    <input type="tel" id="contact" wire:model.defer="contact" class="mt-1 block w-full ...">
                    @error('contact') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="description" class="block ...">Deskripsi Kejadian <span class="text-red-500">*</span></label>
                    <textarea id="description" wire:model.defer="description" rows="4" class="mt-1 block w-full ..."></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="photo" class="block ...">Foto Kejadian (Opsional)</label>
                    <input type="file" id="photo" wire:model="photo" class="mt-1 block w-full ...">
                    @if ($photo) <img src="{{ $photo->temporaryUrl() }}" class="mt-2 h-40 w-auto rounded"> @endif
                    @error('photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full ...">
                    <span wire:loading.remove>Kirim Laporan Darurat</span>
                    <span wire:loading>Mengirim...</span>
                </button>
            </div>
        </form>
    @endif
</div>

{{-- Tambahkan script Leaflet & logic map DI BAGIAN PALING BAWAH file layout --}}
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('livewire:load', function () {
        // Koordinat default (Pekanbaru, Riau)
        const defaultLat = 0.5071;
        const defaultLng = 101.4478;

        const map = L.map('map').setView([defaultLat, defaultLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        // Fungsi untuk update Livewire & alamat
        function updateLocation(lat, lng) {
            // Set nilai properti Livewire
            @this.set('latitude', lat);
            @this.set('longitude', lng);

            // Panggil API reverse geocoding (Nominatim)
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data.display_name) {
                        // Update kolom alamat di form
                        @this.set('location', data.display_name);
                    }
                });
        }
        
        // Panggil saat marker digeser
        marker.on('dragend', function(e) {
            const latLng = e.target.getLatLng();
            updateLocation(latLng.lat, latLng.lng);
        });

        // Panggil saat peta diklik
        map.on('click', function(e) {
            const latLng = e.latlng;
            marker.setLatLng(latLng);
            updateLocation(latLng.lat, latLng.lng);
        });

        // Event listener untuk tombol deteksi lokasi
        document.getElementById('detect-location').addEventListener('click', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    map.setView([lat, lng], 16);
                    marker.setLatLng([lat, lng]);
                    updateLocation(lat, lng);
                }, function() {
                    alert('Tidak bisa mendapatkan lokasi. Pastikan Anda mengizinkan akses lokasi.');
                });
            } else {
                alert('Browser Anda tidak mendukung Geolocation.');
            }
        });
        
        // Inisialisasi lokasi saat pertama kali load
        updateLocation(defaultLat, defaultLng);
    });
</script>
@endpush