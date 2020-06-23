<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number');
            $table->string('series');
            $table->dateTime('emission_at');
            $table->string('authorization_protocol');
            $table->dateTime('authorization_at');
            $table->string('access_key');
            $table->string('document');
            $table->string('qr_code');
            $table->decimal('taxes', 15,2);
            $table->decimal('discount', 15,2);
            $table->decimal('total_products', 15,2);
            $table->decimal('total_paid', 15,2);
            $table->timestamps();

            $table->unsignedBigInteger('tenant_id');
            $table
                ->foreign('tenant_id')
                ->references('id')
                ->on('tenants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
