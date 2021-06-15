<?php

namespace App\Mail;

use App\Models\Invoicing\DTE;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\Plans\PlanUserFlow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

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
     *   At the start of creating the email get the data of the user and the bill
     */
    public function __construct($planUserFlow)
    {
        $this->user = $planUserFlow->user;

        $this->planUserFlow = $planUserFlow;
    }

    /**
     *  Build the message.
     *
     *  @return  $this
     */
    public function build()
    {
        if ($this->planUserFlowHasPDF()) {
            return $this->markdown('mail.new_plan_user')->with([
                'user' => $this->user,
                'bill' => $this->planUserFlow
            ])->attach(storage_path("app/public/{$this->planUserFlow->bill_pdf}"), [
                'as' => "boleta_{$this->planUserFlow->id}_{$this->user->first_name}.pdf",
                'mime' => 'application/pdf',
            ])->subject('Se ha registrado un pago en PuraSangre');
        }

        return $this->markdown('mail.new_plan_user')->with([
            'user' => $this->user,
            'bill' => $this->planUserFlow
        ])->subject('Se ha registrado un pago en PuraSangre');

    }

    /**
     * [planUserFlowHasPDF description]
     *
     * @return  bool    [return description]
     */
    public function planUserFlowHasPDF(): bool
    {
        if (Storage::has($this->planUserFlow->bill_pdf) &&
            $this->planUserFlow->hasPDFGeneratedAlready()) {
            return true;
        }

        return false;
    }
}
