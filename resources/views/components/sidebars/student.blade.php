<ul class="nav flex-column gap-2">

    <li>
        <a href="{{ route('dashboard.main') }}" class="nav-link">
            <i class="fa fa-home me-2"></i> Dashboard
        </a>
    </li>

    <li>
        <a href="{{ route('dashboard.my-courses') }}" class="nav-link">
            <i class="fa fa-book me-2"></i> Mis cursos
        </a>
    </li>

    <li>
        <a href="{{ route('dashboard.progress') }}" class="nav-link">
            <i class="fa fa-chart-bar me-2"></i> Progreso
        </a>
    </li>

    <li>
        <a href="{{ route('dashboard.certificates') }}" class="nav-link">
            <i class="fa fa-trophy me-2"></i> Certificados
        </a>
    </li>

</ul>