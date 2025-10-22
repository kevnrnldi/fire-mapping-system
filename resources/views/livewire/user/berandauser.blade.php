

    
    <div class="container mx-auto px-4 py-2">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Peta Area Kebakaran</h2>
                <div id="map" wire:ignore class="w-full h-[70vh] rounded-md border border-gray-300 relative z-0"></div>
            </div> 

            <div class="lg:col-span-1 bg-white p-6 rounded-lg shadow-md">
                
            
                
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
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center text-sm">Belum ada data area tersimpan.</p>
                    @endforelse
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