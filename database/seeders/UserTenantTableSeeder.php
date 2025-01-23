<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\UserTenant;
use Illuminate\Database\Seeder;

class UserTenantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $souza = Tenant::whereName('Souza')->first();
        $this->createUser(
            'Michel Bolzon Souza Dos Reis',
            'michel.bolzon123@gmail.com',
            '1525605',
            $souza
        );
    }

    public function createUser(string $name, string $email, string $password, Tenant $tenant)
    {
        if(UserTenant::where('email', $email)->count() === 0) {
            UserTenant::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
                'tenant_id' => $tenant->id
            ]);
        }
    }
}
