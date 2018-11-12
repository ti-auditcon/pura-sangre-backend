<!-- START SIDEBAR-->
<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <ul class="side-menu">
          <li @if($page=="home") class="active" @endif>
              <a href="{{url('/')}}"><i class="sidebar-item-icon ti-home"></i>
                  <span class="nav-label">Inicio</span>
              </a>
          </li>
          <li @if($page=="blocks") class="active" @endif>
              <a href="{{url('/clases')}}"><i class="sidebar-item-icon  ti-calendar "></i>
                  <span class="nav-label">Clases</span>
              </a>
          </li>
        @if (!Auth::user()->hasRole(1))
          {{-- <li @if($page=="reservations") class="active" @endif>
            <a href="{{ route('reservations.show', Auth::user()->id) }}"><i class="sidebar-item-icon ti-user"></i>
              <span class="nav-label">Mis Reservas</span>
            </a>
          </li> --}}
          <li @if($page=="users") class="active" @endif>
            <a href="{{ route('users.show', Auth::user()->id) }}"><i class="sidebar-item-icon ti-user"></i>
              <span class="nav-label">Perfil</span>
            </a>
          </li>
        @endif

        @if (Auth::user()->hasRole(1))

          <li @if($page=="users") class="active" @endif>
            <a href="{{ route('users.index') }}"><i class="sidebar-item-icon ti-id-badge"></i>
              <span class="nav-label">Alumnos</span>
            </a>
          </li>
          <li @if($page=="payments") class="active" @endif>
            <a href="{{url('/payments')}}"><i class="sidebar-item-icon ti-money"></i>
              <span class="nav-label">Pagos</span>
            </a>
          </li>
          <li @if($page=="reports") class="active" @endif>
            <a href="{{url('/reports')}}"><i class="sidebar-item-icon ti-bar-chart"></i>
              <span class="nav-label">Reportes</span>
            </a>
          </li>
          <li @if($page=="messages") class="active" @endif>
            <a href="{{url('/messages')}}"><i class="sidebar-item-icon ti-email"></i>
              <span class="nav-label">Mensajeria</span>
            </a>
          </li>
          <li @if($page=="config") class="active" @endif>
            <a href="javascript:;"><i class="sidebar-item-icon ti-settings"></i>
              <span class="nav-label">Configuracion<br /> del box</span>
            </a>
            <div class="nav-2-level">
              <ul>
                <li><a href="form_layouts.html">Centro deportivo</a></li>
                <li><a href="form_advanced.html">Roles y usuarios</a></li>
                <li><a href="{{ route('plans.index') }}">Planes</a></li>
                <li><a href="{{ route('blocks.index') }}">Horarios</a></li>
                <li><a href="{{ route('exercises.index') }}">Ejercicios</a></li>
                <li><a href="form_masks.html">Facturacion</a></li>
              </ul>
            </div>
          </li>
          
        @endif

          {{-- <li @if($page=="mercados") class="active" @endif>
              <a href="mercado.html"><i class="sidebar-item-icon ti-clipboard"></i>
                  <span class="nav-label">Planes</span>
              </a>
          </li> --}}

        </ul>
    </div>
</nav>
<!-- END SIDEBAR-->
