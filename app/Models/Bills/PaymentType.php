<?php

namespace App\Models\Bills;

use App\Models\Bills\Bill;
use Illuminate\Database\Eloquent\Model;

/**
 * [PaymentType description]
 */
class PaymentType extends Model
{
    /**
     * [FLOW description]
     *
     * @var [type]
     */
    const FLOW = 6;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['payment_type'];

    /**
     * [bill description]
     * @method bill
     * @return [model] [description]
     */
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}
