<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPostsTable extends Migration
{
   
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->mediumText('description')->nullable();
        });
        
    }

   
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
