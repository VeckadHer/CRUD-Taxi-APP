@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <h2>Editar Viaje #{{ $viaje->id_viaje }}</h2>

    <form action="{{ url('viaje/'.$viaje->id_viaje) }}" method="POST" class="card p-4">
        @csrf @method('PATCH')

        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-control">
                @foreach(['solicitado','en_curso','completado','cancelado'] as $e)
                    <option value="{{ $e }}" {{ $viaje->estado==$e?'selected':'' }}>{{ $e }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Tarifa final</label>
            <input type="number" step="0.01" name="tarifa_final" class="form-control" value="{{ $viaje->tarifa_final }}">
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ url('viaje') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
