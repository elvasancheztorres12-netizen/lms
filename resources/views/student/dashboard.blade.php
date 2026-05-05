@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')

    <h2 class="mb-4">Mi Panel de Estudiante</h2>

    {{-- RESUMEN --}}
    <div class="row mb-4">

        <div class="col-md-4">
            <div class="card p-3 text-center shadow-sm">
                <h5>Cursos inscritos</h5>
                <h2>{{ $totalCourses }}</h2>
            </div>
        </div>

    </div>

    {{-- CURSOS --}}
    <div class="row">

        @forelse($enrollments as $enrollment)

            @php
                $training = $enrollment->training;
                $course = $training->course;

                $progress = $enrollment->progress->avg('percentage') ?? 0;
            @endphp

            <div class="col-md-4 mb-4">

                <div class="card shadow-sm h-100">

                    <div class="card-body">

                        <h5>{{ $course->title }}</h5>

                        <p class="text-muted small">
                            {{ $course->description }}
                        </p>

                        <p>
                            👨‍🏫 Docente: {{ $training->teacher->username }}
                        </p>

                        <p>
                            💰 Precio: S/ {{ $training->price }}
                        </p>

                        {{-- PROGRESO --}}
                        <div class="mt-3">

                            <div class="d-flex justify-content-between">
                                <small>Progreso</small>
                                <small>{{ round($progress, 1) }}%</small>
                            </div>

                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $progress }}%">
                                </div>
                            </div>

                        </div>

                        {{-- ESTADO --}}
                        <div class="mt-2">

                            @if($progress >= 100)
                                <span class="badge bg-success">Completado</span>
                            @elseif($progress > 0)
                                <span class="badge bg-warning text-dark">En progreso</span>
                            @else
                                <span class="badge bg-secondary">Sin iniciar</span>
                            @endif

                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">
                <div class="alert alert-info">
                    No estás inscrito en ningún curso aún
                </div>
            </div>

        @endforelse

    </div>

@endsection