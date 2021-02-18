@component('mail::message')
# Hola {{ $user->first_name }}


Aca esta el boton para verificar tu correo y pagar el plan que has elegido
Ademas tu contraseña por defecto es: purasangre
Te recomendamos una vez que termines de pagar cambiarla desde la App

@component('mail::button', ['url' => url("/finish-registration?token={$token}&email={$user->email}&plan_id={$plan_id}")])
    Verificar y pagar
@endcomponent

Muchas gracias,<br>
{{ config('app.name') }}
@endcomponent
