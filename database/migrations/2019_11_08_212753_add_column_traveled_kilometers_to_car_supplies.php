<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTraveledKilometersToCarSupplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_supplies', function (Blueprint $table) {
            $table->decimal('traveled_kilometers', 15,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('car_supplies', function (Blueprint $table) {
            $table->dropColumn('traveled_kilometers');
        });
    }
}
