<?php

namespace App\Validators;

use Illuminate\Validation\Validator as LaravelValidator;

interface Validator
{
    public static function make(array $attributes): LaravelValidator;
    public static function rules(): array;
}
