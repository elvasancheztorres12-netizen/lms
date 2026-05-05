@php
    $user = auth()->user();
    $role = optional($user->roles->first())->name;
@endphp

<div class="sidebar-content d-flex flex-column h-100 p-3">

    <span class="badge bg-secondary mb-3">
        Rol: {{ $role }}
    </span>

    @switch($role)

        @case('Administrator')
            @include('components.sidebars.admin')
            @break

        @case('Teacher')
            @include('components.sidebars.teacher')
            @break

        @case('Student')
            @include('components.sidebars.student')
            @break

        @default
            <p>No role assigned</p>

    @endswitch

</div>