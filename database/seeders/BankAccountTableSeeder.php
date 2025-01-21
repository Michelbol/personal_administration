<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\BankAccount;
use Illuminate\Support\Facades\DB;

class BankAccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(BankAccount::where('name', 'Nu Bank')->count() === 0){
            DB::unprepared(file_get_contents(base_path('database/seeds/sql/BankAccountSeeders/insert_nu_bank.sql')));
        }
        if(BankAccount::where('name', 'Caixa Kakogawa')->count() === 0) {
            DB::unprepared(file_get_contents(base_path('database/seeds/sql/BankAccountSeeders/insert_caixa_kakogawa.sql')));
        }
        if(BankAccount::where('name', 'Caixa Grevilha')->count() === 0) {
            DB::unprepared(file_get_contents(base_path('database/seeds/sql/BankAccountSeeders/insert_caixa_grevilea.sql')));
        }
    }
}
