<?php

namespace App\Models\Plans;

use Carbon\Carbon;
use App\Models\Plans\Plan;
use App\Models\Users\User;
use App\Models\Plans\PlanUser;
use App\Models\Bills\PaymentType;
use App\Models\Bills\PaymentStatus;
use App\Models\Plans\FlowOrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanUserFlow extends Model
{
    use SoftDeletes;
    
    /**
     *  Name of the table in the database
     *
     *  @var  string
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
        'start_date',
        'finish_date',
        'counter',
        'plan_status_id',
        'plan_id',
        'user_id',
        'paid',
        'amount',
        'observations',
        'payment_date',
        'bill_pdf',
        'sii_token',
    ];

    /**
     *  Relationship with the user model (who belongs the plan)
     *
     *  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     *  Undocumented function
     *
     *  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     *  methodDescription
     *
     *  @return  \Illuminate\Database\Eloquent\Relations\BelongsTo
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
            'finish_date'     => today()->addMonths($plan->plan_period->period_number),
            'user_id'         => $userId,
            'plan_id'         => $plan->id,
            'payment_type_id' => PaymentType::FLOW,
            'plan_status_id'  => FlowOrderStatus::PENDIENTE,
            'amount'          => round($total),
            'counter'         => $plan->class_numbers * $plan->plan_period->period_number * $plan->daily_clases,
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

    /**
     *  The plan has been paid
     *
     *  @return  bool
     */
    public function isAlreadyPaid(): bool
    {
        return $this->paid === PaymentStatus::PAID;
    }

    /**
     *  The plan has been cancelled
     *
     *  @return  bool
     */
    public function isCancelled(): bool
    {
        return $this->paid === PaymentStatus::CANCELLED;
    }

    /**
     *  The plan has already a receipt generated to SII
     *
     *  @return  bool
     */
    public function isAlreadyIssuedToSII()
    {
        return (bool) $this->sii_token;
    }

    /**
     *  If the PlanUserFlow has been paid or has been cancelled,
     *  return false
     *
     *  @return  bool
     */
    public function isNotAvailableToBePaid(): bool
    {
        return $this->isAlreadyPaid() || $this->isCancelled();
    }

    /**
     *  Check if the Bill has the sii_token
     *
     *  @return  bool
     */
    public function hasNotSiiToken(): bool
    {
        return ! $this->sii_token;
    }

    /**
     *  Check if the bill has the pdf generated
     *
     *  @return  bool
     */
    public function hasPDFGeneratedAlready(): bool
    {
        return (bool) $this->bill_pdf;
    }

    /**
     *  The opposite of hasPDFGeneratedAlready
     *
     *  @return  bool
     */
    public function hasntPDFGenerated(): bool
    {
        return !$this->hasPDFGeneratedAlready();
    }

    /**
     *  Create a PlanUserFlow into database
     *
     *  @return  $this
     */
    public function createOne($request, $plan_user)
    {
        return $this->create([
            'start_date'      => $plan_user->start_date,
            'finish_date'     => $plan_user->finish_date,
            'counter'         => $plan_user->counter,
            'plan_id'         => $plan_user->plan_id,
            'user_id'         => $plan_user->user_id,
            'paid'            => true,
            'amount'          => $request->amount,
            'payment_type_id' => $request->payment_type_id,
            'plan_status_id'  => FlowOrderStatus::PAGADO,
            'observations'    => "Compra de plan: {$plan_user->plan->plan} - {$plan_user->user->full_name}",
            'payment_date'    => Carbon::parse($request->date),
            'sii_token'       => !boolval($request->is_issued_to_sii) ? 'sin emision' : null,
        ]);
    }
}
