@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
<div class="page-content">
    <div class="row row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title px-2 py-3 pt-4">
                        <h5 class="font-strong">
                            Editar clase de {{ optional($clase->claseType)->clase_type }}
                        </h5>
                        <span class="font-light">
													{{ $clase->date->isoFormat('dddd D [de] MMMM [de] YYYY') }} de 
													{{ substr($clase->start_at, 0, -3) }} a {{ substr($clase->finish_at, 0, -3) }}
                        </span>
                    </div>

                    <div class="ibox-tools">
                      <a class="btn btn-info" href="/clases/{{ $clase->id }}">Volver a la clase</a>
                    </div>
                </div>
                <div class="ibox-body">
                    <form action="/clases/{{ $clase->id}}" method="POST">
                        @csrf @method('PUT')

                        <div class="row">
                            <div class="col-12">
                                <label>Encargado de realizar la clase</label>

                                <select name="coach_id" id="icon" class="form-control">
                                    <option value="">Seleccione a una persona para la clase</option>
                                    @foreach ($coaches as $coach)
                                        <option value="{{ $coach->id }}" @if($clase->coach_id === $coach->id) selected @endif>
                                            {{ $coach->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 mt-3">
                                <label>Cantidad máxima de alumnos</label>

                                <input type="text" value="{{ $clase->quota }}" class="form-control" name="quota"/>
                            </div>

                            <button class="submit btn btn-success mt-4 ml-3">Editar clase</button>
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

@endsection
