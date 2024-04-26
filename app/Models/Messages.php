<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;
    protected $fillable = [
    'chat_room_id',
    'chat_room_user_id',
    'content',
    ];
     public function chatRoomUser()
     {
        return $this->belongsTo(ChatRoomUser::class,'chat_room_user_id');
     }
}
