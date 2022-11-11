@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
<div class="page-content">
    <div class="row row justify-content-center">
        <div class="col-6">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-tittle">
                        <h5 class="font-strong">
                            Crear Tipo de Clase
                        </h5>
                    </div>

                    <div class="ibox-tools">
                        <a href="/clases-types" class="btn btn-success">
                            Ir a Tipos de Clases
                        </a>
                    </div>
                </div>
                <div class="ibox-body">
                    <form action="/clases-types" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <label>Nombre</label>

                                <input class="form-control" name="clase_type" type="text" placeholder="Ej: CrossFit"/>
                            </div>
                            <div class="col-12 mt-3">
                                <label>Icono para Tipo de Clase</label>

                                <select name="icon_type" id="icon" class="form-control">
                                    <option value="">Eliga un tipo de clase como icono</option>
                                    @foreach (\App\Models\Clases\IconClaseType::list() as $icon)
                                        <option data-img-src="{{ $icon['url_path'] }}" value="{{ strtolower($icon['name']) }}">
                                            {{ $icon['human_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="col-12">Icono</label>

                                <img class="col-4" id="selected-icon" src="" alt="" width="240">
                            </div>
                        </div>

                        <button class="submit btn btn-success mt-3">Crear tipo de clase</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css') {{-- stylesheet para esta vista --}}

@endsection

@section('scripts') {{-- scripts para esta vista --}}

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let img = document.getElementById('selected-icon');
        let icon = document.getElementById('icon');

        icon.addEventListener('change', () => {
            let value = icon.options[icon.selectedIndex].getAttribute("data-img-src");


            img.setAttribute('src', value)
        });
    });
</script>

@endsection
