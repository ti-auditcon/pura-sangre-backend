function newUser() {
    return {
        data: [
            { name: 'first_name', value: '' },
            { name: 'last_name', value: '' },
            { name: 'birthdate', value: '' },
            { name: 'phone', value: '' },
            { name: 'email', value: '' },
            { name: 'address', value: '' }
        ],
        errors: {
            first_name: '', email: '',
            last_name: '', birthdate: '', phone: ''
        },
        sendButton: {
            text: 'Registrarme y pagar', disabled: false
        },
        plan_id: '',
        formStatus: 'initial',
        notSended: true,
        fill(value, event) {
            console.log(event);
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
            console.log(this.plan_id);
            let formData = this.extractDataFormValues();
            
            axios.post('/new-user', {
                ...formData, plan_id: this.plan_id
            }).then(response => {
                console.log(response);
                
                setTimeout(() => { /**  Set 3 seconds of time out and redirect to pay */
                    window.location.href = `/new-user/${response.data.user_id}/edit?plan_id=${response.data.plan_id}`;
                }, 3000);
            }).catch(error => {
                this.sendButton.disabled = false;
                let responseErrors = error.response.data.errors;
                this.showErrors(responseErrors)
            });
        },
        showErrors(errors) {
            this.data.map(data => {
                if (errors[data.name]) {
                    this.errors[data.name] = errors[data.name][0]
                }
            });
        },
    }
}
