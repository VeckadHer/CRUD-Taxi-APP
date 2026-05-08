@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>{{ isset($empresa) ? 'Editar' : 'Nueva' }} Empresa</h2>
    <form action="{{ isset($empresa) ? url('/empresa/'.$empresa->id_empresa) : url('/empresa') }}" method="POST" class="card p-4">
        @csrf
        {{ isset($empresa) ? method_field('PATCH') : '' }}
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ $empresa->nombre ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label>Razón Social</label>
            <input type="text" name="razon_social" class="form-control" value="{{ $empresa->razon_social ?? '' }}">
        </div>
        <div class="mb-3">
            <label>Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="{{ $empresa->telefono ?? '' }}">
        </div>
        <div class="mb-3">
            <label>Dirección</label>
            <input type="text" name="direccion" class="form-control" value="{{ $empresa->direccion ?? '' }}">
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ url('/empresa') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
