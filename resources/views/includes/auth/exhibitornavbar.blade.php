<header class="p-3 mb-2 border-bottom">
    <div class="container-fluid">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="{{ route('exhibitor.visitors') }}" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
          <img src="{{ asset('images/logo.png') }}" class="me-2" alt="" height="40">
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li><a href="{{ route('exhibitor.visitors') }}" class="nav-link px-2 link-dark
            {{ Request::is('exhibitor/visitors') ? "active" : "" }}">Asistentes</a></li>
          <li><a href="{{ route('exhibitor.groups.index') }}" class="nav-link px-2 link-dark
            {{ Request::is('exhibitor/groups') ? "active" : "" }}">Grupos</a></li>
          <li><a href="{{ route('exhibitor.invite.index') }}" class="nav-link px-2 link-dark
            {{ Request::is('exhibitor/invite') ? "active" : "" }}">Invitar clientes</a></li>
          <li><a href="{{ route('exhibitor.staff.index') }}" class="nav-link px-2 link-dark
            {{ Request::is('exhibitor/staff') ? "active" : "" }}">Staff</a></li>
        </ul>

        @if (Session::get('simulate'))
        <div class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
          <a href="{{ route('exhibitor.simulate.send') }}" class="mb-0">Ver como organizador</a>
        </div>
        @endif

        <div class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
          <p class="mb-0">{{ \Auth::user()->name }}</p>
        </div>

        <div class="dropdown text-end">
          <a href="" class="d-block link-dark text-decoration-none dropdown-toggle" id="user" data-bs-toggle="dropdown" aria-expanded="false">
            @if(\Auth::user()->avatar <> null)
              <img src="{{ asset('/uploads/'.\Auth::user()->avatar) }}" width="32" height="32" class="rounded-circle">
            @else
              <img src="{{ asset('/uploads/default.svg') }}" width="32" height="32" class="rounded-circle">
            @endif
          </a>
          <ul class="dropdown-menu text-small" aria-labelledby="user">
            <li><a class="dropdown-item" href="{{ route('profile') }}">Perfil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="dropdown-item">Cerrar sesión</button>
              </form>
            </li>
          </ul>
        </div>

        @livewire('tracks')
      </div>
    </div>
  </header>