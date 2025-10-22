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

    protected $message = [
        'email.required' => 'Email harus diisi',
        'password.required' => 'Password harus diisi',
        'email.email' => 'Email tidak valid',
        'email.unique' => 'Email sudah terdaftar',
        'password.min' => 'Password minimal 8 karakter',
        'password.confirmed' => 'Password tidak cocok',
        'email.exists' => 'Email belum terdaftar',
        
    ];


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
