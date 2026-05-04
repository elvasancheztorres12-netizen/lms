<form method="POST" action="/login" class="container mt-5" style="max-width: 400px;">
    @csrf

    <h3 class="mb-4">Iniciar sesión</h3>

    <input type="text" name="username" placeholder="Usuario"
        class="form-control mb-3" required>

    <input type="password" name="password" placeholder="Contraseña"
        class="form-control mb-3" required>

    <button class="btn btn-primary w-100">Ingresar</button>

    @if($errors->any())
        <div class="text-danger mt-3">
            {{ $errors->first() }}
        </div>
    @endif
</form>