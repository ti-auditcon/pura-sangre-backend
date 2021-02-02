@component('mail::message')
# Hola {{ $user->first_name }}

Aca esta el boton para verificar tu correo y pagar el plan que has elegido

@component('mail::button', ['url' => url("/finish-registration?token={$token}&email={$user->email}&plan_id={$plan_id}")])
    Verificar y pagar
@endcomponent

Muchas gracias,<br>
{{ config('app.name') }}
@endcomponent
