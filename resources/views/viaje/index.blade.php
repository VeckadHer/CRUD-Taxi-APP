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
        <h2>📋 Viajes</h2>
        @if(auth()->user() && auth()->user()->esPasajero())
            <a href="{{ url('viaje/create') }}" class="btn btn-success">+ Solicitar Viaje</a>
        @endif
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Pasajero</th>
                            <th>Conductor</th>
                            <th>Origen → Destino</th>
                            <th>Distancia</th>
                            <th>Estado</th>
                            <th>Tarifa</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($viajes as $v)
                        <tr>
                            <td>{{ $v->id_viaje }}</td>
                            <td>{{ $v->pasajero->usuario->nombre_completo ?? 'N/A' }}</td>
                            <td>{{ $v->conductor->usuario->nombre_completo ?? '—' }}</td>
                            <td><small>{{ $v->origen_descripcion }} → {{ $v->destino_descripcion }}</small></td>
                            <td>{{ $v->distancia_km }} km</td>
                            <td>
                                @php $colors = ['solicitado'=>'warning','en_curso'=>'info','completado'=>'success','cancelado'=>'danger']; @endphp
                                <span class="badge bg-{{ $colors[$v->estado] ?? 'secondary' }}">{{ $v->estado }}</span>
                            </td>
                            <td>${{ number_format($v->tarifa_final ?? $v->tarifa_estimada, 2) }}</td>
                            <td>
                                <a href="{{ url('viaje/'.$v->id_viaje) }}" class="btn btn-sm btn-info">Ver</a>
                                @if(auth()->user() && auth()->user()->esAdmin())
                                <form action="{{ url('viaje/'.$v->id_viaje) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">🗑️</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-3">No hay viajes</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">{{ $viajes->links() }}</div>
</div>
@endsection
