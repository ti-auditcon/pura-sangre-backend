@extends('layouts.app')

@section('sidebar')
  
@include('layouts.sidebar', ['page' => 'users'])

@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-6">
        <div class="ibox ibox-fullheight">
            <div class="ibox-head">
                <div class="ibox-title">
                    <h5>Gestionar Roles a {{ $user->full_name }}</h5>
                </div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('role-user.store') }}" method="POST">
            @csrf
                <div class="ibox-body">
                    <input id="user_id" type="hidden" name="user_id" value="{{ $user->id }}"/>

                    <div class="mt-2" id="checkbox-roles">
                        Roles Disponibles:
                        @foreach ($roles as $rol)
                            <label class="checkbox checkbox-success">
                                <input
                                    type="checkbox"
                                    id="{{ $rol->role }}"
                                    value="{{ $rol->id }}"
                                    name="role[]"
                                    @if ($user->hasRole($rol->id)) checked @endif
                                />
                                
                                <span class="input-span"></span>
                                
                                {{ ucfirst($rol->role) }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="ibox-footer">
                    <button
                        type="button"
                        class="btn btn-primary"
                        type="submit"
                        onClick="this.form.submit();"
                    >
                        Guardar
                    </button>

                    <a
                       class="btn btn-secondary"
                       href="{{ route('users.show', $user->id) }}"
                    >
                        Perfil de {{ $user->first_name }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@section('css') {{-- stylesheet para esta vista --}}

@endsection


@section('scripts') {{-- scripts para esta vista --}}

<script>
    $( document ).ready(function () {
        // If personal checkbox is checked, then show the cargo input
        $("#personal ").click(function() {  
            if ($(this).prop("checked") == true){
                $('#div-cargo').show();
            }

            if ($(this).prop("checked") == false){
                $('#div-cargo').hide();
            } 
        }); 
    });
</script>

@endsection