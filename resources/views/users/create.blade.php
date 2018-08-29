@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-6">
      <div class="ibox">
        {!! Form::open(['route' => 'users.store']) !!}
        <div class="ibox-body">
        <div class="row">
          <div class="col-sm-6 form-group mb-4">
            <div class="form-group inline @if($errors->has('first_name')) has-warning  @endif">
              <label class="col-form-label">Nombre</label>
              <input class="form-control " name="first_name" required>
            </div>
          </div>
          <div class="col-sm-6 form-group mb-4">
            <div class="form-group inline @if($errors->has('last_name')) has-warning  @endif">
              <label class="col-form-label">Apellido</label>
              <input class="form-control " name="last_name" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6 form-group mb-4">
            <div class="form-group inline @if($errors->has('phone')) has-warning  @endif">
              <label class="col-form-label">Teléfono</label>
              <div class="input-group mb-3">
                <span class="input-group-addon">+56 9</span>
                <input class="form-control " name="phone" type="tel" required>
              </div>
            </div>
          </div>
          <div class="col-sm-6 form-group mb-4">
            <div class="form-group inline @if($errors->has('email')) has-warning  @endif">
              <label class="col-form-label">Email</label>
              <input class="form-control " name="email" required>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6 form-group mb-4">
          	<div class="form-group inline">
          		<input type="hidden" name="count" value="1" />
              <div class="control-group" id="fields">
                <label class="control-label" for="field1">Nombre del contacto de emergencia</label>
                <div class="controls" id="profs">
                  <form class="input-append">
                    <div id="field">
                      <input autocomplete="off" class="input" id="field1" name="contact_name1" type="text" data-items="8"/>
                    </div>
                  </form>
                </div>
              </div>
          	</div>
          </div>
          <div class="col-sm-6 form-group mb-4">
          	<div class="form-group inline">
          		<input type="hidden" name="count" value="1" />
              <div class="control-group" id="fields">
                <label class="control-label" for="field1">Teléfono del contacto de emergencia</label>
                <div class="controls" id="profs">
                  <form class="input-append">
                    <div id="field">
                      <input autocomplete="off" class="input" id="field1" name="contact_phone1" type="text" data-items="8"/>
                      <button id="b1" class="btn add-more" type="button">+</button>
                    </div>
                  </form>
                <br>
                </div>
              </div>
          	</div>
          </div>
          <small>Presiona + para agregar mas de 1 contacto de emergencia</small>
        </div>

          <div class="form-group  @if($errors->has('status_user_id')) has-warning  @endif">
            <label class="form-control-label">Estado del Usuario*</label>
            <select class="selectpicker form-control"  name="user_id" data-live-search="true" required>
             <option value="">Asignar estado...</option>
             @foreach (App\Models\Users\StatusUser::all() as $status_user)
             <option value="{{$status_user->id}}" @if(old('status_user_id')==$status_user->id) selected @endif >{{$status_user->status_user}}</option>
             @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>Género</label>
            <div>
              <label class="radio radio-inline radio-info">
                <input type="radio" name="gender">
                <span class="input-span"></span>Masculino</label>
              <label class="radio radio-inline radio-info">
                <input type="radio" name="gender">
                <span class="input-span"></span>Femenino</label>
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

{{-- script to add inputs for more than 1 emergency contact --}}
<script>
$(document).ready(function(){
    var next = 1;
    $(".add-more").click(function(e){
        e.preventDefault();
        var addto = "#field" + next;
        var addRemove = "#field" + (next);
        next = next + 1;
        var newIn = '<input autocomplete="off" class="input form-control" id="field' + next + '" name="field' + next + '" type="text">';
        var newInput = $(newIn);
        var removeBtn = '<button id="remove' + (next - 1) + '" class="btn btn-danger remove-me" >-</button></div><div id="field">';
        var removeButton = $(removeBtn);
        $(addto).after(newInput);
        $(addRemove).after(removeButton);
        $("#field" + next).attr('data-source',$(addto).attr('data-source'));
        $("#count").val(next);

            $('.remove-me').click(function(e){
                e.preventDefault();
                var fieldNum = this.id.charAt(this.id.length-1);
                var fieldID = "#field" + fieldNum;
                $(this).remove();
                $(fieldID).remove();
            });
    });

});
</script>

@endsection
