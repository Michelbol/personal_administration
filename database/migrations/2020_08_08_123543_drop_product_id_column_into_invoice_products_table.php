<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropProductIdColumnIntoInvoiceProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (config('database.default') === 'sqlite') {
            Schema::table('invoice_products', function (Blueprint $table) {
                $table->dropColumn('product_id');
            });
            return;
        }
        Schema::table('invoice_products', function (Blueprint $table) {
            $table->dropForeign('invoice_products_product_id_foreign');
            $table->dropIndex('invoice_products_product_id_foreign');
            $table->dropColumn('product_id');
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
                $table->unsignedBigInteger('product_id')
                    ->default(0);
                $table->foreign('product_id')
                    ->references('id')
                    ->on('products');
            });
            return;
        }
        Schema::table('invoice_products', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')
                ->references('id')
                ->on('products');
        });
    }
}
