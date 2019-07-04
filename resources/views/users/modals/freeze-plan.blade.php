<div class="modal fade" id="freeze-plan-modal" tabindex="-1" role="dialog" aria-hidden="true">
    
    <div class="modal-dialog modal-l" role="document">
    
        <div class="modal-content">

            <div class="modal-header">
    
                <h5 class="modal-title">Congelar Plan</h5>
    
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    
                <span aria-hidden="true">&times;</span>
    
                </button>
    
            </div>
    
            <form id="form-plan-freeze" action="" method="POST">
                @csrf

                <div class="modal-body">

                    <div class="form-group mt-2">

                        <label class="font-normal">Fecha de inicio de Congelamiento</label>

                        <span
                            class="badge badge-default badge-circle"
                            data-toggle="popover"
                            data-trigger="hover"
                            data-placement="right"
                            data-content="La fecha de inicio de congelamiento del plan, es la fecha en que empezará a contar el plan como &#8221;Inactivo&#8221;."
                        >
                            ?
                        </span>
                        
                        <div class="input-group date datepicker-date-start-freeze @if ($errors->has('start_freeze_date')) has-warning @endif">

                            <span class="input-group-addon bg-white">
                                
                                <i class="fa fa-calendar"></i>
                           
                            </span>

                            <input
                                id="input-date-start-freeze"
                                class="form-control"
                                name="start_freeze_date"
                                type="text"
                                value="{{ old('start_freeze_date', date('d-m-Y')) }}"
                            >
                        
                        </div>
                        
                    </div>

                    <div class="form-group py-3">
                      
                                
                        <label class="font-normal">Fecha de término de Congelamiento</label>

                        <div class="input-group date datepicker-date-end-freeze @if ($errors->has('end_freeze_date')) has-warning @endif">

                            <span class="input-group-addon bg-white">
                                
                                <i class="fa fa-calendar"></i>
                           
                            </span>

                            <input
                                id="input-date-end-freeze"
                                class="form-control"
                                name="end_freeze_date"
                                type="text"
                                value="{{ old('end_freeze_date', date('d-m-Y')) }}"
                            >
                        
                        </div>
                        
                    </div>

                </div>
    

                <div class="modal-footer">

                    <button class="btn btn-danger" type="submit">Congelar Plan</button>
        
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        
                </div>
    
            </form>

        </div>
    
    </div>

</div>
