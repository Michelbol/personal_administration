<?php


namespace App\Repositories;


use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProductRepository
{
    /**
     * @param string $name
     * @return Product|Builder|Model|object|null
     */
    public function findOneProductByName(string $name)
    {
        return Product::whereName($name)->first();
    }

    /**
     * @param int $supplierId
     * @param string $code
     * @return Model|\Illuminate\Database\Query\Builder|object|null|Product
     */
    public function findOneProductBySupplierAndCode(int $supplierId, string $code)
    {
        return Product
            ::join('product_suppliers as ps', 'ps.product_id', 'products.id')
            ->join('suppliers as s', 's.id', 'ps.supplier_id')
            ->where('ps.supplier_id', $supplierId)
            ->where('code', $code)
            ->select('products.*')
            ->first();
    }
}
