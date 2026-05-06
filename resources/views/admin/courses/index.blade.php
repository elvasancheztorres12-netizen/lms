@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between mb-3">
            <h2>Cursos (Admin)</h2>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCourseModal">
                + Crear curso
            </button>
        </div>

        <table class="table table-borderless">
            <thead class="border-bottom">
                <tr>
                    <th></th>
                    <th>Título</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach($courses as $course)
                    <tr class="border-bottom">
                        <td>
                            <div class="avatar-circle rounded-circle bg-avatar-{{ ($loop->index % 4) + 1 }}">
                                {{ strtoupper(substr($course->title, 0, 1)) }}
                            </div>
                        </td>
                        <td>{{ $course->title }}</td>
                        <td>S/ {{ $course->reference_price }}</td>

                        <td class="d-flex gap-2">

                            <a href="{{ route('admin.courses.edit', $course->course_id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form method="POST" action="{{ route('admin.courses.destroy', $course->course_id) }}">
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
        <div class="modal fade" id="createCourseModal" tabindex="-1" aria-labelledby="createCourseModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 rounded-3">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-bold" id="createCourseModalLabel">Crear Curso</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('admin.courses.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Título</label>
                                <input type="text" name="title" id="title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Descripción</label>
                                <textarea name="description" id="description" class="form-control"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="specialty_id" class="form-label">Especialidad</label>
                                <select name="specialty_id" id="specialty_id" class="form-control" required>
                                    <option value="">Seleccionar especialidad</option>
                                    @foreach($specialties as $specialty)
                                        <option value="{{ $specialty->specialty_id }}">{{ $specialty->specialty }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="hours_count" class="form-label">Horas</label>
                                <input type="number" name="hours_count" id="hours_count" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="reference_price" class="form-label">Precio</label>
                                <input type="number" name="reference_price" id="reference_price" class="form-control" step="0.01">
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection