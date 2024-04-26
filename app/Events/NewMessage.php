<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;



    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
    * Get the channels the event should broadcast on.
    *
    * @return array
    */
    public function broadcastOn()
    {
        return ['chat-room.'.$this->message->chat_room_id];
    }
}
