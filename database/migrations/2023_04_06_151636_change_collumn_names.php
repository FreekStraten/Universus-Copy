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
        // check if the column exists
        if (Schema::hasColumn('user_picture', 'wedstrijdId')) {
            // rename to english name
            Schema::table('user_picture', function (Blueprint $table) {
                $table->renameColumn('wedstrijdId', 'competition_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // check if the column exists
        if (Schema::hasColumn('user_picture', 'competition_id')) {
            // undo the changes
            Schema::table('user_picture', function (Blueprint $table) {
                $table->renameColumn('competition_id', 'wedstrijdId');
            });
        }

    }
};
