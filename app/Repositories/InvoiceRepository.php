<?php


namespace App\Repositories;


use App\Models\Invoice;
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

    /**
     * @param string $number
     * @param int $supplier_id
     * @param string $series
     * @return int
     */
    public function countInvoiceByNumberAndSupplierAndSeries(string $number, int $supplier_id, string $series)
    {
        return Invoice
            ::whereSupplierId($supplier_id)
            ->whereNumber($number)
            ->whereSeries($series)
            ->count();
    }
}
