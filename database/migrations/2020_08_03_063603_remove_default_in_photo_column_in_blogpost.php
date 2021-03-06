<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveDefaultInPhotoColumnInBlogpost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blogposts', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blogposts', function (Blueprint $table) {
            $table->string('photo')->nullable()->default('people.png');
        });
    }
}
