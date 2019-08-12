<div class="modal fade" id="clases-types-modal" tabindex="-1" role="dialog" aria-hidden="true">
    
    <div class="modal-dialog modal-l" role="document">
    
        <div class="modal-content">

            <div class="modal-header">
    
                <h5 class="modal-title">Tipos de Clases</h5>
    
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    
                    <span aria-hidden="true">&times;</span>
    
                </button>
    
            </div>
    
            <form id="form-clases-types" action="{{ route('clases-types.update', '+id+') }}" method="POST">
                @csrf @method('PUT')

            <div class="modal-body">

                <div class="form-group mt-2">

                    <div class="input-group">

                        <select class="form-control" id="type-clase-select" name="clase_type" required></select>
                    
                    </div>
                    
                </div>

                <div class="form-group mt-2" id="div-clase-type-name" style="display: none;">
                    <label>Clase</label>
                    
                    <div class="input-group">
                        <input class="form-control" type="text" id="clase_type_name" name="clase_type_name"/>
                    </div>

                    <div class="ibox">
                       <div class="ibox-header">
                            <div class="ibox-tittle">
                                <label class="mt-3">Etapas</label>
                            </div>
                            <div class="ibox-tools">
                                <button class="btn btn-success" id="button-add-stage-type" onclick="event.preventDefault()">
                                    +
                                </button>

                                <button class="btn btn-danger" id="button-delete-stage-type" onclick="event.preventDefault()">
                                    -
                                </button>
                            </div>
                       </div>
                    </div>
                        
                    <div class="row">
                        <div class="col-12" id="div-clase-type"></div>
                    </div>
                   
                </div>
                        <button id="update-clase-type-name" class="btn btn-primary" onclick="event.preventDefault()">
                            Actualizar Clase
                        </button>

                        <button
                            onclick="event.preventDefault()"
                            class="btn btn-danger ml-2"
                            id="sweet-confirm-clase-type-delete"
                            disabled
                        >
                            Eliminar!
                        </button>
                
            </div>


            </form>

            <div class="modal-footer">
                <button class="btn btn-success" id="button-add-clase-type">
                    Nuevo Tipo de Clase
                </button>

                <div id="div-new-clase-type" style="display: none;">
                    <div class="">
                        <label>Nombre del nuevo Tipo de Clase</label>

                        <input class="form-control" type="text" id="new_clase_type" name="new_clase_type"/>
                    </div>

                    <button class="btn btn-success mt-3" id="button-store-new-clase-type" disabled>
                        Agregar Tipo de Clase
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
