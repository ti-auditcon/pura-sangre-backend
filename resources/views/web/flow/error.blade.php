<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Error — Pura Sangre CrossFit</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
        <link href="{{asset('/css/web-register.css')}}" rel="stylesheet" />
    </head>
    <body class="register-wrapper">
        <div class="register">
            <div class="header">
                <h3 class="text-center">
                    Error
                </h3>
            </div>
            <div class="content">

                <div class="flex-center position-ref">
        
                    <div class="content pt-0">
                        {{-- <div> --}}
                            @if (isset($error))
                                @if ($type === 'email')
                                    <div x-data="forInstructions()">
                                        <div x-show="!instructions.areSended">
                                            <div class="mb-2">
                                                {{ $error }}
                                            </div>
                                            <input placeholder="Ingresa tu correo" type="text" x-on:keyup="fillEmail($event)" x-model="instructions.email" class="form-control mb-2" value="{{ isset($email) ? $email : null }}"/>
                                            <select style="width: 100%; padding: .375rem .5rem" class="mb-2" name="plans" x-model="selectedOption">
                                                <option value="">Selecciona un plan</option>
                                                @foreach ($plans as $item)
                                                    <option value="{{ $item->id }}">{{ $item->plan }} - {{ $item->amount }}</option>
                                                @endforeach
                                            </select>
                                            <template x-for="error in errores">
                                                    <p x-text="error"></p>
                                            </template>
                                            <button class="btn btn-sm btn-primary px-3 py-2" x-on:click="requestInstrutions()">Enviame las instrucciones nuevamente</button>
                                        </div>
                                        <div x-show="instructions.areSended" x-text="instructions.message"></div>
                                    </div>
                                 @endif
                            @endif
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
        </div>


        {{-- AlpineJs --}}
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js" defer></script>
        {{-- Axios library --}}
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <script>
            function forInstructions() {
                return {
                    instructions: { areSended: false, message: '', email: ''},
                    errores: [],
                    selectedOption: '',
                    requestInstrutions() {
                        console.log(this.selectedOption);
                        axios.post(`/new-user/request-instructions?email=${this.instructions.email}&plan_id=${this.selectedOption}`)
                            .then(response => {
                                console.log(response);
                                console.log('response');
                                if (response.data.success) {
                                    this.instructions.message = response.data.success;
                                    this.instructions.areSended = true;
                                }
                            }).catch(error => {
                                this.errores = [];
                                let data = error.response.data.errors;
                                Object.values(data).map(error => this.errores.push(error));
                            });
                    },

                    fillEmail(event) {
                        this.instructions.email = event.target.value;

                        this.instructions.error = null;
                    },
                }
            }
        </script>
    </body>
</html>
