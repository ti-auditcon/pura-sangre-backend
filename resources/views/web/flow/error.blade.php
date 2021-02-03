<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Error en el Pago</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <div class="content">
                <div class="title m-b-md">
                    Error
                </div>
                <div class="alert alert-success">
                    @if (isset($error))
                        @if ($type === 'email')
                            <div x-data="forInstructions()">
                                <div x-show="!instructions.areSended">
                                    {{ $error }}
                                    <select name="plans" x-model="selectedOption">
                                        <option value="">Selecciona un plan</option>
                                        @foreach ($plans as $item)
                                            <option value="{{ $item->id }}">{{ $item->plan }} - {{ $item->amount }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" x-on:keyup="fillEmail($event)"
                                             x-model="instructions.email" class="form-control"
                                             value="{{ isset($email) ? $email : null }}"/>

                                        <template x-for="error in errores">
                                                <p x-text="error"></p>
                                        </template>
                                    <button x-on:click="requestInstrutions()">Enviame las instrucciones nuevamente</button>
                                </div>
                                <div x-show="instructions.areSended" x-text="instructions.message"></div>
                            </div>
                         @endif
                    @endif
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
