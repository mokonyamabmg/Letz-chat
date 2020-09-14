<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Friendship extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('friendship', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user1_id')->index();
            $table->foreign('user1_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('user2_id')->index();
            $table->foreign('user2_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friendship');
    }
}
