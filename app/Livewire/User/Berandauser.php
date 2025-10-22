<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FireArea;

class Berandauser extends Component
{





    public function render()
    {
        return view('livewire.user.berandauser', [
            'allAreas' => FireArea::all(),
        ])
        ->layout('layout.user');
    }
}
