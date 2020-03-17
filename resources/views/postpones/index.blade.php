@extends('layouts.app')

@section('sidebar')
    @include('layouts.sidebar',['page'=>'payments'])
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="ibox form-control-air">
            <div class="ibox-head">
                <div class="ibox-title">
                    <h4>POSPONER TODOS LOS PLANES!!</h4></div>
            </div>
            <div class="ibox-body">
                <form action="{{ route('postpones.all') }}" method="POST">
                    @csrf
                    <p>Cuidado, solo use esta vista si esta completamente seguro de lo que hace</p>
                    <div>
                        <label>Fecha de Inicio</label>

                        <input type="date" name="start_date"  placeholder="Inicio"/>
                    </div>
                    <div class="mt-2">
                        <label>Fecha de Término</label>

                        <input type="date" name="finish_date" placeholder="Termino"/>
                    </div>

                    <div>
                        <button type="submit">Posponer todo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
