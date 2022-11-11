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
                            Actualizar Tipo de Clase
                        </h5>
                    </div>

                    <div class="ibox-tools">
                        <a class="btn btn-success" href="/clases-types">Tipos de clases</a>
                    </div>
                </div>
                <div class="ibox-body">
                    <form action="/clases-types/{{ $clasesType->id}}" method="POST">
                        @csrf @method('PUT')

                        <div class="row">
                            <div class="col-12">
                                <label>Nombre</label>

                                <input value="{{ $clasesType->clase_type }}" class="form-control" name="clase_type" type="text" placeholder="Ej: CrossFit"/>
                            </div>
                            <div class="col-12 mt-3">
                                <label>Icono para Tipo de Clase</label>

                                {{-- {{ dd($clasesType, App\Models\Clases\IconClaseType::list()) }} --}}
                                <select name="icon_type" id="icon" class="form-control">
                                    <option value="">Eliga un tipo de clase como icono</option>

                                    @foreach (App\Models\Clases\IconClaseType::list() as $icon)
                                        <option data-img-src="{{ $icon['url_path'] }}"
                                          value="{{ strtolower($icon['name']) }}"
                                          @if (strtolower($clasesType->icon_type) === $icon['name']) selected @endif
                                        >
                                            {{ $icon['human_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 mt-3">
                                <label>Icono</label>

                                <img class="" id="selected-icon" src="" alt="" width="240">
                            </div>

                            <button class="submit btn btn-success mt-3">Actualizar tipo de clase</button>
                        </div>
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
        console.log(icon.options);
        console.log(icon.selectedIndex);
        if (icon.selectedIndex > 0) {
            img.src = icon.options[icon.selectedIndex].dataset.imgSrc;
        }
        
        // let imageSrc = icon.options[icon.selectedIndex].getAttribute("data-img-src");
        // img.setAttribute('src', imageSrc)

        icon.addEventListener('change', () => {
            img.src = icon.options[icon.selectedIndex].dataset.imgSrc;
            // imageSrc = icon.options[icon.selectedIndex].getAttribute("data-img-src");
            // img.setAttribute('src', imageSrc)
        });
    });
</script>

@endsection
