<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // add minimum_aantal_fotos and maximum_aantal_fotos to competitions with a default value of min 1 and max 2 if not set
        Schema::table('competitions', function (Blueprint $table) {
            $table->integer('min_amount_pictures')->default(1);
            $table->integer('max_amount_pictures')->default(2);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // check if the collumns exists
        if (Schema::hasColumns('competitions', ['min_amount_pictures', 'max_amount_pictures'])) {
            // remove minimum_aantal_fotos and maximum_aantal_fotos from competitions
            Schema::table('competitions', function (Blueprint $table) {
                $table->dropColumn('min_amount_pictures');
                $table->dropColumn('max_amount_pictures');
            });
        }
    }
};
