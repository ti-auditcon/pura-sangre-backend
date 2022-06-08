@component('mail::message')
#Hola {{$user->first_name }}, @if ($user->gender == 'mujer') **Bienvenida** @else **Bienvenido** @endif **  a la comunidad PuraSangre CrossFit!**

Estamos felices de que te unas a nuestra familia, estamos para apoyarte y para que cumplas tus objetivos.

Para comenzar, puedes cambiar tu contraseña que por defecto es: **purasangre**, acá abajo te dejaremos un enlace para que puedas actualizarla.

@component('mail::button', ['url' => route('password.reset', $token)])
Cambiar contraseña
@endcomponent

Saludos,

Pura Sangre CrossFit

@endcomponent