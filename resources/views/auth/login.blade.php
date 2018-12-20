@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Acceder') }}</div>

                <div class="card-body my-3">
                    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                        @csrf

                        <div class="form-group row">

                            <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('Correo') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mt-4 mb-2">
                            {{-- <div class="col-12"> --}}
                                <div class="col-6 form-check text-right m-0 pr-0 mb-2">
                                    <input class="mb-0 mr-2" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="mb-0" for="remember">
                                        {{ __('Recordar Datos') }}
                                    </label>
                                    <span>&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;</span>
                                </div>
                                <div class="col-6 pl-0 text-left align-middle">
                                  <a class="" href="{{ route('password.request') }}">
                                      {{ __('¿Olvidaste tu contraseña?') }}
                                  </a>
                                </div>
                            {{-- </div> --}}
                        </div>

                        <div class="form-group row m-0">
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary m-0">
                                    {{ __('Ingresar') }}
                                </button>
                            </div>
                            {{-- <div class="col-12 text-center">
                              <a class="btn btn-link" href="{{ route('password.request') }}">
                                  {{ __('¿Olvidaste tu contraseña?') }}
                              </a>
                            </div> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
