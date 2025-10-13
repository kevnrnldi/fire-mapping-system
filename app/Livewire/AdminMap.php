<?php

namespace App\Livewire;

use Livewire\Component;

class AdminMap extends Component
{

    public function render()
    {
        // Menggunakan layout utama 'layouts.app'
        return view('livewire.admin-map')
                ->layout('layout.mainLayout');
    }
    
}
