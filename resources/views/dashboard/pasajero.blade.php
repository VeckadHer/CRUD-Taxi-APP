@extends('layouts.app')
@section('content')
<div class="container mt-4">
    @if(Session::has('mensaje'))
    <div class="alert alert-success">{{ Session::get('mensaje') }}</div>
    @endif
    @if(Session::has('error'))
    <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif

    <h2>👤 Hola, {{ Auth::user()->nombre_completo }}</h2>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-success"><div class="card-body">
                <h6>Viajes Completados</h6><h3>{{ $stats['viajes_completados'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger"><div class="card-body">
                <h6>Cancelados</h6><h3>{{ $stats['viajes_cancelados'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-primary"><div class="card-body">
                <h6>Total Gastado</h6><h3>${{ number_format($stats['gastado_total'], 2) }}</h3>
            </div></div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning"><div class="card-body">
                <h6>Tu Calificación</h6><h3>{{ $stats['calificacion'] ?? 'N/A' }} ⭐</h3>
            </div></div>
        </div>
    </div>

    @if($viajeActivo)
    <div class="card border-info mb-4 shadow">
        <div class="card-header bg-info text-white"><strong>🚖 Tienes un viaje activo</strong></div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <p><strong>Estado:</strong>
                        <span class="badge bg-{{ $viajeActivo->estado === 'solicitado' ? 'warning' : 'info' }}">
                            {{ $viajeActivo->estado }}
                        </span>
                    </p>
                    <p><strong>Conductor:</strong> {{ $viajeActivo->conductor->usuario->nombre_completo ?? 'Esperando...' }}</p>
                    <p><strong>Empresa:</strong> {{ $viajeActivo->conductor->empresa->nombre ?? '—' }}</p>
                    <p><strong>Origen:</strong> {{ $viajeActivo->origen_descripcion }}</p>
                    <p><strong>Destino:</strong> {{ $viajeActivo->destino_descripcion }}</p>
                    <p><strong>Tarifa:</strong> ${{ $viajeActivo->tarifa_estimada }}</p>
                    <p><strong>Distancia:</strong> {{ $viajeActivo->distancia_km }} km · {{ $viajeActivo->duracion_minutos }} min</p>
                </div>
                <div class="col-md-4 text-end">
                    <form action="{{ url('viaje/'.$viajeActivo->id_viaje.'/cancelar') }}" method="POST">
                        @csrf
                        <input type="hidden" name="razon" value="Cancelado por pasajero">
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Cancelar viaje?{{ $viajeActivo->estado === 'en_curso' ? ' Se aplicará 20% de penalización.' : '' }}')">
                            ❌ Cancelar Viaje
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white"><strong>🚕 Solicitar Nuevo Viaje</strong></div>
        <div class="card-body text-center">
            <a href="{{ url('viaje/create') }}" class="btn btn-lg btn-primary">➕ Nuevo Viaje</a>
        </div>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white"><strong>📋 Mi Historial</strong></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>#</th><th>Fecha</th><th>Ruta</th><th>Conductor</th><th>Empresa</th><th>Estado</th><th>Total</th></tr>
                    </thead>
                    <tbody>
                        @forelse($historial as $v)
                        <tr>
                            <td>{{ $v->id_viaje }}</td>
                            <td>{{ $v->fecha_solicitud ? \Carbon\Carbon::parse($v->fecha_solicitud)->format('d/m H:i') : '—' }}</td>
                            <td><small>{{ $v->origen_descripcion }} → {{ $v->destino_descripcion }}</small></td>
                            <td>{{ $v->conductor->usuario->nombre_completo ?? '—' }}</td>
                            <td><small class="badge bg-info">{{ $v->conductor->empresa->nombre ?? '—' }}</small></td>
                            <td>
                                @php $colors = ['solicitado'=>'warning','en_curso'=>'info','completado'=>'success','cancelado'=>'danger']; @endphp
                                <span class="badge bg-{{ $colors[$v->estado] ?? 'secondary' }}">{{ $v->estado }}</span>
                            </td>
                            <td>${{ number_format($v->tarifa_final ?? $v->tarifa_estimada, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted py-3">Sin viajes</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
