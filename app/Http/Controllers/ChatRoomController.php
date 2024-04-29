<?php

// app/Http/Controllers/ChatRoomController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatRoom;
use App\Models\ChatRoomUser;
use App\Models\Messages;
use Jenssegers\Agent\Agent;
use App\Events\NewMessage;
use Pusher\Pusher;
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
    public function playRoom()
    {

        $lastInsertedId = ChatRoom::latest()->first();
        return redirect()->route('join.room', $lastInsertedId->code);
    }
    public function createRoom(Request $request)
    {


        $room = ChatRoom::create([
            // 'name' => $request->input('name'),
            'code' => Str::random(1) . random_int(1000, 9999) .Str::random(3),
        ]);
        $users = ['avic'];
        // return view('rooms.join', compact('room', 'users'));
        return redirect()->route('join.room', $room->code);
    }

    // Controller logic to fetch room and render Blade template with random user names
    public function join($code, Request $request)
    {


        $userId = mt_rand(1000, 9999);
        // Store the user ID in the session
        $request->session()->put('user_id', $userId);
        // randome name
        $names = ['John', 'Alice', 'Bob', 'Emma', 'Charlie', 'Olivia', 'James', 'Sophia', 'Michael', 'Isabella'];
        $randomIndex = array_rand($names);
        $randomName = $names[$randomIndex];

        // end random name
            $agent = new Agent();
            $browser = $agent->browser();
            $platform = $agent->platform();
            $device = $agent->device();
        // we cant get the IP address in local .. it will work when the project will live
            $userIp = $request->ip();
        // Fetch the chat room
            $room = ChatRoom::where('code', $code)->firstOrFail();
        // Check if the user has already joined this room from the same IP
            $existingUser = $room->users()->where('user_ip', $userIp)->first();

        if (!$existingUser) {
        // User is joining for the first time from this IP, save the details
            $room->users()->create([
                'user_ip' => $userIp,
                'browser' => $browser,
                'platform' => $platform,
                'device' => $device,
                'user_id' => $userId,
            ]);

            // Increment the count of users in the room
            $room->count++;
            $room->save();
        }

        // Generate random user names

        // Pass data to the view
        return view('rooms.join', compact('room','randomName'));
    }


    // Helper function to generate random names

    // message
    public function sendMessage(Request $request)
    {
        // Find the chat room by code
        $room = ChatRoom::where('code', $request->room_code)->firstOrFail();

        // Retrieve the user ID from the session
        $userId = $request->session()->get('user_id');

        // Create a new message in the room with the retrieved user ID
        $message = new Messages();
        $message->chat_room_id = $room->id;
        $message->content = $request->message;
        $message->chat_room_user_id = $userId; // Use the retrieved user ID
        $message->save();

        // Retrieve all messages belonging to the chat room
        $messages = Messages::where('chat_room_id', $room->id)->get();

        // Broadcast the new message
        broadcast(new NewMessage($message))->toOthers();

        // Prepare data to return including the newly sent message and all messages in the chat room
        $responseData = [
        'message' => 'Message sent successfully',
        'all_messages' => $messages, // Add all messages to the response
        ];

        // pusher
           // Pusher credentials from environment variables
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
           $pusher->trigger('chat-room', 'new-message', ['message' => $message]);

        return response()->json($responseData);
    }
    // get messages
    public function getMessages($roomCode)
    {
        // Find the chat room by code
        $room = ChatRoom::where('code', $roomCode)->firstOrFail();
        dd($room);
        // Retrieve messages belonging to the chat room
        if($room != null)
        {
            $messages = $room->messages()->get();
             // Return the messages as JSON response
             return response()->json(['all_messages' => $messages]);
        }


    }
    // remove the session value
    public function removeData(Request $request)
    {
        dd("hello");
        // $request->session()->forget('your_session_key');
        // return response()->json(['success' => true]);
    }



}
