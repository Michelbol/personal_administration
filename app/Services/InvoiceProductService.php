<?php


namespace App\Services;

use App\Models\InvoiceProduct;

class InvoiceProductService extends CRUDService
{

    /**
     * @var InvoiceProduct
     */
    protected $modelClass = InvoiceProduct::class;

    /**
     * @param InvoiceProduct $model
     * @param array $data
     */
    public function fill(&$model, $data)
    {
        $model->name = $data['name'];
        $model->un = $data['un'];
        $model->code = $data['code'];
        $model->quantity = $data['quantity'];
        $model->unitary_value = $data['unitary_value'];
        $model->total_value = $data['total_value'];
    }

    public function createMany(array $data)
    {
        return InvoiceProduct::insert($data);
    }
}
