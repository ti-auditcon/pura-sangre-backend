<!-- START SIDEBAR-->
<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <ul class="side-menu">
            
            <li @if($page=="home") class="active" @endif>
            
                <a href="/"><i class="sidebar-item-icon ti-home"></i>
            
                    <span class="nav-label">Inicio</span>
            
                </a>
            
            </li>
            
            <li @if($page=="clases") class="active" @endif>
            
                <a href="{{url('/clases')}}"><i class="sidebar-item-icon  ti-calendar"></i>
            
                    <span class="nav-label">Clases</span>
            
                </a>
            
            </li>
            
            @if (Auth::user()->hasRole(1) || Auth::user()->hasRole(2))
                <li @if($page=="users") class="active" @endif>
                    <a>
                        <i class="sidebar-item-icon ti-id-badge"></i>
                    
                        <span class="nav-label">Alumnos</span>
                    </a>

                    <div class="nav-2-level">
                        <ul>
                            <li><a href="{{url('/users')}}">Todos los Alumnnos</a></li>
                        
                            <li><a href="{{url('/users/create')}}">Nuevo Alumno</a></li>
                        </ul>
                    </div>
                </li>
            @endif
            
            @if (Auth::user()->hasRole(1))
            
            <li @if($page=="payments") class="active" @endif>
            
                <a href="{{ url('/payments') }}"><i class="sidebar-item-icon ti-money"></i>
            
                    <span class="nav-label">Pagos</span>
            
                </a>
            
            </li>

            <li @if($page == "reports") class="active" @endif>
                <a>
                    <i class="sidebar-item-icon ti-bar-chart"></i>
                    
                    <span class="nav-label">Reportes</span>
                </a>
                <div class="nav-2-level">
                    <ul>
                        <li><a href="{{url('/reports')}}">Gráficos</a></li>
                        
                        <li><a href="{{url('/reports/inactive_users')}}">Usuarios Inactivos</a></li>
                    </ul>
                </div>
            </li>
            <li @if($page=="messages") class="active" @endif>
                <a>
                    <i class="sidebar-item-icon ti-email"></i>
                    
                    <span class="nav-label">Mensajería</span>
                </a>
                <div class="nav-2-level">
                    <ul>
                        <li><a href="{{url('/messages')}}">Correos</a></li>
                        
                        <li><a href="{{url('/alerts')}}">Alertas</a></li>
                        
                        <li><a href="{{url('/notifications')}}">Notificaciones</a></li>
                    </ul>
                </div>
            </li>
            <li @if($page=="config") class="active" @endif>
                <a href="javascript:;">
                    <i class="sidebar-item-icon ti-settings"></i>
                    
                    <span class="nav-label">Configuración<br /> del box</span>
                </a>

                <div class="nav-2-level">
                    <ul>
                        <li><a href="{{ route('plans.index') }}">Planes</a></li>
                        
                        <li><a href="{{ route('blocks.index') }}">Horarios</a></li>
                        
                        {{-- <li><a href="{{ route('density-parameters.index') }}">Parámetros</a></li> --}}
                    </ul>
                </div>
            </li>
            @endif

            @if (!Auth::user()->hasRole(1))
                <li @if($page=="profile") class="active" @endif>
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
