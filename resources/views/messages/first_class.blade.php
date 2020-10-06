@component('mail::message')
#**Felicitaciones {{ $data->first_name }}!!**

**Acabas de asistir a tu primera clase de prueba!**
Ya has dado el primer paso para mejorar tu salud y estado físico. Te desafiamos a seguir con la **Experiencia Pura Sangre CrossFit** en tus siguientes **Clases de Prueba!**

No esperes más y **reserva en nuestra App tu segunda clase de prueba!**

Por último queremos seguir mejorando, y tu opinión es importante para poder entregar el mejor servicio y cumplas tus objetivos junto a nosotros!

Por Favor responde esta breve encuesta.

@component('mail::button', ['url' => 'https://docs.google.com/forms/d/e/1FAIpQLScAqssB8Fecb2N0BNOsXbYpLcc4UlxZe-zDZ9N9gzNvOVHHkA/viewform?embedded=true'])
Encuesta Pura Sangre
@endcomponent

{{-- 
<iframe src="https://docs.google.com/forms/d/e/1FAIpQLScAqssB8Fecb2N0BNOsXbYpLcc4UlxZe-zDZ9N9gzNvOVHHkA/viewform?embedded=true"
        width="640"
        height="1346"
        frameborder="0"
        marginheight="0"
        marginwidth="0"
>Cargando…</iframe> --}}
@endcomponent
