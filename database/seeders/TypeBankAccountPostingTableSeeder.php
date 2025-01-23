<?php

namespace Database\Seeders;

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
        $this->saveTypeBankAccountPosting('Juros Creditados',1);
        $this->saveTypeBankAccountPosting('Transferências P/ Outra Conta', 3);
        $this->saveTypeBankAccountPosting('Depósitos', 4);
        $this->saveTypeBankAccountPosting('Pagamentos de Boleto', 5);
        $this->saveTypeBankAccountPosting('Compras Cartão Débito', 6);
        $this->saveTypeBankAccountPosting('Salário', 7);
        $this->saveTypeBankAccountPosting('Saque', 8);
        $this->saveTypeBankAccountPosting('Seguradora', 9);
        $this->saveTypeBankAccountPosting('FGTS', 10);
        $this->saveTypeBankAccountPosting('PIS', 11);
    }

    public function saveTypeBankAccountPosting($name, $id = null){
        if(TypeBankAccountPosting::whereName($name)->count() === 0) {
            TypeBankAccountPosting::create([
                'id'    => $id,
                'name'  => $name,
            ]);
            return;
        }
        $typeBankAccountPosting = TypeBankAccountPosting::whereName($name)->first();
        $typeBankAccountPosting->name = $name;
        $typeBankAccountPosting->save();
    }
}
