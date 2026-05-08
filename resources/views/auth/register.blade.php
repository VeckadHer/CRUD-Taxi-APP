@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0">📝 Crear Cuenta</h4>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                    </div>
                    @endif

                    <form method="POST" action="{{ url('/register') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="nombre_completo" class="form-control" value="{{ old('nombre_completo') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">¿Cómo te quieres registrar?</label>
                            <select name="rol" class="form-control" required>
                                <option value="pasajero">🧑 Pasajero (solicitar viajes)</option>
                                <option value="conductor">🚖 Conductor (ofrecer viajes)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="form-control" required minlength="6">
                        </div>
                        <button type="submit" class="btn btn-success w-100">Crear Cuenta</button>
                    </form>

                    <hr>
                    <p class="text-center mb-0">
                        ¿Ya tienes cuenta? <a href="{{ url('/login') }}">Inicia sesión</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
