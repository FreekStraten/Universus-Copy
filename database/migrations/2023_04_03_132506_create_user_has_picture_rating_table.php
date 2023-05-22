<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_has_picture_rating', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->string('picture_id', 225);
            $table->integer('star_rating');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('picture_id')->references('id')->on('user_picture')->onDelete('cascade');

            $table->primary(['user_id', 'picture_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_has_picture_rating');
    }
};
