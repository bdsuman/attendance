<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use App\Exceptions\Handler as AppExceptionHandler;
use App\Models\Attendance;
use App\Observers\AttendanceObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Ensure our application exception handler is bound so custom
        // renderable/response logic is always used by the container.
        $this->app->singleton(ExceptionHandlerContract::class, AppExceptionHandler::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Attendance::observe(AttendanceObserver::class);
    }
}
