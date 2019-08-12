<!-- START HEADER-->
<header class="header">
    <div class="page-brand">
        <a href="/">
            <img class="logo" src="{{ asset('img/logo.png') }}" alt="Ir a Dashboard">
        </a>
    </div>
    <div class="d-flex justify-content-between align-items-center flex-1">
        <!-- START TOP-LEFT TOOLBAR-->
        <ul class="nav navbar-toolbar">
            <li>
                <a class="nav-link sidebar-toggler js-sidebar-toggler" href="javascript:;">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
            </li>
        </ul>
        <!-- END TOP-LEFT TOOLBAR-->
        <!-- START TOP-RIGHT TOOLBAR-->
        <ul class="nav navbar-toolbar">
            {{-- @auth() --}}
            @if (Auth::user()->birthdate_users()->count() > 0)
            <li class="dropdown dropdown-notification">
                <a class="nav-link dropdown-toggle toolbar-icon" data-toggle="dropdown" href="javascript:;" aria-expanded="false">
                    <i class="la la-birthday-cake"></i>
                    <span class="envelope-badge">{{ Auth::user()->birthdate_users()->count() }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-media">
                    <div class="dropdown-header text-center">
                        <div><span class="font-18"><strong>Cumpleaños de Hoy</strong></span></div>
                    </div>
                    <div class="p-3">
                        <ul class="media-list timeline scroller" style="overflow: hidden; width: auto;">
                            @foreach (Auth::user()->birthdate_users() as $user)
                            <li class="media py-3">
                                <div class="media-img">
                                    <span><i class="img-avatar img-avatar-mini" style="background-image: @if ($user->avatar) url('{{$user->avatar }}') @else url('{{ asset('/img/default_user.png') }}') @endif "></i></span>
                                </div>
                                <div class="media-body">
                                    <div class="media-heading">
                                        {{ $user->FullName }} cumple {{ toDay()->year - $user->birthdate->format('Y') }} años
                                    </div>
                                    <div class="font-13 text-light">
                                        {{ $user->birthdate->format('d/m/Y') }}
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
            @endif
            <li class="dropdown dropdown-user">
                <a class="nav-link dropdown-toggle link" data-toggle="dropdown">
                    <span class="mr-1">{{ Auth::user()->full_name }} {{-- {{ Auth::user()->last_name }} --}}</span>
                    <div class="img-avatar img-avatar-mini" style="background-image: @if (Auth::user()->avatar) url('{{ Auth::user()->avatar }}') @else url('{{ asset('/img/default_user.png') }}') @endif "></div>
                </a>
                <div class="dropdown-menu dropdown-arrow dropdown-menu-right admin-dropdown-menu">
                    <div class="dropdown-arrow"></div>
                    <div class="dropdown-header">
                        <div>
                            <div class="img-avatar img-avatar-admin" style="background-image: @if (Auth::user()->avatar) url('{{ Auth::user()->avatar }}') @else url('{{ asset('/img/default_user.png') }}') @endif"></div>
                        </div>
                        <div>
                            <h5 class="font-strong text-white">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h5>
                        </div>
                    </div>
                    <div class="admin-menu-content">
                        <div class="d-flex justify-content-end mt-2">
                            <a class="d-flex align-items-center" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Cerrar Sesión<i class="ti-shift-right ml-2 font-20"></i></a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </li>
            {{-- @endauth --}}
        </ul>
        <!-- END TOP-RIGHT TOOLBAR-->
    </div>
</header>
<!-- END HEADER-->
