<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatRoomController;


 Route::post('/play-game', [ChatRoomController::class, 'playRoom'])->name('playRoom');
 Route::get('/create-room', [ChatRoomController::class, 'createRoom'])->name('createRoom');
//  Route::get('/join-room/{code?}', [ChatRoomController::class, 'join'])->name('join.room')->name('join.room');
 Route::post('/join-room', [ChatRoomController::class, 'join'])->name('join.room')->name('join.room');
 // chat
 Route::post('/send-message', [ChatRoomController::class,'sendMessage']);
 Route::post('/send-game-move', [ChatRoomController::class,'gamemove']);
//  Route::get('/get-user/{roomCode}', [ChatRoomController::class,'getuser'])->name('get-user');
