@extends('layouts.app')
@section('content')
<div class="container mt-4">
    @if(Session::has('mensaje'))
    <div class="alert alert-success">{{ Session::get('mensaje') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>💰 Tarifas por Empresa</h2>
        <a href="{{ url('/tarifa/create') }}" class="btn btn-success">+ Nueva Tarifa</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th><th>Empresa / Servicio</th><th>Base</th>
                        <th>Costo/km</th><th>Costo/min</th><th>Mínimo</th><th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tarifas as $t)
                    <tr>
                        <td>{{ $t->id_tarifa }}</td>
                        <td><strong>{{ $t->tipo_servicio }}</strong></td>
                        <td>${{ number_format($t->tarifa_base, 2) }}</td>
                        <td>${{ number_format($t->costo_por_km, 2) }}</td>
                        <td>${{ number_format($t->costo_por_minuto, 2) }}</td>
                        <td>${{ number_format($t->tarifa_minima, 2) }}</td>
                        <td>
                            <a href="{{ url('/tarifa/'.$t->id_tarifa.'/edit') }}" class="btn btn-sm btn-warning">✏️</a>
                            <form action="{{ url('/tarifa/'.$t->id_tarifa) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $tarifas->links() }}</div>
</div>
@endsection
