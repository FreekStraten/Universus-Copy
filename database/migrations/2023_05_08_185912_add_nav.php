<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add a nav  bar table, which contains all of the nav bar items, their order, their url, their name and who can see them
        Schema::create('nav_bar', function (Blueprint $table) {
            $table->id();
            $table->integer('order')->unique();
            $table->string('url');
            $table->string('name');
            $table->timestamps();
        });

        // Nav bar items can have dropdown
        Schema::create('nav_bar_dropdown', function (Blueprint $table) {
            $table->id();
            $table->integer('nav_bar_id');
            $table->string('name');
            $table->string('url');
            $table->integer('order')->unique();
            $table->integer('user_role');
            $table->timestamps();
        });

        // nav bar item can have multiple user roles
        Schema::create('nav_bar_user_role', function (Blueprint $table) {
            $table->id();
            $table->integer('nav_bar_id');
            $table->integer('user_role');
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
        // drop the nav bar table if it exists
        Schema::dropIfExists('nav_bar');
        Schema::dropIfExists('nav_bar_dropdown');
        Schema::dropIfExists('nav_bar_user_role');
    }
};
