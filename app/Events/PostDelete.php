<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostDelete implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $postId;

    /**
     * Create a new event instance.
     */
    public function __construct($postId)
    {
        $this->postId = $postId;
    }

    /**
     * Broadcast channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('posts'); // SAME as create event
    }

    /**
     * Event name
     */
    public function broadcastAs(): string
    {
        return 'delete';
    }
}
