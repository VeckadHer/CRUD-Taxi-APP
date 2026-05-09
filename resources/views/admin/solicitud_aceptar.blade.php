@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>✓ Aprobar Solicitud y Crear Conductor</h2>

    <div class="alert alert-info">
        <strong>📋 Solicitud original:</strong><br>
        <strong>Nombre:</strong> {{ $solicitud->nombre_completo }} ·
        <strong>Teléfono:</strong> {{ $solicitud->telefono }}
        @if($solicitud->email) · <strong>Email original:</strong> {{ $solicitud->email }} @endif
        @if($solicitud->mensaje) <br><strong>Mensaje:</strong> {{ $solicitud->mensaje }} @endif
    </div>

    @if($errors->any())
    <div class="alert alert-danger">
        <strong>Por favor corrige:</strong>
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ url('/solicitudes-conductor/'.$solicitud->id_solicitud.'/aprobar') }}" method="POST">
        @csrf

        <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white"><strong>👤 Datos Personales</strong></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nombre(s) *</label>
                        @php $partes = explode(' ', $solicitud->nombre_completo); @endphp
                        <input type="text" name="nombre" class="form-control" 
                               value="{{ old('nombre', $partes[0] ?? '') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Apellido Paterno *</label>
                        <input type="text" name="apellido_paterno" class="form-control" 
                               value="{{ old('apellido_paterno', $partes[1] ?? '') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Apellido Materno *</label>
                        <input type="text" name="apellido_materno" class="form-control" 
                               value="{{ old('apellido_materno', $partes[2] ?? '') }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Fecha Nacimiento * (+18)</label>
                        <input type="date" name="fecha_nacimiento" class="form-control" 
                               value="{{ old('fecha_nacimiento') }}"
                               max="{{ date('Y-m-d', strtotime('-18 years')) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Código Postal *</label>
                        <input type="text" name="codigo_postal" class="form-control" 
                               value="{{ old('codigo_postal', '40000') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" value="{{ $solicitud->telefono }}" disabled>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Domicilio *</label>
                    <input type="text" name="domicilio" class="form-control" value="{{ old('domicilio') }}" required>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header bg-info text-white"><strong>🔐 Cuenta de Acceso</strong></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span> <small class="text-muted">(debe ser @driver.com)</small></label>
                        @php
                            $emailSugerido = '';
                            if(isset($partes[0]) && isset($partes[1])) {
                                $emailSugerido = strtolower($partes[0] . '.' . $partes[1]) . '@driver.com';
                                $emailSugerido = preg_replace('/[áéíóúñ]/u', '', $emailSugerido);
                            }
                        @endphp
                        <input type="email" name="email" class="form-control" 
                               value="{{ old('email', $emailSugerido) }}" 
                               placeholder="ejemplo@driver.com" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contraseña *</label>
                        <input type="password" name="password" class="form-control" minlength="6" value="{{ old('password', '123456') }}" required>
                        <small class="text-muted">Anota la contraseña para entregársela al conductor</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header bg-warning text-dark"><strong>💼 Datos Laborales</strong></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Empresa *</label>
                        <select name="id_empresa" class="form-control" required>
                            <option value="">— Selecciona —</option>
                            @foreach($empresas as $e)
                            <option value="{{ $e->id_empresa }}" {{ old('id_empresa') == $e->id_empresa ? 'selected' : '' }}>
                                {{ $e->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipo de Vehículo *</label>
                        <select name="tipo_vehiculo_operar" class="form-control" required>
                            <option value="">— Selecciona —</option>
                            <option value="particular" {{ old('tipo_vehiculo_operar') == 'particular' ? 'selected' : '' }}>Vehículo Particular</option>
                            <option value="empresa" {{ old('tipo_vehiculo_operar') == 'empresa' ? 'selected' : '' }}>Unidad de Empresa</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Licencia *</label>
                    <input type="text" name="licencia_conducir" class="form-control" 
                           value="{{ old('licencia_conducir') }}" placeholder="Ej: AB123456GRO" required>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mb-5">
            <button type="submit" class="btn btn-success btn-lg">✓ Aprobar y Crear Cuenta</button>
            <a href="{{ url('/solicitudes-conductor') }}" class="btn btn-secondary btn-lg">Cancelar</a>
        </div>
    </form>
</div>
@endsection
