
<div 
    x-data="{ showModal: false, showDeleteModal: false, deleteId: null }" 
    @close-modal-event.window="showModal = false; removeNewMarker();">
    
    <div class="container mx-auto px-4 py-2">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Peta Area <span class="text-yellow-500">Kebakaran</span></h2>
                <div id="map" wire:ignore class="w-full h-[70vh] rounded-md border border-gray-300 relative z-0"></div>
            </div> 

            <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-md">
                
                <button 
                    @click="showModal = true; $nextTick(() => { initModalMap(); })" type="button" 
                    class="w-full mb-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600
                     hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    + Tambah Data Area Baru
                </button>
                
                <h2 class="text-2xl font-bold text-gray-800 mb-4 border-t pt-4">Informasi Area</h2>
                
                <div class="h-[60vh] overflow-y-auto space-y-3">
                    @forelse ($allAreas as $area)
                        <div class="p-3 border rounded-md shadow-sm">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-sm">
                                        @if ($area->jenis_ikon == 'kebakaran')
                                            <span>🔥 Area Kebakaran</span>
                                        @else
                                            <span>🚒 Pos Pemadam</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-600 mt-1">{{ $area->alamat }}</p>
                                    @if ($area->tanggal_kejadian)
                                        <p class="text-xs text-gray-500 mt-1">Tgl: {{ $area->tanggal_kejadian->format('d M Y') }}</p>
                                    @endif
                                </div>
                                    <button 
                                   @click="deleteId = {{ $area->id }}; showDeleteModal = true;"
                                    wire:confirm="Anda yakin ingin menghapus area ini?"
                                    type="button" 
                                    class="text-gray-400 hover:text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center text-sm">Belum ada data area tersimpan.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

 
    <div x-show="showModal" class="fixed inset-0 z-40 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-transparent bg-opacity-50"></div>

            <div 
                @click.away="showModal = false; $wire.resetForm(); removeNewMarker();"
                x-show="showModal" 
                x-transition 
                class="bg-white rounded-lg shadow-xl overflow-hidden max-w-4xl w-full z-50">
                
                <div class="flex justify-between items-center p-4 border-b bg-red-600">
                    <h3 class="text-lg font-bold text-white ">Buat Data<span class="text-yellow-500">Area Kebakaran</span> </h3>
                    <button @click="showModal = false; $wire.resetForm(); removeNewMarker();" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>

                <form wire:submit.prevent="saveArea">
                    
                    {{-- Blok untuk menampilkan error validasi --}}
                    @if ($errors->any())
                        <div class="p-4 m-6 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <p class="font-bold">Terjadi Kesalahan Validasi:</p>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Peta diletakkan di dalam form --}}
                    <div class="p-4">
                        <p class="text-sm text-gray-700 mb-2">Klik di peta di bawah ini untuk memilih lokasi:</p>
                        <div id="modal-map" wire:ignore class="w-full h-[400px] rounded shadow-lg shadow-gray-300  z-50"></div>
                    </div>

                    {{-- Form input diletakkan di bawah peta --}}
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="jenisIkon" class="block text-sm font-medium text-gray-700">Tipe Area</label>
                            <select id="jenisIkon" wire:model.live="jenisIkon" class="mt-1 block w-full rounded-md border-gray-300 p-3 shadow-lg ring-1 ring-gray-200 sm:text-sm">
                                <option value="kebakaran">Area Kebakaran</option>
                                <option value="pos_pemadam">Pos Pemadam</option>
                            </select>
                        </div>
                        
                        @if ($jenisIkon == 'kebakaran')
                            <div>
                                <label for="tanggalKejadian" class="block text-sm font-medium text-gray-700">Tanggal Kejadian</label>
                                <input type="date" id="tanggalKejadian" wire:model.defer="tanggalKejadian" class="mt-1 block w-full p-3 rounded-lg ring-1 ring-gray-200 shadow-lg border-gray-300  sm:text-sm">
                                @error('tanggalKejadian') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        @else
                            <div></div>
                        @endif

                        <div class="md:col-span-2">
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat / Lokasi Laporan</label>
                            <textarea id="alamat" wire:model.live="alamat" rows="2" class="mt-1 block w-full  border-gray-300 p-3  ring-1 ring-gray-200 shadow-lg  sm:text-sm" placeholder="Otomatis terisi saat klik peta..."></textarea>
                            @error('alamat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Koordinat (Otomatis)</label>
                            <input type="text" value="{{ $latitude }}, {{ $longitude }}" class="mt-1 block w-full p-3 rounded-lg ring-1 ring-gray-200 shadow-lg border-gray-300 sm:text-sm bg-gray-100" readonly>
                            @error('latitude') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    

                    {{-- Footer Modal --}}
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan
                        </button>
                        <button 
                            @click="showModal = false; $wire.resetForm(); removeNewMarker();" 
                            type="button" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-200 sm:mt-0 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div x-show="showDeleteModal" class="fixed inset-0 z-40 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen p-4">
        
        <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 bg-transparent bg-opacity-50"></div>

        <div 
            @click.away="showDeleteModal = false; deleteId = null;"
            x-show="showDeleteModal" 
            x-transition 
            class="bg-white rounded-lg shadow-xl overflow-hidden max-w-sm w-full z-50">
            
            <div class="p-6">
                <div class="flex items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-4 text-left">
                        <h3 class="text-lg font-medium text-gray-900">Konfirmasi Hapus</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Anda yakin ingin menghapus data area ini? Tindakan ini tidak dapat dibatalkan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button 
                    @click="$wire.deleteArea(deleteId); showDeleteModal = false;"
                    type="button" 
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                    Hapus
                </button>
                <button 
                    @click="showDeleteModal = false; deleteId = null;"
                    type="button" 
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
</div> 


@push('scripts')
<script>
    // --- Variabel Global ---
    let map; // Peta utama (read-only)
    let modalMap; // Peta di dalam modal (interactive)
    let newMarker = null; // Marker sementara untuk input di modal
    let existingMarkersLayer = null; // Layer untuk data dari DB di peta utama

   
    var iconKebakaran = L.icon({
        iconUrl: '{{ asset('assets/img/fire.png') }}', 
        iconSize: [32, 32], iconAnchor: [16, 32], popupAnchor: [0, -32]
    });
    var iconPosPemadam = L.icon({
        iconUrl: '{{ asset('assets/img/fire-station.png') }}', 
        iconSize: [32, 32], iconAnchor: [16, 32], popupAnchor: [0, -32]
    });


    function loadExistingMarkers() {
        console.log("DEBUG: loadExistingMarkers() dipanggil."); // <-- Log 1
        
        if (!map) {
            console.error("DEBUG: Peta utama ('map') belum siap saat loadExistingMarkers dipanggil."); // <-- Log 2
            return; 
        }

        if (existingMarkersLayer) {
            map.removeLayer(existingMarkersLayer);
        }
        existingMarkersLayer = L.layerGroup().addTo(map);

        const existingAreas = @json($allAreas); 
        

        Object.values(existingAreas).forEach(area => {
            console.log("DEBUG: area:", area);
            let icon = (area.jenis_ikon === 'kebakaran') ? iconKebakaran : iconPosPemadam;
            let popupContent = `<b>${area.jenis_ikon === 'kebakaran' ? 'Area Kebakaran' : 'Pos Pemadam'}</b><br>${area.alamat}`;
            
            // Tambahkan format tanggal jika ada
            if (area.jenis_ikon === 'kebakaran' && area.tanggal_kejadian) {
                const tgl = new Date(area.tanggal_kejadian).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                popupContent += `<br>Tgl: ${tgl}`;
            }

            L.marker([area.latitude, area.longitude], { icon: icon })
             .bindPopup(popupContent)
             .addTo(existingMarkersLayer);
        });
    }


    function removeNewMarker() {
        if (modalMap && newMarker) {
            modalMap.removeLayer(newMarker);
            newMarker = null;
        }
    }

 
    function initModalMap() {
        const modalMapElement = document.getElementById('modal-map');
        if (!modalMapElement) return;

        // 1. Inisialisasi peta HANYA SEKALI
        if (!modalMapElement._leaflet_id) { 
            modalMap = L.map('modal-map').setView([-3.79, 102.26], 13);
            
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19, attribution: '&copy; OpenStreetMap'
            }).addTo(modalMap);

            // 2. Tambahkan listener klik HANYA ke peta modal
            modalMap.on('click', function(e) {
                const { lat, lng } = e.latlng;
                
                if (newMarker) {
                    newMarker.setLatLng(e.latlng); // Pindahkan
                } else {
                    // Buat marker baru
                    newMarker = L.marker(e.latlng, {
                        draggable: true,
                        icon: L.icon({ iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png', iconSize: [25, 41], iconAnchor: [12, 41] })
                    }).addTo(modalMap);

                    // Tambahkan event drag
                    newMarker.on('dragend', function(e) {
                        const { lat, lng } = e.target.getLatLng();
                        @this.set('latitude', lat);
                        @this.set('longitude', lng); // <-- PERBAIKAN BUG
                        @this.call('getAlamat', lat, lng);
                    });
                }
                
                // Panggil Livewire untuk set lat/lng dan alamat
                @this.set('latitude', lat);
                @this.set('longitude', lng); // <-- PERBAIKAN BUG
                @this.call('getAlamat', lat, lng);
            });
        }


        setTimeout(() => {
            if (modalMap) {
                modalMap.invalidateSize();
            }
        }, 100); 
    }



    document.addEventListener('alamat-updated', event => {
        const alamatTextarea = document.getElementById('alamat');
        if (alamatTextarea) {
            alamatTextarea.value = event.detail.alamat;
        }
    });

    // Dipanggil Livewire saat 'saveArea' atau 'deleteArea' selesai
    Livewire.on('refresh-map', () => {
        removeNewMarker();      // Hapus marker biru dari peta modal (jika ada)
        loadExistingMarkers();  // Muat ulang semua marker di peta utama
    });


    document.addEventListener('livewire:initialized', () => {
        
        const mapElement = document.getElementById('map'); 
        if (!mapElement || mapElement._leaflet_id) {
            return; 
        }

        // 1. Inisialisasi Peta Utama
        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' });
        var stadia = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_satellite/{z}/{x}/{y}{r}.{ext}', { maxZoom: 20, attribution: '&copy; Stadia Maps', ext: 'jpg' });

        map = L.map('map', {
            layers: [osm],
            center: [-3.79, 102.26],
            zoom: 13
        });

        var baseMaps = {
            "OpenStreetMap": osm,
            "Satelit (Stadia)": stadia
        };
        L.control.layers(baseMaps).addTo(map);

        // 2. Muat marker yang ada
        loadExistingMarkers();

        // 3. Solusi Sidebar (hanya untuk peta utama)
        const observer = new ResizeObserver(() => map.invalidateSize());
        observer.observe(mapElement);

        const hamburgerButton = document.getElementById('hamburger-button');
        const backdrop = document.getElementById('sidebar-backdrop');
        
        const fixMainMapSize = () => {
            setTimeout(() => map.invalidateSize(), 400); 
        };

        if(hamburgerButton) hamburgerButton.addEventListener('click', fixMainMapSize);
        if(backdrop) backdrop.addEventListener('click', fixMainMapSize);
        
        setTimeout(() => map.invalidateSize(), 400);
    });
</script>
@endpush