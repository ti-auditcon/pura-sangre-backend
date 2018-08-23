<?php

namespace App\Policies\Bills;

use App\Models\Users\User;
use App\Models\Bills\Bill;
use Illuminate\Auth\Access\HandlesAuthorization;

class BillPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the bill.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Bills\Bill  $bill
     * @return mixed
     */
    public function view(User $user, Bill $bill)
    {
        return $user->id === $bill->user_id;
    }

    /**
     * Determine whether the user can create bills.
     *
     * @param  \App\Models\Users\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the bill.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Bills\Bill  $bill
     * @return mixed
     */
    public function update(User $user, Bill $bill)
    {
        //
    }

    /**
     * Determine whether the user can delete the bill.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Bills\Bill  $bill
     * @return mixed
     */
    public function delete(User $user, Bill $bill)
    {
        //
    }

    /**
     * Determine whether the user can restore the bill.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Bills\Bill  $bill
     * @return mixed
     */
    public function restore(User $user, Bill $bill)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the bill.
     *
     * @param  \App\Models\Users\User  $user
     * @param  \App\Models\Bills\Bill  $bill
     * @return mixed
     */
    public function forceDelete(User $user, Bill $bill)
    {
        //
    }
}
