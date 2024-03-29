@component('mail::message')
# Hola {{ $user->first_name }},

Hemos registrado el pago del **Plan {{ $bill->plan->plan }}** en el sistema, este plan** comienza el {{\Carbon\Carbon::parse($bill->start_date)->format('d/m/Y')}} y termina el {{\Carbon\Carbon::parse($bill->finish_date)->format('d/m/Y')}}**

Fecha de pago: {{ \Carbon\Carbon::parse($bill->payment_date)->format('d/m/Y') }}<br>
<b>Monto: {{'$ ' . number_format($bill->amount, $decimal = 0, '.', '.')}}</b>

@if ($bill_pdf)
Además puedes descargar tu boleta, a través del siguiente link:

@component('mail::button', ['url' => $bill_pdf])
Descargar boleta
@endcomponent
@endif

Saludos,

Pura Sangre CrossFit

@endcomponent
