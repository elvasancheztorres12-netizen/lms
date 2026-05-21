@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4 py-1">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 mb-4 text-gray-800">Usuarios</h1>
            <button class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">
                <i class="fas fa-user-plus me-1"></i> + Crear usuario
            </button>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card shadow mb-4">
            <div class="card-header bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Usuarios</h5>
                    <button class="btn btn-outline-primary btn-sm" type="button" data-toggle="collapse" data-target="#filtersCollapse" aria-expanded="false">
                        <i class="fas fa-filter"></i> Filtros
                    </button>
                </div>
                <div class="collapse mt-3" id="filtersCollapse">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small">Buscar</label>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Nombre o DNI" value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Rol</label>
                            <select name="role" class="form-select">
                                <option value="">Todos</option>
                                @foreach($roles as $role)
                                    @if($role->name !== 'Student') {{-- Ocultamos Student de los filtros administrativos --}}
                                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Año</label>
                            <select name="year" class="form-select">
                                <option value="">Todos</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary me-2">Aplicar</button>
                            @if(request()->hasAny(['search', 'role', 'year']))
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-danger">Limpiar</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card-body p-3">
                @php
                    $usersByRole = $users->groupBy(function($user) {
                        return $user->roles->pluck('name')->first() ?? 'Sin Rol';
                    });
                @endphp

                <div class="accordion" id="usersAccordion">
                    @foreach($usersByRole as $roleName => $roleUsers)
                        @if($roleName === 'Administrator' || $roleName === 'Teacher')
                            <div class="accordion-item border-0 shadow-sm mb-2 rounded">
                                <h2 class="accordion-header" id="heading{{ str_replace(' ', '', $roleName) }}">
                                    <button class="accordion-button collapsed bg-white text-dark fw-bold" type="button" data-toggle="collapse" data-target="#collapse{{ str_replace(' ', '', $roleName) }}" aria-expanded="false" aria-controls="collapse{{ str_replace(' ', '', $roleName) }}">
                                        <i class="fas fa-user-shield me-2 text-secondary"></i> {{ $roleName }} ({{ $roleUsers->count() }} usuarios)
                                    </button>
                                </h2>
                                <div id="collapse{{ str_replace(' ', '', $roleName) }}" class="accordion-collapse collapse" aria-labelledby="heading{{ str_replace(' ', '', $roleName) }}" data-parent="#usersAccordion">
                                    <div class="accordion-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="align-middle px-3" style="width: 50px;"></th>
                                                        <th class="align-middle">Nombre</th>
                                                        <th class="align-middle">Detalles</th>
                                                        <th class="align-middle">Estado</th>
                                                        <th class="align-middle text-end px-3">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($roleUsers as $user)
                                                        @php
                                                            $fullName = trim(($user->person->first_names ?? 'Sin nombre') . ' ' . ($user->person->last_names ?? ''));
                                                            $initials = strtoupper(substr($user->person->first_names ?? 'S', 0, 1) . substr($user->person->last_names ?? 'N', 0, 1));
                                                        @endphp
                                                        <tr>
                                                            <td class="align-middle ps-3 pe-0">
                                                                <div class="avatar-circle rounded-circle bg-avatar-{{ ($loop->index % 4) + 1 }}">
                                                                    {{ $initials }}
                                                                </div>
                                                            </td>
                                                            <td class="align-middle fw-bold">{{ $fullName }}</td>
                                                            <td class="align-middle text-muted small">{{ $user->person->email ?? 'Sin email' }}</td>
                                                            <td class="align-middle">
                                                                <span class="badge {{ $roleName == 'Administrator' ? 'bg-danger' : 'bg-warning text-dark' }}">{{ $roleName }}</span>
                                                            </td>
                                                            <td class="align-middle text-end pe-3">
                                                                <button class="btn btn-sm btn-info text-white btn-edit-user" 
                                                                    data-toggle="modal" 
                                                                    data-target="#editUserModal"
                                                                    data-id="{{ $user->user_id }}"
                                                                    data-first_names="{{ $user->person->first_names ?? '' }}"
                                                                    data-last_names="{{ $user->person->last_names ?? '' }}"
                                                                    data-document_type="{{ $user->person->document_type ?? '' }}"
                                                                    data-document_number="{{ $user->person->document_number ?? '' }}"
                                                                    data-email="{{ $user->person->email ?? '' }}"
                                                                    data-phone="{{ $user->person->phone ?? '' }}"
                                                                    data-address="{{ $user->person->address ?? '' }}"
                                                                    data-gender="{{ $user->person->gender ?? '' }}"
                                                                    data-birth_date="{{ $user->person->birth_date ?? '' }}"
                                                                    data-username="{{ $user->username }}"
                                                                    data-status="{{ $user->status }}"
                                                                    data-role_id="{{ $user->roles->first()->role_id ?? '' }}"
                                                                    data-specialties="{{ json_encode($user->specialties->pluck('specialty_id')->toArray()) }}">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DE CREACIÓN --}}
    <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{ route('admin.users.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createUserModalLabel">Crear Nuevo Usuario</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Nombres *</label>
                        <input type="text" name="first_names" class="form-control form-control-sm" maxlength="20" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Apellidos *</label>
                        <input type="text" name="last_names" class="form-control form-control-sm" maxlength="20" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label small">Tipo Doc.</label>
                        <select name="document_type" id="create_document_type" class="form-select form-select-sm">
                            <option value="DNI">DNI</option>
                            <option value="CE">C.E.</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label small fw-bold">Num. Documento *</label>
                        <input type="text" name="document_number" id="create_document_number" 
                               class="form-control form-control-sm" maxlength="8" minlength="8" pattern="\d{8}"
                               inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label small">Género</label>
                        <select name="gender" class="form-select form-select-sm">
                            <option value="">Seleccionar</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Email *</label>
                        <input type="email" name="email" class="form-control form-control-sm" maxlength="150" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small">Teléfono</label>
                        <input type="text" name="phone" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="form-label small">Dirección</label>
                        <input type="text" name="address" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small">Fecha Nacimiento</label>
                        <input type="date" name="birth_date" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Rol de Acceso *</label>
                        <select name="role_id" id="create_role_id" class="form-select form-select-sm role-selector" required>
                            <option value="">Seleccione un rol</option>
                            @foreach($roles as $role)
                                @if($role->name !== 'Student') {{-- MEJORA: El admin no crea estudiantes desde aquí --}}
                                    <option value="{{ $role->role_id }}" data-name="{{ $role->name }}">{{ $role->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12 my-2 d-none specialties-container">
                        <div class="card bg-light border p-3">
                            <h6 class="fw-bold mb-2 text-primary small"><i class="fas fa-tags"></i> Seleccionar Especialidades del Profesor</h6>
                            <div class="row">
                                @foreach($specialties as $specialty)
                                    <div class="col-md-4 mb-1">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="specialty_ids[]" value="{{ $specialty->specialty_id }}" id="create_spec_{{ $specialty->specialty_id }}">
                                            <label class="form-check-label small text-dark" for="create_spec_{{ $specialty->specialty_id }}">
                                                {{ $specialty->specialty }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Usuario *</label>
                        <input type="text" name="username" class="form-control form-control-sm" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small">Estado</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="A">Activo</option>
                            <option value="I">Inactivo</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Contraseña *</label>
                        <input type="password" name="password" class="form-control form-control-sm" minlength="6" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Confirmar Contraseña *</label>
                        <input type="password" name="password_confirmation" class="form-control form-control-sm" minlength="6" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL DE EDICIÓN --}}
    <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="editUserForm" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Nombres *</label>
                        <input type="text" name="first_names" id="edit_first_names" class="form-control form-control-sm" maxlength="20" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Apellidos *</label>
                        <input type="text" name="last_names" id="edit_last_names" class="form-control form-control-sm" maxlength="20" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label small">Tipo Doc.</label>
                        <select name="document_type" id="edit_document_type" class="form-select form-select-sm">
                            <option value="DNI">DNI</option>
                            <option value="CE">C.E.</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label small fw-bold">Num. Documento *</label>
                        <input type="text" name="document_number" id="edit_document_number" 
                               class="form-control form-control-sm" maxlength="8" minlength="8" pattern="\d{8}"
                               inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <label class="form-label small">Género</label>
                        <select name="gender" id="edit_gender" class="form-select form-select-sm">
                            <option value="">Seleccionar</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Email *</label>
                        <input type="email" name="email" id="edit_email" class="form-control form-control-sm" maxlength="150" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small">Teléfono</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="form-label small">Dirección</label>
                        <input type="text" name="address" id="edit_address" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small">Fecha Nacimiento</label>
                        <input type="date" name="birth_date" id="edit_birth_date" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Rol de Acceso *</label>
                        <select name="role_id" id="edit_role_id" class="form-select form-select-sm role-selector" required>
                            @foreach($roles as $role)
                                @if($role->name !== 'Student')
                                    <option value="{{ $role->role_id }}" data-name="{{ $role->name }}">{{ $role->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12 my-2 d-none specialties-container">
                        <div class="card bg-light border p-3">
                            <h6 class="fw-bold mb-2 text-info small"><i class="fas fa-tags"></i> Modificar Especialidades del Profesor</h6>
                            <div class="row">
                                @foreach($specialties as $specialty)
                                    <div class="col-md-4 mb-1">
                                        <div class="form-check">
                                            <input class="form-check-input edit-spec-checkbox" type="checkbox" name="specialty_ids[]" value="{{ $specialty->specialty_id }}" id="edit_spec_{{ $specialty->specialty_id }}">
                                            <label class="form-check-label small text-dark" for="edit_spec_{{ $specialty->specialty_id }}">
                                                {{ $specialty->specialty }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-2">
                        <label class="form-label small fw-bold">Usuario *</label>
                        <input type="text" name="username" id="edit_username" class="form-control form-control-sm" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small">Estado</label>
                        <select name="status" id="edit_status" class="form-select form-select-sm">
                            <option value="A">Activo</option>
                            <option value="I">Inactivo</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small">Nueva Contraseña (Opcional)</label>
                        <input type="password" name="password" class="form-control form-control-sm" minlength="6" placeholder="Dejar en blanco para mantener">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label small">Confirmar Nueva Contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control form-control-sm" minlength="6" placeholder="Dejar en blanco para mantener">
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info btn-sm text-white">Actualizar Usuario</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // Lógica para cambiar dinámicamente las restricciones según el Tipo de Documento elegido
            function setupDocumentValidation(docTypeSelect, docNumberInput) {
                if(docTypeSelect.value === 'DNI') {
                    docNumberInput.setAttribute('maxlength', '8');
                    docNumberInput.setAttribute('minlength', '8');
                    docNumberInput.setAttribute('pattern', '\\d{8}');
                } else {
                    docNumberInput.setAttribute('maxlength', '20');
                    docNumberInput.removeAttribute('minlength');
                    docNumberInput.removeAttribute('pattern');
                }
            }

            // Listeners para el tipo de documento en creación y edición
            const createDocType = document.getElementById('create_document_type');
            const createDocNum = document.getElementById('create_document_number');
            createDocType.addEventListener('change', () => setupDocumentValidation(createDocType, createDocNum));

            const editDocType = document.getElementById('edit_document_type');
            const editDocNum = document.getElementById('edit_document_number');
            editDocType.addEventListener('change', () => setupDocumentValidation(editDocType, editDocNum));

            function toggleSpecialties(selectElement) {
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                const roleName = selectedOption ? selectedOption.getAttribute('data-name') : '';
                const modal = selectElement.closest('.modal-body');
                const container = modal.querySelector('.specialties-container');

                if (roleName === 'Teacher') {
                    container.classList.remove('d-none');
                } else {
                    container.classList.add('d-none');
                    container.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
                }
            }

            document.querySelectorAll('.role-selector').forEach(select => {
                select.addEventListener('change', function() {
                    toggleSpecialties(this);
                });
            });

            document.querySelectorAll('.btn-edit-user').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    
                    document.getElementById('editUserForm').action = `/admin/users/${id}`;

                    document.getElementById('edit_first_names').value = this.getAttribute('data-first_names');
                    document.getElementById('edit_last_names').value = this.getAttribute('data-last_names');
                    document.getElementById('edit_document_type').value = this.getAttribute('data-document_type') || 'DNI';
                    document.getElementById('edit_document_number').value = this.getAttribute('data-document_number');
                    document.getElementById('edit_email').value = this.getAttribute('data-email');
                    document.getElementById('edit_phone').value = this.getAttribute('data-phone');
                    document.getElementById('edit_address').value = this.getAttribute('data-address');
                    document.getElementById('edit_gender').value = this.getAttribute('data-gender');
                    document.getElementById('edit_birth_date').value = this.getAttribute('data-birth_date');
                    document.getElementById('edit_username').value = this.getAttribute('data-username');
                    document.getElementById('edit_status').value = this.getAttribute('data-status');
                    
                    const roleSelect = document.getElementById('edit_role_id');
                    roleSelect.value = this.getAttribute('data-role_id');

                    document.querySelectorAll('.edit-spec-checkbox').forEach(cb => cb.checked = false);

                    const assignedSpecialties = JSON.parse(this.getAttribute('data-specialties') || '[]');
                    assignedSpecialties.forEach(specId => {
                        const checkbox = document.getElementById(`edit_spec_${specId}`);
                        if (checkbox) checkbox.checked = true;
                    });

                    // Forzar la validación de DNI/CE al abrir el modal de edición
                    setupDocumentValidation(editDocType, editDocNum);
                    toggleSpecialties(roleSelect);
                });
            });
        });
    </script>
@endsection