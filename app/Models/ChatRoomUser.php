<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoomUser extends Model
{
    use HasFactory;
    protected $fillable = ['user_ip','random_name', 'browser', 'platform', 'device'];
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }
    public function usersMessages()
    {
        return $this->hasMany(Messages::class,'chat_room_user_id');
    }
}
