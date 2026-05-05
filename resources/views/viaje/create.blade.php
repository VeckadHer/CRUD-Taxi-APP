@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>{{ isset($viaje) ? 'Editar' : 'Crear' }} Viaje</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
        @endforeach
    </div>
    @endif

    <form action="{{ isset($viaje) ? url('viaje/'.$viaje->id_viaje) : url('viaje') }}" method="POST" class="card p-4">
        @csrf
        {{ isset($viaje) ? method_field('PATCH') : '' }}

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Pasajero</label>
                <select name="id_pasajero" class="form-control" required>
                    <option value="">Seleccionar</option>
                    @foreach($pasajeros as $p)
                    <option value="{{ $p->id_pasajero }}" {{ isset($viaje) && $viaje->id_pasajero == $p->id_pasajero ? 'selected' : '' }}>
                        {{ $p->usuario->nombre_completo }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label>Conductor</label>
                <select name="id_conductor" class="form-control" required>
                    <option value="">Seleccionar</option>
                    @foreach($conductores as $c)
                    <option value="{{ $c->id_conductor }}" {{ isset($viaje) && $viaje->id_conductor == $c->id_conductor ? 'selected' : '' }}>
                        {{ $c->usuario->nombre_completo }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Origen</label>
                <input type="text" name="origen_descripcion" class="form-control" value="{{ $viaje->origen_descripcion ?? '' }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Destino</label>
                <input type="text" name="destino_descripcion" class="form-control" value="{{ $viaje->destino_descripcion ?? '' }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Distancia (km)</label>
                <input type="number" step="0.01" name="distancia_km" class="form-control" value="{{ $viaje->distancia_km ?? '' }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Duración (min)</label>
                <input type="number" name="duracion_minutos" class="form-control" value="{{ $viaje->duracion_minutos ?? '' }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label>Tarifa</label>
                <select name="id_tarifa" class="form-control">
                    @foreach($tarifas as $t)
                    <option value="{{ $t->id_tarifa }}" {{ isset($viaje) && $viaje->id_tarifa == $t->id_tarifa ? 'selected' : '' }}>
                        {{ $t->tipo_servicio }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-control" required>
                <option value="solicitado" {{ isset($viaje) && $viaje->estado == 'solicitado' ? 'selected' : '' }}>Solicitado</option>
                <option value="en_curso" {{ isset($viaje) && $viaje->estado == 'en_curso' ? 'selected' : '' }}>En Curso</option>
                <option value="completado" {{ isset($viaje) && $viaje->estado == 'completado' ? 'selected' : '' }}>Completado</option>
                <option value="cancelado" {{ isset($viaje) && $viaje->estado == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ url('viaje') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
