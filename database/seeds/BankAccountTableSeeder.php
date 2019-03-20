<?php

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
        BankAccount::create([
            'name' => 'Nu Bank',
            'agency' => '0001',
            'number_account' => "8229254",
            'digit_account' => "4",
            'bank_id' => 96
        ]);
        BankAccount::create([
            'name' => 'Caixa Kakogawa',
            'agency' => '3531',
            'operation' => '013',
            'number_account' => "5093",
            'digit_account' => "5",
            'bank_id' => 33
        ]);
        BankAccount::create([
            'name' => 'Caixa Grevilha',
            'agency' => '3123',
            'operation' => '013',
            'number_account' => "18747",
            'digit_account' => "9",
            'bank_id' => 33
        ]);
    }
}
