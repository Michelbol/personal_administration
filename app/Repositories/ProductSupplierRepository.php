<?php


namespace App\Repositories;

use App\Models\ProductSupplier;

class ProductSupplierRepository
{
    /**
     * @param string $code
     * @param int $supplierId
     * @return int
     */
    public function countByCodeAndSupplierId(string $code, int $supplierId)
    {
        return ProductSupplier
            ::whereCode($code)
            ->whereSupplierId($supplierId)
            ->where('p.deleted_at', null)
            ->join('products as p', 'p.id', 'product_suppliers.product_id')
            ->count();
    }
}
