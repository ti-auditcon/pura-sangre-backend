<div class="modal fade" id="edit-density-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear densidad para Clases</h5>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('density-parameters.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row level-row">
                        <div class="col-6">
                            <label>Nivel</label>
                            
                            <input id="input-level" class="form-control" type="text" name="level" placeholder="Ej: Bajo" />
                        </div>
                        
                        <div class="col-3"> 
                            <label>Desde</label>
                            
                            <input id="input-from" class="form-control" type="number" name="from"/>
                        </div>
                        
                        <div class="col-3">  
                            <label>Hasta</label>
                            
                            <input id="input-to" class="form-control" type="number" name="to"/>
                        </div>        

                        <div class="">  
                            <label>Color</label>
                            
                            <input id="input-color" class="form-control" type="text" name="color"/>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">                    
                    <button type="submit" class="btn btn-primary">
                        Guardar
                    </button>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>