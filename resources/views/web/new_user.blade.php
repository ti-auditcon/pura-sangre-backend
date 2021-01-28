@extends('web.layout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="ibox ibox-fullheight">
            <div class="ibox-head">
                <div class="ibox-title">
                    Completa tu compra registrandote
                </div>

                <div class="ibox-tools">
                </div>
            </div>

            <div class="ibox-body">
                @isset($plan)
                    <div class="row justify-content-center">
                        <div class="col-11 col-lg-7">
                            <form x-data="newUser()" 
                                    x-init="flatpickr($refs.input, {{ json_encode((object)['dateFormat' => 'd-m-Y', 'locale' => 'es']) }});getSelectedPlan({{ $plan->id }});">
                                @csrf
                                <div class="ibox-body">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label class="col-form-label">Nombre</label>
                                        
                                            <input class="form-control" x-on:keyup="fill('first_name', $event)" name="first_name">

                                            <span x-show.transition.in="errors.first_name" x-text="errors.first_name" class="text-warning"></span>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="col-form-label">Apellido</label>
                                        
                                            <input class="form-control" 
                                                    x-on:keyup="fill('last_name', $event)"
                                                    name="last_name"
                                            />
                                                
                                            <span x-show.transition.in="errors.last_name" x-text="errors.last_name" class="text-warning"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 form-group">
                                            <label class="font-normal">Fecha de nacimiento</label>
                                        
                                            <div class="input-group date">
                                                {{-- <input id="datepicker-birthdate" type="text" x-on:change="console.log($event)" class="form-control"/> --}}
                                                    <input class="form-control"
                                                            x-ref="input"
                                                            type="text"
                                                            x-on:change="fill('birthdate', $event)"
                                                    />                                          
                                            </div>

                                            <span x-show.transition.in="errors.birthdate" x-text="errors.birthdate" class="text-warning"></span>
                                        </div>
                                        <div class="col-md-7 form-group">
                                            <label class="col-form-label">Dirección</label>
                                
                                            <input class="form-control"
                                                    x-on:keyup="fill('address', $event)"
                                                    name="address"
                                                    placeholder="Ingresa una dirección"
                                                    autocomplete="off"/>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group inline">
                                                <label class="col-form-label">Teléfono</label>
                                            
                                                <div class="input-group">
                                                    <span class="input-group-addon">+56 9</span>
                                                
                                                    <input class="form-control" x-on:keyup="fill('phone', $event)" name="phone" type="tel">
                                                </div>

                                                <span x-show.transition.in="errors.phone" x-text="errors.phone" class="text-warning"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group inline">
                                            <label class="col-form-label">Correo electronico</label>
                                        
                                            <input class="form-control" 
                                                    x-on:keyup="fill('email', $event)" 
                                                    type="email" 
                                                    name="email"
                                                />

                                            <span x-show.transition.in="errors.email" x-text="errors.email" class="text-warning"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <label class="mr-3">Género</label>
                                            <label class="radio radio-inline radio-info">
                                                <input type="radio" name="gender" value="hombre">
                                                <span class="input-span"></span>
                                                Masculino
                                            </label>

                                            <label class="radio radio-inline radio-info">
                                                <input type="radio" name="gender" value="mujer">
                                                <span class="input-span"></span>
                                                Femenino
                                            </label>
                                            
                                            <label class="radio radio-inline radio-info">
                                                <input type="radio" name="gender" value="other">
                                                <span class="input-span"></span>
                                                Otro
                                            </label>
                                        </div>
                                    </div>

                                    <button class="btn btn-primary"
                                            x-on:click="sendForm"
                                            type="button"
                                            x-bind:disabled="sendButton.disabled"
                                            x-text="sendButton.text">Registrarme y pagar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    Lo siento el plan que elegiste no esta disponible por el momento para ser contratado

                    Pero puedes elegir entre los siguientes

                    @foreach ($contractable_plans as $plan)
                        <span>
                            <a href="/new-user/create?plan_id={{ $plan->id }}">{{ $plan->plan }}</a>
                        </span>
                    @endforeach
                @endisset
            </div>
        </div>
    </div>
</div>
@endsection

@section('css') {{-- stylesheet para esta vista --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection


@section('scripts') {{-- scripts para esta vista --}}
    <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
    
    <script src="https://npmcdn.com/flatpickr@4.6.9/dist/l10n/es.js"></script>

    <script>
        const ENDPOINT_URL = @json(url('/new-user/store'));
    </script>
    {{-- AlpineJs --}}
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js" defer></script>
    {{-- Axios library --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    {{-- New User logic --}}
    <script src="{{ asset('/js/purasangre/web/new-user.js') }}"></script>
    {{-- RUT --}}
    <script src="{{ asset('js/jquery.rut.min.js') }}"></script>


    <script>
        $(function()  {
            $("input#rut-src").rut({
                formatOn: 'keyup',
                minimumLength: 8, // validar largo mínimo; default: 2
                validateOn: 'keyup' // si no se quiere validar, pasar null
            });
            
            $("input#rut-src").rut().on('rutInvalido', function(e) {
                $('#rut-group').addClass('has-error');
                $('#rut-group span').show();
                $('#rut-submit').prop('disabled', true);
            });
            $("input#rut-src").rut().on('rutValido', function(e, rut, dv) {
                $('#rut-group').removeClass('has-error');
                $('#rut-group span').hide();
                $('#rut-submit').prop('disabled', false);
            });
        })
    </script>

    {{-- <script src="{{ asset('/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('/js/bootstrap-datepicker.es.min.js') }}"></script>

    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script>
        let maxDate = moment().subtract('10', 'years').format("DD-MM-YYYY");

        $('#datepicker-birthdate').datepicker({
            keyboardNavigation: false,
            forceParse: true,
            calendarWeeks: false,
            format: "dd-mm-yyyy",
            endDate: maxDate,
            language: "es",
            autoclose: true,
            maxViewMode: 3,
        });
    </script> --}}
@endsection