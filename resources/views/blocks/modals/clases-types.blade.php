<div class="modal fade" id="clases-types-modal" tabindex="-1" role="dialog" aria-hidden="true">
    
    <div class="modal-dialog modal-l" role="document">
    
        <div class="modal-content">

            <div class="modal-header">
    
                <h5 class="modal-title">Tipos de Clases</h5>
    
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    
                    <span aria-hidden="true">&times;</span>
    
                </button>
    
            </div>
    
        {{--     <form action="" method="POST" class="day-delete">
    
                @csrf @method('')

            </form> --}}

            <div class="modal-body">

                <div class="form-group mt-2">

                    <div class="input-group">

                        <select class="form-control" id="type-clase-select" name="clase_type" required>
                            
                            <option value="">Eliga un tipo de clase...</option>
                        
                        </select>
                    
                    </div>
                    
                </div>

                <div class="form-group mt-2" id="div-clase-type-name" style="display: none;">
                    
                    <label>Clase</label>
                    
                    <div class="input-group" id="holahola">
                        
                        <input class="form-control" type="text" id="clase_type_name" name="clase_type_name"/>

                        <div class="input-group-prepend">
                            
                            <button>Actualizar</button>
                        
                        </div>
                       
                    </div>
            
                </div>
            
            </div>
    
            <div class="modal-footer">

                <button class="btn btn-danger" id="sweet-confirm-clase-type-delete">Eliminar!</button>
    
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    
            </div>
    
        </div>
    
    </div>

</div>
