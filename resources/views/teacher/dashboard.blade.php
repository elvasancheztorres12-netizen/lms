@extends('layouts.app')

@section('title', 'Dashboard Docente')

@section('content')

    <h2 class="mb-4">Panel del docente</h2>

    {{-- 🔥 TARJETAS RESUMEN --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm text-center p-3">
                <h5>Total cursos</h5>
                <h2>{{ $totalCourses }}</h2>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm text-center p-3">
                <h5>Total estudiantes</h5>
                <h2>{{ $totalStudents }}</h2>
            </div>
        </div>
    </div>

    {{-- 🚀 ACCIONES --}}
    <div class="mb-4">
        <a href="#" class="btn btn-primary">
            <i class="fa fa-plus"></i> Crear curso
        </a>
    </div>

    {{-- 📚 LISTA DE CURSOS --}}
    <div class="row">
        @forelse($trainings as $training)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">

                    <div class="card-body">
                        <h5>{{ $training->course->title }}</h5>

                        <p class="text-muted small">
                            {{ $training->course->description }}
                        </p>

                        <span class="badge bg-info text-dark">
                            {{ $training->modality }}
                        </span>

                        <p class="mt-2 mb-1">
                            💰 S/ {{ $training->price }}
                        </p>

                        <p class="mb-2">
                            👨‍🎓 {{ $training->enrollments->count() }} estudiantes
                        </p>

                        <a href="#" class="btn btn-outline-primary btn-sm w-100">
                            Ver curso
                        </a>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No tienes cursos asignados todavía
                </div>
            </div>
        @endforelse
    </div>

@endsection