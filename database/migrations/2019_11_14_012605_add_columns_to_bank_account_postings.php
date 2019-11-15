<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToBankAccountPostings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_account_postings', function (Blueprint $table) {
            $table->unsignedBigInteger('income_id');
            $table->unsignedBigInteger('expense_id');

            $table->foreign('income_id')->references('id')->on('incomes');
            $table->foreign('expense_id')->references('id')->on('incomes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_account_postings', function (Blueprint $table) {
            $table->dropForeign('bank_account_postings_income_id_foreign');
            $table->dropIndex('bank_account_postings_income_id_foreign');
            $table->dropColumn('income_id');

            $table->dropForeign('bank_account_postings_expense_id_foreign');
            $table->dropIndex('bank_account_postings_expense_id_foreign');
            $table->dropColumn('expense_id');
        });
    }
}
