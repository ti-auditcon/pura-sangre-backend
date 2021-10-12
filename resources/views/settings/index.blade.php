@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar', ['page' => 'settings'])
@endsection

@section('content')
<div class="page-content">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-tittle">
                <h5 class="font-strong">Configuraciones del box</h5>
            </div>
        </div>
        <div class="ibox-body">
            <form method="POST" action="{{ route('settings.update', $settings->id) }}">
                @method('PATCH')
                @csrf
                <div class="row">
                    <div class="col-sm-12 col-md-6 form-group mb-2">
                        <div class="form-group inline">
                            <label class="col-form-label">Minutos para enviar confirmacion de la clase</label>

                            <select name="minutes_to_send_notifications" class="form-control">
                                <option value="">Eliga los minutos</option>
                                @foreach (App\Models\Settings\Setting::listOfAvailableMinutesToSendPushes() as $minutes)
                                    <option value="{{ $minutes }}"
                                        @if ($settings->minutes_to_send_notifications === $minutes) selected @endif>
                                        {{ $minutes }} minutos / {{ $minutes/60 }} horas
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                
                    <div class="col-sm-12 col-md-6 form-group mb-2">
                        <div class="form-group inline">
                            <label class="col-form-label">Minutos para remover a los alumnos</label>

                            <select name="minutes_to_remove_users" class="form-control">
                                <option value="">Eliga los minutos</option>
                                
                                @foreach (App\Models\Settings\Setting::listOfAvailableMinutesToRemoveUsersFromClases() as $minutes)
                                    <option value="{{ $minutes }}" @if ($settings->minutes_to_remove_users === $minutes) selected @endif>
                                        {{ $minutes }} minutos / {{ $minutes/60 }} horas
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Actualizar datos</button> 
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('css')

@endsection

@section('scripts') {{-- scripts para esta vista --}}

@endsection
