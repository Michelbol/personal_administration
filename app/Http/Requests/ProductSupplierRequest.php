<?php

namespace App\Http\Requests;

use App\Models\Enum\ValidationEnum;
use Illuminate\Foundation\Http\FormRequest;

class ProductSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => ValidationEnum::REQUIRED,
            'un' => ValidationEnum::REQUIRED,
            'product_id' => ValidationEnum::REQUIRED,
            'supplier_id' => ValidationEnum::REQUIRED,
        ];
    }
}
