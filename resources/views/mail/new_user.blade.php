@component('mail::message')
#@if ($user->gender == 'mujer') **Bienvenida** @else **Bienvenido** @endif **a la comunidad!**

Estamos felices de que te unas a nuestra familia, estamos aquí para apoyarte y para que cumplas tus objetivos.

Para comenzar, puedes cambiar tu contraseña que por defecto es: **purasangre**, acá abajo te dejaremos un enlace para que puedas actualizarla.

@component('mail::button', ['url' => route('password.reset', $token)])
Cambiar contraseña
@endcomponent

Saludos,

Pura Sangre CrossFit

@endcomponent