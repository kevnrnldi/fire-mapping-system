<?php

namespace App\Livewire;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;
use App\Models\User;


class Akun extends Component
{
    use WithPagination;

    // Properti untuk Modal & Form
    public $isModalOpen = false;
    public $userId;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    // Properti untuk Konfirmasi Hapus
    public $isConfirmingDelete = false;
    public $userIdToDelete;

    // Properti untuk Pencarian
    public $search = '';

    // Aturan validasi
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->userId)],
            'password' => [$this->userId ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
        ];
    }

    // Reset halaman saat melakukan pencarian
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::where('id', '!=', Auth::id()) // Jangan tampilkan user yang sedang login
                     ->where(function ($query) {
                         $query->where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('email', 'like', '%' . $this->search . '%');
                     })
                     ->paginate(10);

        return view('livewire.akun', [
            'users' => $users,
        ])->layout('layout.mainLayout'); // Sesuaikan dengan file layout admin Anda
    }

    // --- FUNGSI MODAL ---
    private function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function openModal() { $this->isModalOpen = true; }
    public function closeModal() { $this->isModalOpen = false; }

    // --- CREATE ---
    public function create()
    {
        $this->resetForm();
        $this->openModal();
    }

    // --- UPDATE ---
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->openModal();
    }

    // --- FUNGSI PENYIMPANAN (CREATE & UPDATE) ---
    public function store()
    {
        $this->validate();
        
        $userData = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if (!empty($this->password)) {
            $userData['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->userId], $userData);

        session()->flash('message', $this->userId ? 'Akun Admin berhasil diperbarui.' : 'Akun Admin berhasil ditambahkan.');
        
        $this->closeModal();
        $this->resetForm();
    }

    // --- DELETE ---
    public function confirmDelete($id)
    {
        $this->userIdToDelete = $id;
        $this->isConfirmingDelete = true;
    }
    
    public function closeConfirmModal() { $this->isConfirmingDelete = false; }

    public function delete()
    {
        User::find($this->userIdToDelete)->delete();
        session()->flash('message', 'Akun Admin berhasil dihapus.');
        $this->closeConfirmModal();
    }
}
