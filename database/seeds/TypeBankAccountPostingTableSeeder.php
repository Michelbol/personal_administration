<?php

use Illuminate\Database\Seeder;

class TypeBankAccountPostingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\TypeBankAccountPosting::create([
            'id'    => 1,
            'name'  => 'Juros Creditados'
        ]);
        \App\Models\TypeBankAccountPosting::create([
            'id'    => 2,
            'name' => 'Juros Debitados'
        ]);
        \App\Models\TypeBankAccountPosting::create([
            'id'    => 3,
            'name' => 'Trasnferências P/ Outra Conta'
        ]);
        \App\Models\TypeBankAccountPosting::create([
            'id'    => 4,
            'name' => 'Depósitos'
        ]);
        \App\Models\TypeBankAccountPosting::create([
            'id'    => 5,
            'name' => 'Pagamentos de Boleto'
        ]);
        \App\Models\TypeBankAccountPosting::create([
            'id'    => 6,
            'name' => 'Compras Cartão Débito'
        ]);
        \App\Models\TypeBankAccountPosting::create([
            'id'    => 7,
            'name' => 'Salário'
        ]);
        \App\Models\TypeBankAccountPosting::create([
            'id'    => 8,
            'name' => 'Saque'
        ]);
        \App\Models\TypeBankAccountPosting::create([
            'id'    => 9,
            'name' => 'Seguradora'
        ]);
    }
}
