<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Spatie\Activitylog\Facades\Activity;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register any events for your application.
     */
    public function register(): void
    {
        Event::listen(Login::class, function (Login $event) {
            Activity::causedBy($event->user)
                ->event('login')
                ->log("User logged in: {$event->user->name}");
        });

        Event::listen(Logout::class, function (Logout $event) {
            Activity::causedBy($event->user)
                ->event('logout')
                ->log("User logged out: {$event->user->name}");
        });
    }
}
