<?php

namespace App\Validators;

class BookCreationValidator extends BaseValidator
{
    public static function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'authors' => 'required|array',
            'authors.*' => 'required|string|distinct',
        ];
    }
}
