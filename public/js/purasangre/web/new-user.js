
function newUser() {
    return {
        data: [
            { name: 'first_name', value: '' },
            { name: 'last_name', value: '' },
            { name: 'birthdate', value: '' },
            { name: 'phone', value: '' },
            { name: 'email', value: '' },
            { name: 'password', value: '' },
            { name: 'address', value: '' }
        ],
        errors: {
            first_name: '', email: '',
            last_name: '', birthdate: '', phone: ''
        },
        formStatus: { isFinished: false, message: ''},
        instructions: { areSended: false, message: '', email: '', error: null, buttonIsDisabled: false },
        plan_id: '',
        redirectButton: null,
        sendButton: { text: 'Registrarme y pagar', disabled: false },
        fill(value, event) {
            this.data.map(field => {
                if (field.name === value) {
                    field.value = event.target.value;
                    this.cleanWarning(field.name);
                }
            });
        },
        getSelectedPlan(plan_id) {
            this.plan_id = plan_id;
        },
        cleanWarning(fieldName) {
            this.errors[fieldName] = null;
        },
        extractDataFormValues() {
            let arrayFormated = [];
            this.data.map(data => arrayFormated[data.name] = data.value);
            return arrayFormated;
        },
        async sendForm() {
            this.sendButton.disabled = true;
            let formData = this.extractDataFormValues();
            
            axios.post('/new-user', {
                ...formData, plan_id: this.plan_id
            }).then(response => {
                console.log(response.data);
                this.finishResgistration(response.data.success);
            }).catch(error => {
                this.sendButton.disabled = false;
                let responseErrors = error.response.data.errors;
                this.showErrors(responseErrors)
            });
        },
        finishResgistration(message) {
            this.formStatus.message = message;
            this.formStatus.isFinished = true;
        },
        showErrors(errors) {
            this.data.map(data => {
                if (errors[data.name]) {
                    this.errors[data.name] = errors[data.name][0]
                }
            });
        },
        requestInstructions() {
            this.instructions.buttonIsDisabled = true;
            console.log('requestInstructions');
            axios.post(`/new-user/request-instructions`, {
                'email': this.instructions.email,
                'plan_id': this.plan_id
            })
                .then(response => {
                    if (response.data.success) {
                        this.instructions.message = response.data.success;
                        this.instructions.areSended = true;
                    }
                }).catch(error => {
                    this.instructions.buttonIsDisabled = false;
                    this.instructions.error = error.response.data.errors.email[0];
                });
        },
        fillEmail(event) {
            this.instructions.email = event.target.value;

            this.instructions.error = null;
        },
    }
}
