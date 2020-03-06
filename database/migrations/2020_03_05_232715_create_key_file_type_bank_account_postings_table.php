<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeyFileTypeBankAccountPostingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('key_file_type_bank_account_postings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('key_file')->unique();
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('expense_id')->nullable();
            $table->unsignedBigInteger('income_id')->nullable();
            $table->timestamps();

            $table->foreign('type_id')->references('id')->on('type_bank_account_postings');
            $table->foreign('expense_id')->references('id')->on('expenses');
            $table->foreign('income_id')->references('id')->on('incomes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('key_file_type_bank_account_postings');
    }
}
