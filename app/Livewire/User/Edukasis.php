<?php

namespace App\Livewire\User;
use Livewire\WithPagination;
use Livewire\Component;
use App\Models\EducationArticle;
class Edukasis extends Component
{
        use WithPagination;
        public $search = '';
     public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Menggunakan `when()` untuk query yang lebih bersih
        $articles = EducationArticle::query()
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('title', 'like', '%' . $this->search . '%')
                            ->orWhere('content', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(9); // Sesuaikan jumlah item per halaman

        return view('livewire.user.edukasis', [
            'articles' => $articles,
        ])->layout('layout.user');
    }
}
