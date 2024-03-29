@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar',['page'=>'student'])
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-6">
        <div class="ibox">
            <div class="ibox-head">
                <div class="ibox-title">Agregar usuario</div>
            </div>
            {!! Form::open(['route' => 'users.store']) !!}
            <div class="ibox-body">
                <div class="col-12 p-0 my-0 mt-3">
                    <span class="col-form-label hidden">Por favor, ingrese un rut válido</span>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group mb-2">
                        <div class="form-group inline @if($errors->has('first_name')) has-warning  @endif">
                            <label class="col-form-label">Nombre</label>
                         
                            <input class="form-control " name="first_name" value="{{ old('first_name') }}" required>
                        </div>
                    </div>
                    <div class="col-sm-6 form-group mb-2">
                        <div class="form-group inline @if($errors->has('last_name')) has-warning  @endif">
                            <label class="col-form-label">Apellido</label>
                           
                            <input class="form-control " name="last_name" value="{{ old('last_name') }}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group mb-2">
                        <div id="rut-group" class="form-group inline @if($errors->has('rut')) has-warning  @endif">
                            <label class="col-form-label">Rut</label>
                            
                            <input class="form-control" id="rut-src" name="rut" type="text" value="{{ old('rut') }}" required>
                           
                            <div class="col-12 p-0 my-0 mt-3">
                                <span class="col-form-label hidden">Por favor, ingrese un rut válido</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 form-group mb-2">
                        <div class="form-group">
                            <label class="font-normal">Fecha de nacimiento</label>
                           
                            <div class="input-group date">
                                <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                              
                                <input autocomplete="off" class="form-control datepicker-birthdate" name="birthdate" value="{{ old('birthdate') }}" type="text" value="{{ date('d-m-Y') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group mb-4">
                        <div class="form-group inline @if($errors->has('phone')) has-warning  @endif">
                            <label class="col-form-label">Teléfono</label>
                          
                            <div class="input-group mb-3">
                                <span class="input-group-addon">+56 9</span>
                              
                                <input class="form-control" name="phone" value="{{ old('phone') }}" type="tel" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 form-group mb-4">
                        <div class="form-group inline @if($errors->has('email')) has-warning  @endif">
                            <label class="col-form-label">Email</label>
                           
                            <input class="form-control " name="email" value="{{ old('email') }}" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group mb-2">
                        <div class="form-group @if($errors->has('since')) has-warning  @endif">
                            <label class="font-normal">Atleta desde</label>
                            
                            <div class="input-group date">
                                <span class="input-group-addon bg-white"><i class="fa fa-calendar"></i></span>
                                
                                <input autocomplete="off" class="form-control datepicker-since" name="since" value="{{ old('since') }}" type="text" value="{{ date('d-m-Y') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 form-group mb-4">
                        <div class="form-group inline @if($errors->has('address')) has-warning  @endif">
                            <label class="col-form-label">Dirección</label>
                           
                            {{-- <input class="form-control" placeholder="Ejemplo: Longitudinal Sur Km 188,9, Curicó" name="address" value="{{ old('address') }}"> --}}
                            <input id="pac-input" class="form-control" value="{{ old('address') }}" name="address"  placeholder="Ingresa una dirección" autocomplete="off"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group mb-4">
                        <div class="form-group inline @if($errors->has('contact_name')) has-warning  @endif">
                            <label class="col-form-label">Contacto de emergencia</label>
                            
                            <input class="form-control " name="contact_name" value="{{ old('contact_name') }}">
                        </div>
                    </div>
                    <div class="col-sm-6 form-group mb-4">
                        <div class="form-group inline @if($errors->has('contact_phone')) has-warning  @endif">
                            <label class="col-form-label">Teléfono de contacto de emergencia</label>
                           
                            <div class="input-group mb-3">
                                <span class="input-group-addon">+56 9</span>
                               
                                <input class="form-control" name="contact_phone" value="{{ old('contact_phone')}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-6">
                        <label class="checkbox checkbox-success">
                            <input type="checkbox" name="test_user" checked />
                        
                            <span class="input-span"></span>
                        
                            Activar plan de Prueba
                        </label>
                    </div>

                    <div class="col-6">
                        <label class="mr-3">Género</label>
                        <label class="radio radio-inline radio-info">
                            <input type="radio" name="gender" required value="hombre">

                            <span class="input-span"></span>

                            Masculino
                        </label>

                        <label class="radio radio-inline radio-info">
                            <input type="radio" name="gender" value="mujer">

                            <span class="input-span"></span>Femenino
                        </label>
                    </div>
                </div>

                <button class="btn btn-primary" type="submit">Ingresar Alumno</button>
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
    $('.datepicker-birthdate').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: true,
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

<script src="{{ asset('js/moment.min.js') }}"></script>

<script>
    var today = moment().format("DD-MM-YYYY");
    $('.datepicker-since').datepicker({
        todayBtn: "linked",
        keyboardNavigation: false,
        forceParse: true,
        calendarWeeks: true,
        format: "dd-mm-yyyy",
        startDate: today,
        endDate: "01-01-2030",
        language: "es",
        autoclose: true,
        maxViewMode: 3,
        todayHighlight: true
    });
</script>

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
{{-- END RUT --}}

    {{-- GOOGLE MAPS API --}}
    <script>
        function initAutocomplete() {
            // Create the search box and link it to the UI element.
            var input = document.getElementById('pac-input');
    
            var searchBox = new google.maps.places.SearchBox(input);
        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBdjSN29qOPy3mKi4MGOoRp9VWUP9pPaHc&libraries=places&callback=initAutocomplete" async defer>
    </script>
@endsection