<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FriendRequests extends Migration
{
 
    public function up()
    {
        Schema::create('friend_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_from')->unsigned();
            $table->integer('user_to')->unsigned();
            $table->timestamps();
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('friend_requests');
    }
}
