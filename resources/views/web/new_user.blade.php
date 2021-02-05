<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Completa tu compra y registro — Pura Sangre CrossFit</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <link href="{{asset('/css/web-register.css')}}" rel="stylesheet" />
</head>
<body class="register-wrapper">
    <div class="register">
        <div class="header">
            {{-- <p>
                Paso 1 de 2
            </p> --}}
            <h3>Completa tu compra registrandote</h3>
        </div>
        <div class="content">
            @isset($plan)
                <div class="row justify-content-center">
                    <div 
                        class="col-12 mb-3" x-data="newUser()" 
                        x-init="flatpickr($refs.input, {{ json_encode((object)['dateFormat' => 'd-m-Y', 'locale' => 'es']) }});getSelectedPlan({{ $plan->id }});"
                    >
                        <div class="ibox-body" x-show="!formStatus.isFinished">
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label class="col-form-label">Nombre</label>
                                
                                    <input class="form-control" x-on:keyup="fill('first_name', $event)" name="first_name">

                                    <span x-show.transition.in="errors.first_name" x-text="errors.first_name" class="text-danger fs-6 fw-light lh-1"></span>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label class="col-form-label">Apellido</label>
                                
                                    <input class="form-control" 
                                            x-on:keyup="fill('last_name', $event)"
                                            name="last_name"
                                    />
                                        
                                    <span x-show.transition.in="errors.last_name" x-text="errors.last_name" class="text-danger fs-6 fw-light lh-1"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label class="col-form-label">Fecha de nacimiento</label>
                                
                                    <div class="date">
                                        {{-- <input id="datepicker-birthdate" type="text" x-on:change="console.log($event)" class="form-control"/> --}}
                                            <input class="form-control"
                                                    x-ref="input"
                                                    type="text"
                                                    x-on:change="fill('birthdate', $event)"
                                            />                                          
                                    </div>

                                    <span x-show.transition.in="errors.birthdate" x-text="errors.birthdate" class="text-danger fs-6 fw-light lh-1"></span>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                        <label class="col-form-label">Teléfono</label>
                                    
                                        <div class="input-group">
                                            {{-- <span class="input-group-addon">+56 9</span> --}}
                                            <span class="input-group-text" id="basic-addon1">+56 9</span>
                                        
                                            <input class="form-control" x-on:keyup="fill('phone', $event)" name="phone" type="tel">
                                        </div>

                                        <span x-show.transition.in="errors.phone" x-text="errors.phone" class="text-danger fs-6 fw-light lh-1"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group mb-3">
                                    <label class="col-form-label">Dirección</label>
                        
                                    <input class="form-control"
                                            x-on:keyup="fill('address', $event)"
                                            name="address"
                                            placeholder="Ingresa una dirección"
                                            autocomplete="off"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label class="col-form-label">Correo electronico</label>
                                
                                    <input class="form-control" 
                                            x-on:keyup="fill('email', $event)" 
                                            type="email" 
                                            name="email"
                                        />

                                    <span x-show.transition.in="errors.email" x-text="errors.email" class="text-danger fs-6 fw-light lh-1"></span>
                                </div>
                                
                                <div class="col-md-6 form-group mb-3">
                                    <label class="col-form-label">Elige una contraseña</label>
                                
                                    <input class="form-control"
                                            x-on:keyup="fill('password', $event)" 
                                            type="password" 
                                            name="password"
                                        />

                                    <span x-show.transition.in="errors.password" x-text="errors.password" class="text-danger fs-6 fw-light lh-1"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-6 mb-3">
                                    <label class="d-block fw-light">Género</label>
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

                            <a class="btn btn-sm btn-primary px-3 py-2"
                                x-on:click="sendForm"
                                type="button"
                                x-bind:disabled="sendButton.disabled"
                                x-text="sendButton.text"
                            >
                                Registrarme y pagar
                            </a>

                            <div x-show="redirectButton">
                                Si no te redirige, has clic aqui
                                <a :href="redirectButton">Ir a pagar</a>
                            </div>
                        </div>
                        <div x-show="formStatus.isFinished" x-text="formStatus.message"></div>

                        <div x-show="errors.email" class="my-4 py-4 px-3 atention-message rounded">
                            <span>Parece que ya tienes cuenta en PuraSangre. Tal vez quieras:</span>
                            <br>
                            <div>
                                <div x-show="!instructions.areSended">
                                    <span>Tuve problemas al comprar y quiero que me envien las instrucciones de nuevo</span>

                                    <input type="text" x-on:keyup="fillEmail($event)"
                                            x-model="instructions.email" class="form-control mt-3 mb-2" placeholder="Ingresa tu correo"/>
                                    {{-- <input type="text" x-on:keyup="instructions.email" class="form-control"/> --}}
                                    <div x-show="instructions.error !== null">
                                        <p x-text="instructions.error"></p>
                                    </div>
                                    <button x-on:click="requestInstructions()"
                                            x-bind:disabled="instructions.buttonIsDisabled"
                                            class="btn btn-sm btn-primary px-3 py-2"
                                    >
                                        Enviame las instrucciones nuevamente
                                    </button>
                                </div>
                                <div x-show="instructions.areSended" x-text="instructions.message"></div>
                            </div>
                            
                            {{-- <br>
                            <span>Quiero terminar mi pago</span> --}}
                        </div>

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
            {{-- <br> <br> --}}
            <div class="text-muted">
                Recuerda que la aplicacion de PuraSangre esta disponible para Android e IOS
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
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
</body>
</html>
