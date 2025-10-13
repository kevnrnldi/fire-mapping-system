<?php

namespace App\Livewire\Admin;
use Illuminate\Support\Facades\Hash; 
use Livewire\Component;

class ChangePassword extends Component
{

    // public bool $isPasswordUpdated = false;
    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPassword_confirmation = '';

    public function updatePassword(){
        $this->validate([
            'currentPassword' => ['required', 'string', 'current_password'],
            'newPassword' => ['required', 'string', 'min:8', 'confirmed'],
        ]);


        auth()->user()->update([
            'password' => Hash::make($this->newPassword)
        ]);

        // $this->isPasswordUpdated = true;

         $this->dispatch('password-updated');
        $this->reset();

        // session()->flash('success', 'Password berhasil diubah');

        // return $this->redirect(route('admin.change-password'));
    }

    public function render()
    {
        return view('livewire.admin.change-password')
        ->layout('layout.mainLayout');
    }
}
