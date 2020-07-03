<?php


namespace App\Services;

use App\Models\Car;

class CarService extends CRUDService
{
    /**
     * @var Car
     */
    protected $modelClass = Car::class;

    /**
     * @param Car $model
     * @param array $data
     */
    public function fill(&$model, $data)
    {
        $model->model = $data['model'];
        $model->year = $data['year'];
        $model->brand = $data['brand'];
        $model->license_plate = $data['license_plate'];
        $model->annual_licensing = $data['annual_licensing'];
        $model->annual_insurance = $data['annual_insurance'];
    }
}
