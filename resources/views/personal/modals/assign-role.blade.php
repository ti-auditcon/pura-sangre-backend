<div class="modal fade" id="user-assign" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Asignación de Roles</h5>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.role-user.store') }}" method="POST" id="form-val">
                @csrf
                <div class="modal-body messages-modal-body">
                    <tbody>
                        <div class="ibox-body">
                            <div class="row">
                                <div class="col-4">
                                    <input id="user_id" type="hidden" name="user_id"/>

                                <div class="col-sm">
                                    <div 
                                        class="img-circle-container" 
                                        style="background-image: url('https://images.pexels.com/photos/736716/pexels-photo-736716.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260')"
                                    >
                                    </div>
                                </div>
                                </div>
                                <div class="col-8">
                                    <div class="row mt-4">
                                        {{-- <div class="col-12">
                                            <span>Rut:</span>
                                            <span id="rut"></span>
                                        </div> --}}
                                        <div class="col-12 mt-4">
                                            <span>Nombre:</span>
                                            <span id="full_name"></span>
                                        </div>

                                        {{-- <div id="rut"></div> --}}
                                        {{-- <div id="full_name"></div> --}}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4" id="checkbox-roles">
                                Roles Disponibles:
                                @foreach ($roles as $rol)
                                    <label class="checkbox checkbox-success">
                                        <input type="checkbox" value="{{ $rol->id }}" name="role[]">
                                        
                                        <span class="input-span"></span>
                                        
                                        {{ $rol->role }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="button" class="btn btn-primary" type="submit" onClick="this.form.submit();">
                                Guardar
                            </button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                Cerrar
                            </button>
                        </div>
                    </tbody>
                    <div id="form-input">
                        {{-- <input type="text" name="id[]"> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>