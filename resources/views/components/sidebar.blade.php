@php
    $user = auth()->user();
    $role = optional($user->roles->first())->name ?? 'Student';
@endphp

<div class="sidebar-content d-flex flex-column h-100 p-3">

    <div class="mb-3">
        <span class="badge bg-secondary text-uppercase">
            Rol: {{ $role }}
        </span>
    </div>

    {{-- CARGA DINÁMICA --}}
    @if($role === 'Administrator')
        @include('components.sidebars.admin')
    @elseif($role === 'Teacher')
        @include('components.sidebars.teacher')
    @else
        @include('components.sidebars.student')
    @endif

</div>