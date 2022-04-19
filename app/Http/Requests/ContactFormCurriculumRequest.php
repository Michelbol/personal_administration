<?php

namespace App\Http\Requests;

use App\Models\Enum\ValidationEnum;
use Illuminate\Foundation\Http\FormRequest;

class ContactFormCurriculumRequest extends FormRequest
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
            'name' => [
                ValidationEnum::REQUIRED
            ],
            'email' => [
                ValidationEnum::REQUIRED,
                ValidationEnum::EMAIL
            ],
            'subject' => [
                ValidationEnum::NULLABLE
            ],
            'message' => [
                ValidationEnum::REQUIRED
            ],
            'g-recaptcha-response' => [
                ValidationEnum::RECAPTCHA
            ],
        ];
    }
}
