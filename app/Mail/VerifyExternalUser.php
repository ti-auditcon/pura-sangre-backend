<?php

namespace App\Mail;

use App\Models\Users\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyExternalUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * User
     *
     * @var  User
     */
    public $user;

    /**
     * Token
     *
     * @var  string
     */
    public $token;
    
    /**
     * Token
     *
     * @var  string
     */
    public $plan_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $token, $plan_id = null)
    {
        $this->user = $user;

        $this->token = $token;

        $this->plan_id = $plan_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('web.emails.verify_user')
                    ->subject('Finaliza tu inscripcion a PuraSangre!');
    }
}
