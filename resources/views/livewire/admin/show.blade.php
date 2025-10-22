@extends('layout.mainLayout') 

@section('content')
<div class="container mx-auto size-auto px-4 sm:px-6 lg:px-8 py-3">
    
    {{-- Tombol Kembali --}}
    <div class="mb-4">
        <a href="{{ route('admin.laporan') }}" {{-- Pastikan nama route list laporan benar --}}
           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
           ← Kembali ke Daftar Laporan
        </a>
    </div>

    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 bg-red-600">
            <h3 class="text-lg leading-6 font-medium text-white">
                Detail Laporan #{{ $report->id }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-300">
                Informasi lengkap mengenai laporan kebakaran yang masuk.
            </p>
        </div>
        <div class="px-4 py-5 sm:p-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                {{-- Detail Pelapor --}}
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Pelapor</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $report->name ?? '-' }}</dd> {{-- Tambah fallback --}}
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Kontak</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $report->contact ?? '-' }}</dd> {{-- Tambah fallback --}}
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Waktu Lapor</dt>
                   <dd class="mt-1 text-sm text-gray-900">
                       {{ $report->created_at?->format('d M Y, H:i') ?? '-' }} {{-- Pastikan null check --}}
                   </dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Status Laporan</dt>
                    <dd class="mt-1 text-sm">
                         <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $report->report_status == 'Diterima' ? 'bg-green-100 text-green-800' : ($report->report_status == 'Ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $report->report_status ?? 'N/A' }} {{-- Tambah fallback --}}
                         </span>
                    </dd>
                </div>

                {{-- Detail Lokasi --}}
                 <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Lokasi</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $report->location ?? '-' }}</dd> {{-- Tambah fallback --}}
                </div>
                 @if($report->latitude && $report->longitude)
                 <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Koordinat</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $report->latitude }}, {{ $report->longitude }}
                        <a href="https://www.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}" target="_blank" class="text-blue-600 hover:underline ml-2">(Lihat di Google Maps)</a> {{-- Perbaiki Link Google Maps --}}
                    </dd>
                     <div id="map" wire:ignore class="mt-2 border border-red-600 rounded-lg shadow-sm " style="height: 300px; z-index: 0;"></div>
                </div>
                @endif

                {{-- Deskripsi --}}
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Deskripsi Kejadian</dt>
                    <dd class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded border border-gray-200">{{ $report->description ?? '-' }}</dd> {{-- Tambah fallback --}}
                </div>

                {{-- Status Kebakaran --}}
               <div class="sm:col-span-2">
                     <dt class="text-sm font-medium text-gray-500">Status Kebakaran</dt>
                     <dd class="mt-1 text-sm text-gray-900">
                         @if($report->report_status == 'Diterima')
                            {{-- Tampilkan status saat ini --}}
                             <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ $report->fire_status == 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $report->fire_status }}
                             </span>

                             {{-- Tampilkan tombol "Tandai Selesai" jika belum Selesai --}}
                             @if ($report->fire_status == 'Dalam Penanganan')
                                 <form action="{{ route('admin.laporan.updateFireStatus', $report->id) }}" method="POST" class="inline-block ml-3">
                                     @csrf
                                     @method('PATCH')
                                     <input type="hidden" name="fire_status" value="Selesai">
                                     <button type="submit" 
                                             onclick="return confirm('Anda yakin ingin menandai laporan ini sebagai Selesai?')"
                                             class="  group relative inline-flex py-2 items-center justify-center overflow-hidden rounded-md border border-neutral-200   [box-shadow:0px_4px_1px_#a3a3a3] active:translate-y-[2px] active:shadow-none px-3   shadow-sm text-xs font-medium text-white  bg-green-600 hover:bg-green-700 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 ">
                                             Tandai Selesai
                                     </button>
                                 </form>
                             @endif
                         @else
                            {{-- Tampilkan status jika laporan belum/tidak diterima --}}
                            {{ $report->fire_status ?? 'N/A' }}
                         @endif
                     </dd>
                </div>


                @if ($report->images->isNotEmpty())
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Foto Kejadian</dt>
                    <dd class="mt-1 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4"> 
                        @foreach ($report->images as $image) {{-- Loop melalui setiap gambar --}}
                            <a href="{{ asset('storage/' . $image->path) }}" {{-- Akses path dari objek $image --}}
                                target="_blank" 
                                class="block border border-gray-200 rounded-md overflow-hidden hover:opacity-80 transition">
                                <img src="{{ asset('storage/' . $image->path) }}" {{-- Akses path dari objek $image --}}
                                    alt="Foto Laporan" 
                                    class="w-full h-auto object-cover"> {{-- Sesuaikan tinggi jika perlu --}}
                            </a>
                        @endforeach
                    </dd>
                </div>
                @endif

            </dl>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const reportLat = {{ $report->latitude }};
            const reportlng = {{ $report->longitude }};

            var map = L.map('map').setView([reportLat, reportlng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            L.marker([reportLat, reportlng]).addTo(map)
                .bindPopup(`<b>Lokasi Laporan</b><br>{{ Str::limit($report->location, 100) }}`)
                .openPopup();
        })
        
       
    </script>
@endpush
    

@endsection