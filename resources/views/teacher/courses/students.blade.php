@extends('layouts.app')

@section('content')

    <div class="container mt-4">
        <h2>
            Estudiantes del curso: {{ $training->course->title }}
        </h2>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    <tr>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No hay estudiantes inscritos</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection