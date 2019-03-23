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
        if(\App\Models\TypeBankAccountPosting::where('name', 'Juros Creditados')->count() === 0){
            \App\Models\TypeBankAccountPosting::create([
                'id'    => 1,
                'name'  => 'Juros Creditados'
            ]);
        }
        if(\App\Models\TypeBankAccountPosting::where('name', 'Juros Debitados')->count() === 0) {
            \App\Models\TypeBankAccountPosting::create([
                'id' => 2,
                'name' => 'Juros Debitados'
            ]);
        }
        if(\App\Models\TypeBankAccountPosting::where('name', 'Trasnferências P/ Outra Conta')->count() === 0) {
            \App\Models\TypeBankAccountPosting::create([
                'id' => 3,
                'name' => 'Trasnferências P/ Outra Conta'
            ]);
        }
        if(\App\Models\TypeBankAccountPosting::where('name', 'Depósitos')->count() === 0) {
            \App\Models\TypeBankAccountPosting::create([
                'id' => 4,
                'name' => 'Depósitos'
            ]);
        }
        if(\App\Models\TypeBankAccountPosting::where('name', 'Pagamentos de Boleto')->count() === 0) {
            \App\Models\TypeBankAccountPosting::create([
                'id' => 5,
                'name' => 'Pagamentos de Boleto'
            ]);
        }
        if(\App\Models\TypeBankAccountPosting::where('name', 'Compras Cartão Débito')->count() === 0) {
            \App\Models\TypeBankAccountPosting::create([
                'id' => 6,
                'name' => 'Compras Cartão Débito'
            ]);
        }
        if(\App\Models\TypeBankAccountPosting::where('name', 'Salário')->count() === 0) {
            \App\Models\TypeBankAccountPosting::create([
                'id' => 7,
                'name' => 'Salário'
            ]);
        }
        if(\App\Models\TypeBankAccountPosting::where('name', 'Saque')->count() === 0) {
            \App\Models\TypeBankAccountPosting::create([
                'id' => 8,
                'name' => 'Saque'
            ]);
        }
        if(\App\Models\TypeBankAccountPosting::where('name', 'Seguradora')->count() === 0) {
            \App\Models\TypeBankAccountPosting::create([
                'id' => 9,
                'name' => 'Seguradora'
            ]);
        }
    }
}
