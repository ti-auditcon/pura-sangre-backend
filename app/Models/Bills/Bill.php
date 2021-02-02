<?php

namespace App\Models\Bills;

use Carbon\Carbon;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Bills\Installment;
use App\Models\Bills\PaymentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use SoftDeletes;

    /**
     *  The attributes that should be mutated to dates.
     *
     *  @var array
     */
    protected $dates = ['date', 'deleted_at'];

    /**
     *  The attributes that should be cast to native types. Like a getter Method
     *
     *  @var  array
     */
    protected $casts = [
        'date' => 'datetime:d-m-Y'
    ];

    /**
     *  The attributes that are mass assignable.
     *
     *  @var array
     */
    protected $fillable = [
        'payment_type_id', 'plan_user_id', 'date',
        'start_date', 'finish_date', 'detail', 'amount'
    ];

    /**
     *  [payment_type description]
     *
     *  @method payment_type
     *
     *  @return [model]       [description]
     */
    public function payment_type()
    {
      return $this->belongsTo(PaymentType::class);
    }

    /**
     *  [plan_user description]
     *
     *  @return  [type]  [return description]
     */
    public function plan_user()
    {
        return $this->belongsTo('App\Models\Plans\PlanUser');
    }

    /**
     *  Generaate a bill for a plan_user
     *
     *  @param   PlanUser  $plan_user    [$plan_user description]
     *  @param   Flow      $paymentData  [$paymentData description]
     *
     *  @return  $this
     */
    public function makeFlowBill($plan_user, $paymentData)
    {
        return $this->create([
            'start_date' => $plan_user->start_date,
            'finish_date' => $plan_user->finish_date,
            'counter' => $plan_user->counter,
            'payment_type_id' => PaymentType::FLOW,
            'plan_user_id' => $plan_user->id,
            'date' => today(),
            'amount' => $paymentData['balance'],
            'total_paid' => $paymentData['amount'],
        ]);
    }
}
