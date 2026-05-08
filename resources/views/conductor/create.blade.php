@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>🚖 Registrar Nuevo Conductor</h2>
        <a href="{{ url('/conductor') }}" class="btn btn-secondary">← Volver</a>
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <strong>Por favor corrige los siguientes errores:</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ url('/conductor') }}" method="POST">
        @csrf

        {{-- Datos personales --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white">
                <strong>👤 Datos Personales</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nombre(s) <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Apellido Paterno <span class="text-danger">*</span></label>
                        <input type="text" name="apellido_paterno" class="form-control" value="{{ old('apellido_paterno') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Apellido Materno <span class="text-danger">*</span></label>
                        <input type="text" name="apellido_materno" class="form-control" value="{{ old('apellido_materno') }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha de Nacimiento <span class="text-danger">*</span></label>
                        <input type="date" name="fecha_nacimiento" class="form-control" value="{{ old('fecha_nacimiento') }}" 
                               max="{{ date('Y-m-d', strtotime('-18 years')) }}" required>
                        <small class="text-muted">Debe ser mayor de 18 años</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Teléfono <span class="text-danger">*</span></label>
                        <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}" maxlength="20" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Código Postal <span class="text-danger">*</span></label>
                        <input type="text" name="codigo_postal" class="form-control" value="{{ old('codigo_postal', '40000') }}" maxlength="10" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Domicilio <span class="text-danger">*</span></label>
                    <input type="text" name="domicilio" class="form-control" value="{{ old('domicilio') }}" placeholder="Calle, número, colonia, ciudad" required>
                </div>
            </div>
        </div>

        {{-- Cuenta --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-info text-white">
                <strong>🔐 Cuenta de Acceso</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contraseña <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                        <small class="text-muted">Mínimo 6 caracteres. Esta contraseña se le entregará al conductor.</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Datos laborales --}}
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-warning text-dark">
                <strong>💼 Datos Laborales</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Empresa <span class="text-danger">*</span></label>
                        <select name="id_empresa" class="form-control" required>
                            <option value="">— Selecciona empresa —</option>
                            @foreach($empresas as $e)
                            <option value="{{ $e->id_empresa }}" {{ old('id_empresa') == $e->id_empresa ? 'selected' : '' }}>
                                {{ $e->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipo de Vehículo a Operar <span class="text-danger">*</span></label>
                        <select name="tipo_vehiculo_operar" class="form-control" required>
                            <option value="">— Selecciona —</option>
                            <option value="particular" {{ old('tipo_vehiculo_operar') == 'particular' ? 'selected' : '' }}>Vehículo Particular</option>
                            <option value="empresa" {{ old('tipo_vehiculo_operar') == 'empresa' ? 'selected' : '' }}>Unidad de la Empresa</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Licencia de Conducir <span class="text-danger">*</span></label>
                    <input type="text" name="licencia_conducir" class="form-control" value="{{ old('licencia_conducir') }}" placeholder="Ej: AB123456GRO" required>
                    <small class="text-muted">ID único de la licencia de conducir</small>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mb-5">
            <button type="submit" class="btn btn-success btn-lg">✓ Registrar Conductor</button>
            <a href="{{ url('/conductor') }}" class="btn btn-secondary btn-lg">Cancelar</a>
        </div>
    </form>
</div>
@endsection
