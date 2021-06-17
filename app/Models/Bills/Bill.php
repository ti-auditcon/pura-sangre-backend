<?php

namespace App\Models\Bills;

use Carbon\Carbon;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Bills\Installment;
use App\Models\Bills\PaymentType;
use App\Models\Plans\PlanUserFlow;
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
     *  [setDateAttribute description]
     *
     *  @param   [type]  $value  [$value description]
     *
     *  @return  [type]          [return description]
     */
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::parse($value);
    }

    /**
     * [setStartDateAttribute description]
     *
     * @param   [type]  $value  [$value description]
     *
     * @return  [type]          [return description]
     */
    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = Carbon::parse($value);
    }

    /**
     * [setFinishDateAttribute description]
     *
     * @param   [type]  $value  [$value description]
     *
     * @return  [type]          [return description]
     */
    public function setFinishDateAttribute($value)
    {
        $this->attributes['finish_date'] = Carbon::parse($value);
    }

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
     *  Upate a registered payment
     *
     *  @param   Request  $request
     *
     *  @return  $this  
     */
    public function updateBill($request)
    {
        return $this->update([
            'amount'          => (int) $request->amount,
            'date'            => $request->date,
            'payment_type_id' => (int) $request->payment_type_id,
            'plan_user_id'    => $request->plan_user_id,
            'start_date'      => $request->start_date,
            'finish_date'     => $request->finish_date,
        ]);
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

    /**
     *  [storeBill description]
     *
     *  @param   [type]  $request   [$request description]
     *  @param   [type]  $planuser  [$planuser description]
     *
     *  @return  $this
     */
    public function storeBill($request, $planuser)
    {
        dd($request, $planuser);
        $this->create([
            'plan_user_id'    => $planuser->id,
            'payment_type_id' => $request->payment_type_id,
            'date'            => Carbon::parse($request->date),
            'start_date'      => $planuser->start_date,
            'finish_date'     => $planuser->finish_date,
            'detail'          => $request->detalle,
            'amount'          => $request->amount,
        ]);
    }
}
