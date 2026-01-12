<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\Activitylog\Models\Activity;

class LogAuthenticationActivity
{
    /**
     * Handle user login events.
     */
    public function handleLogin(Login $event): void
    {
        activity('login')
            ->causedBy($event->user)
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'guard' => $event->guard,
            ])
            ->log('Usuario inició sesión exitosamente');
    }

    /**
     * Handle user logout events.
     */
    public function handleLogout(Logout $event): void
    {
        if ($event->user) {
            activity('logout')
                ->causedBy($event->user)
                ->withProperties([
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log('Usuario cerró sesión');
        }
    }

    /**
     * Handle failed login attempts.
     */
    public function handleFailed(Failed $event): void
    {
        $properties = [
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'email' => $event->credentials['email'] ?? 'N/A',
            'guard' => $event->guard,
        ];

        if ($event->user) {
            activity('login_failed')
                ->causedBy($event->user)
                ->withProperties($properties)
                ->log('Intento de inicio de sesión fallido - contraseña incorrecta');
        } else {
            activity('login_failed')
                ->withProperties($properties)
                ->log('Intento de inicio de sesión fallido - usuario no encontrado');
        }
    }

    /**
     * Handle lockout events.
     */
    public function handleLockout(Lockout $event): void
    {
        $request = $event->request;
        
        activity('lockout')
            ->withProperties([
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'email' => $request->input('email', 'N/A'),
                'lockout_time' => now()->toDateTimeString(),
            ])
            ->log('Cuenta bloqueada por demasiados intentos fallidos');
    }

    /**
     * Handle user registration events.
     */
    public function handleRegistered(Registered $event): void
    {
        activity('auth')
            ->causedBy($event->user)
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Nuevo usuario registrado');
    }

    /**
     * Handle password reset events.
     */
    public function handlePasswordReset(PasswordReset $event): void
    {
        activity('auth')
            ->causedBy($event->user)
            ->withProperties([
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Contraseña restablecida exitosamente');
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return array<string, string>
     */
    public function subscribe($events): array
    {
        return [
            Login::class => 'handleLogin',
            Logout::class => 'handleLogout',
            Failed::class => 'handleFailed',
            Lockout::class => 'handleLockout',
            Registered::class => 'handleRegistered',
            PasswordReset::class => 'handlePasswordReset',
        ];
    }
}

