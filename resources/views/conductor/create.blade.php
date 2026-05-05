@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>{{ isset($conductor) ? 'Editar' : 'Crear' }} Conductor</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
        @endforeach
    </div>
    @endif

    <form action="{{ isset($conductor) ? url('conductor/'.$conductor->id_conductor) : url('conductor') }}" method="POST" class="card p-4">
        @csrf
        {{ isset($conductor) ? method_field('PATCH') : '' }}

        <div class="mb-3">
            <label>Usuario</label>
            <select name="id_usuario" class="form-control" required>
                <option value="">Seleccionar</option>
                @foreach($usuarios as $u)
                <option value="{{ $u->id_usuario }}" {{ isset($conductor) && $conductor->id_usuario == $u->id_usuario ? 'selected' : '' }}>
                    {{ $u->nombre_completo }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Licencia</label>
            <input type="text" name="licencia_conducir" class="form-control" value="{{ $conductor->licencia_conducir ?? '' }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Estado</label>
                <select name="estado" class="form-control" required>
                    <option value="activo" {{ isset($conductor) && $conductor->estado == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ isset($conductor) && $conductor->estado == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                    <option value="ocupado" {{ isset($conductor) && $conductor->estado == 'ocupado' ? 'selected' : '' }}>Ocupado</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Disponible</label>
                <select name="disponible" class="form-control">
                    <option value="1" {{ isset($conductor) && $conductor->disponible ? 'selected' : '' }}>Sí</option>
                    <option value="0" {{ isset($conductor) && !$conductor->disponible ? 'selected' : '' }}>No</option>
                </select>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ url('conductor') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
