/////////////////////////////////////////////////
//       		Clases Type Stages             //
/////////////////////////////////////////////////
claseTypeStagesTable = $('#clase-type-stages-table').DataTable({
    "paging": true,
    "ordering": true,
    "processing": true,
    "order": [[1, "asc"]],
    "dom": 'lrtp',
    "pageLength": 6,
    "bLengthChange": false, // Thought this line could hide the LengthMenu
    "bpageLength": false,
    "bPaginate": false,
    "language": {
        "lengthMenu": "Mostrar _MENU_ elementos",
        "zeroRecords": "Sin datos",
        "paginate": {
            "previous": "Anterior",
            "next": "Siguiente",
        },
        "processing": 'Cargando datos...'
    },
    "ajax": {
        type: 'GET',
        url: "",
        dataType: 'json',
        error: function (e) {
            // toastr.warning(`No ha cargado correctamente, seleccione nuevamente el tipo de clase`);
        },
    },
    "columnDefs": [
        {
            "targets": [2],
            "orderable": false
        }
    ],
    "columns": [
        { "data": "stage_type" },
        {
            "data": "featured",
            "render": function (data, other, row) {
                const featured = data ? 'checked' : '';
                return `<label class="ui-switch switch-icon switch-large">
                            <input type="radio"
                                    value="${row.id}"
                                    class="checkboxBla"
                                    name="featured_stage"
                                    ${featured}/>
                            <span></span>
                        </label>`;
            },
        },
        {
            "data": "actions",
            "render": function (data, other, row) {
                return `<button class="btn btn-icon-only btn-warning edit-stage-button"
                                type="button"
                                data-stage-clase-id="${row.id}"
                                data-stage-clase-name="${row.stage_type}"
                        >
                            <i class="la la-edit"></i>
                        </button>
                        <button class="btn btn-icon-only btn-danger sweet-clase-type-stage-delete"
                                type="button"
                                data-stage-type-id="${row.id}"
                                data-name="${row.stage_type}"
                        >
                            <i class="la la-trash"></i>
                        </button>`;
            },
        },
    ],
});

$(document).on('click', 'input[name="featured_stage"]', function () {
    if ($(this).is(':checked')) {
        var StageTypeId = $(this).val();

        $.ajax({
            url: `/stages-types/${StageTypeId}`,
            type: 'PATCH',
            data: {
                featured: true,
                _method: 'POST',
                _token: $('meta[name=csrf-token]').attr("content")
            },
            success: function (result) {
                toastr.success(result.success);

                claseTypeStagesTable.ajax.reload();
            },
            error: (error) => {
                console.log('No se ha podido actualizar la etapa');
                console.log(error);
            }
        });
    }
});

/**
 * Enabled input for New Stage
 */
$('#new_stage_type').on('keyup', function () {
    if (this.value.length) {
        return $("#button-store-new-stage").attr("disabled", false);
    }

    return $("#button-store-new-stage").attr("disabled", true);
});

/**
 * Button to Modal create stage
 */
$(document).on('click', '#button-new-stage', (button) => {
    var claseTypeId = $('#button-new-stage').data('clase-type-id');

    /** Pass data to modal */
    $("#button-store-new-stage").val(claseTypeId);

    // open modal #create-stage-modal
    $('#create-stage-modal').modal('show');
});

/**
 * Clean input modal and close it
 */
function closeCreateStageModal() {
    $('#new_stage_type').val(null);

    $('#create-stage-modal').modal('hide');
}

/**
 * Store a new Stage type
 */
$('#button-store-new-stage').on('click', function () {
    stageName = $('#new_stage_type').val();
    claseTypeId = $('#button-store-new-stage').val();
    console.log(claseTypeId);
    $.post(`/clases-types/${claseTypeId}`, {
        stage_type: stageName,
        _token: $('meta[name=csrf-token]').attr("content"),
    }).done(response => {
        if (response.success) {
            toastr.success(response.success);
            closeCreateStageModal();
            claseTypeStagesTable.ajax.url(`clases-types/${claseTypeId}/stages-types`).load();
        }
    }).fail(error => {
        console.log(error);
        toastr.warning('Ha habido un error al intentar crear la etapa')
    });
});

//////////////////////////////////////////////////
//                                              //
//     ---     UPDATE STAGE NAME   ---          //
//                                              //
//////////////////////////////////////////////////
/**
 * Action to Update Stage Name
 */
$(document).on('click', '.edit-stage-button', function () {
    console.log('edit-stage-button');
    const StageClaseName = $(this).data('stage-clase-name');
    const StageClaseId = $(this).data('stage-clase-id');

    $('.edit-stage-clase-modal input[name="stage_clase_id"]').val(StageClaseId);

    $('.edit-stage-clase-modal input[name="stage_type"]').val(StageClaseName);

    $('.edit-stage-clase-modal').modal('show');
});

/**
 * If input for EDIT Stage Clase has some value,
 * then make "Actualizar" buton available
 */
$('#input_stage_clase').on('click', function () {
    if (this.value.length) {
        return $("#button-stage-clase").attr("disabled", false);
    }

    return $("#button-stage-clase").attr("disabled", true);
});

/** Update clase Type Name */
$('#button-stage-clase').on('click', function () {
    var stage_clase_id = $('#input_stage_clase_id').val();
    var stage_clase = $('#input_stage_clase').val();

    if (stage_clase) {
        $.ajax({
            url: `/stages-types/${stage_clase_id}`,
            type: 'PATCH',
            data: {
                stage_type: stage_clase,
                _method: 'POST',
                _token: $('meta[name=csrf-token]').attr("content")
            },
            success: function (result) {
                $("#button-stage-clase").attr("disabled", true);

                toastr.success(result.success);

                claseTypeStagesTable.ajax.reload();

                $('.edit-stage-clase-modal').modal('hide');
            },
            error: () => {
                console.log('Ha fallado al intentar editar el nombre de una etapa');
            }
        });
    }
});

//////////////////////////////////////////////////
//                                              //
//     ---       DELETE STAGE      ---          //
//                                              //
//////////////////////////////////////////////////

/**
 * Action to "trash button" on some ClaseType clicked
 */
$(document).on('click', '.sweet-clase-type-stage-delete', function () {
    manageDeleteClaseTypeStage($(this), claseTypeStagesTable);
});

/**
 * Manage Delete Clase Type Stage
 */
function manageDeleteClaseTypeStage(ClaseTypeStage, table) {
    var ClaseTypeStageId = ClaseTypeStage.data('stage-type-id');

    swal({
        title: `¿Confirma la eliminación de la etapa "${ClaseTypeStage.data('name')}"?`,
        text: 'Se eliminarán además de todas rutinas de entrenamiento que ya han sido creadas.',
        type: 'warning',
        showCancelButton: true,
        confirmButtonClass: 'btn-danger',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Eliminar',
        closeOnConfirm: false,
    }, () => {
        deleteClaseTypeStage(ClaseTypeStageId, table);

        swal.close();
    });
}

/**
 * Send Ajax with a selected Clase type to be deleted
 *
 * @return ajax, toastr | null
 */
function deleteClaseTypeStage(ClaseTypeStageId, table) {
    if (ClaseTypeStageId) {
        $.ajax({
            url: `/stages-types/${ClaseTypeStageId}`,
            type: 'POST',
            data: {
                _method: 'DELETE',
                _token: $('meta[name=csrf-token]').attr("content")
            },
            success: function (response) {
                if (response.success) {
                    table.ajax.reload();

                    toastr.success(response.success);
                }
            }
        });
    }
}
