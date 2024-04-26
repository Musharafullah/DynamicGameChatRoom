<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code','count'];
    public function users()
    {
        return $this->hasMany(ChatRoomUser::class);
    }
    public function usersMessages()
    {
        return $this->hasMany(Messages::class);
    }
}
