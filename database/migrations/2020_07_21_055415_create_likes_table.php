<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->increments('id');

            $table->timestamps();
            $table->unsignedInteger('blogpost_id')->nullable()->index();
            $table->foreign('blogpost_id')->references('id')->on('blogposts')->onDelete('cascade');
            $table->unsignedInteger('comment_id')->nullable()->index();
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
            $table->unsignedInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
