<div class="modal fade" id="edit-density-modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Nivel</h5>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="edit-density-parameter-modal" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row level-row">
                        <div class="col-5">
                            <label>Nivel</label>
                            
                            <input id="input-level" class="form-control" type="text" name="level" placeholder="Ej: Bajo" />
                        </div>
                        
                        <div class="col-2"> 
                            <label>Desde</label>
                            
                            <input id="input-from" class="form-control" type="number" name="from"/>
                        </div>
                        
                        <div class="col-2">  
                            <label>Hasta</label>
                            
                            <input id="input-to" class="form-control" type="number" name="to"/>
                        </div>        

                        <div class="col-3">  
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