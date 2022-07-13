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
    <div class="register" x-data="newUser()" 
      x-init="flatpickr($refs.input, {{ json_encode((object)['dateFormat' => 'd-m-Y', 'locale' => 'es']) }});getSelectedPlan({{ $plan->id }});"
    >
      <div class="header">
            <h3 class="text-center" x-text="title">Completa tu compra registrandote</h3>
        </div>
        <div class="content">
            @isset($plan)
                <div class="row justify-content-center">
                    <div class="col-12 mb-3">
                        <div class="ibox-body" x-show="!formStatus.isFinished">
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label class="col-form-label">Nombre</label>
                                
                                    <input class="form-control" x-on:keyup="fill('first_name', $event)" name="first_name">

                                    <span x-show.transition.in="errors.first_name" x-text="errors.first_name" class="text-danger fs-6 fw-light lh-1"></span>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label class="col-form-label">Apellido</label>
                                
                                    <input class="form-control" 
                                            x-on:keyup="fill('last_name', $event)"
                                            name="last_name"
                                    />

                                    <span x-show.transition.in="errors.last_name" x-text="errors.last_name" class="text-danger fs-6 fw-light lh-1"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group mb-3" id="rut-group">
                                    <label class="col-form-label">Rut</label>
                                
                                    <input id="rut-src" class="form-control" x-on:keyup="fill('rut', $event)" name="rut">

                                    <div id="div-invalid-message" class="col-12 p-0 my-0 mt-3" hidden>
                                        <span class="col-form-label">Por favor, ingrese un rut válido</span>
                                    </div>
                                    <span x-show.transition.in="errors.rut" x-text="errors.rut" class="text-danger fs-6 fw-light lh-1"></span>
                                </div>

                                <div class="col-md-6 form-group mb-3">
                                    <label class="col-form-label">Correo electronico</label>
                                
                                    <input class="form-control" 
                                            x-on:keyup="fill('email', $event)" 
                                            type="email" 
                                            name="email"
                                        />

                                    <span x-show.transition.in="errors.email" x-text="errors.email" class="text-danger fs-6 fw-light lh-1"></span>
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
                                        <span class="input-group-text" id="basic-addon1">+56 9</span>
                                    
                                        <input class="form-control" x-on:keyup="fill('phone', $event)" name="phone" type="tel">
                                    </div>

                                    <span x-show.transition.in="errors.phone" x-text="errors.phone" class="text-danger fs-6 fw-light lh-1"></span>
                                </div>

                            </div>

                                <div class="col-md-12 form-group mb-3">
                                    <label class="col-form-label">Dirección</label>
                        
                                    <input class="form-control"
                                            x-on:keyup="fill('address', $event)"
                                            name="address"
                                            placeholder="Ingresa una dirección"
                                            autocomplete="off"/>
                                </div>
                            <div class="form-group row">
                                <div class="col-6 mb-3">
                                    <label class="d-block fw-light">Sexo</label>
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
                                </div>
                            </div>

                            <button class="btn btn-sm btn-primary px-3 py-2"
                                x-on:click="sendForm"
                                type="button"
                                :disabled="sendButton.disabled"
                                x-text="sendButton.text"
                            >
                                Registrarme y pagar &gt;
                            </button>

                            <div x-show="redirectButton">
                                Si no te redirige, has clic aqui
                                <a :href="redirectButton">Ir a pagar &gt;</a>
                            </div>
                        </div>
                        <div x-show="errors.email == 'El email ya ha sido registrado.'" class="my-4 py-4 px-3 atention-message rounded">
                            <span>Parece que ya tienes cuenta en PuraSangre.</span>
                            <br>
                            <div>
                                <div x-show="!instructions.areSended">
                                    <span>-Si tuviste problemas al comprar y quieres que te enviemos las instrucciones de nuevo</span>

                                    <input type="text" x-on:keyup="fillEmail($event)"
                                            x-model="instructions.email" class="form-control mt-3 mb-2" placeholder="Ingresa tu correo"/>

                                    <div x-show="instructions.error !== null">
                                        <p class="text-danger" x-text="instructions.error"></p>
                                    </div>
                                    
                                    <button x-on:click="requestInstructions()"
                                            x-bind:disabled="instructions.buttonIsDisabled"
                                            class="btn btn-sm btn-primary px-3 py-2"
                                    >
                                        Enviame las instrucciones nuevamente &gt;
                                    </button>
                                </div>
                                <div x-show="instructions.areSended" x-text="instructions.message"></div>
                            </div>
                            
                            {{-- <br>
                            <span>Quiero terminar mi pago</span> --}}
                        </div>

                        <div x-show="formStatus.isFinished" x-text="formStatus.message" class="text-center" style=""></div>
                    </div>
                </div>
            @else
                <div>
                    Lo siento el plan que elegiste no esta disponible por el momento para ser contratado
                    Pero puedes elegir entre los siguientes
                    @foreach ($contractable_plans as $plan)
                        <span>
                            <a href="/new-user/create?plan_id={{ $plan->id }}">{{ $plan->plan }}</a>
                        </span>
                    @endforeach
                </div>
            @endisset
            {{-- <br> <br> --}}
            <div class="mt-3">
                <div class="text-center">
                    <span class="text-muted" style="font-size: 15px">
                        Recuerda que la aplicacion de Pura Sangre CrossFit esta disponible para Android e IOS
                    </span>
                    <div class="mt-2">
                        <a href="https://play.google.com/store/apps/details?id=purasangrecrossfit.app.com&hl=es" target="_blank" style="text-decoration: none;">
                            <img src="https://purasangrecrossfit.cl/static/742bfdbbf3bf40c98083a857d2b86366/28cc6/badge-googleplay.webp" style="width: 120px">
                        </a>
                        <a href="https://itunes.apple.com/us/app/pura-sangre-crossfit/id1447657358" target="_blank" style="text-decoration: none;">
                            <img src="https://purasangrecrossfit.cl/static/518a43709f3b8eec33fb05e43db48533/4d1c6/badge-appstore.webp" style="width: 110px">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
    <script src="https://npmcdn.com/flatpickr/dist/flatpickr.min.js"></script>
    <script src="https://npmcdn.com/flatpickr@4.6.9/dist/l10n/es.js"></script>
    <script>
        const ENDPOINT_URL = @json(url('/new-user/store'));
    </script>
    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    {{-- AlpineJs --}}
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js" defer></script>
    {{-- Axios library --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    {{-- New User logic --}}
    <script src="{{ asset('/js/purasangre/web/new-user.js') }}"></script>
    {{-- RUT --}}
    <script src="{{ asset('/js/jquery.rut.min.js') }}"></script>

    <script>
        $(function()  {
            $("input#rut-src").rut({
                formatOn: 'keyup',
                minimumLength: 8, // validar largo mínimo; default: 2
                validateOn: 'keyup' // si no se quiere validar, pasar null
            });
            
            $("input#rut-src").rut().on('rutInvalido', function(e) {
                console.log('hola es inacisdfljsdk');
                $('#rut-group').addClass('text-danger');
                $('#div-invalid-message').attr('hidden', false);
            });
            $("input#rut-src").rut().on('rutValido', function(e, rut, dv) {
                $('#rut-group').removeClass('text-danger');
                $('#div-invalid-message').attr('hidden', true);
            });
        })
    </script>
</body>
</html>
