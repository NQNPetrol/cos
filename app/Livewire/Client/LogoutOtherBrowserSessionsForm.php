<?php

namespace App\Livewire\Client;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Agent;
use Livewire\Component;

class LogoutOtherBrowserSessionsForm extends Component
{
    public $sessions = [];

    public $password = '';

    public $confirmingLogout = false;

    public function mount()
    {
        $this->loadSessions();
    }

    public function loadSessions()
    {
        if (config('session.driver') !== 'database') {
            $this->sessions = collect([]);

            return;
        }

        $this->sessions = collect(
            DB::connection(config('session.connection'))
                ->table(config('session.table', 'sessions'))
                ->where('user_id', Auth::user()->getAuthIdentifier())
                ->orderBy('last_activity', 'desc')
                ->get()
        )->map(function ($session) {
            $agent = $this->createAgent($session);

            return (object) [
                'agent' => [
                    'is_desktop' => $agent->isDesktop(),
                    'platform' => $agent->platform(),
                    'browser' => $agent->browser(),
                ],
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });
    }

    protected function createAgent($session)
    {
        return tap(new Agent, function ($agent) use ($session) {
            $agent->setUserAgent($session->user_agent);
        });
    }

    public function confirmLogout()
    {
        $this->password = '';
        $this->dispatch('confirming-logout-other-browser-sessions');
        $this->confirmingLogout = true;
    }

    public function logoutOtherBrowserSessions()
    {
        if (! Hash::check($this->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('Esta contraseña no coincide con nuestros registros.')],
            ]);
        }

        Auth::logoutOtherDevices($this->password);
        $this->deleteOtherSessionRecords();

        $this->confirmingLogout = false;
        $this->loadSessions();
        $this->dispatch('loggedOut');
    }

    protected function deleteOtherSessionRecords()
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config('session.connection'))
            ->table(config('session.table', 'sessions'))
            ->where('user_id', Auth::user()->getAuthIdentifier())
            ->where('id', '!=', session()->getId())
            ->delete();
    }

    public function render()
    {
        return view('client.profile.logout-other-browser-sessions-form', [
            'sessions' => $this->sessions,
        ]);
    }
}
