<?php

namespace App\Mail;

use App\Models\Users\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GoneAwayUserEmail extends Mailable
{
    /**
     *  Name of the user to send email
     *
     *  @var string
     */
    public $user;

    /**
     *  Create a new message instance.
     *
     *  @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     *  Build the message.
     *
     *  @return $this
     */
    public function build()
    {
        return $this->markdown('messages.gone_away_user')
                    ->from('contacto@purasangrecrossfit.cl')
                    ->subject("{$this->user} te extrañamos!");
    }
}
