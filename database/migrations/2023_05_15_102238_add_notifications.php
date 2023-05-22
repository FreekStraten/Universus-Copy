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
        // Add a table that has a foreign key to the users table, a title, a body and a boolean that indicates if the notification has been read.
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('title');
            $table->string('body')->nullable();
            $table->foreignId('competition_id')->nullable()->constrained('competitions');
            $table->foreignId('participation_id')->nullable()->references('participation_id')->on('participation');
            $table->string('message')->nullable();
            $table->boolean('read')->default(false);
            $table->timestamps();
            $table->softDeletes();
            // index
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Drop the table if it exists
        Schema::dropIfExists('notifications');
    }
};
