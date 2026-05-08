@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>{{ isset($tarifa) ? 'Editar' : 'Nueva' }} Tarifa</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <form action="{{ isset($tarifa) ? url('/tarifa/'.$tarifa->id_tarifa) : url('/tarifa') }}" method="POST" class="card p-4">
        @csrf
        @if(isset($tarifa)) @method('PATCH') @endif

        <div class="mb-3">
            <label>Empresa / Tipo de Servicio</label>
            <input type="text" name="tipo_servicio" class="form-control" value="{{ $tarifa->tipo_servicio ?? '' }}" required>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Tarifa Base ($)</label>
                <input type="number" step="0.01" name="tarifa_base" class="form-control" value="{{ $tarifa->tarifa_base ?? 25 }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Tarifa Mínima ($)</label>
                <input type="number" step="0.01" name="tarifa_minima" class="form-control" value="{{ $tarifa->tarifa_minima ?? 35 }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Costo por km ($)</label>
                <input type="number" step="0.01" name="costo_por_km" class="form-control" value="{{ $tarifa->costo_por_km ?? 6 }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Costo por minuto ($)</label>
                <input type="number" step="0.01" name="costo_por_minuto" class="form-control" value="{{ $tarifa->costo_por_minuto ?? 1.5 }}" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ url('/tarifa') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
