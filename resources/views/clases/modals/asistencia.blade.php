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
         <table id="confirm-table" class="table table-hover">


         </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button class="btn btn-primary" type="submit">Confirmar lista</button>
      </div>
  		{{Form::close()}}
    </div>
  </div>
</div>
