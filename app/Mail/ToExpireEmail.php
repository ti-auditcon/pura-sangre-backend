<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ToExpireEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $planuser;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $planuser)
    {
        $this->user = $user;
        $this->planuser = $planuser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('messages.plan_vencimiento_template')
                    ->subject('Tu plan esta a punto de vencer');
    }
}