<?php

use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('alertas:procesar')->dailyAt('08:00');
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');

        $middleware->redirectUsersTo(function (Request $request) {
            $intendedUrl = $request->query('intended') ?? session()->get('url.intended');
            $user = $request->user();

            if ($intendedUrl && $user instanceof User && $user->isIntendedUrlAllowed($intendedUrl)) {
                session()->forget('url.intended');

                return $intendedUrl;
            }

            session()->forget('url.intended');

            return route('dashboard');
        });

        $middleware->validateCsrfTokens(except: [
            'admin/enrollments/*/payment/webhook',
        ]);

        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (PostTooLargeException $e) {
            return redirect()->back()->with('error', 'El archivo es demasiado grande. El tamaño máximo permitido es 10 MB.');
        });
    })->create();
