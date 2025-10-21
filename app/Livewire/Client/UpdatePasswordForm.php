<?php

namespace App\Livewire\Client;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class UpdatePasswordForm extends Component
{
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);

        $this->dispatch('saved');
    }

    public function render()
    {
        return view('client.profile.update-password-form');
    }
}