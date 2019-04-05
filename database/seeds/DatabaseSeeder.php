<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(TenantTableSeeder::class);
         $this->call(UserTenantTableSeeder::class);
         $this->call(UsersTableSeeder::class);
         $this->call(BanksTableSeeder::class);
         $this->call(BankAccountTableSeeder::class);
         $this->call(TypeBankAccountPostingTableSeeder::class);
    }
}
