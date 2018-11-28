<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $demo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($demo)
    {
        $this->demo = $demo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('messages.template')
                    ->subject($this->demo->subject)
                    ->from('raul.berrios@auditcon.cl');
    }
}
        //text define la plantilla que se usará
        // ->text('mails.demo_plain')
 

        // return $this->from('sender@example.com')

        // ->attach(public_path('/images').'/demo.jpg', [
        //         'as' => 'demo.jpg',
        //         'mime' => 'image/jpeg',
        //  ])