<?php

namespace App\Http\Requests;

use App\Models\Enum\ValidationEnum;
use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
            'company_name' => ValidationEnum::REQUIRED,
            'fantasy_name' => ValidationEnum::REQUIRED,
            'cnpj' => ValidationEnum::REQUIRED,
            'address' => ValidationEnum::REQUIRED,
            'address_number' => ValidationEnum::REQUIRED,
            'neighborhood' => ValidationEnum::REQUIRED,
            'city' => ValidationEnum::REQUIRED,
            'state' => ValidationEnum::REQUIRED,
        ];
    }
}
