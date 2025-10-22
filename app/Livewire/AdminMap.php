<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\FireArea;

class AdminMap extends Component
{

    public $allAreas;
    public $latitude;
    public $longitude;
    public $alamat;
    public $jenisIkon = 'kebakaran'; 
    public $tanggalKejadian;

    public function mount(){
        $this->loadAreas();
    }

    public function loadAreas(){
        $this->allAreas = FireArea::all()->sortByDesc('created_at');
    }

    public function getAlamat($lat, $lon){
        $userAgent = config('app.name', 'Laravel') . ' - PetaKebakaran/1.0';
        $url = "https://nominatim.openstreetmap.org/reverse";

        try {
            $response = Http::withHeaders(['User-Agent' => $userAgent])
                ->get($url, [
                    'format' => 'json', 'lat' => $lat, 'lon' => $lon,
                    'accept-language' => 'id', 'addressdetails' => 1
                ]);

            if ($response->successful() && isset($response->json()['display_name'])) {
                $alamatLengkap = $response->json()['display_name'];
                $this->alamat = $alamatLengkap;

                // Kirim event untuk mengisi textarea di modal
                $this->dispatch('alamat-updated', ['alamat' => $alamatLengkap]);
            } else {
                $this->alamat = 'Alamat tidak ditemukan.';
                $this->dispatch('alamat-updated', ['alamat' => 'Alamat tidak ditemukan.']);
            }
        } catch (\Exception $e) {
            Log::error('Gagal ambil data Nominatim: ' . $e->getMessage());
            $this->alamat = 'Gagal mengambil alamat.';
            $this->dispatch('alamat-updated', ['alamat' => 'Gagal mengambil alamat. Periksa log.']);
        }
    }

    protected function rules()
    {
        $rules = [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'alamat' => 'required|string|max:255',
            'jenisIkon' => 'required|in:kebakaran,pos_pemadam',
            'tanggalKejadian' => $this->jenisIkon == 'kebakaran' ? 'required|date' : 'nullable',
        ];

        return $rules;
    }

    public function saveArea(){

        // dd($this->validate($this->rules()));
        $validatedData = $this->validate($this->rules());

        FireArea::create([
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'alamat' => $this->alamat,
            'jenis_ikon' => $this->jenisIkon,
            'tanggal_kejadian' => $this->jenisIkon == 'kebakaran' ? $this->tanggalKejadian : null,
        ]);

        $this->loadAreas(); 
        $this->resetForm(); 
        $this->dispatch('refresh-map'); 
        $this->dispatch('close-modal-event'); 

        return redirect(request()->header('Referer'));
    }

    public function deleteArea($id)
    {
        $area = FireArea::find($id);
        if ($area) {
            $area->delete();
        }

        $this->loadAreas(); // 
        $this->dispatch('refresh-map');
    }

    public function resetForm()
    {
        $this->reset(['latitude', 'longitude', 'alamat', 'tanggalKejadian', 'jenisIkon']);
        $this->jenisIkon = 'kebakaran';
        $this->resetErrorBag();
    }

    public function render()
    {
        $allAreas = FireArea::all();
        // Menggunakan layout utama 'layouts.app'
        return view('livewire.admin-map', compact('allAreas'))
                ->layout('layout.mainLayout');
    }
    
}
