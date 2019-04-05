<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('isFixed')->default(false);
            $table->decimal('amount', 15,2);
            $table->integer('due_date')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('tenant_id');

            $table->foreign('tenant_id')->references('id')->on('tenants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incomes');
    }
}
