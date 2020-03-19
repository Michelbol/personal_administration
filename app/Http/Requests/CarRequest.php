<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
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
            'model' => 'max:100',
            'license_plate' => 'required|max:15',
            'annual_licensing' => 'date_format:d/m/Y',
            'annual_insurance' => 'date_format:d/m/Y',
        ];
    }
}
