@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between mb-3">
            <div>
                <h2>Trainings</h2>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm mt-2">
                    Volver
                </a>
            </div>
            <a href="{{ route('admin.trainings.create') }}" class="btn btn-primary">
                + Crear Training
            </a>
        </div>

        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Curso</th>
                    <th>Profesor</th>
                    <th>Modalidad</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($trainings as $training)
                    <tr>
                        <td>{{ $training->training_id }}</td>
                        <td>{{ optional($training->course)->title }}</td>
                        <td>{{ optional($training->teacher->person)->first_names ?? 'Sin nombre' }}</td>
                        <td>{{ ucfirst($training->modality) }}</td>
                        <td>S/ {{ number_format($training->price, 2) }}</td>
                        <td>
                            <a href="{{ route('admin.trainings.edit', $training->training_id) }}" class="btn btn-sm btn-warning">
                                Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            No hay trainings activos
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

@endsection