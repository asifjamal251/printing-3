<?php
use App\Http\Middleware\Authorize;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));

            Route::middleware('web')
                ->prefix('client')
                ->name('client.')
                ->group(base_path('routes/client.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('admin', [
            \App\Http\Middleware\Admin\RedirectIfAuthenticated::class,
            \App\Http\Middleware\Admin\RedirectIfNotAuthenticated::class,
        ]);
        $middleware->appendToGroup('client', [
            \App\Http\Middleware\Client\RedirectIfAuthenticated::class,
            \App\Http\Middleware\Client\RedirectIfNotAuthenticated::class,
        ]);

        $middleware->alias([
            'admin.guest' => \App\Http\Middleware\Admin\RedirectIfAuthenticated::class,
            'admin.auth' => \App\Http\Middleware\Admin\RedirectIfNotAuthenticated::class,
            'can' => Authorize::class,
            'client.guest' => \App\Http\Middleware\Client\RedirectIfAuthenticated::class,
            'client.auth' => \App\Http\Middleware\Client\RedirectIfNotAuthenticated::class,
            'optimizeImages' => \Spatie\LaravelImageOptimizer\Middlewares\OptimizeImages::class,
            '2fa' => \PragmaRX\Google2FALaravel\Middleware::class,
            'check.admin.ip' => \App\Http\Middleware\CheckAdminIP::class,
            'login.time' => \App\Http\Middleware\LoginTimeRestriction::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
