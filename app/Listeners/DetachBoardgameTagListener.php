<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Boardgame;

/**
 * This listener detach ALL tags of current boardgame
 */
class DetachBoardgameTagListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $boardgame = Boardgame::findOrFail($event->boardgame_id);        
        $boardgame->tags()->detach();
    }
}
