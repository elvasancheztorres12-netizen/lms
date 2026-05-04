<ul class="nav flex-column gap-2">

    <li>
        <a href="{{ route('dashboard.teacher') }}"
            class="nav-link {{ request()->routeIs('dashboard.teacher') ? 'active' : '' }}">
            <i class="fa fa-home me-2"></i> Dashboard
        </a>
    </li>

    <li>
        <a href="#">Cursos</a>
    </li>

    <li>
        <a href="#" class="nav-link">
            <i class="fa fa-plus me-2"></i> Crear curso
        </a>
    </li>

    <li>
        <a href="#" class="nav-link">
            <i class="fa fa-tasks me-2"></i> Evaluaciones
        </a>
    </li>

    <li>
        <a href="#" class="nav-link">
            <i class="fa fa-users me-2"></i> Estudiantes
        </a>
    </li>

</ul>