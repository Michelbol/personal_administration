<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetFinancialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_financials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('month');
            $table->string('year', 6);
            $table->boolean('isFinalized')->default(false);
            $table->decimal('initial_balance',15,2)->default(0);
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
        Schema::dropIfExists('budget_financials');
    }
}
