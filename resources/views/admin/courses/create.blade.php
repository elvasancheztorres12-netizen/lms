@extends('layouts.app')

@section('content')

    <div class="container mt-4">

        <h2>Crear Curso</h2>

        <form method="POST" action="{{ route('admin.courses.store') }}">
            @csrf

            <input type="text" name="title" placeholder="Título" class="form-control mb-2">

            <textarea name="description" placeholder="Descripción" class="form-control mb-2"></textarea>

            <div class="mb-3">
                <label class="form-label">Especialidad</label>
                <select name="specialty_id" class="form-control" required>
                    <option value="">Seleccionar especialidad</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->specialty_id }}">{{ $specialty->specialty }}</option>
                    @endforeach
                </select>
            </div>

            <input type="number" name="hours_count" placeholder="Horas" class="form-control mb-2">

            <input type="number" name="reference_price" placeholder="Precio" class="form-control mb-2">

            <div class="d-flex gap-2">
                <button class="btn btn-primary">
                    Guardar
                </button>
                <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
                    Volver
                </a>
            </div>

        </form>

    </div>

@endsection