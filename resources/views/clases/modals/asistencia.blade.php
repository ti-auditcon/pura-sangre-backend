<div class="modal fade" id="confirm-assistance-modal" tabindex="-1" role="dialog"  aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      {!! Form::open(['route' => ['clase.confirm', $clase->id], 'method' => 'POST', 'id'=>'confirm']) !!}
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Asistentes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <table id="students-table" class="table table-hover">
            <thead class="thead-default">
               <tr>
                  <th width="70%">Alumno</th>
                  <th width="10%">Estado</th>
                  <th width="10%">Asistencia</th>
               </tr>
            </thead>
            <tbody>
           {{--  @foreach ($clase->reservations as $reservation)
               <tr>
                  <td>
                     <a class="media-img" href="javascript:;">
                     <img class="img-circle" src="{{$reservation->user->avatar}}" alt="image" width="54"></a>
                     <span class="badge-{{$reservation->user->status_user->type}} badge-point"></span>
                     <a href="{{url('/users/'.$reservation->user->id)}}">
                        {{$reservation->user->first_name}} {{$reservation->user->last_name}}
                     </a>
                  </td>
                  <td>
                     <span class="badge badge-{{$reservation->reservation_status->type}} badge-pill">{{strtoupper($reservation->reservation_status->reservation_status)}}</span>
                  </td>
                  <td>
                     <label class="ui-switch switch-icon switch-large">
                        <input name="asistencia[]" type="checkbox"  class="checkboxBla">
                        <span></span>
                     </label>
                     <input hidden class="user_id_class" type="text" name="user_id[]" disabled value="{{$reservation->user->id}}">
                  </td>
               </tr>
            @endforeach --}}
            </tbody>
         </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button class="btn btn-primary" type="submit">Confirmarl lista</button>
      </div>
  		{{Form::close()}}
    </div>
  </div>
</div>
