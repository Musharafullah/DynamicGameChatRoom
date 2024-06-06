<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GameMoveEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
   public $roomId;
   public $board;

   public function __construct($roomId, $board)
   {
   $this->roomId = $roomId;
   $this->board = $board;
   }

   public function broadcastOn()
   {
        return ['tic-tac-toe-channel'];
   }

   public function broadcastAs()
   {
        return 'game-move-'.$this->roomId;
   }

        //    public function broadcastWith()
        //    {
        //     return [
        //     'roomId' => $this->roomId,
        //     'board' => $this->board,
        //     ];
        //    }
}
