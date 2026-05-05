@extends('layouts.app')
@section('content')
<div class="container mt-4">
    @if(Session::has('mensaje'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ Session::get('mensaje') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Tarifas</h2>
        <a href="{{ url('tarifa/create') }}" class="btn btn-success">+ Nueva Tarifa</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Base</th>
                    <th>$/km</th>
                    <th>$/min</th>
                    <th>Mínima</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tarifas as $t)
                <tr>
                    <td>{{ $t->id_tarifa }}</td>
                    <td>{{ $t->tipo_servicio }}</td>
                    <td>${{ $t->tarifa_base }}</td>
                    <td>${{ $t->costo_por_km }}</td>
                    <td>${{ $t->costo_por_minuto }}</td>
                    <td>${{ $t->tarifa_minima }}</td>
                    <td>
                        <a href="{{ url('tarifa/'.$t->id_tarifa.'/edit') }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ url('tarifa/'.$t->id_tarifa) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">No hay tarifas</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $tarifas->links() }}
</div>
@endsection
