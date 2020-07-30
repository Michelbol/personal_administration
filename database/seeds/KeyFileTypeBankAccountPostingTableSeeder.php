<?php

use Illuminate\Database\Seeder;
use App\Models\KeyFileTypeBankAccountPosting;

class KeyFileTypeBankAccountPostingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $othersIncome = 11;
        $internalTransference = 12;
        $incomeInterest = 4;
        $incomeTransference = 2;
        $othersExpensive = 10;
        $tim = 1;
        $billet = 11;
        $debitCard = 8;
        $salary = 1;
        $withdraw = 13;
        $insuranceCompany = 12;
        $fgts = 6;
        $pis = 7;

        $this->saveKeyFileTypeBankAccountPosting(1, 'REM BASICA',null, $othersIncome);
        $this->saveKeyFileTypeBankAccountPosting(1, 'CRED JUROS', null, $incomeInterest);
        $this->saveKeyFileTypeBankAccountPosting(1, 'CAN DB ACC', null,  $incomeInterest);
        $this->saveKeyFileTypeBankAccountPosting(1, 'SALAR PRIV', null,  $incomeInterest);
        $this->saveKeyFileTypeBankAccountPosting(1, 'ES DB ACC', null,  $incomeInterest);

        $this->saveKeyFileTypeBankAccountPosting(3, 'DOC ELET', null, $incomeTransference);
        $this->saveKeyFileTypeBankAccountPosting(3, 'TEV MESM T', $internalTransference, $internalTransference);
        $this->saveKeyFileTypeBankAccountPosting(3, 'DOC ELET E', $othersExpensive);
        $this->saveKeyFileTypeBankAccountPosting(3, 'TARIFA DOC', $othersExpensive);
        $this->saveKeyFileTypeBankAccountPosting(3, 'SAQUETERMINAL', $othersExpensive);

        $this->saveKeyFileTypeBankAccountPosting(4, 'CRED TEV', null, $incomeTransference);
        $this->saveKeyFileTypeBankAccountPosting(4, 'CRED TED', null, $incomeTransference);
        $this->saveKeyFileTypeBankAccountPosting(4, 'DP DINH AG', null, $incomeTransference);

        $this->saveKeyFileTypeBankAccountPosting(5, 'PAG FONE', $tim);
        $this->saveKeyFileTypeBankAccountPosting(5, 'PAG BOLETO', $billet);

        $this->saveKeyFileTypeBankAccountPosting(6, 'COMPRA ELO', $debitCard);

        $this->saveKeyFileTypeBankAccountPosting(7, 'TEDSALARIO', null, $salary);

        $this->saveKeyFileTypeBankAccountPosting(8, 'SAQUE ATM', $withdraw);
        $this->saveKeyFileTypeBankAccountPosting(8, 'SAQ CARTAO', $withdraw);

        $this->saveKeyFileTypeBankAccountPosting(9, 'SEGURADORA', $insuranceCompany);

        $this->saveKeyFileTypeBankAccountPosting(10, 'CRED FGTS', null,$fgts);

        $this->saveKeyFileTypeBankAccountPosting(11, 'ABONO PIS', null, $pis);
    }


    public function saveKeyFileTypeBankAccountPosting($type_id, $key_file, $expense_id = null, $income_id = null){
        if(KeyFileTypeBankAccountPosting::whereTypeId($type_id)->where('key_file', $key_file)->count() === 0) {
            KeyFileTypeBankAccountPosting::create([
                'type_id'  => $type_id,
                'key_file'  => $key_file,
                'expense_id'  => $expense_id,
                'income_id'  => $income_id,
            ]);
            return;
        }
        $typeBankAccountPosting = KeyFileTypeBankAccountPosting::whereTypeId($type_id)->where('key_file', $key_file)->first();
        $typeBankAccountPosting->key_file  = $key_file;
        $typeBankAccountPosting->expense_id  = $expense_id;
        $typeBankAccountPosting->income_id  = $income_id;
        $typeBankAccountPosting->save();
    }
}
