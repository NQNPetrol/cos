<?php

namespace App\Livewire\Client;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    public $photo;

    public $state = [];

    public function mount()
    {
        // Precargar los datos del usuario actual
        $user = Auth::user();
        $this->state = [
            'name' => $user->name,
            'email' => $user->email,
        ];
    }

    public function updateProfileInformation()
    {
        $user = Auth::user();

        $this->validate([
            'photo' => ['nullable', 'image', 'max:1024'],
            'state.name' => ['required', 'string', 'max:255'],
            'state.email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ]);

        if (isset($this->photo)) {
            $user->updateProfilePhoto($this->photo);
        }

        $user->update([
            'name' => $this->state['name'],
            'email' => $this->state['email'],
        ]);

        if ($user->wasChanged('email')) {
            $user->forceFill([
                'email_verified_at' => null,
            ])->save();

            $user->sendEmailVerificationNotification();
        }

        $this->dispatch('saved');
    }

    public function deleteProfilePhoto()
    {
        Auth::user()->deleteProfilePhoto();
    }

    public function sendEmailVerification()
    {
        Auth::user()->sendEmailVerificationNotification();
        $this->verificationLinkSent = true;
    }

    public function render()
    {
        return view('client.profile.update-profile-information-form');
    }
}
