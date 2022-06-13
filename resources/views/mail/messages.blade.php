@component('mail::message')
#Hola {{ $mail_data->user }},

{{ $mail_data->message }}

@isset($mail_data->image_url)
<img src="{{ asset($mail_data->image_url) }}" style="max-width:100%" alt="App Logo"/>
@endisset

Saludos,

Pura Sangre CrossFit

@endcomponent