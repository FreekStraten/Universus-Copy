<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToParticipationsTable extends Migration
{
    public function up()
    {
        Schema::table('participation', function (Blueprint $table) {
            $table->foreign('competition_id', 'fk_participations_competition_id')
                ->references('id')
                ->on('competitions')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('participation', function (Blueprint $table) {
            $table->dropForeign('fk_participations_competition_id');
        });
    }
}
