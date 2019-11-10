<?php

use Illuminate\Database\Seeder;
use App\Models\TypeBankAccountPosting;

class TypeBankAccountPostingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(TypeBankAccountPosting::where('name', 'Juros Creditados')->count() === 0){
            TypeBankAccountPosting::create([
                'id'    => 1,
                'name'  => 'Juros Creditados'
            ]);
        }
        if(TypeBankAccountPosting::where('name', 'Juros Debitados')->count() === 0) {
            TypeBankAccountPosting::create([
                'id' => 2,
                'name' => 'Juros Debitados'
            ]);
        }
        if(TypeBankAccountPosting::where('name', 'Trasnferências P/ Outra Conta')->count() === 0) {
            TypeBankAccountPosting::create([
                'id' => 3,
                'name' => 'Trasnferências P/ Outra Conta'
            ]);
        }
        if(TypeBankAccountPosting::where('name', 'Depósitos')->count() === 0) {
            TypeBankAccountPosting::create([
                'id' => 4,
                'name' => 'Depósitos'
            ]);
        }
        if(TypeBankAccountPosting::where('name', 'Pagamentos de Boleto')->count() === 0) {
            TypeBankAccountPosting::create([
                'id' => 5,
                'name' => 'Pagamentos de Boleto'
            ]);
        }
        if(TypeBankAccountPosting::where('name', 'Compras Cartão Débito')->count() === 0) {
            TypeBankAccountPosting::create([
                'id' => 6,
                'name' => 'Compras Cartão Débito'
            ]);
        }
        if(TypeBankAccountPosting::where('name', 'Salário')->count() === 0) {
            TypeBankAccountPosting::create([
                'id' => 7,
                'name' => 'Salário'
            ]);
        }
        if(TypeBankAccountPosting::where('name', 'Saque')->count() === 0) {
            TypeBankAccountPosting::create([
                'id' => 8,
                'name' => 'Saque'
            ]);
        }
        if(TypeBankAccountPosting::where('name', 'Seguradora')->count() === 0) {
            TypeBankAccountPosting::create([
                'id' => 9,
                'name' => 'Seguradora'
            ]);
        }
        if(TypeBankAccountPosting::where('name', 'FGTS')->count() === 0) {
            TypeBankAccountPosting::create([
                'id' => 10,
                'name' => 'FGTS'
            ]);
        }
        if(TypeBankAccountPosting::where('name', 'PIS')->count() === 0) {
            TypeBankAccountPosting::create([
                'id' => 11,
                'name' => 'PIS'
            ]);
        }
    }
}
