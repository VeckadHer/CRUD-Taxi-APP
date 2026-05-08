@extends('layouts.app')
@section('content')
<div class="container-fluid mt-4">
    @if(Session::has('mensaje'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ Session::get('mensaje') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <h2 class="mb-4">📊 Panel Administrativo - Iguala App</h2>

    {{-- Tarjetas de estadísticas --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <h6>Viajes Pendientes</h6>
                    <h2 class="mb-0">{{ $stats['viajes_pendientes'] }}</h2>
                    <small>Esperando conductor</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info shadow">
                <div class="card-body">
                    <h6>Viajes en Curso</h6>
                    <h2 class="mb-0">{{ $stats['viajes_en_curso'] }}</h2>
                    <small>Activos ahora</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success shadow">
                <div class="card-body">
                    <h6>Completados</h6>
                    <h2 class="mb-0">{{ $stats['viajes_completados'] }}</h2>
                    <small>Total histórico</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow">
                <div class="card-body">
                    <h6>Cancelados</h6>
                    <h2 class="mb-0">{{ $stats['viajes_cancelados'] }}</h2>
                    <small>Con o sin penalización</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-success shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">💰 Ingresos Hoy</h6>
                    <h3 class="text-success">${{ number_format($stats['ingresos_hoy'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-primary shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">💵 Ingresos Totales</h6>
                    <h3 class="text-primary">${{ number_format($stats['ingresos_total'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">🚖 Conductores Disponibles</h6>
                    <h3>{{ $stats['conductores_disponibles'] }}/{{ $stats['total_conductores'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">👥 Pasajeros Registrados</h6>
                    <h3>{{ $stats['total_pasajeros'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    {{-- Gráficos --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <strong>Distribución de Viajes</strong>
                </div>
                <div class="card-body">
                    <canvas id="chartViajes"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <strong>Métodos de Pago</strong>
                </div>
                <div class="card-body">
                    <canvas id="chartPagos"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Top performers --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">⭐ Mejor Conductor</div>
                <div class="card-body">
                    @if($mejorConductor && $mejorConductor->usuario)
                        <h5>{{ $mejorConductor->usuario->nombre_completo }}</h5>
                        <p class="mb-1">Licencia: {{ $mejorConductor->licencia_conducir }}</p>
                        <p class="mb-0">Calificación: <strong>{{ $mejorConductor->calificacion_promedio }}/5 ⭐</strong></p>
                    @else
                        <p class="text-muted">Sin datos</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">🏆 Pasajero con Más Viajes</div>
                <div class="card-body">
                    @if($pasajeroTop)
                        <h5>{{ $pasajeroTop->nombre_completo }}</h5>
                        <p class="mb-0">Viajes realizados: <strong>{{ $pasajeroTop->total }}</strong></p>
                    @else
                        <p class="text-muted">Sin datos</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de viajes recientes --}}
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <strong>📋 Viajes Recientes</strong>
            <a href="{{ url('viaje') }}" class="btn btn-sm btn-light">Ver todos</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Pasajero</th>
                            <th>Conductor</th>
                            <th>Origen → Destino</th>
                            <th>Estado</th>
                            <th>Tarifa</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($viajesRecientes as $v)
                        <tr>
                            <td>{{ $v->id_viaje }}</td>
                            <td>{{ $v->pasajero->usuario->nombre_completo ?? 'N/A' }}</td>
                            <td>{{ $v->conductor->usuario->nombre_completo ?? '—' }}</td>
                            <td><small>{{ $v->origen_descripcion }} → {{ $v->destino_descripcion }}</small></td>
                            <td>
                                @php
                                    $colors = [
                                        'solicitado' => 'warning', 'en_curso' => 'info',
                                        'completado' => 'success', 'cancelado' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $colors[$v->estado] ?? 'secondary' }}">{{ $v->estado }}</span>
                            </td>
                            <td>${{ number_format($v->tarifa_final ?? $v->tarifa_estimada, 2) }}</td>
                            <td>
                                <a href="{{ url('viaje/'.$v->id_viaje) }}" class="btn btn-sm btn-outline-primary">Ver</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center text-muted">No hay viajes registrados</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Gráfico de viajes
    new Chart(document.getElementById('chartViajes'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($viajesPorEstado)) !!},
            datasets: [{
                data: {!! json_encode(array_values($viajesPorEstado)) !!},
                backgroundColor: ['#ffc107', '#0dcaf0', '#198754', '#dc3545']
            }]
        }
    });

    // Gráfico de métodos de pago
    new Chart(document.getElementById('chartPagos'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($metodosPago)) !!},
            datasets: [{
                label: 'Pagos',
                data: {!! json_encode(array_values($metodosPago)) !!},
                backgroundColor: '#0d6efd'
            }]
        },
        options: { plugins: { legend: { display: false } } }
    });
</script>
@endsection
