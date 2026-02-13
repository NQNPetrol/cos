<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use App\Observers\PermissionObserver;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;
use Livewire\Livewire;
use App\Livewire\Client\UpdateProfileInformationForm;
use App\Livewire\Client\UpdatePasswordForm;
use App\Livewire\Client\LogoutOtherBrowserSessionsForm;
use App\Livewire\Client\DeleteUserForm;
use App\Listeners\LogAuthenticationActivity;
use App\Models\Recorrido;
use App\Models\RecorridoTimetable;
use App\Policies\RecorridoPolicy;
use App\Policies\RecorridoTimetablePolicy;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         Permission::observe(PermissionObserver::class);
         Route::aliasMiddleware('role', RoleMiddleware::class);
         Livewire::component('client.update-profile-information-form', UpdateProfileInformationForm::class);
         Livewire::component('client.update-password-form', UpdatePasswordForm::class);
         Livewire::component('client.logout-other-browser-sessions-form', LogoutOtherBrowserSessionsForm::class);
         Livewire::component('client.delete-user-form', DeleteUserForm::class);
         
         // Register authentication activity log subscriber
         Event::subscribe(LogAuthenticationActivity::class);

         // Register policies
         Gate::policy(Recorrido::class, RecorridoPolicy::class);
         Gate::policy(RecorridoTimetable::class, RecorridoTimetablePolicy::class);
    }
}
