@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between mb-3">
            <h2>Especialidades</h2>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSpecialtyModal">
                + Crear especialidad
            </button>
        </div>

        <table class="table table-borderless">
            <thead class="border-bottom">
                <tr>
                    <th></th>
                    <th>Especialidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($specialties as $specialty)
                    <tr class="border-bottom">
                        <td>
                            <div class="avatar-circle rounded-circle bg-avatar-{{ ($loop->index % 4) + 1 }}">
                                {{ strtoupper(substr($specialty->specialty, 0, 1)) }}
                            </div>
                        </td>
                        <td>{{ $specialty->specialty }}</td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('admin.specialties.edit', $specialty->specialty_id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.specialties.destroy', $specialty->specialty_id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="createSpecialtyModal" tabindex="-1" aria-labelledby="createSpecialtyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-3">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="createSpecialtyModalLabel">Crear Especialidad</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('admin.specialties.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="specialty" class="form-label">Especialidad</label>
                                <input type="text" name="specialty" id="specialty" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection