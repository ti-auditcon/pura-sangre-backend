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

                        <select class="form-control" id="type-clase-select" name="clase_type" required></select>
                    
                    </div>
                    
                </div>

                <div class="form-group mt-2" id="div-clase-type-name" style="display: none;">
                    
                    <label>Clase</label>
                    
                    <div class="input-group" id="holahola">
                        
                        <input class="form-control" type="text" id="clase_type_name" name="clase_type_name"/>

                        <div class="input-group-prepend">
                            
                            <button id="update-clase-type-name" class="btn btn-primary">Actualizar</button>
                        
                        </div>
                       
                    </div>
            
                </div>
            
            </div>
    

            <div class="modal-body">
                
                <div class=""> 
                
                    <button class="btn btn-success" id="button-add-clase-type">+ Tipo de Clase</button>

                </div>
                    
                <div class="">
                    
                    {{-- <button class="btn btn-danger" id="sweet-confirm-clase-type-delete" disabled>Eliminar!</button> --}}
                    
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                
                </div>

            </div>

            <div class="modal-body" id="div-new-clase-type" style="display: none;">
                
                <div class="">
                    
                    <label>Nombre del nuevo Tipo de Clase</label>

                    <input class="form-control" type="text" id="new_clase_type" name="new_clase_type"/>
                
                </div>

                <button class="btn btn-success mt-3" id="button-store-new-clase-type" disabled>Agregar Tipo de Clase</button>
            
            </div>
    
        </div>
    
    </div>

</div>
