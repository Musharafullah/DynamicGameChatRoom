<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_room_users', function (Blueprint $table) {
            $table->id();
                    $table->foreignId('chat_room_id')->constrained()->onDelete('cascade');
                    $table->string('random_name')->nullable();
                    $table->string('user_ip')->nullable();
                    $table->string('browser')->nullable();
                    $table->string('platform')->nullable();
                    $table->string('device')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_room_users');
    }
};
