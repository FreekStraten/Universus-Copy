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
        // Create a table for a category which has multiple competitions
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->dateTime('archived_at')->nullable();
            $table->timestamps();
        });

        // Create a table for a competition which belongs to a category. Name, description, min amount competitors, max amount competitors, start date, end date, category_id
        Schema::create('competitions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->integer('min_amount_competitors');
            $table->integer('max_amount_competitors');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('user_id');
            $table->dateTime('archived_at')->nullable();
            $table->timestamps();
            $table->foreignId('category_id')
                ->references('id')
                ->on('categories')
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
        Schema::dropIfExists('competitions');
        Schema::dropIfExists('categories');

    }
};
