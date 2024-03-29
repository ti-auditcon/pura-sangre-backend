@extends('layouts.app')

@section('sidebar')

@include('layouts.sidebar',['page'=>'student'])

@endsection


@section('content')

<div class="row justify-content-center">
    <div class="col-12 col-xl-6">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Editar datos de {{$user->first_name}} {{$user->last_name}}</div>
            </div>

            {!! Form::open(['route' => ['users.update', $user->id], 'method' => 'PUT', 'files' => true]) !!}

            <div class="ibox-body">
                <div class="row">
                    <div class="col-sm-6 form-group mb-2">
                        <div class="form-group inline @if($errors->has('first_name')) has-warning  @endif">
                            <label class="col-form-label">Nombre</label>

                            <input class="form-control"
                                   name="first_name"
                                   value="{{ $user->first_name }}"
                                   required
                            />
                        </div>
                    </div>

                    <div class="col-sm-6 form-group mb-2">
                        <div class="form-group inline @if($errors->has('last_name')) has-warning  @endif">
                            <label class="col-form-label">Apellido</label>

                            <input class="form-control"
                                   name="last_name"
                                   value="{{ $user->last_name }}"
                                   required
                            />
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 form-group mb-2">
                        <div id="rut-group"
                             class="form-group inline @if($errors->has('rut')) has-warning @endif"
                        >
                            <label class="col-form-label">Rut</label>

                            <input class="form-control"
                                   id="rut-src"
                                   name="rut"
                                   type="text"
                                   value="{{ old('rut', $user->rut_formated) }}"
                                   required
                            />

                            <div class="col-12 p-0 my-0 mt-3">
                                <span class="col-form-label hidden">
                                    Por favor, ingrese un rut válido
                                </span>
                            </div>
                        </div>
                    </div>

                        <div class="col-sm-6 form-group mb-2">
                            <label class="col-form-label">Contraseña</label>

                            <button class="btn btn-warning form-control sweet-user-reset-password"
                                    data-user-name="{{ $user->full_name }}"
                                    data-user-id="{{ $user->id }}"
                            >
                                Restablecer contraseña
                            </button>
                        </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group mb-2">
                        <div class="form-group inline @if($errors->has('phone')) has-warning  @endif">
                            <label class="col-form-label">Número de celular</label>

                            <div class="input-group mb-3">
                                <span class="input-group-addon">+56 9</span>

                                <input class="form-control"
                                       name="phone"
                                       value="{{ $user->phone }}"
                                       type="tel"
                                />
                            </div>
                        </div>
                    </div>
                    {{-- {{dd($user->emergency)}} --}}
                    <div class="col-sm-6 form-group mb-2">
                        <div class="form-group inline @if($errors->has('email')) has-warning  @endif">
                            <label class="col-form-label">Email</label>

                            <input class="form-control"
                                   name="email"
                                   value="{{ $user->email }}"
                                   @if (!Auth::user()->hasRole(1)) readonly @endif
                                   required
                            />
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group mb-2">
                        <div class="form-group" id="birthdate-picker">
                            <label class="font-normal">Fecha de nacimiento</label>
                            <div class="input-group date">
                                <span class="input-group-addon bg-white">
                                    <i class="fa fa-calendar"></i>
                                </span>

                                <input class="form-control"
                                       name="birthdate"
                                       value="{{ Carbon\Carbon::parse($user->birthdate)->format('d-m-Y') }}"
                                       type="text"
                                       value="{{ date('d/m/Y') }}"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 form-group mb-2">
                        <div class="form-group" id="since-picker">
                            <label class="font-normal">Atleta desde</label>

                            <div class="input-group date">
                                <span class="input-group-addon bg-white">
                                    <i class="fa fa-calendar"></i>
                                </span>

                                <input class="form-control"
                                       name="since"
                                       value="{{ Carbon\Carbon::parse($user->since)->format('d-m-Y') }}"
                                       type="text"
                                       value="{{ date('d/m/Y') }}"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 form-group mb-2">
                        <div class="form-group ">
                            <label class="col-form-label">Dirección</label>

                            <div class="input-group">
                                <input id="pac-input"
                                       name="address"
                                       value="{{ $user->address }}"
                                       class="form-control"
                                       type="text"
                                       placeholder="Ingresa una dirección"
                                       autocomplete="off"
                                />

                                <div id="div-badge-address" class="badge d-flex align-items-center pl-3 pr-3">
                                    <i id="i-icon" class="fa fa-map-marker" aria-hidden="true"></i>
                                </div>
                            </div>

                            {{-- hidden lat and long inputs --}}
                            <input id="lat-input" type="text" name="lat" value="{{ $user->lat }}" hidden />

                            <input id="long-input" type="text" name="lng" value="{{ $user->lng }}" hidden/>
                        </div>
                    </div>
                </div>

            <div class="row">
                <div class="col-sm-6 form-group mb-4">
                    <div class="form-group inline @if($errors->has('contact_name')) has-warning  @endif">
                        <label class="col-form-label">Contacto de emergencia</label>

                        <input class="form-control"
                               name="contact_name"
                               @if($user->emergency) value="{{ $user->emergency->contact_name }}" @endif
                        />
                    </div>
                </div>
                <div class="col-sm-6 form-group mb-4">
                    <div class="form-group inline @if($errors->has('contact_phone')) has-warning  @endif">
                        <label class="col-form-label">Teléfono de contacto de emergencia</label>

                        <div class="input-group mb-3">
                            <span class="input-group-addon">+56 9</span>

                            <input class="form-control"
                                   name="contact_phone"
                                   @if($user->emergency) value="{{ $user->emergency->contact_phone }}" @endif
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <button class="btn btn-primary mr-2" type="submit">
                    Actualizar datos
                </button>

                <a class="btn btn-secondary" href="{{ route('users.show', $user->id) }}">Volver</a>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
</div>



@endsection


@section('css') {{-- stylesheet para esta vista --}}

@endsection


@section('scripts') {{-- scripts para esta vista --}}

<script>
    $('#birthdate-picker .input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        format: "dd-mm-yyyy",
        startDate: "01-01-1910",
        endDate: "01-01-2030",
        language: "es",
        orientation: "bottom auto",
        autoclose: true,
        maxViewMode: 3,
        todayHighlight: true
    });

    $('#since-picker .input-group.date').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        format: "dd-mm-yyyy",
        startDate: "01-01-1910",
        endDate: "01-01-2030",
        language: "es",
        orientation: "bottom auto",
        autoclose: true,
        maxViewMode: 3,
        todayHighlight: true
    });
</script>


<script>
    jQuery(function() {
        jQuery("input[type=file]").change(function() {
            readURL(this);
        });

        const readURL = (input) => {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = (e) => {
                    jQuery('#logo-img').attr('src', e.target.result)
                    jQuery('#container-logo').css('display', 'block');
                }
                reader.readAsDataURL(input.files[0]);
            }
        };
    })
</script>

<script>
    $(function() {
        $("input:file").change(function() {
            $("#imgback").prop('hidden', true);
            // console.log("si");
            // var fileName = $(this).val();
            // $(".filename").html(fileName);
        });
    });
</script>

{{-- RUT --}}
<script src="{{ asset('/js/jquery.rut.min.js') }}"></script>

<script>
    $(function() {
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
    });
</script>
{{-- END RUT --}}

   {{-- GOOGLE MAPS API --}}
   <script>
    function initAutocomplete() {
        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');

        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            // console.log('Place been changed');
            // place variable will have all the information you are looking for.

            $("#lat-input").val(place.geometry['location'].lat());
            $("#long-input").val(place.geometry['location'].lng());

            if(place.geometry['location'].lat() && place.geometry['location'].lng()) {
                // console.log(place.geometry['location'].lat());
                $('#div-badge-address').removeClass();

                $('#div-badge-address').addClass('badge d-flex align-items-center pl-3 pr-3 badge-success');
            } else {
                // console.log('no hay coordenadas');
                $('#div-badge-address').removeClass();

                $('#div-badge-address').addClass('badge d-flex align-items-center pl-3 pr-3 badge-warning');
            }
        });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBdjSN29qOPy3mKi4MGOoRp9VWUP9pPaHc&libraries=places&callback=initAutocomplete" async defer></script>

<script>
    $( document ).ready(function() {
        // console.log('ready listo');
        if (!$("#lat-input").val() || !$("#long-input").val()) {
            $('#div-badge-address').addClass('badge-warning');
            // console.log('no hay valores');
        } else {
            $('#div-badge-address').addClass('badge-success');
        }
    });
</script>

<script>
    $('.sweet-user-reset-password').click(function(e) {
        e.preventDefault();
        var userName = $(this).data('user-name');
        var userId = $(this).data('user-id');

        swal({
            title: `Seguro desea restablecer la contraseña de ${userName}?`,
            text: "No olvide avisar al usuario de este cambio, para que sepa cual es su nueva contraseña",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn-warning',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Restablecer',
            closeOnConfirm: true
        }, function() {
            $.ajax({
                method: "POST",
                url: `/users/${userId}/reset-password`,
                data: {
                    _method: 'PATCH',
                    _token: $('meta[name=csrf-token]').attr("content")
                },
            }).done(function(response) {
                console.log(response);
                toastr.success(response.success);
            }).fail(response => {
                console.log(response.fail);

                toastr.warning('No se ha podido restablecer la contraseña');
            });
        });
    });
</script>
@endsection