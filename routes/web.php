<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatRoomController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
// routes/web.php


// Route::get('/', [ChatRoomController::class, 'create'])->name('create.room');
// Route::get('/play-game', [ChatRoomController::class, 'playRoom'])->name('playRoom');
// Route::get('/create-room', [ChatRoomController::class, 'createRoom'])->name('createRoom');
// Route::get('/join-room/{code?}', [ChatRoomController::class, 'join'])->name('join.room');

// // chat
// Route::post('/send-message', [ChatRoomController::class,'sendMessage']);
// Route::get('/get-user/{roomCode}', [ChatRoomController::class,'getuser'])->name('get-user');


Route::get('/{any}', function () {
return view('layouts.app');
})->where('any', '^(?!api).*$');
