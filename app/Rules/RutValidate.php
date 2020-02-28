<?php

namespace App\Rules;

use App\Models\Users\User;
use Freshwork\ChileanBundle\Rut;
use Illuminate\Contracts\Validation\Rule;

class RutValidate implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $rut
     * 
     * @return boolean
     */
    public function passes($attribute, $rut)
    {
        if (!Rut::parse($rut)->quiet()->validate()) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return  'El formato del RUT es incorrecto';
    }
}
