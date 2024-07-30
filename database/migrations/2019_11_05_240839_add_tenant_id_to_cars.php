<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTenantIdToCars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (config('database.default') === 'sqlite') {
            Schema::table('cars', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable();
                $table->foreign('tenant_id')->references('id')->on('tenants');
            });
    
            \Illuminate\Support\Facades\DB::statement('update cars set tenant_id = 1');
            
            Schema::table('cars', function (Blueprint $table) {
                $table->foreign('tenant_id')->references('id')->on('tenants');
            });
            return;
        }

        Schema::table('cars', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable();
            $table->foreign('tenant_id')->references('id')->on('tenants');
        });

        \Illuminate\Support\Facades\DB::statement('update cars set tenant_id = 1');
        Schema::table('cars', function (Blueprint $table) {
            $table->dropForeign('cars_tenant_id_foreign');
            $table->dropIndex('cars_tenant_id_foreign');
        });
        \Illuminate\Support\Facades\DB::statement('alter table cars modify tenant_id BIGINT unsigned not null;');
        Schema::table('cars', function (Blueprint $table) {
            $table->foreign('tenant_id')->references('id')->on('tenants');
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
            Schema::table('cars', function (Blueprint $table) {
                $table->dropColumn('tenant_id');
            });
            return;
        }
        Schema::table('cars', function (Blueprint $table) {
            $table->dropForeign('cars_tenant_id_foreign');
            $table->dropIndex('cars_tenant_id_foreign');
            $table->dropColumn('tenant_id');
        });
    }
}
