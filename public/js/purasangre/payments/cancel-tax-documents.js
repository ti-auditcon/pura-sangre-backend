jQuery(document).on('click', ".cancel-bill-button", function () {
    $("#canceBillModal").modal();

    currentTaxToken = $(this).data("token");

    fillCancelBillModal($(this).data());
});

function fillCancelBillModal(billData) {
    Object.keys(billData).forEach(key => {
        $(`#${key}`).text(new BillData(billData[key])[key]());
    });
}

class BillData {
    constructor(data) {
        this.data = data;

        this.taxes = taxDocuments;
    }

    getTaxDocument(value) {
        return this.taxes[value] ? `${value} - ${this.taxes[value]}` : false;
    }

    iva() {
        return `${this.data}%`;
    }

    fchemis() {
        return moment(this.data).format('DD-MM-YYYY');
    }

    mnttotal() {
        return new Intl.NumberFormat("es-CL", { style: 'currency', currency: 'CLP' })
            .format(this.data);
    }

    folio() {

        return this.data;
    }

    tipodte() {
        return this.getTaxDocument(this.data) || this.data;
    }

    token() { }
}

//
//
//  send credit note
//
//
$("#issue-cancel-document").on('click', function (event) {
    enableIssueButton(false);

    event.preventDefault();

    issueElectronicNote();
});


async function issueElectronicNote() {
    issue(currentTaxToken).then(response => {
        // if (response.status >= 400) {
        //     alert(response.message);

        //     return;
        // }
        alert(response.message);

        window.location = "/invoices/issued";
    }).catch(error => {
        alert(error.responseJSON.message);

        enableIssueButton(true);
    });
}

function enableIssueButton(enable = true) {
    let button = $("#issue-cancel-document");

    if (enable) {
        return changeTextAndStatusToButton(button, "Emitir", false);
    }

    changeTextAndStatusToButton(button, "Emitiendo...", true);
}

/**
 *
 */
function issue(token) {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: `/tax-documents/${token}/cancel`,
        }).done(successResponse => resolve(successResponse))
            .fail(errorResponse => reject(errorResponse));
    });
}

/** 
 * the first one is the button itself
 * the second is the text inside the button
 * the last is the status of the button (true means is disabled) 
 * 
 * @return  void
 */
function changeTextAndStatusToButton(button, text, isDisabled) {
    button.text(text);

    button.prop('disabled', isDisabled);
}