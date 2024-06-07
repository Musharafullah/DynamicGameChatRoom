<?php

// app/Http/Controllers/ChatRoomController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatRoom;
use App\Models\ChatRoomUser;
use App\Models\Messages;
use Jenssegers\Agent\Agent;
use Pusher\Pusher;
use App\Events\NewMessage;
use App\Events\GameMoveEvent;

use Illuminate\Support\Str;

class ChatRoomController extends Controller
{
    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {

    }
    public function playRoom(Request $request)
    {
        $lessthen = '';
        if($request->roomcode)
        {
            $lessthen = ChatRoom::where('code', $request->roomcode)->where('count', '<', 2)->first();

        }
        else{

            $lessthen = ChatRoom::where('count', '<', 2)->first();
        }
        $lessthen->count++;
        $lessthen->save();

        return response()->json(['data' => $lessthen]);
    }
    public function createRoom(Request $request)
    {


        $room = ChatRoom::create([
            'code' => Str::random(1) . random_int(1000, 9999) .Str::random(3),
        ]);
        $data = [
            'code'=>$room->code,
        ];
        return response()->json(['data' => $data]);
    }
    public function join(Request $request)
    {

        // Check if userId is provided, otherwise generate a random one
        $userId = $request->userId ?? mt_rand(1000, 9999);
        // just for temprary - when we will live it then we will get the live IP address
        $userIp = $userId;

        $names = ['John', 'Alice', 'Bob', 'Emma', 'Charlie', 'Olivia', 'James', 'Sophia', 'Michael', 'Isabella'];
        $randomIndex = array_rand($names);
        $randomName = $names[$randomIndex];

        // $userIp = $request->ip();

        $room = ChatRoom::where('code', $request->code)->firstOrFail();
        $existingUser = $room->users()->where('user_ip', $userIp)->first();
        if (!$existingUser) {
            $room->users()->create([
                'user_ip' => $userIp,
                'random_name' => $randomName,
                'user_id' => $request->userId,
            ]);
                $room->count++;
                $room->save();
                 $userName = $randomName;
        }else{
             $userName = 'abc';
        }

        // Retrieve all users in the room except the current user
        $userList = $room->users->pluck('random_name')->except($randomName)->toArray();
        return response()->json([
            'room' => $room,
            'usersNames' => $userList,
            'userName' => $randomName,
            'userId'=>$userId,
        ]);
    }
    public function sendMessage(Request $request)
    {

        $message = [
            'code'=>$request->roomId,
            'userId'=>$request->roomId,
            'userName'=>$request->userName,
            'content'=>$request->content,
        ];

            $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true,
            ]
            );

                // Trigger an event on a channel
                $pusher->trigger('tic-tac-toe-channel', 'NewMessage-'.$request->roomId, ['message'=>$message]);

         // Broadcast the new message to the Pusher channel
         broadcast(new NewMessage($message))->toOthers();

         return response()->json($message);

    }
    // gamemove
    public function gamemove(Request $request)
    {

        // Get the room code and move data from the request
        $roomId = $request->roomId;
        $board = $request->board;

        $pusher = new Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true,
        ]
        );

        // Trigger an event on a channel
        $pusher->trigger('tic-tac-toe-channel', 'game-move-'.$roomId, ['board'=>$board,
        'player'=>$request->player]);

        // Broadcast the game move data to the Pusher channel
        event(new GameMoveEvent($roomId, $board));
        // Return a response if necessary
        return response()->json(['message' => 'Game move broadcasted successfully']);
    }





}