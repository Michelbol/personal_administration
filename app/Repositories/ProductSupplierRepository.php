<?php


namespace App\Repositories;

use App\Models\ProductSupplier;
use DB;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Collection;

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

    /**
     * @param string $code
     * @param int $supplierId
     * @return ProductSupplier
     */
    public function findOneCodeAndSupplierId(string $code, int $supplierId)
    {
        return ProductSupplier
            ::whereCode($code)
            ->whereSupplierId($supplierId)
            ->where('p.deleted_at', null)
            ->join('products as p', 'p.id', 'product_suppliers.product_id')
            ->first('product_suppliers.*');
    }

    /**
     * @return Collection
     */
    public function selectOptions()
    {
        return ProductSupplier
            ::from('product_suppliers as ps')
            ->join('products as p', 'p.id', 'ps.product_id')
            ->join('suppliers as s', 's.id', 'ps.supplier_id')
            ->where('p.deleted_at', null)
            ->orderBy('name')
            ->get(
                [
                    $this->queryRawProductName(),
                    'ps.id'
                ]);
    }

    /**
     * @param string $name
     * @return Expression
     */
    public function queryRawProductName($name = 'name')
    {
        if (config('database.default') === 'sqlite') {
            return DB::raw(
                "COALESCE(p.name, '') || ' - ' || COALESCE(s.fantasy_name, '') || ' - ' || COALESCE(ps.un, '') || ' - ' || COALESCE(ps.code, '') as $name"
            );
        }
        return DB::raw(
            "CONCAT(COALESCE(p.name, ''),' - ',COALESCE(s.fantasy_name, ''),' - ',COALESCE(ps.un, ''),' - ',COALESCE(ps.code, '')) as $name"
        );
    }
}
