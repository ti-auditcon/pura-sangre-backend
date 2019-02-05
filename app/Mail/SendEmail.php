<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $demo;
    public $user;
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($demo, $user, $token)
    {
        $this->demo = $demo;
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('messages.template')
        //             ->subject($this->demo->subject)
        //             ->from('contacto@purasangrecrossfit.cl');
                return $this->view('users.welcome.new')
                    ->subject('Bienvenido a PuraSangre!')
                    ->from('contacto@purasangrecrossfit.cl');
    }
}