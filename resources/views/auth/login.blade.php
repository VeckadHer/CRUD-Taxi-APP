@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">🚕 Iguala App - Iniciar Sesión</h4>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                    </div>
                    @endif

                    <form method="POST" action="{{ url('/login') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                    </form>

                    <hr>
                    <p class="text-center mb-0">
                        ¿No tienes cuenta? <a href="{{ url('/register') }}">Regístrate aquí</a>
                    </p>
                </div>
                <div class="card-footer bg-light">
                    <small class="text-muted">
                        <strong>Cuentas de prueba:</strong><br>
                        👤 admin@iguala.app / 123456<br>
                        🚖 conductor@iguala.app / 123456<br>
                        🧑 pasajero@iguala.app / 123456
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
