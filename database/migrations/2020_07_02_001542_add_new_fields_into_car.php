<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsIntoCar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->string('brand')->nullable();
            $table->string('year')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn('brand');
        });
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn('year');
        });
    }
}
