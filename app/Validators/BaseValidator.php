<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator as LaravelValidator;

abstract class BaseValidator implements Validator
{
    private function __construct() {}

    public static function make(array $attributes): LaravelValidator
    {
        return ValidatorFacade::make($attributes, static::rules());
    }
}
