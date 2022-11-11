/////////////////////////////////////////////////
//       		clases Types                   //
/////////////////////////////////////////////////
/** ----------  CLASE TYPES TABLE --------  */
claseTypesTable = $('#clases-types-table').DataTable({
    "ajax": {
        type: 'GET',
        url: '/clases-types-all/',
        dataType: 'json',
        error: function (e) {
            toastr.warning(`Algo no ha cargado correctamente, por favor recargue esta página`);
        },
    },
    "processing": true,
    "paging": true,
    "ordering": true,
    "order": [
        [0, "asc"]
    ],
    "columnDefs": [
        {
            "targets": [1],
            "orderable": false
        }
    ],
    "columns": [{
        "data": "clase_type",
        "render": function (data, other, row) {
            return `<div class="img-avatar"
                                style="background-image:  url('https://api3.purasangrecrossfit.cl/icon/clases/${row.icon}') ">
                        </div>
                        ${data}`;
        },
    },
    {
        "data": "actions",
        "render": function (data, other, row) {
            return `<a class="btn btn-icon-only btn-warning" href="/clases-types/${row.id}/edit">
                            <i class="la la-edit"></i>
                        </a>
                        <button class="btn btn-info btn-icon-only btn-danger sweet-clase-type-delete"
                                type="button"
                                data-clase-type-id="${row.id}"
                                data-name="${row.clase_type}"
                        >
                            <i class="la la-trash"></i>
                        </button>`;
        },
    },
    ],
    "pageLength": 6,
    "bLengthChange": false, //thought this line could hide the LengthMenu
    "bpageLength": false,
    "bPaginate": false,
    "language": {
        "lengthMenu": "Mostrar _MENU_ elementos",
        "zeroRecords": "Sin datos",
        "info": "Mostrando página _PAGE_ de _PAGES_",
        "infoEmpty": "",
        "infoFiltered": "(filtrado de _MAX_ registros totales)",
        "search": "Filtrar:",
        "paginate": {
            "first": "Primero",
            "last": "Último",
            "next": "Siguiente",
            "previous": "Anterior"
        },
        "processing": 'Cargando datos...'
    }
});

/**
 * Get all stage for a specific claseType
 *
 * @param  integer
 */
function manageStagesClaseTypes(stage_type) {
    $.get(`stage-types/${stage_type}`).done(function (response) {
        response.forEach(function (stage) {
            const isChecked = stage.featured ? 'checked' : '';
            $('#div-clase-type').append(
                `<div class="clase-stage-div">
                    <input type="text"
                           class="form-control mt-2"
                           value="${stage.stage_type}"
                           name="stage_type[${stage.id}]"
                    />
                    <label class="ui-switch switch-icon switch-large">
                        <input name="featured"
                            type="radio"
                            value="${stage.id}"
                            class="checkboxBla"
                            ${isChecked}
                        />
                        <span></span>
                    </label>
                </div>`
            );
        });
    });
}

// Show input to add a new brand clase type
$('#button-add-clase-type').click(function () {
    $('#div-new-clase-type').show();
});


/**
 * If input for new Clase Type has some value,
 * make "Agregar tipo de Clase" available
 */
$('#new_clase_type').keyup(function () {
    if (this.value.length) {
        return $("#button-store-new-clase-type").attr("disabled", false);
    }

    return $("#button-store-new-clase-type").attr("disabled", true);
});

// ///////////////////////////////////////////////////////////
//          METHODS   (GET, POST, PUT, DELETE)             //
// //////////////////////////////////////////////////////////
/** Store a new clase type */
$('#button-store-new-clase-type').click(function () {
    var new_clase_type_name = $('#new_clase_type').val();

    if (new_clase_type_name) {
        $.ajax({
            url: "/clases-types",
            type: 'POST',
            data: {
                clase_type: new_clase_type_name,
                _method: 'POST',
                _token: $('meta[name=csrf-token]').attr("content")
            },
            success: function (result) {
                $('#new_clase_type').val(null);

                $("#button-store-new-clase-type").attr("disabled", true);
                // console.log('llegue hasta aca');
                $('#create-clases-types-modal').modal('hide');
                toastr.success(result.success);

                claseTypesTable.ajax.reload();
            },
            error: () => {
                console.log('Ha fallado el guardar un nuevo tipo de clase');
            }
        });

    }
});

// ///////////////////////////////////////////////////////////
//                 UPDATE CLASE TYPE NAME                   //
// //////////////////////////////////////////////////////////
/**
 * Action to edit ClaseType name
 */
$(document).on('click', '.edit-clase-buton', function () {
    const ClaseTypeName = $(this).data('clase-type-name');
    const ClaseTypeId = $(this).data('clase-type-id');
    console.log(ClaseTypeId);

    $('.edit-clases-types-modal input[name="clase_type_id"]').val(ClaseTypeId);

    $('.edit-clases-types-modal input[name="clase_type"]').val(ClaseTypeName);

    $('.edit-clases-types-modal').modal('show');
});

/**
 * If input for EDIT Clase Type has some value,
 * then make "Actualizar" buton available
 */
$('#input_clase_type').keyup(function () {
    if (this.value.length) {
        return $("#button-edit-clase-type").attr("disabled", false);
    }

    return $("#button-edit-clase-type").attr("disabled", true);
});

/** Update clase Type Name */
$('#button-edit-clase-type').click(function () {
    var clase_type_id = $('#input_clase_type_id').val();
    var clase_type_name = $('#input_clase_type').val();

    if (clase_type_name) {
        $.ajax({
            url: `/clases-types/${clase_type_id}`,
            type: 'PATCH',
            data: {
                clase_type: clase_type_name,
                _method: 'POST',
                _token: $('meta[name=csrf-token]').attr("content")
            },
            success: function (result) {
                $('#input_clase_type').val(null);
                $('#input_clase_type_id').val(null);

                $("#button-edit-clase-type").attr("disabled", true);

                toastr.success(result.success);

                claseTypesTable.ajax.reload();

                $('.edit-clases-types-modal').modal('hide');
            },
            error: () => {
                console.log('Ha fallado store-new-clase-type');
            }
        });
    }
});

// Allow to get focus in the input text modal for SWAL
$('#clases-types-modal').on('shown.bs.modal', function () {
    $(document).off('focusin.modal');
});

/**
 * Action to "trash button" on some ClaseType clicked
 */
$(document).on('click', '.sweet-clase-type-delete', function () {
    manageDeleteClaseType($(this), claseTypesTable);
});

/**
 *
 */
function manageDeleteClaseType(ClaseType, table) {
    var claseTypeId = ClaseType.data('clase-type-id');

    swal({
        title: `¿Confirma la eliminación del tipo de clase "${ClaseType.data('name')}"?`,
        text: "Esta acción no de podrá deshacer",
        type: 'warning',
        showCancelButton: true,
        confirmButtonClass: 'btn-danger',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Eliminar',
        closeOnConfirm: false
    }, () => {
        deleteClaseType(claseTypeId, table);

        swal.close();
    });
}


/**
 * Send Ajax with a selected Clase type to be deleted
 *
 * @return ajax, toastr | null
 */
function deleteClaseType(claseTypeId, table) {
    if (claseTypeId) {
        $.ajax({
            url: `/clases-types/${claseTypeId}`,
            type: 'post',
            data: {
                _method: 'delete',
                _token: $('meta[name=csrf-token]').attr("content")
            },
            success: function (result) {
                table.ajax.reload();

                toastr.success(result);
            }
        });
    }
}

/**
 * Perfom action on some clicked row over ClaseType Table
 */
$('#clases-types-table tbody').on('click', 'tr', function (e) {
    rowClaseType = claseTypesTable.row(this).data();

    $('#clase-type-name').empty();
    $('#stage-types-head').empty();

    // console.log(rowClaseType);
    manageClaseTypeStages(rowClaseType);

    $('#clase-type-name').append(rowClaseType.clase_type);

    // console.log(rowClaseType.id);
    $('#stage-types-head').append(`
        <button type="button"
                class="btn btn-success"
                id="button-new-stage"
                data-clase-type-id="${rowClaseType.id}"
        >
            Agregar etapa
        </button>
    `);
});

/**
 * Manage the stages from an specific Clase Type
 * Make visible button to save featured stages statuses
 */
function manageClaseTypeStages(ClaseType) {
    claseTypeStagesTable.ajax.url(`clases-types/${ClaseType.id}/stages-types`).load();
    // Make visible button to save featured stages statuses
    // $('#button-update-stages').attr('hidden', false);
}
