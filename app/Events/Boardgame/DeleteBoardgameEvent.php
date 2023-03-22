<?php

namespace App\Events\Boardgame;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeleteBoardgameEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public int $boardgame_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $boardgame_id)
    {
        $this->boardgame_id = $boardgame_id;
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