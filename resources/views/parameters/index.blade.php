@extends('layouts.app')

@section('sidebar')
  
  @include('layouts.sidebar',['page'=>'users'])

@endsection

@section('content')
<div class="page-content">
    <div class="row">
        <div class="col-lg-5">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-tittle">
                        <h5 class="font-strong mb-4">Configuraciones para densidad de Clases</h5>
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
                
                {{-- <form action="{{ route('density-parameters.updateAll') }}" method="POST"> --}}

                    <div class="ibox-body">
                        <div>
                            <table class="table table-hover">
                                <thead class="thead-default">
                                    <tr>
                                        <th width="30%">Nivel</th>
                                        
                                        <th width="23%">Desde</th>

                                        <th width="23%">Hasta</th>
                                        
                                        <th width="20%">Color</th>

                                        <th width="10%">Acciones</th>
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
                                            
                                            <td>{{ $density->color }}</td>
                                            <td>
                                                <button 
                                                    class="btn btn-info edit-density-modal"
                                                    name="edit-density-modal"
                                                    data-target="#edit-density-modal"
                                                    data-toggle="modal"
                                                    data-id="{{ $density->id }}"
                                                    data-level="{{ $density->level }}"
                                                    data-from="{{ $density->from }}"
                                                    data-to="{{ $density->to }}"
                                                    data-color="{{ $density->color }}"
                                                >
                                                    E
                                                </button>


                                                <form action="{{ route('density-parameters.destroy', $density->id) }}" method="POST" class="density-parameter-delete">
                                                @csrf @method('DELETE')
                                                </form>
                                                <button
                                                    class="btn btn-info btn-danger sweet-density-parameter-delete"
                                                    data-id="{{ $density->id }}"
                                                    data-name="{{ $density->level }}"
                                                >
                                                    <i class="la la-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        Sin densidades creadas para las clases...
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="text-right mt-3">
                            <button
                                type="submit"
                                class="btn btn-success"
                                onClick="this.disabled = true; this.value = 'Actualizando…'; this.form.submit();"
                            >
                                <i class="fa fa-floppy-o"></i> Actualizar
                            </button>  
                        </div>
                    </div>
                {{-- </form> --}}
            </div>
        </div>
    {{-- </div> --}}
     {{--    <div class="col-lg-7">

            <div class="ibox">
                
                <div class="ibox-body">
                    
                    <h5 class="font-strong mb-4">Parametros de notificationes</h5>
                    
                    <p>Lorem Ipsum Aliqua id consequat laborum incididunt adipiscing ut consectetur dolor voluptate non est ex dolore voluptate fugiat adipiscing qui deserunt nisi magna irure tempor non cupidatat amet fugiat est ad sint adipiscing
                    est officia cillum consectetur reprehenderit non.</p>
                
                </div>
            
            </div>
        
        </div> --}}
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
            $('form.density-parameter-delete').submit();
        });
    });
    </script>

<script>
    $( document ).ready(function () {
        $('.edit-density-modal').click(function () {
            $('#input-id').val($(this).data('id'));
            $('#input-level').val($(this).data('level'));
            $('#input-from').val($(this).data('from'));
            $('#input-to').val($(this).data('to'));
            $('#input-color').val($(this).data('color'));
        });
    });
</script>

{{-- <script>
    $( document ).ready(function () {
        // Create rows
        $( '#row-create' ).click(function () {
            var number_rows = $('.level-row').length;

            var row_number = number_rows + 1;

            if (number_rows >= 1) {
                $( '#row-delete' ).prop('disabled', false);
            }

            if (number_rows <= 4) {
                $( '.modal-body' ).append(
                    '<div class="row mt-3 level-row" id="' + row_number + '">'+
                        '<div class="col-3">'+
                            '<label>Nivel</label>'+
                            
                            '<input class="form-control" type="text" name="level_'+ row_number +'" placeholder="Ej: Bajo" />'+
                        '</div>'+
                            
                       '<div class="col-2"> '+
                            '<label>Desde</label>'+
                            
                            '<input class="form-control" type="number" name="from_'+ row_number +'"/>'+
                        '</div>'+
                        
                        '<div class="col-2"> '+
                            '<label>Hasta</label>'+
                            
                            '<input class="form-control" type="number" name="to_'+ row_number +'"/>'+
                        '</div>'+
                    '</div>'
                );
            }

        });

        // Remove rows
        $( '#row-delete' ).click(function () {
            var last_row = $('.level-row').length;

            if (last_row > 1) {
                $( '#' + last_row ).remove();
            } 

            if (last_row == 2) {
                $( '#row-delete' ).prop('disabled', true);
            }
        });
    });
</script> --}}

@endsection
