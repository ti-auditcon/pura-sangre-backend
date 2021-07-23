<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\Plans\PlanUserFlow;
use Illuminate\Queue\SerializesModels;

class NewPlanUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     *  The user who made the purchase of the plan
     *
     *  @var  User
     */
    protected $user;

    /**
     *  The bill of the purchase
     *
     *  @var  PlanUserFlow
     */
    protected $planUserFlow;

    /**
     *  At the start of creating the email get the data of the user and the bill
     */
    public function __construct($planUserFlow, $bill_pdf = null)
    {
        $this->planUserFlow = $planUserFlow;

        $this->user = $planUserFlow->user;

        $this->bill_pdf = $bill_pdf;
    }

    /**
     *  Build the message.
     *
     *  @return  $this
     */
    public function build()
    {
        return $this->markdown('mail.new_plan_user')->with([
            'user' => $this->user,
            'bill' => $this->planUserFlow,
            'bill_pdf' => $this->bill_pdf
        ])->subject('Se ha registrado un pago en PuraSangre');
    }
}
