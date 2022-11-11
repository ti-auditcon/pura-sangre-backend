<?php

namespace App\Models\Bills;

use App\Models\Bills\Installment;
use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
        /**
     * The payment is done
     *
     * @var  int
     */
    const PAID = 1;

    /**
     * The payment canceled status
     *
     * @var  int
     */
    const CANCELLED = 3;

    protected $fillable = ['payment_status'];
}
