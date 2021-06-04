<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class MyResetPassword extends Notification
{
    use Queueable;

    public $token;

    public static $toMailCallback;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }
        return (new MailMessage)
            ->subject(Lang::get('Cambio de Contraseña!'))
            ->markdown('mail.passresets.resetpass', ['token' => $this->token]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }
}

            // return (new MailMessage)
            // ->subject(Lang::get('Cambio de Contraseña'))
            // ->line(Lang::get('Hola, en el siguiente enlace podras cambiar tu contraseña.'))
            // ->action(Lang::get('Reset Password'), url(config('app.url').route('password.reset', $this->token, false)))
            // ->line(Lang::get('Si no has pedido cambio o actualización de contraseña solo ignora este correo.'));
