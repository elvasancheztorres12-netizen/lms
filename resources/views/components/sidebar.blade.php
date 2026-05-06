@php
    $role = optional(auth()->user()->roles->first())->name;
@endphp

<div class="sidebar-content d-flex flex-column h-100 p-3">

    <span class="badge bg-secondary mb-3">
        Rol: {{ $role ? ucfirst(strtolower($role)) : 'Sin asignar' }}
    </span>

    @if($role === 'Administrator')
        <ul class="nav flex-column gap-2">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active bg-primary text-white' : '' }}">
                    🧭 Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active bg-primary text-white' : '' }}">
                    👥 Usuarios
                </a>
            </li>
            <li>
                <a href="{{ route('admin.courses.index') }}" class="nav-link {{ request()->routeIs('admin.courses.*') ? 'active bg-primary text-white' : '' }}">
                    📚 Mis cursos
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    🎓 Cursos globales
                </a>
            </li>
            <li>
                <a href="#" class="nav-link">
                    ⚙️ Configuración
                </a>
            </li>
        </ul>
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
    @endif

</div>