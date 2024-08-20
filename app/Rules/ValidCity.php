<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidCity implements Rule
{
    public function passes($attribute, $value)
    {
        return !preg_match('/[@!#$%&*]/', $value);
    }

    public function message()
    {
        return 'The :attribute must not contain special characters.';
    }
}
