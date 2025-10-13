<?php

namespace App\Livewire\Auth;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected function rules(){
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function login(){
        $credentials = $this->validate();
        if(Auth::attempt($credentials, $this->remember)){
            request()->session()->regenerate();
            return $this->redirect(route('admin.dashboard'));
        }
        $this->addError('email', 'Email atau password salah');
    }
    public function render()
    {
        return view('livewire.auth.login')
        ->layout('layout.login', ['title' => 'Login']);
    }
}
