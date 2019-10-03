@extends('layouts.app')

@section('sidebar')
  
  @include('layouts.sidebar',['page'=>'users'])

@endsection

@section('content')
<div class="page-content">
    <div class="row">
        <div class="col-lg-6">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-tittle">
                        <h5 class="font-strong">Configuraciones para densidad de Clases</h5>
                    </div>
                    
                    <div class="ibox-tools">
                        <button 
                            class="btn btn-success save-value"
                            name="create-density-modal"
                            data-target="#create-density-modal"
                            data-toggle="modal"
                        >
                            +
                        </button>
                    </div>
                </div>
                <div class="ibox-body">
                    <div>
                        <table class="table table-hover">
                            <thead class="thead-default">
                                <tr>
                                    <th width="25%">Nivel</th>
                                    
                                    <th width="20%">Desde</th>

                                    <th width="20%">Hasta</th>
                                    
                                    <th width="20%">Color</th>

                                    <th width="30%">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($densities as $density)
                                    <tr>
                                        <td>{{ $density->level }}</td>
                                        
                                        <td>
                                            <div class="d-flex align-items-center">
                                                {{ $density->from }}%
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center">
                                                {{ $density->to }}%
                                            </div>
                                        </td>
                                        
                                        <td style="background-color: {{ $density->color }}">
                                        </td>

                                        <td class="row">
                                            <button 
                                                class="btn btn-info edit-density-modal btn-icon-only la la-pencil"
                                                name="edit-density-modal"
                                                data-target="#edit-density-modal"
                                                data-toggle="modal"
                                                data-id="{{ $density->id }}"
                                                data-level="{{ $density->level }}"
                                                data-from="{{ $density->from }}"
                                                data-to="{{ $density->to }}"
                                                data-color="{{ $density->color }}"
                                            >
                                            </button>


                                            <form id="form-density-destroy-{{ $density->id }}" action="{{ route('density-parameters.destroy', $density->id) }}" method="POST" class="density-parameter-delete">
                                            @csrf @method('DELETE')
                                            </form>
                                            <button
                                                class="btn btn-info btn-danger btn-icon-only la la-trash sweet-density-parameter-delete"
                                                data-id="{{ $density->id }}"
                                                data-name="{{ $density->level }}"
                                            >
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    Sin densidades creadas para las clases...
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('parameters.modals.create-density')

@include('parameters.modals.edit-density')

@endsection

@section('css')

{{-- <link href="https://farbelous.io/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css" rel="stylesheet"> --}}

@endsection

@section('scripts') {{-- scripts para esta vista --}}


  <script>
    $('.sweet-density-parameter-delete').click(function(e) {
        var id = $(this).data('id');
        
        swal({
            title: "Desea eliminar el nivel: "+$(this).data('name')+"?",
            // text: "(Se borrarán todas las cuotas o planes futuros, manteniendo los ya consumidos)",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Eliminar',
            closeOnConfirm: false
        },function(){
            //redirección para eliminar usuario
            $('#form-density-destroy-' + id).submit();
        });
    });
    </script>

<script>
    $( document ).ready(function () {
        $('.edit-density-modal').click(function () {
            $('.edit-density-parameter-modal').attr('action', 'density-parameters/' + $(this).data('id'));
            $('#input-level').val($(this).data('level'));
            $('#input-from').val($(this).data('from'));
            $('#input-to').val($(this).data('to'));
            $('#input-color').val($(this).data('color'));
        });
    });
</script>

@endsection
