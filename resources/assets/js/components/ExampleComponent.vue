<template>
 
<div class="ibox">
    <div class="ibox-head">
        <div class="ibox-title">Alumnos</div>
        <button class="btn btn-success" data-toggle="modal" data-target="#user-assign">Agregar alumno a la clase</button>
        <input type="hidden" value="" name="user_id">
        <button class="btn btn-success sweet-user-join">
        <i class=""></i>Reservar</button>
    </div>
    <div class="ibox-body">
      <div class="ibox-fullwidth-block">
        <table id="students-table" class="table table-hover">
          <thead class="thead-default thead-lg">
            <tr>
              <th width="80%">Alumno</th>
              <th width="20%">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="student in students">
                <td>{{student.first_name}} {{student.last_name}}</td>
                <td><button class="btn btn-outline-info btn-icon-only btn-circle btn-sm btn-thick sweet-user-delete" type="button" @click="createAlumnos(student.id)"><i class="la la-trash"></i></button>
                </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
</div>

</template>

<script>
    import axios  from 'axios'
    
    export default {
        props: ['clase'],
        data () {
            return {
                students : [],
            }
        },
        created: function() {
            this.getAlumnos();
        },
        methods: {
          getAlumnos: function() {
            var urlIdeas = '/clases/'+this.clase+'/reservations';
            axios.get(urlIdeas).then(response => {
                this.students = response.data
            });
          },
          createAlumnos: function() {
              var url = '/clases/'+this.clase+'/users/';
              var student = this.student.id;
              axios.post(url, {
                id: this.newKeep
              }).then(response => {
                this.getKeeps();
                this.newKeep = '';
                this.errors = [];
                $('#create').modal('hide');
                toastr.success('Nueva tarea creada con éxito');
              }).catch(error => {
                this.errors = 'Corrija para poder crear con éxito'
              });
            },
        }
        // mounted: function () {
        //     console.log('hola adentor');
        //     var prueba = '/clases/'+this.clase+'/reservations';
        //     axios.get(prueba).then(response => 
        //     console.log(response));
        // }
    }
</script>



   <!-- <div class="ibox">
        <div class="ibox-head">
          <div class="ibox-title">Alumnos</div>
          @if (Auth::user()->hasRole(1))
            <button class="btn btn-success" data-toggle="modal" data-target="#user-assign">Agregar alumno a la clase</button>
          @else
            {!! Form::open(['route' => ['clases.users.store', 'clase' => $clase->id], 'method' => 'post']) !!}
                <input type="hidden" value="{{Auth::user()->id}}" name="user_id">
                <button class="btn btn-success sweet-user-join" data-id="{{$clase->id}}" data-name="{{$clase->date}}">
                <i class=""></i>Reservar</button>
            {!! Form::close() !!}
          @endif
        </div>
        <div class="ibox-body">
          <div class="ibox-fullwidth-block">
            <table id="students-table" class="table table-hover">
              <thead class="thead-default thead-lg">
                <tr>
                  <th width="80%">Alumno</th>
                  @if (Auth::user()->hasRole(1) || Auth::user()->hasRole(2))
                  <th width="20%">Acciones</th>
                  @endif
                </tr>
              </thead>
              <tbody>
                @foreach ($clase->reservations as $reservation)
                <tr>
                  <td>
                    <a class="media-img" href="javascript:;">
                        <img class="img-circle" src="{{url('/img/users/u'.rand(1,11).'.jpg')}}" alt="image" width="54">
                    </a>
                    @if($reservation->user->status_user_id == 1 )
                      <span class="badge-success badge-point"></span>
                    @elseif($reservation->user->status_user_id == 2 )
                      <span class="badge-danger badge-point"></span>
                    @elseif($reservation->user->status_user_id == 3 )
                      <span class="badge-warning badge-point"></span>
                    @endif
                    <a @if (Auth::user()->hasRole(1) || Auth::user()->hasRole(2)) href="{{url('/users/'.$reservation->user->id)}}" @endif>
                      {{$reservation->user->first_name}} {{$reservation->user->last_name}}
                    </a>
                  </td>
                  @if (Auth::user()->hasRole(1))
                  <td>
            {!! Form::open(['route' => ['clases.users.destroy', 'clase' => $clase->id, 'user' => $reservation->user->id], 'method' => 'delete', 'id'=>'delete'.$reservation->user->id]) !!}
                    <button class="btn btn-outline-info btn-icon-only btn-circle btn-sm btn-thick sweet-user-delete" type="button"
            data-id="{{$reservation->user->id}}" data-name="{{$reservation->user->first_name}} {{$reservation->user->last_name}}"><i class="la la-trash"></i></button>
            {!! Form::close() !!}
                  </td>
                  @endif
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
    </div> -->