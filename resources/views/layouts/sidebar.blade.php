<!-- START SIDEBAR-->
<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <ul class="side-menu">
          <li @if($page=="home") class="active" @endif>
              <a href="{{url('/')}}"><i class="sidebar-item-icon ti-home"></i>
                  <span class="nav-label">Inicio</span>
              </a>
          </li>
          <li @if($page=="students") class="active" @endif>
              <a href="{{url('/students')}}"><i class="sidebar-item-icon ti-id-badge"></i>
                  <span class="nav-label">Alumnos</span>
              </a>
          </li>
          <li @if($page=="blocks") class="active" @endif>
              <a href="{{url('/blocks')}}"><i class="sidebar-item-icon  ti-calendar "></i>
                  <span class="nav-label">Clases</span>
              </a>
          </li>
          <li @if($page=="mercados") class="active" @endif>
              <a href="mercado.html"><i class="sidebar-item-icon ti-credit-card"></i>
                  <span class="nav-label">Pagos</span>
              </a>
          </li>
          <li @if($page=="mercados") class="active" @endif>
              <a href="mercado.html"><i class="sidebar-item-icon ti-clipboard"></i>
                  <span class="nav-label">Planes</span>
              </a>
          </li>

          <li @if($page=="config") class="active" @endif>
              <a href="configuracion.html"><i class="sidebar-item-icon ti-settings"></i>
                  <span class="nav-label">Configuracion</span>
              </a>
          </li>
        </ul>
    </div>
</nav>
<!-- END SIDEBAR-->
