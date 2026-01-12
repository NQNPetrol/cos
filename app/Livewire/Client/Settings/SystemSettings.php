<?php

namespace App\Livewire\Client\Settings;

use Livewire\Component;

class SystemSettings extends Component
{
    public string $supportEmail = 'cos@cyhsur.com';

    public function render()
    {
        return view('livewire.client.settings.system-settings')
            ->layout('layouts.cliente');
    }
}

