<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

#[Title('Вход в систему')]
class Login extends Component
{
    #[Validate('required|email')]
    public $email = '';

    #[Validate('required|min:6')]
    public $password = '';

    public $remember = false;

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        $this->addError('email', 'Неверный email или пароль.');
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}
