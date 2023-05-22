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
        // alter the participation table to add a main photo id which can be nullable
        Schema::table('participation', function (Blueprint $table) {
            $table->string('main_photo_id')->nullable();
        });

        // alter the user_has_picture_rating table to add a participation id instead of photo id
        Schema::table('user_has_picture_rating', function (Blueprint $table) {
            $table->unsignedBigInteger('participation_id')->nullable();
            $table->foreign('participation_id')->references('participation_id')->on('participation');
            $table->text('feedback')->nullable();

            $table->timestamps();
        });

        // alter the user_picture table to add a participation id, remove main picture
        Schema::table('user_picture', function (Blueprint $table) {
            $table->dropColumn('main_picture');
            $table->unsignedBigInteger('participation_id')->nullable();
            $table->foreign('participation_id')->references('participation_id')->on('participation');
        });





    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // undo all changes after checking if they can be rolled back first
        Schema::table('participation', function (Blueprint $table) {
            $table->dropColumn('main_photo_id');
        });

        Schema::table('user_has_picture_rating', function (Blueprint $table) {
            $table->dropForeign('user_has_picture_rating_participation_id_foreign');
            $table->dropColumn('participation_id');
        });

        Schema::table('user_picture', function (Blueprint $table) {
            $table->dropForeign('user_picture_participation_id_foreign');
            $table->dropColumn('participation_id');
            $table->string('main_picture')->nullable();
        });










    }
};
