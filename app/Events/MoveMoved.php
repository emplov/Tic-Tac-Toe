<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use phpDocumentor\Reflection\Types\Integer;

class MoveMoved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var int|string
     */
    public int|string $block_id;

    /**
     * @var int|string
     */
    public integer|string $room_id;

    /**
     * @var string
     */
    public string $turn;

    /**
     * @var int|string
     */
    public integer|string $user_id;

    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($room_id, $block_id, $turn = 'x')
    {
        $this->block_id = $block_id;
        $this->room_id = $room_id;
        $this->turn = $turn;
        $this->user_id = auth()->user()->id;
        $this->user = auth()->user();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|PrivateChannel|array
     */
    public function broadcastOn(): Channel|PrivateChannel|array
    {
        return new PrivateChannel('moved.' . $this->room_id);
    }
}
