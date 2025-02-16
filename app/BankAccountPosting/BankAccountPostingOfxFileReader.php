<?php

namespace App\BankAccountPosting;

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\BankAccountPosting;
use App\Models\Enum\TypeBankAccountPostingEnum;
use App\Models\KeyFileTypeBankAccountPosting;
use App\Models\TypeBankAccountPosting;
use App\Ofx;
use App\Repositories\BankAccountRepository;
use App\Repositories\BankRepository;
use App\Repositories\TypeBankAccountPostingRepository;
use App\Services\BankAccountPostingService;
use Carbon\Carbon;
use GuzzleHttp\Psr7\UploadedFile;
use function PHPUnit\Framework\isNull;

class BankAccountPostingOfxFileReader {


    public function __construct(
        private BankAccountPostingService $bankAccountPostingService,
        private BankAccountRepository $bankAccountRepository,
        private BankRepository $bankRepository,
        private BankAccountPostingOfxCaixa $bankAccountPostingOfxCaixa,
        private TypeBankAccountPostingRepository $typeBankAccountPostingRepo,
    ){}


    public function readFiles(array $filesOfx): void {
        DB::beginTransaction();
        foreach ($filesOfx as $fileOfx) {
            $this->readFile($fileOfx);
        }
        DB::commit();
    }

    public function readFile(UploadedFile $fileOfx): void {
        $type_bank_account_posting_not_saves = [];
        $ofx = new Ofx($fileOfx);
        $bankAccount = $this->mountBankAccountOfx($ofx);
        foreach ($ofx->bankTranList as $transactions) {
            $keyFileTypeBankAccountPosting = $this->bankAccountPostingOfxCaixa->buildKeyFileTypeBankAccountPosting($transactions);
            $typeBankAccountPosting = $this->typeBankAccountPostingRepo->getTypeByKeyFile((string)$transactions->MEMO);
            $bankAccountPosting = $this->bankAccountPostingOfxCaixa->mountBankAccountPostingOfx($transactions, $bankAccount, $keyFileTypeBankAccountPosting);
            if (is_null($typeBankAccountPosting)) {
                $type_bank_account_posting_not_saves[] = $transactions->MEMO;
                continue;
            }
            if (sizeof($type_bank_account_posting_not_saves) === 0) {
                $bankAccountPosting->save();
            }
        }
        if (sizeof($type_bank_account_posting_not_saves) !== 0) {
            throw new \Exception('\nExistem tipos não salvos: ' . implode(",", $type_bank_account_posting_not_saves));
        }
    }

    private function mountBankAccountOfx($ofx)
    {
        $bank = $this->bankRepository->findBankByNumber((int)$ofx->bankId);
        $accountNumber = (string) $ofx->acctId;
        $number_account = substr($accountNumber, 0, 8);
        return $this->bankAccountRepository->findBankAccountByBankAndAccountNumber($bank, (int)$number_account);
    }
}
