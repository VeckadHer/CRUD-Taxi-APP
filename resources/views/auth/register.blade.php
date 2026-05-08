@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0">📝 Crear Cuenta de Usuario</h4>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">El registro es exclusivo para usuarios. Si quieres ser conductor, <a href="{{ url('/solicitud-conductor') }}">deja tus datos aquí</a>.</p>

                    @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                    </div>
                    @endif

                    <form method="POST" action="{{ url('/register') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nombre(s) *</label>
                                <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Apellido Paterno *</label>
                                <input type="text" name="apellido_paterno" class="form-control" value="{{ old('apellido_paterno') }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Apellido Materno *</label>
                                <input type="text" name="apellido_materno" class="form-control" value="{{ old('apellido_materno') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha de Nacimiento *</label>
                                <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}"
                                       max="{{ date('Y-m-d', strtotime('-18 years')) }}" required>
                                <small class="text-muted">Debes ser mayor de 18 años</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Teléfono *</label>
                                <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Domicilio *</label>
                                <input type="text" name="domicilio" class="form-control" value="{{ old('domicilio') }}" placeholder="Calle, número, colonia" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Código Postal *</label>
                                <input type="text" name="codigo_postal" class="form-control" value="{{ old('codigo_postal', '40000') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email *</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contraseña *</label>
                                <input type="password" name="password" class="form-control" minlength="6" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Confirmar Contraseña *</label>
                                <input type="password" name="password_confirmation" class="form-control" minlength="6" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 btn-lg">Crear Cuenta</button>
                    </form>

                    <hr>
                    <p class="text-center mb-0">¿Ya tienes cuenta? <a href="{{ url('/login') }}">Inicia sesión</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
