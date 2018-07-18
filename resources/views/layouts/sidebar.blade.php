<!-- START SIDEBAR-->
<nav class="page-sidebar" id="sidebar">
    <div id="sidebar-collapse">
        <ul class="side-menu">
          <li @if($page=="marketplace") class="active" @endif>
              <a href="index.html"><i class="sidebar-item-icon ti-home"></i>
                  <span class="nav-label">Inicio</span>
              </a>
          </li>
          <li @if($page=="users") class="active" @endif>
              <a href="productores.html"><i class="sidebar-item-icon ti-world"></i>
                  <span class="nav-label">Clases</span>
              </a>
          </li>
          <li @if($page=="logistica") class="active" @endif>
              <a href="logistica.html"><i class="sidebar-item-icon ti-truck"></i>
                  <span class="nav-label">Alumnos</span>
              </a>
          </li>
          <li @if($page=="mercados") class="active" @endif>
              <a href="mercado.html"><i class="sidebar-item-icon ti-stats-up"></i>
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
