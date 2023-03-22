<?php

namespace App\Events\Boardgame;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateBoardgameEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public int $boardgame_id;
    public array $tag_ids;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $boardgame_id, array $tag_ids)
    {
        $this->boardgame_id = $boardgame_id;
        $this->tag_ids = $tag_ids;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
