<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

//events
use App\Events\Boardgame\StoreBoardgameEvent;
use App\Events\Boardgame\UpdateBoardgameEvent;
use App\Events\Boardgame\DeleteBoardgameEvent;
//listeners
use App\Listeners\SyncBoardgameTagListener;
use App\Listeners\DetachBoardgameTagListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        StoreBoardgameEvent::class => [
            SyncBoardgameTagListener::class,
        ],
        UpdateBoardgameEvent::class => [
            SyncBoardgameTagListener::class,
        ],
        DeleteBoardgameEvent::class => [
            DetachBoardgameTagListener::class,
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
