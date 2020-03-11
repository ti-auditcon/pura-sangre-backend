@component('mail::message')
#**Hola {{ $user->first_name }},**

Hemos notado que has dejado de entrenar, no pierdas la motivación por lograr tus objetivos, estamos para seguir mejorando tu estado físico, salud y bienestar.

Queremos que vuelvas y juntos mejorar tu calidad de vida! Escríbenos a nuestro WhatsApp o responde este correo.

@component('mail::button', ['url' => '', 'color' => 'green'])
Contáctanos!
@endcomponent

> *"En la vida no puedes retroceder, pero si puedes volver a empezar."*


La comunidad te espera!

Pura Sangre CrossFit
@endcomponent