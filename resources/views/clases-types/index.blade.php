@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar')
@endsection

@section('content')
<div class="page-content">
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-tittle">
                        <h5 class="font-strong">
                            Tipos de Clases
                        </h5>
                    </div>

                    <div class="ibox-tools">
                        <a href="/clases-types/create"
                            class="btn btn-success save-value"
                            name="create-clases-types-modal"
                        >
                            Nuevo tipo de clase
                        </a>
                    </div>
                </div>
                <div class="ibox-body">
                    <table id="clases-types-table" class="table table-hover">
                        <thead class="thead-default">
                            <tr>
                                <th width="60%">Tipo de Clase</th>

                                <th width="40%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-tittle">
                        <h5 class="font-strong">
                            Etapas de <span id="clase-type-name" class="text-success"></span>
                        </h5>
                    </div>
                    <div class="ibox-tools" id="stage-types-head"></div>
                </div>
                <div class="ibox-body">
                    {{-- <span id="loading-stages" class="text-warning" hidden>Cargando datos...</span> --}}
                    <table id="clase-type-stages-table" class="table table-hover">
                        <thead class="thead-default">
                            <tr>
                                <th width="40%">Etapa</th>

                                <th width="30%">Destacada</th>

                                <th width="30%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('clases-types.modals.create')

@include('clases-types.modals.edit')

@include('clases-types.modals.create-stage')

@include('clases-types.modals.edit-clase-stage')

@endsection

@section('css') {{-- stylesheet para esta vista --}}
    <link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet"/>
@endsection

@section('scripts') {{-- scripts para esta vista --}}
    {{--  datatable --}}
    <script src="{{ asset('js/datatables.min.js') }}"></script>

    {{-- PuraSangre customized javascripts --}}
    <script src="{{ asset('js/purasangre/clases-types.js') }}"></script>

    <script src="{{ asset('js/purasangre/clases-types-stages.js') }}"></script>
@endsection
