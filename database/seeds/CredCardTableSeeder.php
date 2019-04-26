<?php

use Illuminate\Database\Seeder;

class CredCardTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\CreditCard::create([
            'name'  => 'Nu Bank',
            'limit' => 6750,
            'default_closing_date' => 1,
            'bank_id' => 96
        ]);
    }
}
