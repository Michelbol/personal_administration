<?php

namespace App\BankAccountPosting;

use App\Ofx;
use App\Repositories\BankAccountRepository;
use App\Repositories\BankRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class BankAccountPostingOfxFileReader {


    public function __construct(
        private BankAccountRepository $bankAccountRepository,
        private BankRepository $bankRepository,
    ){}


    public function readFiles(array $filesOfx): void {
        DB::beginTransaction();
        foreach ($filesOfx as $fileOfx) {
            $this->readFile($fileOfx);
        }
        DB::commit();
    }

    public function readFile(UploadedFile $fileOfx): void {
        $ofx = new Ofx($fileOfx);
        $bankAccount = $this->searchForBankAccountFromOfxInfo($ofx);

        $bankAccountPostingOfx = BankAccountPostingOfxResolver::resolve($bankAccount->bank);
        $bankAccountPostingOfx->saveBankAccountPostingFromOfx($ofx, $bankAccount);

        $typeBankAccountPostingNotSaved = $bankAccountPostingOfx->retrieveTypeBankAccountPostingNotSaved();
        if (sizeof($typeBankAccountPostingNotSaved) !== 0) {
            throw new \Exception('\nExistem tipos não salvos: ' . implode(",", $typeBankAccountPostingNotSaved));
        }
    }

    private function searchForBankAccountFromOfxInfo($ofx)
    {
        $bank = $this->bankRepository->findBankByNumber((int)$ofx->bankId);
        $accountNumber = (string) $ofx->acctId;
        $number_account = substr($accountNumber, 0, 8);
        return $this->bankAccountRepository->findBankAccountByBankAndAccountNumber($bank, (int)$number_account);
    }
}
