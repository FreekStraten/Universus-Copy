<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('user_picture', function (Blueprint $table) {
            $table->dropForeign('user_picture_participation_id_foreign');
            $table->foreign('participation_id')->references('participation_id')->on('participation')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('user_picture', function (Blueprint $table) {
            $table->dropForeign('user_picture_participation_id_foreign');
            $table->foreign('participation_id')->references('participation_id')->on('participation');
        });
    }
};
