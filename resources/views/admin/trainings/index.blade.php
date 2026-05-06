@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between mb-3">
            <div>
                <h2>Capacitaciones</h2>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm mt-2">
                    Volver
                </a>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTrainingModal">
                + Crear capacitación
            </button>
        </div>

        <table class="table table-borderless">
            <thead class="border-bottom">
                <tr>
                    <th></th>
                    <th>Curso</th>
                    <th>Profesor</th>
                    <th>Modalidad</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trainings as $training)
                    <tr class="border-bottom">
                        <td>
                            <div class="avatar-circle rounded-circle bg-avatar-{{ ($loop->index % 4) + 1 }}">
                                {{ strtoupper(substr(optional($training->course)->title ?? 'C', 0, 1)) }}
                            </div>
                        </td>
                        <td>{{ optional($training->course)->title }}</td>
                        <td>{{ optional($training->teacher->person)->first_names ?? 'Sin nombre' }}</td>
                        <td>{{ ucfirst($training->modality) }}</td>
                        <td>S/ {{ number_format($training->price, 2) }}</td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('admin.trainings.edit', $training->training_id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.trainings.destroy', $training->training_id) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            No hay capacitaciones activas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Modal -->
        <div class="modal fade" id="createTrainingModal" tabindex="-1" aria-labelledby="createTrainingModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-3">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="createTrainingModalLabel">Crear Capacitación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('admin.trainings.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="course_id" class="form-label">Curso</label>
                                <select name="course_id" id="course_id" class="form-control" required>
                                    <option value="">Seleccionar curso</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->course_id }}">{{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="teacher_id" class="form-label">Docente</label>
                                <select name="teacher_id" id="teacher_id" class="form-control" required>
                                    <option value="">Seleccionar docente</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->user_id }}">{{ $teacher->person->first_names ?? 'Sin nombre' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="modality" class="form-label">Modalidad</label>
                                <select name="modality" id="modality" class="form-control" required>
                                    <option value="virtual">Virtual</option>
                                    <option value="presential">Presencial</option>
                                    <option value="hybrid">Híbrida</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Precio</label>
                                <input type="number" name="price" id="price" class="form-control" step="0.01" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection