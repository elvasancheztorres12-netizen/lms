@php
    $role = optional(auth()->user()->roles->first())->name;
@endphp

<div class="sidebar-content d-flex flex-column h-100 p-3">

    <span class="badge bg-secondary mb-3">
        Rol: {{ $role ? ucfirst(strtolower($role)) : 'Sin asignar' }}
    </span>

    @if($role === 'Administrator')
        <div class="mb-3">
            <h6 class="text-muted mb-2">Administración</h6>
            <ul class="nav flex-column gap-1">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active bg-primary text-white' : '' }}">
                        <i class="bi bi-house-door me-2"></i>Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active bg-primary text-white' : '' }}">
                        <i class="bi bi-people me-2"></i>Usuarios
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.specialties.index') }}" class="nav-link {{ request()->routeIs('admin.specialties.*') ? 'active bg-primary text-white' : '' }}">
                        <i class="bi bi-tags me-2"></i>Especialidades
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.courses.index') }}" class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active bg-primary text-white' : '' }}">
                        <i class="bi bi-book me-2"></i>Cursos
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.trainings.index') }}" class="nav-link {{ request()->routeIs('admin.trainings.*') ? 'active bg-primary text-white' : '' }}">
                        <i class="bi bi-mortarboard me-2"></i>Capacitaciones
                    </a>
                </li>
            </ul>
        </div>
    @elseif($role === 'Teacher')
        <ul class="nav flex-column gap-2">
            <li>
                <a href="{{ route('teacher.dashboard') }}" class="nav-link {{ request()->routeIs('teacher.dashboard') ? 'active bg-primary text-white' : '' }}">
                    🧭 Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('teacher.courses') }}" class="nav-link {{ request()->routeIs('teacher.courses') ? 'active bg-primary text-white' : '' }}">
                    📚 Mis cursos
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    📝 Evaluaciones
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    👨‍🎓 Estudiantes
                </a>
            </li>
        </ul>
    @elseif($role === 'Student')
        <ul class="nav flex-column gap-2">
            <li>
                <a href="{{ route('student.dashboard') }}" class="nav-link {{ request()->routeIs('student.dashboard') ? 'active bg-primary text-white' : '' }}">
                    🧭 Dashboard
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    📚 Mis cursos
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    📊 Progreso
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    🏆 Certificados
                </a>
            </li>
        </ul>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100 text-start">
                🔒 Cerrar sesión
            </button>
        </form>
    @endif

</div>