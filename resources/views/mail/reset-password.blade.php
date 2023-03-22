@component('mail::message')
#Hola,

Hola, en el siguiente enlace podras cambiar tu contraseña

@component('mail::button', ['url' => route('password.reset', $token)])
Cambiar contraseña
@endcomponent

Si no solicitaste cambiar tu contraseña, puedes ignorar este correo.

Saludos,

Pura Sangre CrossFit

@endcomponent