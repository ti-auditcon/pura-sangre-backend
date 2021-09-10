<div class="modal fade" id="canceBillModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Emitir nota de crédito</h5>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="canceBillModal" id="cancel-bill-form" method="POST" action="{{ route('taxes.cancel', 1) }}">
                @method('DELETE') 
                @csrf

                <span class="bg-warning text-white" hidden>
                    Mientras dure este proceso por favor no cierre esta ventana, una vez terminado la ventana se recargará sola...
                </span>
                <div class="modal-body">
                    <h5>Datos de la boleta a anular</h5>

                    <div class="row mb-2">
                        <div class="col-12 text-muted">Fecha de emisión:</div>
                        <div class="col-12" id="fchemis" name="ho"> - </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12 text-muted">Monto total: </div>
                        <div class="col-12" id="mnttotal">-</div>
                    </div> 

                    <div class="row mb-2">
                        <div class="col-12 text-muted">Folio de documento:</div>
                        <div class="col-12" id="folio">-</div>
                    </div>    

                    <div class="row mb-2">
                        <div class="col-12 text-muted">Tipo:</div>
                        <div class="col-12" id="tipodte">-</div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-12 text-muted">IVA:</div>
                        <div class="col-12" id="iva">-</div>
                    </div>
                </div>

                <div class="modal-footer">                    
                    <button id="issue-cancel-document" class="btn btn-primary">
                        Emitir
                    </button>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cerrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>