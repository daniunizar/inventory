<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Boardgame;

/**
 * This event sync all tags of current boardgame with $tag_ids
 */
class SyncBoardgameTagListener
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
        $tag_ids = $event->tag_ids;
        
        $boardgame->tags()->sync($tag_ids);
    }
}
