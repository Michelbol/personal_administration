<?php

namespace App\Models\Enum;

class ValidationEnum extends BasicEnum {
    const REQUIRED = 'required';
    const URL = 'url';
    const NULLABLE = 'nullable';
    const EMAIL = 'email';
    const RECAPTCHA = 'recaptcha';
}
