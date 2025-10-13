<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\GuestReport;

class Laporan extends Component
{
    use WithFileUploads;

   // 1. NAMA PROPERTI DISESUAIKAN DENGAN DATABASE
    public $name;
    public $contact;
    public $location;
    public $latitude;
    public $longitude;
    public $description;
    public $photo;
    public $reportSubmitted = false;

    // 2. ATURAN VALIDASI JUGA DISESUAIKAN
    protected $rules = [
        'name' => 'required|string|max:100',
        'contact' => 'required|string|max:15',
        'location' => 'required|string|min:10',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'description' => 'required|string|min:10',
        'photo' => 'nullable|image|max:5120',
    ];

    public function submitReport()
    {
        $this->validate();
        $photoPath = null;
        
        if ($this->photo) {
            $photoPath = $this->photo->store('fire-reports', 'public');
        }

        // 3. KUNCI ARRAY DI SINI DISESUAIKAN DENGAN NAMA KOLOM DATABASE
        GuestReport::create([
            'name' => $this->name,
            'contact' => $this->contact,
            'location' => $this->location,
            'latitude' => $this->latitude,  
            'longitude' => $this->longitude,
            'description' => $this->description,
            'photo' => $photoPath,
            'report_status' => "Menunggu Verifikasi",
            'fire_status' => "Sedang Terjadi",
        ]);

        $this->reportSubmitted = true;
    }

    public function render()
    {
        return view('livewire.laporan')->layout('layout.user'); 
    }
}
