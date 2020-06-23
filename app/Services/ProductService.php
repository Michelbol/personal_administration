<?php


namespace App\Services;


use App\Models\Product;

class ProductService extends CRUDService
{
    /**
     * @var Product
     */
    protected $modelClass = Product::class;

    /**
     * @param Product $model
     * @param array $data
     */
    public function fill(&$model, $data)
    {
        $model->name = $data['name'];
    }
}
