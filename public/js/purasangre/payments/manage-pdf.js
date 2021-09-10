/** */
async function managePDFRequest(event) {
    changeButtonToRequesting(event);

    let parametersForPDF = transformDataParameters(event);
    requestIssuedPdfDataFromSii(parametersForPDF).then(success => {
        generatePdfFromBase64(success.data);

        changeButtonToCorrectFinishState(event);
    }).catch(error => {
        changeButtonToNormalState(event);

        return alert(error.responseJSON.message);
    });
}

function changeButtonToRequesting(button) {
    button.prop('disabled', true);  // disable button

    button.text('Solicitando...');
}

function changeButtonToNormalState(button) {
    button.text('Solicitar PDF');    //  change text button

    button.prop('disabled', false);  //  enable button
}

/**
 *  @argument 
 */
function changeButtonToCorrectFinishState(button) {
    button.text('Listo');

    button.prop('disabled', true);  //  disable button
}

/**
 *  
 */
function transformDataParameters(field) {
    return {
        "token": field.data('token'),
    }
}

/**
 *
 */
function requestIssuedPdfDataFromSii(dte) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: "/dte/get-issued-pdf",
            data: { token: dte.token },
        }).done(successResponse => {
            resolve(successResponse);
        }).fail(errorResponse => {
            reject(errorResponse)
        });
    });
}

function generatePdfFromBase64(stringBase64) {
    var byteCharacters = atob(stringBase64);
    var byteNumbers = new Array(byteCharacters.length);
    for (var i = 0; i < byteCharacters.length; i++) {
        byteNumbers[i] = byteCharacters.charCodeAt(i);
    }
    var byteArray = new Uint8Array(byteNumbers);
    var file = new Blob([byteArray], { type: 'application/pdf;base64' });
    var fileURL = URL.createObjectURL(file);
    fileURL.download = "hola.pdf";
    window.open(fileURL);
}