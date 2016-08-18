<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBestPlayerOfLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('best_player_of_leagues', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('player_id');
            $table->integer('league_season_id');
            $table->integer('award_id');
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
        Schema::drop('best_player_of_leagues');
    }
}
