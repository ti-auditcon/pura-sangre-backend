@extends('layouts.app')

@section('sidebar')
  
  @include('layouts.sidebar',['page'=>'users'])

@endsection

@section('content')
<div class="page-content">
    <div class="row">
        <div class="col-lg-4">
            <div class="ibox">
            
            {{--   <div class="alert alert-warning" style="display: none;">
            
                    <strong>Por favor corrige los siguientes errores!</strong><br>
            
                    <ul class="errores-alertas"></ul>
            
                </div> --}}
            <form action="{{ route('density-parameters.store') }}" method="POST">
                @csrf
                <div class="ibox-body">
            
                    <h5 class="font-strong mb-4">Configuraciones para densidad de Clases</h5>

                    <div>
                        <table class="table table-hover">
                            <thead class="thead-default">
                                <tr>
                                    <th width="43%">Nivel</th>
                                    
                                    <th width="33%">Porcentaje</th>
                                    
                                    <th width="14%">Color</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($densities as $density)
                                    <tr>
                                        <td>{{ ucfirst($density->level) }}</td>
                                        
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <input
                                                    type="number"
                                                    name="{{ $density->level }}"
                                                    value="{{ $density->percentage }}"
                                                    class="form-control"
                                                    min="1"
                                                    max="100"
                                                />
                                                
                                                <label class="ml-2 mr-4">%</label>
                                            </div>
                                        </td>
                                        
                                        <td class="bg-{{ $density->color }}"></td>
                                    </tr>
                                @endforeach
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
            </form>

            
            </div>

        </div>

        <div class="col-lg-8">

            <div class="ibox">
                
                <div class="ibox-body">
                    
                    <h5 class="font-strong mb-4">Parametros de notificationes</h5>
                    
                    <p>Lorem Ipsum Aliqua id consequat laborum incididunt adipiscing ut consectetur dolor voluptate non est ex dolore voluptate fugiat adipiscing qui deserunt nisi magna irure tempor non cupidatat amet fugiat est ad sint adipiscing
                    est officia cillum consectetur reprehenderit non.</p>
                
                </div>
            
            </div>
        
        </div>
    </div>
</div>
@endsection

@section('css')

@endsection

@section('scripts') {{-- scripts para esta vista --}}

<script>
    $(function() {

    });
</script>

@endsection
