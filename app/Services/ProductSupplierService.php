<?php


namespace App\Services;


use App\Models\ProductSupplier;
use App\Repositories\ProductSupplierRepository;
use Exception;

class ProductSupplierService extends CRUDService
{
    /**
     * @var ProductSupplier
     */
    protected $modelClass = ProductSupplier::class;
    /**
     * @var ProductSupplierRepository
     */
    protected $repository;

    public function __construct()
    {
        $this->repository = new ProductSupplierRepository();
    }

    /**
     * @param ProductSupplier $model
     * @param array $data
     */
    public function fill(&$model, $data)
    {
        $model->code = $data['code'];
        $model->un = $data['un'];
        $model->product_id = $data['product_id'];
        $model->supplier_id = $data['supplier_id'];
        $model->brand_id = $data['brand_id'] ?? null;
    }

    /**
     * @param $data
     * @throws Exception
     */
    public function countOrCreate($data)
    {
        $count = $this->repository->countByCodeAndSupplierId($data['code'], $data['supplier_id']);
        if($count === 0){
            $this->create($data);
        }
    }

    public function find($id)
    {
        return $this->findById($id);
    }
}
