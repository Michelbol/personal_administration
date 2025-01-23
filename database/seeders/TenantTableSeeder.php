<?php

namespace Database\Seeders;

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
        $this->createTenant('Souza', 'souza');
    }

    public function createTenant(string $name,string $subdomain)
    {
        if(Tenant::whereName($name)->whereSubDomain($subdomain)->count() === 0){
            Tenant::create([
                'name' => $name,
                'sub_domain' => $subdomain
            ]);
        }
    }
}
