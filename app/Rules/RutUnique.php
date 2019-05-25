<?php

namespace App\Rules;

use App\Models\Users\User;
use Freshwork\ChileanBundle\Rut;
use Illuminate\Contracts\Validation\Rule;

class RutUnique implements Rule
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
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $rut = Rut::parse($value)->number();
        if (User::where('rut', $rut)->first()) {
            return false;
        } else {
            return true;
        } 
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return  'El rut ya ha sido tomado';
    }
}
