<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postsnotifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('userId')->unsigned();
            $table->mediumText('message');
            $table->mediumText('postId');
            $table->integer('is_read');
            $table->boolean('is_seen');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
