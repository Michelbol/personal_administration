<?php


namespace App\Repositories;


use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InvoiceRepository
{
    /**
     * @param string $cnpj
     * @return Supplier|Builder|Model|object|null
     */
    public function findOneSupplierByCnpj(string $cnpj)
    {
        return Supplier::whereCnpj($cnpj)->first();
    }
}
