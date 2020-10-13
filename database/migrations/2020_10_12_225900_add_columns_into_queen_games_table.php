<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsIntoQueenGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('queen_games', function (Blueprint $table) {
            $table->integer('white_left')->default(0);
            $table->integer('black_left')->default(0);
            $table->integer('difficulty')->default(0);
            $table->integer('start_game')->default(0);
            $table->integer('end_game')->default(0);
            $table->string('type_white')->nullable();
            $table->string('type_black')->nullable();
            $table->string('type_black_machine')->nullable();
            $table->string('type_white_machine')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
