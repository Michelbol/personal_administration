<?php

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
        if(UserTenant::where('email', 'michel.bolzon123@gmail.com')->count() === 0){
            UserTenant::create([
                'name' => 'Michel Bolzon Souza Dos Reis',
                'email' => 'michel.bolzon123@gmail.com',
                'password' => bcrypt('1525605'),
                'tenant_id' => 1
            ]);
        }
        if(UserTenant::where('email', 'larizakaluk@gmail.com')->count() === 0){
            UserTenant::create([
                'name' => 'Larissa Zakaluk de Souza',
                'email' => 'larizakaluk@gmail.com',
                'password' => bcrypt('123456'),
                'tenant_id' => 1
            ]);
        }
    }
}
