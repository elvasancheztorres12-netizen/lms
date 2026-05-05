<nav class="navbar navbar-expand-lg bg-white border-bottom fixed-top">
    <div class="container-fluid px-3 px-lg-4">

        @php
            $user = auth()->user();
            $role = optional($user->roles->first())->name;
        @endphp

        <div class="d-flex align-items-center">
            <button class="btn btn-link text-dark me-3 d-lg-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#sidebarOffcanvas">
                <i class="fa fa-bars fa-lg"></i>
            </button>

            <a href="
                @if($role === 'Administrator') {{ route('dashboard.admin') }}
                @elseif($role === 'Teacher') {{ route('dashboard.teacher') }}
                @elseif($role === 'Student') {{ route('dashboard.student') }}
                @endif
            " class="navbar-brand d-flex align-items-center">
                <img src="{{ asset('images/Systematic_logo.png') }}" alt="Logo" style="max-width: 100px;">
            </a>
        </div>

        <!-- Buscador -->
        <form class="flex-grow-1 mx-3 d-none d-md-block">
            <div class="input-group">
                <span class="input-group-text bg-light border-0">
                    <i class="fa fa-search text-muted"></i>
                </span>
                <input type="text" class="form-control border-0 bg-light" placeholder="Buscar cursos...">
            </div>
        </form>

        <!-- Usuario -->
        <div class="dropdown">
            <button class="btn btn-light rounded-pill d-flex align-items-center gap-2 px-2 px-md-3"
                data-bs-toggle="dropdown">

                <img src="{{ asset('images/undraw_profile_2.svg') }}" class="rounded-circle" width="36" height="36">

                <div class="d-none d-md-flex flex-column text-start">
                    <strong>{{ $user->username }}</strong>
                    <small class="text-muted">{{ $role }}</small>
                </div>
            </button>

            <ul class="dropdown-menu dropdown-menu-end mt-2">

                {{-- Dashboard según rol --}}
                @if($role === 'Administrator')
                    <li><a class="dropdown-item" href="{{ route('dashboard.admin') }}">Dashboard</a></li>
                @elseif($role === 'Teacher')
                    <li><a class="dropdown-item" href="{{ route('dashboard.teacher') }}">Dashboard</a></li>
                @elseif($role === 'Student')
                    <li><a class="dropdown-item" href="{{ route('dashboard.student') }}">Dashboard</a></li>
                @endif

                <li>
                    <hr class="dropdown-divider">
                </li>

                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger">Cerrar sesión</button>
                    </form>
                </li>
            </ul>
        </div>

    </div>
</nav>