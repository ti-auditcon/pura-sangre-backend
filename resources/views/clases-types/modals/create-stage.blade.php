<div class="modal fade" id="create-stage-modal" aria-hidden="true">
    <div class="modal-dialog-email modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear Etapa</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

                <div class="modal-body">
                    <div class="row level-row">
                        <div class="col-12">
                            <label>Nombre de la Etapa</label>

                            <input class="form-control"
                                   id="new_stage_type"
                                   name="new_stage_type"
                                   type="text"
                                   placeholder="Ej: Etapa Uno"
                            />
                        </div>
                    </div>
                </div>

                <div class="modal-footer mt-3">
                    <button class="btn btn-success" id="button-store-new-stage" disabled>
                        Agregar Etapa
                    </button>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal" >
                        Cerrar
                    </button>
                </div>
        </div>
    </div>
</div>
