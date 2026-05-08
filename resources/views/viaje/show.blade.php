@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <a href="{{ url('viaje') }}" class="btn btn-link mb-3">← Volver</a>

    <div class="card shadow">
        <div class="card-header bg-dark text-white">
            <strong>Viaje #{{ $viaje->id_viaje }}</strong>
            @php $colors = ['solicitado'=>'warning','en_curso'=>'info','completado'=>'success','cancelado'=>'danger']; @endphp
            <span class="badge bg-{{ $colors[$viaje->estado] ?? 'secondary' }} ms-2">{{ $viaje->estado }}</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>👥 Personas</h5>
                    <p><strong>Pasajero:</strong> {{ $viaje->pasajero->usuario->nombre_completo ?? 'N/A' }}</p>
                    <p><strong>Conductor:</strong> {{ $viaje->conductor->usuario->nombre_completo ?? 'No asignado' }}</p>

                    <h5 class="mt-4">📍 Ruta</h5>
                    <p><strong>Origen:</strong> {{ $viaje->origen_descripcion }}</p>
                    <p><strong>Destino:</strong> {{ $viaje->destino_descripcion }}</p>
                    <p><strong>Distancia:</strong> {{ $viaje->distancia_km }} km</p>
                    <p><strong>Duración estimada:</strong> {{ $viaje->duracion_minutos }} minutos</p>
                </div>
                <div class="col-md-6">
                    <h5>💰 Costos</h5>
                    <p><strong>Tipo de servicio:</strong> {{ $viaje->tarifa->tipo_servicio ?? 'N/A' }}</p>
                    <p><strong>Tarifa estimada:</strong> ${{ number_format($viaje->tarifa_estimada, 2) }}</p>
                    <p><strong>Tarifa final:</strong> 
                        <span class="text-success fs-4">${{ number_format($viaje->tarifa_final ?? 0, 2) }}</span>
                    </p>

                    <h5 class="mt-4">🕐 Tiempos</h5>
                    <p><strong>Solicitado:</strong> {{ $viaje->fecha_solicitud ? \Carbon\Carbon::parse($viaje->fecha_solicitud)->format('d/m/Y H:i:s') : '—' }}</p>
                    <p><strong>Iniciado:</strong> {{ $viaje->fecha_inicio ? \Carbon\Carbon::parse($viaje->fecha_inicio)->format('d/m/Y H:i:s') : '—' }}</p>
                    <p><strong>Finalizado:</strong> {{ $viaje->fecha_fin ? \Carbon\Carbon::parse($viaje->fecha_fin)->format('d/m/Y H:i:s') : '—' }}</p>
                </div>
            </div>

            @if($viaje->cancelado_por)
            <div class="alert alert-danger mt-3">
                <strong>❌ Cancelado por:</strong> {{ $viaje->cancelado_por }}<br>
                <strong>Razón:</strong> {{ $viaje->razon_cancelacion }}
            </div>
            @endif

            @if($viaje->pago)
            <div class="card mt-3 border-success">
                <div class="card-header bg-success text-white">💳 Información de Pago</div>
                <div class="card-body">
                    <p class="mb-1"><strong>Método:</strong> {{ ucfirst($viaje->pago->metodo_pago) }}</p>
                    <p class="mb-1"><strong>Estado:</strong> {{ $viaje->pago->estado_pago }}</p>
                    <p class="mb-1"><strong>Referencia:</strong> {{ $viaje->pago->referencia }}</p>
                    <p class="mb-0"><strong>Monto:</strong> ${{ number_format($viaje->pago->monto, 2) }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
