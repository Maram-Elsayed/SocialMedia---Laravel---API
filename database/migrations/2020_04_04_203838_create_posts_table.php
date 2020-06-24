<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
   
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->mediumText('caption')->nullable();
            $table->string('cover_image')->nullable();
            $table->integer('user_id');
            $table->timestamps();
            
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
