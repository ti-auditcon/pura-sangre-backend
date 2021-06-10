<?php

namespace App\Models\Plans;

use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Bills\PaymentType;
use App\Models\Plans\FlowOrderStatus;
use Illuminate\Database\Eloquent\Model;

class PlanUserFlow extends Model
{
    /**
     * Name of the table inthe Database
     *
     * @var  string
     */
    protected $table = 'plan_user_flows';
    
    /**
     *  Values to be treated like date formar
     *
     * @var  array
     */
    protected $dates = ['deleted_at', 'start_date', 'finish_date'];
    
    /**
     *  Massive assignment
     *
     *  @var  array
     */
    protected $fillable = [
        'start_date', 'finish_date', 'amount', 'observations',
        'bill_pdf', 'sii_token', 'counter', 'plan_status_id',
        'discount_id', 'plan_id', 'user_id', 'payment_date', 'paid'
    ];

    /**
     *  Undocumented function
     *
     *  @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     *  Undocumented function
     *
     *  @return void
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     *  methodDescription
     *
     *  @return  returnType
     */
    public function planUser()
    {
        return $this->belongsTo(PlanUser::class);
    }

    /**
     *  Generate an order
     *
     *  @param   Plan     $plan
     *  @param   integer  $userId
     *
     *  @return  $this
     */
    public function makeOrder($plan, $userId)
    {
        $fee = $plan->amount * 0.0396;
        $total = $plan->amount + $fee;

        return $this->create([
            'start_date'      => today(),
            'finish_date'     => today()->addMonths($plan->plan_period_id),
            'user_id'         => $userId,
            'plan_id'         => $plan->id,
            'payment_type_id' => PaymentType::FLOW,
            'plan_status_id'  => FlowOrderStatus::PENDIENTE,
            'amount'          => round($total),
            'counter'         => $plan->class_numbers,
            'observations'    => "Compra de plan: {$plan->plan}",
        ]);
    }

    /**
     *  Checks if the Plan user flow is paid
     *
     *  @return  boolean
     */
    public function isPaid()
    {
        return $this->plan_status_id === FlowOrderStatus::PAGADO;
    }

    /**
     *  [annul description]
     *
     *  @param   [type]$observations  [$observations description]
     *
     *  @return  [type]               [return description]
     */
    public function annul($observations = null)
    {
        $this->update([
            'plan_status_id' => FlowOrderStatus::ANULADO,
            'observations' => $observations
        ]);
    }
    
    /**
     * [pay description]
     *
     *  @param   [type]$observations  [$observations description]
     *
     *  @return  [type]               [return description]
     */
    public function changeStatusToPaid($observations = null)
    {
        $this->update([
            'plan_status_id' => FlowOrderStatus::PAGADO,
            'observations' => $observations
        ]);
    }
}
