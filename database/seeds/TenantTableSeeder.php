<?php

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tenant::create([
            'name' => 'Souza',
            'sub_domain' => 'souza'
        ]);
        Tenant::create([
            'name' => 'Zakaluk',
            'sub_domain' => 'zakaluk'
        ]);
    }
}
