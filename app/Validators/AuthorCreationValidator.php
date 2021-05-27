<?php

namespace App\Validators;

class AuthorCreationValidator extends BaseValidator
{
    public static function rules(): array
    {
        return [
            'name' => 'required|max:255',
        ];
    }
}
