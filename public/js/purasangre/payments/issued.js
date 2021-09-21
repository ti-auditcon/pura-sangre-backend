const url_path = "/invoices/issued/json";
let current_page = 1;
let last_page = 1;

$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});

jQuery(function () {
    // let dataTaxDocumentTable = getTaxDocumentsData();
    dtesTable = $('#dtes-table').DataTable({
        "ajax": {
            type: 'GET',
            url: url_path,
            dataType: 'json',
            error: function (e) {
                alert(`Se ha detectado un error, si al intentarlo de nuevo, vuelve a salir este mensaje, por favor comuniquese con algun administrador`);
            },
            complete: function (data) {
                $('.docsPagination__text').text(`${data.responseJSON.recordsFiltered ?? 'sin'} entradas`);
                last_page = data.responseJSON.last_page;

                if (current_page < last_page) {
                    $('.right-direction').prop('disabled', false);  // enable button
                }
            }
        },
        "processing": true,
        "serverSide": false,
        "searching": false,
        "order": [[6, "desc"]],
        "columnDefs": [
            {
                "targets": [6],
                "visible": false,
                "searchable": false
            }
        ],
        "info": false,
        "paging": false,
        "language": {
            "loadingRecords": "Consultando datos al SII...",
            "processing": "Esta consulta puede tomar unos momentos...",
            "lengthMenu": "Mostrar _MENU_ elementos",
            "zeroRecords": "Sin resultados",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Sin resultados",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Filtrar:",
            "paginate": {
                "first": "Primero",
                "last": "último",
                "next": "Siguiente",
                "previous": "Anterior"
            },
        },
        "columns": [
            {
                "data": "RznSocRecep",
                "render": function (data, other, row) {
                    return data ?? `<a href="/users/${row.user_id}">${row.full_name}</a>`;
                }
            },
            {
                "data": "service",
                "render": function (data, other, row) {
                    return row.service;
                }
            },
            {
                "data": "TipoDTE",
                "render": function (data, other, row) {
                    return `${taxDocuments[data]} Nº ${row.Folio} ${row.paid === 3 ? '<span class="badge badge-warning badge-pill">ANULADA</span>' : '' }`;
                }
            },
            {
                "data": "MntTotal",
                "render": function (data, other, row) {
                    if (data > 0) {
                        return new Intl.NumberFormat("es-CL", { style: 'currency', currency: 'CLP' })
                            .format(data);
                    }
                    return 'sin valor';
                }
            },
            {
                "data": "FchEmis",
                "render": function (data, other, row) {
                    return data ? moment(data).format("DD-MM-YYYY") : 'sin fecha';
                }
            },
            {
                "data": "actions",
                "render": function (data, other, row) {
                    let cancelButton = '';
                    let assignUser = '';
                    // let withoutUser = `<button class="badge badge-default badge-circle"
                    //                             data-toggle="popover"
                    //                             data-trigger="hover"
                    //                             data-placement="right"
                    //                             data-content="Este documento no esta asociado a un usuario."
                    //                     >
                    //                         ?
                    //                     </button>`;
                    if (cancellableTaxDocuments.includes(row.paid)) {
                        cancelButton = `<button class="ml-2 btn btn-warning text-white cancel-bill-button"
                                                data-token="${row.Token}"
                                                data-fchemis="${row.FchEmis}"
                                                data-mnttotal="${row.MntTotal}"
                                                data-folio="${row.Folio}"
                                                data-tipodte="${row.TipoDTE}"
                                                data-iva="${row.IVA}"
                            >
                            Anular
                                </button >`;
                    }
                    // if (row.user_id === null) {
                    //     assignUser = `<button class="ml-2 btn btn-info text-white assign-tax-document-button"
                    //                             data-token="${row.Token}"
                    //                 >
                    //                     Asignar documento
                    //                 </button >`;
                    // }
                    return `<div class="row">
                                <button class="dte-link btn btn-success text-white"
                                    data-token="${row.Token}"
                                >
                                    Solicitar PDF
                                </button>
                                ${cancelButton}
                            </div>
                        `;
                }
            },
            { "data": "FchEmis" }
        ],
        "drawCallback": function (settings) {
            $(".dte-link").on("click", function (action) {
                managePDFRequest($(this));
            });
        }
    });

    // Allow to get focus in the input text modal
    $('.cancel_bill_modal').on('click', function () {
        $('.cancel_bill_modal').modal('show');
    });

    $(document).on("click", '.dteList-button', function (action) {
        $('.left-direction').prop('disabled', true);  // enable button
        $('.right-direction').prop('disabled', true);  // enable button
        let direction = $(this).data('direction');

        if (direction === '+') {
            dtesTable.clear().draw();

            if (current_page === last_page) {
                return;
            }
            addOneToCurrentPage();

            dtesTable.ajax.url(`${url_path}?page=${current_page}`);
            dtesTable.ajax.reload(function () {
                if (current_page > 1) {
                    $('.left-direction').prop('disabled', false);  // enable button
                }
                if (current_page >= last_page) {
                    $('.right-direction').prop('disabled', true);  // enable button
                } else {
                    $('.right-direction').prop('disabled', false);
                }
            });

            return;
        }

        if (current_page === 1) {
            return;
        }
        dtesTable.clear().draw();

        subtractOneToCurrentPage();
        dtesTable.ajax.url(`${url_path}?page=${current_page}`);
        dtesTable.ajax.reload(function () {
            if (current_page === 1) {
                $('.left-direction').prop('disabled', true);  // enable button
            } else {
                $('.left-direction').prop('disabled', false);  // enable button
            }
            if (current_page < last_page) {
                $('.right-direction').prop('disabled', false);  // enable button
            }
        });
    });
});

function addOneToCurrentPage() {
    current_page++;
}

function subtractOneToCurrentPage() {
    current_page--;
}