<!-- START SIDEBAR-->
<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <ul class="side-menu">
            <li @if(url()->current() == url("/")) class="active" @endif>
                <a href="/"><i class="sidebar-item-icon ti-home"></i>
                    <span class="nav-label">Inicio</span>
                </a>
            </li>

            <li @if(url()->current() == url("/clases")) class="active" @endif>
                <a href="{{ url('/clases') }}"><i class="sidebar-item-icon  ti-calendar"></i>
                    <span class="nav-label">Clases</span>
                </a>
            </li>

            {{-- If auth user has Role Admin or COACH  --}}
            @if (auth()->user()->isAdmin() || auth()->user()->hasRole(2))
                <li @if( in_array(url()->current(), [url("/users"), url("/users/create")]) ) class="active" @endif>
                    <a>
                        <i class="sidebar-item-icon ti-id-badge"></i>

                        <span class="nav-label">Alumnos</span>
                    </a>

                    <div class="nav-2-level">
                        <ul>
                            <li><a href="{{ url('/users') }}">Todos los Alumnnos</a></li>

                            <li><a href="{{ url('/users/create') }}">Nuevo Alumno</a></li>
                        </ul>
                    </div>
                </li>
            @endif

            @if (Auth::user()->isAdmin())
                <li @if(in_array(url()->current(), [
                            url("/payments"),
                            url("/invoices")
                        ])) class="active" @endif>
                    <a>
                        <i class="sidebar-item-icon ti-money"></i>

                        <span class="nav-label">Pagos</span>
                    </a>
                    <div class="nav-2-level">
                        <ul>
                            <li><a href="{{ url('/payments') }}">Pagos</a></li>

                            <li><a href="{{ url('/invoices/recevied') }}">Documentos recibidos</a></li>

                            <li><a href="{{ url('/invoices/issued') }}">Documentos emitidos</a></li>
                        </ul>
                    </div>
                </li>

                <li @if( in_array(url()->current(), [
                            url("/reports"),
                            url("/reports/inactive_users"),
                            url("/reports/data-plans")
                        ]) ) class="active" @endif>
                    <a>
                        <i class="sidebar-item-icon ti-bar-chart"></i>

                        <span class="nav-label">Reportes</span>
                    </a>
                    <div class="nav-2-level">
                        <ul>
                            <li><a href="{{ url('/reports') }}">Gráficos</a></li>

                            <li><a href="{{ url('/reports/inactive_users') }}">Usuarios Inactivos</a></li>

                            <li><a href="{{ url('/reports/data-plans') }}">Análisis Diarios</a></li>

                            <li><a href="{{ url('/reports/students') }}">Análisis de Alumnos</a></li>
                        </ul>
                    </div>

                
                <li @if( in_array(url()->current(), [
                            url("/messages"),
                            url("/alerts"),
                            url("/notifications")
                        ]) ) class="active" @endif>
                    <a>
                        <i class="sidebar-item-icon ti-email"></i>

                        <span class="nav-label">Mensajería</span>
                    </a>
                    <div class="nav-2-level">
                        <ul>
                            <li><a href="{{ url('/messages') }}">Correos</a></li>

                            <li><a href="{{ url('/alerts') }}">Alertas</a></li>

                            <li><a href="{{ url('/notifications') }}">Notificaciones</a></li>
                        </ul>
                    </div>
                </li>
                <li @if( in_array(url()->current(), [
                            url("/plans"),
                            url("/blocks"),
                            url("/density-parameters")
                        ]) ) class="active" @endif>
                    <a href="javascript:;">
                        <i class="sidebar-item-icon ti-settings"></i>

                        <span class="nav-label">Configuración<br /> del box</span>
                    </a>

                    <div class="nav-2-level">
                        <ul>
                            <li><a href="{{ route('plans.index') }}">Planes</a></li>

                            <li><a href="{{ route('blocks.index') }}">Horarios</a></li>

                            <li><a href="{{ route('clases-types.index') }}">Tipos de Clases</a></li>

                            <li><a href="{{ route('density-parameters.index') }}">Parámetros</a></li>

                            <li><a href="{{ route('settings.index') }}">General</a></li>

                            {{-- <li><a href="{{ route('postpones.index') }}">Posponer planes</a></li> --}}
                        </ul>
                    </div>
                </li>
            @endif

            @if (! auth()->user()->isAdmin())
                <li @if(url()->current() === url("profile")) class="active" @endif>
                    <a href="{{ route('users.show', Auth::user()->id) }}">
                        <i class="sidebar-item-icon ti-user"></i>

                        <span class="nav-label">Perfil</span>
                    </a>
                </li>
            @endif
        </ul>
        <ul class="brand">
            <img src="{{asset('/img/asomic.png')}}">
        </ul>
    </div>
</nav>
<!-- END SIDEBAR-->
