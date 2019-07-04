/**
 * changeClaseTypeSelect  
 * Manage data on clase type SELECT
 */
function changeClaseTypeSelect(selectValue = 0, name = null) {

	if (selectValue.length != 0) {
		$('#type-clase-select option[value="' + selectValue + '"]').attr("selected", true);

		var selected_clase = $('#type-clase-select option[value="' + selectValue + '"]').text();

		$("#clase_type_name").val(selected_clase);

    	$('#sweet-confirm-clase-type-delete').attr( "disabled", false );
    
    	$('#div-clase-type-name').show();

	} else {

    	$("#sweet-confirm-clase-type-delete").attr("disabled", true);

    	$("#div-clase-type-name").hide();

	}
}

function getAllClasesType(claseType = null) {
	// Delete all option inside select
	$('#type-clase-select').find('option').remove();

	// Add default option to select
	$('#type-clase-select').append($('<option>Eliga un tipo de clase...</option>').val(null));

	// Get all classes types
	$.get("/clases-types/").done( function (response) {

		response.forEach( function (el) {
			$('#type-clase-select').append(
		        $('<option></option>').val(el.id).html(el.clase_type)
		    );
		    $('#type-clase-select-calendar').append(
		        $('<option></option>').val(el.id).html(el.clase_type)
		    );
		});

	}).done( function () {
		if (claseType) {
			// Set an specific option to select
			new changeClaseTypeSelect(claseType);
			// $('#type-clase-select option[value="' + claseType + '"]').attr("selected", true);
		} else {
			$('#div-clase-type-name').hide();
		}

	});
}

// Open modal with All the classes type
$('#clases-type-button-modal').click(function () {
	new getAllClasesType();
});

// Show input to add a new brand clase type
$('#button-add-clase-type').click(function () {
	$('#div-new-clase-type').show();
});

$('#new_clase_type').keyup(function(){
	if (this.value == 0) {
	
		$("#button-store-new-clase-type").attr("disabled", true);
	
	} else {

		$("#button-store-new-clase-type").attr("disabled", false);
	
	}
});

/** 
 *	Enable/disable update/delete button on modal 
 *	if there is no text on input
 */
$('#clase_type_name').keyup(function(){
	if (this.value == 0) {
	
		$("#sweet-confirm-clase-type-delete").attr("disabled", true);

		$("#update-clase-type-name").attr("disabled", true);
	
	} else {

		$("#sweet-confirm-clase-type-delete").attr("disabled", false);
		
		$("#update-clase-type-name").attr("disabled", false);
	
	}
});

/** Get an specific type clase */
$('#type-clase-select').change(function () {
	// Check if select has a value different to 0
	if (this.value) {
		$.get("/clases-types/" + this.value)
		.done( function( data ) {
			$("#clase_type_name").val(data.clase_type);

			// Show the inputs with the filled data
			$('#div-clase-type-name').show();
		});
	}
});

/** Select an specific clase type on SELECT */
$("#type-clase-select").change(function() {

    new changeClaseTypeSelect(this.value, this.options[this.selectedIndex].text);

});

/////////////////////////////////////////////////
//     METHODS   (GET, POST, PUT , DELETE)     //
/////////////////////////////////////////////////

/** Store a new clase type */
$('#button-store-new-clase-type').click(function () {
	var new_clase_type_name = $('#new_clase_type').val();

	if (new_clase_type_name) {
		$.ajax({
		    url: "clases-types",
		    type: 'POST',
		    data: {
		    	clase_type: new_clase_type_name,
		    	_method: 'POST',
		    	_token: $('meta[name=csrf-token]').attr("content")
		    },
		    success: function(result) {

		    	$('#new_clase_type').val(null);

		    	$("#button-store-new-clase-type").attr("disabled", true);

		    	new getAllClasesType(result.data);

		    	toastr.success(result.success);
		    }
		});
	}
});

/** Update clase type name */
$('#update-clase-type-name').click(function () {
	var selected_clase = $('#type-clase-select').children("option:selected").val();
	
	var clase_type_name = $('#clase_type_name').val();

	if (clase_type_name) {
		$.ajax({
		    url: "/clases-types/" + selected_clase,
		    type: 'post',
		    data: {
		    	clase_type: clase_type_name,
            	_method: 'put',
		    	_token: $('meta[name=csrf-token]').attr("content")
		    },
		    success: function(result) {
		    	new getAllClasesType(result.data);
		    	
		    	toastr.success(result.success);
		    }
		});
	}
});

    // Allow to get focus in the input text modal for SWAL
    $('#clases-types-modal').on('shown.bs.modal', function() {
    
        $(document).off('focusin.modal');
    
    });

	////////////////////////////////////////////////
	// 		DELETE an specific ClaseType SWAL     //
	////////////////////////////////////////////////
    // $('#sweet-confirm-clase-type-delete').click(function() {
    //     Swal.fire({
    //         title: '¿Esta seguro que quiere eliminar el día completo?',
    //         text: 'Para eliminar definitivamente por favor ingresa el nombre del tipo de clase. ATENCIÓN, Al eliminar un tipo de clase se eliminan todos sus bloques asociados; todas las clases asociadas a dichos boques; todas las reservaciones asociadas a dichas clases, se devuelven todos los cupos de las reservaciones cuyas clases no se hayan realizado aún ',
    //         input: "text",
    //         inputAttributes: {
    //             autocapitalize: 'off',
    //             placeholder: 'Nombre del tipo de clase'
    //         },
    //         showCancelButton: true,
    //         cancelButtonText: 'Cancelar',
    //         confirmButtonText: 'Eliminar!',
    //         showLoaderOnConfirm: true,
    //         preConfirm: (input) => {
    //             if (input !== 'ELIMINAR') {
    //                 Swal.showValidationMessage(
    //                   `Palabra incorrecta`
    //                 )
    //             } else {  
    //                 // var date = $("#input-date-day").val();

    //                 // let remove_day_url = '{{ url('calendar/clases/delete') }}';

    //                 // return $.post(remove_day_url, { date: date })
    //                 //     .fail(error => {
    //                 //         Swal.showValidationMessage(
    //                 //             `Algo ha fallado: ${error}`
    //                 //         )
    //                 //     })
    //             }


	// var selected_clase = $('#type-clase-select').children("option:selected").val();

	// var confirm = 'hola';

	// if (selected_clase) {
	// 	$.ajax({
	// 	    url: "/clases-types/" + selected_clase,
	// 	    type: 'post',
	// 	    data: {
	// 	    	word_confirm: confirm,
 //            	_method: 'delete',
	// 	    	_token: $('meta[name=csrf-token]').attr("content")
	// 	    },
	// 	    success: function(result) {
	// 	    	new getAllClasesType();
	// 	    	toastr.success(result);
	// 	    }
	// 	});
	// }


    //         },
    //         allowOutsideClick: () => !Swal.isLoading()
    //     }).then((response) => {

    //         if (response.value.success) {
    //             Swal.fire({ 
    //                 title: response.value.success,
    //                 text: 'Presiona "OK" para recargar la página',
    //                 confirmButtonText: 'OK!',
    //             }).then(() => {
    //                 // Refresh page
    //                 location.reload();
    //             })
    //         }
            
    //     })
    // });