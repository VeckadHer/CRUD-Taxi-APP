@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>✏️ Editar Conductor #{{ $conductor->id_conductor }}</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <form action="{{ url('/conductor/'.$conductor->id_conductor) }}" method="POST">
        @csrf @method('PATCH')

        <div class="card shadow-sm mb-3">
            <div class="card-header bg-primary text-white"><strong>👤 Datos Personales</strong></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nombre(s)</label>
                        <input type="text" name="nombre" class="form-control" 
                               value="{{ old('nombre', explode(' ', $conductor->usuario->nombre_completo)[0] ?? '') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Apellido Paterno</label>
                        <input type="text" name="apellido_paterno" class="form-control" 
                               value="{{ old('apellido_paterno', $conductor->usuario->apellido_paterno) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Apellido Materno</label>
                        <input type="text" name="apellido_materno" class="form-control" 
                               value="{{ old('apellido_materno', $conductor->usuario->apellido_materno) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $conductor->usuario->telefono) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Código Postal</label>
                        <input type="text" name="codigo_postal" class="form-control" value="{{ old('codigo_postal', $conductor->usuario->codigo_postal) }}" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Domicilio</label>
                    <input type="text" name="domicilio" class="form-control" value="{{ old('domicilio', $conductor->usuario->domicilio) }}" required>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-3">
            <div class="card-header bg-warning text-dark"><strong>💼 Datos Laborales</strong></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Empresa</label>
                        <select name="id_empresa" class="form-control" required>
                            @foreach($empresas as $e)
                            <option value="{{ $e->id_empresa }}" {{ $conductor->id_empresa == $e->id_empresa ? 'selected' : '' }}>{{ $e->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tipo de Vehículo</label>
                        <select name="tipo_vehiculo_operar" class="form-control" required>
                            <option value="particular" {{ $conductor->tipo_vehiculo_operar == 'particular' ? 'selected' : '' }}>Vehículo Particular</option>
                            <option value="empresa" {{ $conductor->tipo_vehiculo_operar == 'empresa' ? 'selected' : '' }}>Unidad de Empresa</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Licencia</label>
                    <input type="text" name="licencia_conducir" class="form-control" value="{{ old('licencia_conducir', $conductor->licencia_conducir) }}" required>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg">💾 Guardar Cambios</button>
        <a href="{{ url('/conductor') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
