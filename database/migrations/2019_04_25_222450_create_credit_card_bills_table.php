<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditCardBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_card_bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cred_card_id');
            $table->dateTime('opening_date');
            $table->dateTime('closing_date');
            $table->timestamps();

            $table->foreign('cred_card_id')->references('id')->on('credit_cards');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('credit_card_bills');
    }
}
