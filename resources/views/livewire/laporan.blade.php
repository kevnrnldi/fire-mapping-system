<div class="max-w-5xl mx-auto p-6 bg-teal-50  rounded-lg shadow-md border border-red-600">
    @if ($reportSubmitted)
        <div class="text-center p-8">
            <h3 class="text-2xl font-bold text-green-600 ">✅ Laporan Terkirim!</h3>
            <p class="text-gray-600  mt-2">Terima kasih, laporan Anda telah kami terima dan akan segera ditindaklanjuti.</p>
        </div>
    @else
        <form wire:submit.prevent="submitReport">


            {{-- Notif Error --}}
            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-100 border border-red-400 p-4">
                    {{-- ... Tampilan notifikasi gagal ... --}}
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <h2 class="text-2xl font-bold text-red-600  mb-6">Laporan Kebakaran</h2>
            
            {{-- Bagian Peta --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 ">Tandai Lokasi di Peta <span class="text-red-500">*</span></label>
                <div id="map" wire:ignore class="mt-2 border border-red-600 rounded-lg shadow-sm " style="height: 300px; z-index: 0;"></div>
                <button type="button" id="detect-location" class="mt-2 text-sm  text-blue-600 hover:underline focus:outline-none">Deteksi Lokasi Saya</button>
                @error('latitude') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>



            <div class="space-y-4">
                {{-- Alamat terisi otomatis --}}
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 ">Alamat Lengkap Kejadian <span class="text-red-500">*</span></label>
                    <textarea id="location" wire:model.defer="location" rows="2" class="mt-1  block w-full rounded-md p-3 shadow-sm border border-red-600  focus:border-red-500 focus:ring-red-500 " placeholder="Masukkan Alamat"></textarea>
                    @error('location') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Nama Pelapor --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 ">Nama Anda<span class="text-red-500">*</span></label>
                    <input type="text" id="name" wire:model.defer="name" class="mt-1 p-3 block w-full rounded-md  border border-red-600 shadow-sm focus:border-red-500 focus:ring-red-500 " placeholder="Masukkan nama lengkap Anda">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Kontak Pelapor --}}
                <div>
                    <label for="contact" class="block text-sm font-medium text-gray-700 ">No. Telepon Aktif <span class="text-red-500">*</span></label>
                    <input type="tel" id="contact" wire:model.defer="contact" class="mt-1 p-3 block w-full rounded-md border border-red-600  shadow-sm focus:border-red-500 focus:ring-red-500 " placeholder="Contoh: 081234567890">
                    @error('contact') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Deskripsi Kejadian --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 ">Deskripsi Kejadian <span class="text-red-500">*</span></label>
                    <textarea id="description" wire:model.defer="description" rows="4" class="mt-1 p-3 block w-full rounded-md border border-red-600 shadow-sm focus:border-red-500 focus:ring-red-500 " placeholder="Jelaskan secara singkat apa yang terjadi"></textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                {{-- Upload Foto (Multiple) --}}
               <div>
                <label for="photos" class="block text-sm font-medium text-gray-700 ">Foto Kejadian (Bisa lebih dari satu)</label>
                <input 
                    type="file" 
                    id="photos" 
                    wire:model="photos" 
                    multiple 
                    accept="image/*"  
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0  file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                <div wire:loading wire:target="photos" class="mt-2 text-sm text-gray-500">Mengunggah foto...</div>

                @if ($photos)
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4">
                            {{-- Loop melalui setiap foto yang dipilih --}}
                            @foreach ($photos as $index => $photo)
                                <div class="relative">
                                    {{-- Pratinjau gambar --}}
                                    <img src="{{ $photo->temporaryUrl() }}" class="h-40 w-full object-cover rounded-lg shadow">
                                    
                                    {{-- Tombol Hapus (Tanda Silang) --}}
                                    <button 
                                        type="button" 
                                        wire:click.prevent="removePhoto({{ $index }})" 
                                        class="absolute top-2 right-2 bg-red-600 text-white rounded-full h-6 w-6 flex items-center justify-center hover:bg-red-700 focus:outline-none transition-opacity duration-200 opacity-75 hover:opacity-100">
                                        {{-- SVG untuk ikon 'X' --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    @error('photos.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full bg-red-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-300 ease-in-out flex items-center justify-center">
                    <span wire:loading.remove wire:target="submitReport">Kirim Laporan Darurat</span>
                    <span wire:loading wire:target="submitReport">
                        <svg class="animate-spin  ml-7 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Mengirim...
                    </span>
                </button>
            </div>
        </form>
    @endif
</div>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('livewire:initialized', function () {
        // Cek jika peta sudah ada untuk menghindari inisialisasi ganda
        if (document.getElementById('map')._leaflet_id) {
            return;
        }

        const defaultLat = -3.79; 
        const defaultLng = 102.26;

        const map = L.map('map').setView([defaultLat, defaultLng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        let circle = L.circle([defaultLat, defaultLng], {
            color: 'red',
            draggable:true,
            radius: 100,
            fillColor: '#f03',
            fillOpacity: 0.3
    }).addTo(map);

        function updateLocation(lat, lng) {
            @this.set('latitude', lat);
            @this.set('longitude', lng);
            
            // Beri feedback visual bahwa alamat sedang dicari
            const locationTextarea = document.getElementById('location');
            if(locationTextarea) 
            {
                locationTextarea.value = 'Mencari alamat...';
            }

            fetch(`/ambil-alamat?lat=${lat}&lon=${lng}`)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errData => {
                            throw new Error(errData.message || `HTTP error! status: ${response.status}`);
                        }).catch(error => {
                            throw new Error(`Gagal mengambil data: Status ${response.status}.`);    
                        });
                    }     
                    return response.json();                    
                     
                })
                .then(data => {
                    if(data.error) {
                        @this.set('location', data.error);
                    } else if (data && data.display_name) {
                        @this.set('location', data.display_name);
                    } else {
                        @this.set('location', 'Alamat tidak ditemukan.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching address:', error);
                    @this.set('location', 'Gagal mengambil data alamat. Periksa koneksi internet Anda.');
                });
        }
        
        circle.on('dragend', e => updateLocation(e.target.getLatLng().lat, e.target.getLatLng().lng));
        map.on('click', e => {
            circle.setLatLng(e.latlng);
            updateLocation(e.latlng.lat, e.latlng.lng);
        });

        document.getElementById('detect-location').addEventListener('click', () => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map.setView([lat, lng], 16);
                    circle.setLatLng([lat, lng]);
                    updateLocation(lat, lng);
                }, () => {
                    alert('Tidak bisa mendapatkan lokasi. Pastikan Anda mengizinkan akses lokasi di browser.');
                });
            } else {
                alert('Browser Anda tidak mendukung Geolocation.');
            }
        });
        
        // Inisialisasi alamat saat pertama kali halaman dimuat
        updateLocation(defaultLat, defaultLng);
    });
</script>
@endpush