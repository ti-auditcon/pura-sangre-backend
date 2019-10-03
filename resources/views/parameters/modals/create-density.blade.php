<div class="modal fade" id="create-density-modal" aria-hidden="true">
    <div class="modal-dialog-email modal-dialog modal-lg" role="document">
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
                        <div class="col-5">
                            <label>Nivel</label>
                            
                            <input class="form-control" type="text" name="level" placeholder="Ej: Bajo" />
                        </div>
                        
                        <div class="col-2"> 
                            <label>Desde</label>
                            
                            <input class="form-control" type="number" name="from"/>
                        </div>
                        
                        <div class="col-2">  
                            <label>Hasta</label>
                            
                            <input class="form-control" type="number" name="to"/>
                        </div>

                        <div class="col-3">  
                            <label>Color</label>
                            
                            <input class="form-control" type="text" name="color"/>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">                    
                    <button type="submit" class="btn btn-primary">
                        Crear
                    </button>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>