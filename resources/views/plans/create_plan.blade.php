
@extends('layouts.app')
@section('sidebar')
  @include('layouts.sidebar',['page'=>'student'])
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-6">
        <div class="ibox form-control-air">
            
            <div class="ibox-head">
            
                <div class="ibox-title">Crear un nuevo plan</div>
            
            </div>
            
            {!! Form::open(['route' => 'plans.store']) !!}
            
            <div class="ibox-body">
            
                <div class="row">
            
                    <div class="col-sm-6 form-group">
            
                        <div class="form-group inline @if($errors->has('plan')) has-warning  @endif">
            
                            <label class="col-form-label">Nombre del Plan*</label>
            
                            <input class="form-control form-control-air" name="plan" placeholder="Ejemplo: 12 sesiones" required>
            
                        </div>
            
                    </div>
            
                    <div class="col-sm-6 form-group @if($errors->has('plan_period_id')) has-warning  @endif">
            
                        <label class="col-form-label">Período*</label>
            
                        <select class="selectpicker form-control form-control-air"  name="plan_period_id" required>
            
                            <option value="">Elegir período...</option>
            

                            @foreach (App\Models\Plans\PlanPeriod::all() as $plan_period)
            
                            <option
                                value="{{ $plan_period->id }}"
                                @if (old('plan_period_id') == $plan_period->id) selected @endif
                            >
                                {{ $plan_period->period }}
                            </option>
            
                            @endforeach
            
                        </select>
            
                    </div>
            
                </div>
            
                <div class="row">
            
                    <div class="col-sm-6 form-group">
            
                        <label class="col-form-label">Valor del Plan</label>
            
                        <div class="input-group-icon input-group-icon-left">
            
                            <span class="input-icon input-icon-left"><i class="la la-dollar"></i></span>
            
                            <input 
                                class="form-control form-control-air" 
                                name="amount"
                                type="text" 
                                placeholder="solo números"
                                required
                            />
            
                        </div>
            
                    </div>
            
                    <div class="col-sm-6 form-group">
            
                        <div class="form-group inline @if($errors->has('class_numbers')) has-warning  @endif">
            
                            <label class="col-form-label">Numero de Clases</label>
            
                            <input class="form-control form-control-air" type="number" name="class_numbers" placeholder="0" required>
            
                        </div>
            
                    </div>
            
                </div>
            
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label class="col-form-label">Numero de clases unicas diarias</label>
            
                        <div class="input-group-icon">
                            <input class="form-control form-control-air" name="daily_clases" type="number" value="1" required/>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12 form-group">
                        <label class="col-form-label">Descripción</label>
            
                        <div class="input-group-icon">
                            <textarea class="form-control form-control-air" name="description"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row col-sm-12 form-group mt-3">
                    <label class="checkbox checkbox-success">
                        <input type="checkbox" name="contractable"/>
                        
                        <span class="input-span"></span>

                        Contratable por APP
                    </label>

                    <label class="checkbox checkbox-success ml-3">
                        <input type="checkbox" name="convenio"/>
                        
                        <span class="input-span"></span>

                        Es Convenio
                    </label>
                </div>

                <div class="mt-4">
                    <button class="btn btn-primary btn-air mr-2" type="submit">Crear Plan</button>

                    <a class="btn btn-secondary" href="{{ route('plans.index') }}">Volver</a>
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
