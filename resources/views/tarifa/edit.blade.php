@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>{{ isset($tarifa) ? 'Editar' : 'Crear' }} Tarifa</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
        @endforeach
    </div>
    @endif

    <form action="{{ isset($tarifa) ? url('tarifa/'.$tarifa->id_tarifa) : url('tarifa') }}" method="POST" class="card p-4">
        @csrf
        {{ isset($tarifa) ? method_field('PATCH') : '' }}

        <div class="mb-3">
            <label>Tipo de Servicio</label>
            <input type="text" name="tipo_servicio" class="form-control" value="{{ $tarifa->tipo_servicio ?? '' }}" placeholder="economico, premium, van, moto, lujo" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Tarifa Base ($)</label>
                <input type="number" step="0.01" name="tarifa_base" class="form-control" value="{{ $tarifa->tarifa_base ?? '' }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Costo por km ($)</label>
                <input type="number" step="0.01" name="costo_por_km" class="form-control" value="{{ $tarifa->costo_por_km ?? '' }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Costo por minuto ($)</label>
                <input type="number" step="0.01" name="costo_por_minuto" class="form-control" value="{{ $tarifa->costo_por_minuto ?? '' }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Tarifa Mínima ($)</label>
                <input type="number" step="0.01" name="tarifa_minima" class="form-control" value="{{ $tarifa->tarifa_minima ?? '' }}" required>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ url('tarifa') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
