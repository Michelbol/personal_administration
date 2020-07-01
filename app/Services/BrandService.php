<?php


namespace App\Services;

use App\Models\Brand;

class BrandService extends CRUDService
{
    /**
     * @var Brand
     */
    protected $modelClass = Brand::class;

    /**
     * @param Brand $model
     * @param array $data
     */
    public function fill(&$model, $data)
    {
        $model->name = $data['name'];
    }
}
