<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeProductIdToNullableIntoInvoiceProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_products', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_products', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable(false)->change();
        });
    }
}
