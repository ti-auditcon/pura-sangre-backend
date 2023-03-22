@component('mail::message')
#Hola {{$user->first_name }},

Tu **Plan {{ $planuser->plan->plan }}** está a punto de vencer, la **fecha en que termina** es el **{{\Carbon\Carbon::parse($planuser->finish_date)->format('d/m/Y')}}**

**Fecha de pago:** @isset ($planuser->bill) {{\Carbon\Carbon::parse($planuser->bill->date)->format('d/m/Y')}} @else {{'no aplica'}} @endisset


**Monto:** @isset ($planuser->bill) {{'$ '.number_format($planuser->bill->amount, $decimal = 0, '.', '.')}} @else {{'$ 0'}} @endisset


Recuerda que puedes renovar tu plan pagando directamente en recepción o realizando una transferencia, te dejamos los datos de transferencia más abajo.

Saludos,

Pura Sangre CrossFit

@component('mail::panel')
# Datos de transferencia
# Banco: **Banco de Chile**
# Rut: **76.411.109-5**
# Tipo de cuenta: **Cuenta Corriente**
# Número de cuenta: ** 2121017703 **
# Nombre: **Pura Sangre CrossFit**
# Email: ** purasangrecrossfit@gmail.com **
@endcomponent


@endcomponent