<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNewUserEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * $user data
     * 
     * @var array
     */
    public $user;

    /**
     * $token for reset password
     * 
     * @var string
     */
    public $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $token)
    {
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
        return $this->view('users.welcome.new')
                    ->subject('Bienvenido a PuraSangre!');
    }
}
