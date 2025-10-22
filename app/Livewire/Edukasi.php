<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EducationArticle;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;

class Edukasi extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $isModalOpen = false;
    public $id;
    public $title;
    public $content;
    public $image; 
    public $currentImage; // Untuk menyimpan path foto yang sudah ada
    public $video;

    public $isConfirmingDelete = false;
    public $articleIdToDelete;

    public $search = '';

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|max:2048', 
            'video' => ['nullable', 'string', 'max:255', 'url', function ($attribute, $value, $fail) {
                if ($value && !preg_match('/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+$/', $value)) {
                    $fail('URL video harus dari YouTube.');
                }
            }],
        ];
    }

    protected $messages = [
      'title.required' => 'Judul artikel harus diisi.',
      'content.required' => 'Isi artikel harus diisi.',
      'image.required' => 'Foto artikel harus diunggah.',
      'image.image' => 'File yang diunggah bukan gambar.',
      'image.max' => 'Ukuran gambar maksimal 2MB.',
      'title.max' => 'Judul artikel tidak boleh lebih dari 255 karakter.',
      'content.max' => 'Isi artikel tidak boleh lebih dari 255 karakter.',
    ];


    public function updatingSearch() { $this->resetPage(); }

    public function render()
    {
        $articles = EducationArticle::where('title', 'like', '%'.$this->search.'%')
                                    ->orWhere('content', 'like', '%'.$this->search.'%')
                                    ->latest()
                                    ->paginate(10);

        return view('livewire.Edukasi', [
            'articles' => $articles,
        ])->layout('layout.mainLayout'); // Sesuaikan dengan layout admin Anda
    }

    private function resetForm()
    {
        $this->id = null;
        $this->title = '';
        $this->content = '';
        $this->image = null;
        $this->currentImage = null;
        $this->video = '';
    }

    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }

    // --- CREATE ---
    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    // --- EDIT ---
    public function edit($id)
    {
        $article = EducationArticle::findOrFail($id);
        $this->id = $id;
        $this->title = $article->title;
        $this->content = $article->content;
        $this->currentImage = $article->image; 
        $this->video = $article->video;
        $this->image = null; 
        $this->openModal();
    }

    // --- SIMPAN (CREATE & UPDATE) ---
    public function store()
    {
        $this->validate();

        $imagePath = $this->currentImage; // Default ke gambar lama

        if ($this->image) {
            // Hapus gambar lama jika ada dan ada gambar baru diupload
            if ($this->currentImage) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($this->currentImage);
            }
            $imagePath = $this->image->store('education-images', 'public');
        }

        EducationArticle::updateOrCreate(['id' => $this->id], [
            'title' => $this->title,
            'content' => $this->content,
            'image' => $imagePath,
            'video' => $this->video,
        ]);

        session()->flash('success', $this->id ? 'Artikel berhasil diperbarui.' : 'Artikel berhasil ditambahkan.');
        
        $this->closeModal();
        $this->resetForm();
    }

    // --- DELETE ---
    public function confirmDelete($id)
    {
        $this->articleIdToDelete = $id;
        $this->isConfirmingDelete = true;
    }
    
    public function closeConfirmModal() { $this->isConfirmingDelete = false; }

    public function delete()
    {
        $article = EducationArticle::findOrFail($this->articleIdToDelete);
        if ($article->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($article->image);
        }
        $article->delete();
        session()->flash('error', 'Artikel berhasil dihapus.');
        $this->closeConfirmModal();
    }

    // Mengambil ID video YouTube dari URL
    public function getYoutubeId($url)
    {
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $match)) {
            return $match[1];
        }
        return null;
    }
}