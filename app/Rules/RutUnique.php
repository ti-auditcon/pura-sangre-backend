<?php

namespace App\Rules;

use App\Models\Users\User;
use Freshwork\ChileanBundle\Rut;
use Illuminate\Contracts\Validation\Rule;

class RutUnique implements Rule
{
    /**
     * User to be cheched
     * 
     * @var collection
     */
    protected $user;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $rut
     * @return boolean
     */
    public function passes($attribute, $rut)
    {
        // Parse rut to number to check with the DataBase
        $number_rut = (int) Rut::parse($rut)->number();

        if ( is_null($this->user) && User::where('rut', $number_rut)->first()) {
            return false;
        }

        // Get a user whose rut is equal to $number_rut
        // otherwise, assign a 0 to avoid errors
        $id_user_checked = optional(User::where('rut', $number_rut)->first())->id;

        if ( ! is_null($this->user) &&
             ! is_null($id_user_checked) &&
             $this->user->id !== $id_user_checked ) {

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
        return  'El rut ya ha sido tomado';
    }
}
