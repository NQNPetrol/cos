<?php

namespace App\Livewire\Client\Settings;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserProfile extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public $photo;
    
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';
    
    public string $delete_password = '';
    
    public $sessions = [];

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->loadSessions();
    }

    public function loadSessions(): void
    {
        if (config('session.driver') === 'database') {
            $this->sessions = DB::connection(config('session.connection'))
                ->table(config('session.table', 'sessions'))
                ->where('user_id', Auth::id())
                ->orderBy('last_activity', 'desc')
                ->get()
                ->map(function ($session) {
                    $agent = $this->createAgent($session);
                    return (object) [
                        'id' => $session->id,
                        'agent' => [
                            'is_desktop' => $agent['is_desktop'],
                            'platform' => $agent['platform'],
                            'browser' => $agent['browser'],
                        ],
                        'ip_address' => $session->ip_address,
                        'is_current_device' => $session->id === Session::getId(),
                        'last_active' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    ];
                })
                ->toArray();
        }
    }

    protected function createAgent($session): array
    {
        $userAgent = $session->user_agent ?? '';
        
        return [
            'is_desktop' => !preg_match('/Mobile|Android|iPhone|iPad/', $userAgent),
            'platform' => $this->getPlatform($userAgent),
            'browser' => $this->getBrowser($userAgent),
        ];
    }

    protected function getPlatform(string $userAgent): string
    {
        if (preg_match('/Windows/', $userAgent)) return 'Windows';
        if (preg_match('/Macintosh|Mac OS/', $userAgent)) return 'macOS';
        if (preg_match('/Linux/', $userAgent)) return 'Linux';
        if (preg_match('/iPhone/', $userAgent)) return 'iPhone';
        if (preg_match('/iPad/', $userAgent)) return 'iPad';
        if (preg_match('/Android/', $userAgent)) return 'Android';
        return 'Unknown';
    }

    protected function getBrowser(string $userAgent): string
    {
        if (preg_match('/Chrome/', $userAgent) && !preg_match('/Edge|Edg/', $userAgent)) return 'Chrome';
        if (preg_match('/Firefox/', $userAgent)) return 'Firefox';
        if (preg_match('/Safari/', $userAgent) && !preg_match('/Chrome/', $userAgent)) return 'Safari';
        if (preg_match('/Edge|Edg/', $userAgent)) return 'Edge';
        if (preg_match('/Opera|OPR/', $userAgent)) return 'Opera';
        return 'Unknown';
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function updateProfilePhoto(): void
    {
        $this->validate([
            'photo' => ['required', 'image', 'max:2048'],
        ]);

        $user = Auth::user();
        
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $path = $this->photo->store('profile-photos', 'public');
        
        $user->forceFill([
            'profile_photo_path' => $path,
        ])->save();

        $this->photo = null;
        
        $this->dispatch('photo-updated');
    }

    public function deleteProfilePhoto(): void
    {
        $user = Auth::user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            
            $user->forceFill([
                'profile_photo_path' => null,
            ])->save();
        }

        $this->dispatch('photo-deleted');
    }

    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', PasswordRule::defaults(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }

    public function logoutOtherBrowserSessions(): void
    {
        $this->validate([
            'current_password' => ['required', 'string', 'current_password'],
        ]);

        Auth::guard()->logoutOtherDevices($this->current_password);

        $this->deleteOtherSessionRecords();

        $this->reset('current_password');
        $this->loadSessions();

        $this->dispatch('sessions-logged-out');
    }

    protected function deleteOtherSessionRecords(): void
    {
        if (config('session.driver') === 'database') {
            DB::connection(config('session.connection'))
                ->table(config('session.table', 'sessions'))
                ->where('user_id', Auth::id())
                ->where('id', '!=', Session::getId())
                ->delete();
        }
    }

    public function deleteUser(): void
    {
        $this->validate([
            'delete_password' => ['required', 'string', 'current_password:web'],
        ]);

        $user = Auth::user();

        Auth::logout();

        $user->delete();

        Session::invalidate();
        Session::regenerateToken();

        $this->redirect('/', navigate: true);
    }

    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('client.dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    public function render()
    {
        return view('livewire.client.settings.user-profile')
            ->layout('layouts.cliente');
    }
}

