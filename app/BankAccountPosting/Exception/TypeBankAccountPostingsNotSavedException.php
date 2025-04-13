<?php

namespace App\BankAccountPosting\Exception;

class TypeBankAccountPostingsNotSavedException extends \Exception {

    private array $typeBankAccountPostingsNotSaved;

    public function __construct(
        array $typeBankAccountPostingNotSaved,
        string $message = "Existem tipos não salvos",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        $this->typeBankAccountPostingsNotSaved = $this->prepareTypeBankAccountPostingToSerialize($typeBankAccountPostingNotSaved);

        parent::__construct($message, $code, $previous);
    }

    public function getTypeBankAccountPostingNotSaved(): array {
        return $this->typeBankAccountPostingsNotSaved;
    }

    private function prepareTypeBankAccountPostingToSerialize(array $typeBankAccountPostingsNotSaved): array {
        $serializedTypeBankAccountPostingsNotSaved = [];
        foreach ($typeBankAccountPostingsNotSaved as $typeBankAccountPostingNotSaved) {
            $serializedTypeBankAccountPostingsNotSaved[] = (string)$typeBankAccountPostingNotSaved;
        }
        return $serializedTypeBankAccountPostingsNotSaved;
    }
}
