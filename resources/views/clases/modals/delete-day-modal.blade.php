<div class="modal fade" id="delete-entire-day-modal" tabindex="-1" role="dialog" aria-hidden="true">
    
    <div class="modal-dialog modal-l" role="document">
    
        <div class="modal-content">

            <div class="modal-header">
    
                <h5 class="modal-title">Eliminar un día completo del calendario</h5>
    
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    
                <span aria-hidden="true">&times;</span>
    
                </button>
    
            </div>
    
            <form action="" method="POST" class="day-delete">
    
                @csrf @method('DELETE')

            </form>

            <div class="modal-body">

                <div class="form-group mt-2">
                            
                    <label class="font-normal">Fecha a eliminar</label>
                        
                        <div class="input-group date datepicker-delete-entire-day">

                            <span class="input-group-addon bg-white">
                                
                                <i class="fa fa-calendar"></i>
                           
                            </span>

                            <input
                                class="form-control"
                                name="day_delete"
                                value="{{ old('day_delete') }}"
                                type="text"
                                value="{{ date('d-m-Y') }}"
                            >
                        
                        </div>
                    
                </div>

            </div>
    
            <div class="modal-footer">

                <button class="btn btn-danger" id="sweet-clase-delete">Eliminar!</button>
    
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
    
            </div>
    
        </div>
    
    </div>

</div>
