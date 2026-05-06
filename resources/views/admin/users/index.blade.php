@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between mb-3">
            <h2>Usuarios</h2>
        </div>

        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->user_id }}</td>
                        <td>{{ $user->person->first_names ?? 'Sin nombre' }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ optional($user->roles->first())->name }}</td>
                        <td>{{ $user->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $users->links() }}

    </div>

@endsection