<?php

namespace App\Validators;

class BookEditingValidator extends BaseValidator
{
    public static function rules(): array
    {
        return [
            'name' => 'nullable|max:255',
            'authors' => 'nullable|array',
            'authors.*' => 'required_with:authors|string|distinct',
        ];
    }
}
