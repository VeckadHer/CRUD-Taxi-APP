@extends('layouts.app')
@section('content')
<div class="container mt-4">
    @if(Session::has('mensaje'))
    <div class="alert alert-success">{{ Session::get('mensaje') }}</div>
    @endif
    @if(Session::has('error'))
    <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-0">🚖 {{ Auth::user()->nombre_completo }}</h2>
            <small class="text-muted">{{ $conductor->empresa->nombre ?? 'Sin empresa' }} · Lic. {{ $conductor->licencia_conducir }}</small>
        </div>
    </div>

    {{-- TOGGLE DE ESTADO --}}
    <div class="card shadow border-{{ $stats['disponible'] ? 'success' : 'secondary' }} mb-3">
        <div class="card-body">
            <h6 class="mb-3"><strong>Mi estado actual:</strong></h6>
            <form action="{{ url('/conductor/toggle-disponibilidad') }}" method="POST" class="d-flex gap-2">
                @csrf
                <button type="submit" name="estado" value="disponible" 
                        class="btn btn-{{ $stats['estado'] === 'disponible' ? 'success' : 'outline-success' }}">
                    🟢 Disponible
                </button>
                <button type="submit" name="estado" value="en_viaje" 
                        class="btn btn-{{ $stats['estado'] === 'en_viaje' ? 'warning' : 'outline-warning' }}">
                    🟡 En Viaje
                </button>
                <button type="submit" name="estado" value="inactivo" 
                        class="btn btn-{{ $stats['estado'] === 'inactivo' ? 'secondary' : 'outline-secondary' }}">
                    🔴 Inactivo
                </button>
            </form>
            <small class="text-muted mt-2 d-block">
                Estado actual: <strong>{{ $stats['estado'] }}</strong>
                @if($stats['estado'] === 'disponible') · Estás recibiendo solicitudes de viaje @endif
                @if($stats['estado'] === 'inactivo') · Los pasajeros no pueden seleccionarte @endif
            </small>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-success"><div class="card-body">
                <h6>Viajes Hoy</h6><h3>{{ $stats['viajes_completados_hoy'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-primary"><div class="card-body">
                <h6>Ingresos Hoy</h6><h3>${{ number_format($stats['ingresos_hoy'], 2) }}</h3>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info"><div class="card-body">
                <h6>Total Viajes</h6><h3>{{ $stats['viajes_totales'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning"><div class="card-body">
                <h6>Calificación</h6><h3>{{ $stats['calificacion'] ?? 'N/A' }} ⭐</h3>
            </div></div>
        </div>
    </div>

    {{-- Viaje activo --}}
    @if($viajeActivo)
    <div class="card border-info mb-4 shadow">
        <div class="card-header bg-info text-white"><strong>🚕 Viaje en Curso</strong></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <p><strong>Pasajero:</strong> {{ $viajeActivo->pasajero->usuario->nombre_completo ?? 'N/A' }}</p>
                    <p><strong>Origen:</strong> {{ $viajeActivo->origen_descripcion }}</p>
                    <p><strong>Destino:</strong> {{ $viajeActivo->destino_descripcion }}</p>
                    <p><strong>Distancia:</strong> {{ $viajeActivo->distancia_km }} km · {{ $viajeActivo->duracion_minutos }} min</p>
                    <p><strong>Tarifa:</strong> ${{ $viajeActivo->tarifa_estimada }}</p>
                </div>
                <div class="col-md-4 text-end">
                    <form action="{{ url('viaje/'.$viajeActivo->id_viaje.'/finalizar') }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg w-100">✓ Finalizar Viaje</button>
                    </form>
                    <form action="{{ url('viaje/'.$viajeActivo->id_viaje.'/cancelar') }}" method="POST">
                        @csrf
                        <input type="hidden" name="razon" value="Cancelado por conductor">
                        <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('¿Cancelar?')">❌ Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Solicitudes para mí --}}
    @if(!$viajeActivo)
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-warning text-dark">
            <strong>📍 Solicitudes Asignadas a Mí ({{ $solicitudes->count() }})</strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th><th>Pasajero</th><th>Ruta</th>
                            <th>Distancia</th><th>Tarifa</th><th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($solicitudes as $s)
                        <tr>
                            <td>{{ $s->id_viaje }}</td>
                            <td>{{ $s->pasajero->usuario->nombre_completo ?? 'N/A' }}</td>
                            <td><small>{{ $s->origen_descripcion }} → {{ $s->destino_descripcion }}</small></td>
                            <td>{{ $s->distancia_km }} km</td>
                            <td><strong class="text-success">${{ $s->tarifa_estimada }}</strong></td>
                            <td>
                                <form action="{{ url('viaje/'.$s->id_viaje.'/aceptar') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">✓ Aceptar</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">No hay solicitudes en este momento</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- Historial del día --}}
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white"><strong>📋 Historial de Hoy</strong></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>#</th><th>Pasajero</th><th>Ruta</th><th>Estado</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                        @forelse($historialHoy as $v)
                        <tr>
                            <td>{{ $v->id_viaje }}</td>
                            <td>{{ $v->pasajero->usuario->nombre_completo ?? 'N/A' }}</td>
                            <td><small>{{ $v->origen_descripcion }} → {{ $v->destino_descripcion }}</small></td>
                            <td>
                                @php $colors = ['completado'=>'success','cancelado'=>'danger']; @endphp
                                <span class="badge bg-{{ $colors[$v->estado] ?? 'secondary' }}">{{ $v->estado }}</span>
                            </td>
                            <td>${{ number_format($v->tarifa_final ?? 0, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Sin viajes hoy</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
