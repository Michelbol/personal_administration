<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankAccountPostingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_account_postings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('document', 150);
            $table->dateTime('posting_date');
            $table->decimal('amount',15,2);
            $table->enum('type',['C', 'D']);
            $table->decimal('account_balance', 15,2);
            $table->unsignedBigInteger('bank_account_id');
            $table->unsignedBigInteger('type_bank_account_posting_id');
            $table->timestamps();

            $table->foreign('bank_account_id')->references('id')->on('bank_accounts');
            $table->foreign('type_bank_account_posting_id')->references('id')->on('type_bank_account_postings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_account_postings');
    }
}
