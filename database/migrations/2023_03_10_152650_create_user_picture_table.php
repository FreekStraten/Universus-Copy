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
        Schema::create('user_picture', function (Blueprint $table) {
            $table->string('id');
            $table->dateTime('submission_date');
            $table->foreignId('userId')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreignId('wedstrijdId')
                ->references('id')
                ->on('competitions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_picture');
    }
};
