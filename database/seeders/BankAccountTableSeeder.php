<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\BankAccount;

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
            BankAccount::create([
                'name' => 'Nu Bank',
                'agency' => '0001',
                'number_account' => "8229254",
                'digit_account' => "4",
                'bank_id' => 96,
                'tenant_id' => 1
            ]);
        }
        if(BankAccount::where('name', 'Caixa Kakogawa')->count() === 0) {
            BankAccount::create([
                'name' => 'Caixa Kakogawa',
                'agency' => '3531',
                'operation' => '013',
                'number_account' => "5093",
                'digit_account' => "5",
                'bank_id' => 33,
                'tenant_id' => 1
            ]);
        }
        if(BankAccount::where('name', 'Caixa Grevilha')->count() === 0) {
            BankAccount::create([
                'name' => 'Caixa Grevilha',
                'agency' => '3123',
                'operation' => '013',
                'number_account' => "18747",
                'digit_account' => "9",
                'bank_id' => 33,
                'tenant_id' => 1
            ]);
        }
    }
}
