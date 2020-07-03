<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFipeHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fipe_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('value');
            $table->dateTime('consultation_date');
            $table->timestamps();

            $table->unsignedBigInteger('car_id');
            $table
                ->foreign('car_id')
                ->references('id')
                ->on('cars');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fipe_histories');
    }
}
