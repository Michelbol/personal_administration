<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBudgetFinancialPostingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_financial_postings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('posting_date');
            $table->decimal('amount',15,2);
            $table->decimal('account_balance', 15,2);
            $table->unsignedBigInteger('income_id')->nullable();
            $table->unsignedBigInteger('expense_id')->nullable();
            $table->unsignedBigInteger('budget_financial_id');
            $table->timestamps();

            $table->foreign('budget_financial_id')->references('id')->on('budget_financials');
            $table->foreign('income_id')->references('id')->on('incomes');
            $table->foreign('expense_id')->references('id')->on('expenses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget_financial_postings');
    }
}
