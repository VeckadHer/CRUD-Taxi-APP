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
        <h2>Viajes</h2>
        <a href="{{ url('viaje/create') }}" class="btn btn-success">+ Nuevo Viaje</a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Pasajero</th>
                    <th>Conductor</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Estado</th>
                    <th>Tarifa Final</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($viajes as $viaje)
                <tr>
                    <td>{{ $viaje->id_viaje }}</td>
                    <td>{{ $viaje->pasajero->usuario->nombre_completo ?? 'N/A' }}</td>
                    <td>{{ $viaje->conductor->usuario->nombre_completo ?? 'N/A' }}</td>
                    <td>{{ $viaje->origen_descripcion }}</td>
                    <td>{{ $viaje->destino_descripcion }}</td>
                    <td><span class="badge bg-info">{{ $viaje->estado }}</span></td>
                    <td>${{ $viaje->tarifa_final }}</td>
                    <td>
                        <a href="{{ url('viaje/'.$viaje->id_viaje.'/edit') }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ url('viaje/'.$viaje->id_viaje) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center">No hay viajes</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $viajes->links() }}
</div>
@endsection
