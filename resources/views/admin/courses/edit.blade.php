@extends('layouts.app')

@section('content')

    <div class="container mt-4">

        <h2>Editar Curso</h2>

        <form method="POST" action="{{ route('admin.courses.update', $course->course_id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Título</label>
                <input type="text" name="title" value="{{ $course->title }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="description" class="form-control">{{ $course->description }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Especialidad</label>
                <select name="specialty_id" class="form-control" required>
                    <option value="">Seleccionar especialidad</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->specialty_id }}" {{ $course->specialty_id == $specialty->specialty_id ? 'selected' : '' }}>
                            {{ $specialty->specialty }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Horas</label>
                <input type="number" name="hours_count" value="{{ $course->hours_count }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Precio de Referencia</label>
                <input type="number" step="0.01" name="reference_price" value="{{ $course->reference_price }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Ruta del Banner</label>
                <input type="text" name="banner_path" value="{{ $course->banner_path }}" class="form-control">
            </div>

            <button class="btn btn-primary">
                Actualizar
            </button>

            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
                Cancelar
            </a>

        </form>

    </div>

@endsection