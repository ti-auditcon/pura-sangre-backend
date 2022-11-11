<div class="modal fade edit-stage-clase-modal" aria-hidden="true">
    <div class="modal-dialog-email modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Nombre de la Etapa</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <input type="text" name="stage_clase_id" id="input_stage_clase_id" hidden>

                <div class="modal-body">
                    <div class="row level-row">
                        <div class="col-12">
                            <label>Nombre</label>

                            <input class="form-control"
                                   id="input_stage_clase"
                                   name="stage_type"
                                   type="text"
                            />
                        </div>
                    </div>
                </div>

                <div class="modal-footer mt-3">
                    <button class="btn btn-success" id="button-stage-clase" disabled>
                        Actualizar
                    </button>

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Cerrar
                    </button>
                </div>
        </div>
    </div>
</div>
