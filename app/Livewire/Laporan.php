<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\GuestReport;
use Illuminate\Support\Facades\DB;

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
    public $photos = [];
    public $reportSubmitted = false;

    // 2. ATURAN VALIDASI JUGA DISESUAIKAN
    protected $rules = [
        'name' => 'required|string|max:100',
        'contact' => 'required|string|max:15',
        'location' => 'required|string|min:10',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'description' => 'required|string|min:10',
        'photos' => 'required|array|min:1',
        'photos.*' => 'image|max:5120',
    ];

    protected $messages = [
        'photos.required' => 'Anda wajib mengunggah setidaknya satu foto kejadian.',
        'photos.*.image' => 'File yang diunggah harus berupa gambar.',
        'photos.*.max' => 'Ukuran setiap foto tidak boleh lebih dari 5MB.',
        'photos.min' => 'Anda wajib mengunggah setidaknya satu foto kejadian.',
        'name.required' => 'Anda wajib mengisi nama.',
        'contact.required' => 'Anda wajib mengisi nomor kontak.',
        'location.required' => 'Anda wajib mengisi alamat.',
        'location.min' => 'Alamat minimal 10 karakter.',
        'description.required' => 'Anda wajib mengisi deskripsi kejadian.',
        'description.min' => 'Deskripsi kejadian minimal 10 karakter.',
        'photos.*.required' => 'Anda wajib mengunggah setidaknya satu foto kejadian.',
    ];

    public function submitReport()
    {
        $validatedData = $this->validate();
        
        DB::transaction(function () use ($validatedData) {
            $report = GuestReport::create([
            'name' => $validatedData['name'],
            'contact' => $validatedData['contact'],
            'location' => $validatedData['location'],
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
            'description' => $validatedData['description'],
            'report_status' => "Menunggu Verifikasi",
            'fire_status' => "Sedang Terjadi",
            ]);
            foreach ($this->photos as $photo) {
                $photoPath = $photo->store('fire-reports', 'public');
                $report->images()->create([
                    'path' => $photoPath
                ]);
            }
        });
        $this->reportSubmitted = true;
        $this->resetForm();
    }

    public function uploadPhotos()
    {
        $this->validate([
            'photos.*' => 'image|max:5120',
        ]);
    }

    private function resetForm()
    {
        $this->reset(['name', 'contact', 'location', 'latitude', 'longitude', 'description', 'photos']);
    }

    public function removePhoto($index)
    {
        $photos = $this->photos;
        array_splice($photos, $index, 1);
        $this->photos = $photos;
    }

    public function render()
    {
        return view('livewire.laporan')->layout('layout.user'); 
    }
}
