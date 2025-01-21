<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToCarSupplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('car_supplies', function (Blueprint $table) {
            $table->integer('fuel')->default(0);
            $table->string('gas_station', 150)->nullable();
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });

        \Illuminate\Support\Facades\DB::statement('update car_supplies set tenant_id = 1');

        Schema::table('cars', function(Blueprint $table){
            $table->unsignedBigInteger('tenant_id')->nullable(false)->change();
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
            Schema::table('car_supplies', function (Blueprint $table) {
                $table->dropColumn('fuel');
            });
            Schema::table('car_supplies', function (Blueprint $table) {
                $table->dropColumn('gas_station');
            });
            Schema::table('car_supplies', function (Blueprint $table) {
                $table->dropColumn('tenant_id');
            });
            return;
        }
        Schema::table('car_supplies', function (Blueprint $table) {
            $table->dropColumn('fuel');
            $table->dropColumn('gas_station');
            $table->dropForeign('car_supplies_tenant_id_foreign');
            $table->dropIndex('car_supplies_tenant_id_foreign');
            $table->dropColumn('tenant_id');
        });
    }
}
