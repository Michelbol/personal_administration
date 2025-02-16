<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductSupplierIdColumnIntoInvoiceProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_products', function (Blueprint $table) {
            $table->unsignedBigInteger('product_supplier_id')->nullable();
            $table->foreign('product_supplier_id')
                ->references('id')
                ->on('product_suppliers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (config('database.default') === 'sqlite') {
            Schema::table('invoice_products', function (Blueprint $table) {
                $table->dropColumn('product_supplier_id');
            });
            return;
        }
        Schema::table('invoice_products', function (Blueprint $table) {
            $table->dropForeign('invoice_products_product_supplier_id_foreign');
            $table->dropIndex('invoice_products_product_supplier_id_foreign');
            $table->dropColumn('product_supplier_id');
        });
    }
}
