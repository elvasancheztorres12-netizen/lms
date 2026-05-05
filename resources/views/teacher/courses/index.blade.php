@extends('layouts.app')

@section('content')

    <div class="container mt-4">
        <h2>Mis Cursos</h2>

        <div class="row">
            @forelse ($trainings as $training)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm">

                        <div class="card-body">
                            <h5 class="card-title">
                                {{ $training->course->title }}
                            </h5>

                            <p class="card-text">
                                {{ $training->course->description }}
                            </p>

                            <p class="text-muted">
                                Modalidad: {{ $training->modality }}
                            </p>

                            <p>
                                Estudiantes: {{ $training->enrollments->count() }}
                            </p>
                            <p>
                                Precio: S/ {{ $training->price }}
                            </p>

                            <a href="{{ route('teacher.courses.students', $training->training_id) }}"
                                class="btn btn-sm btn-primary">
                                Ver estudiantes
                            </a>
                        </div>

                    </div>
                </div>
            @empty
                <p>No tienes cursos asignados.</p>
            @endforelse
        </div>
    </div>

@endsection