@extends('layouts.app')
@section('content')
<div class="container-fluid mt-4">
    @if(Session::has('mensaje'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ Session::get('mensaje') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <h2 class="mb-4">📊 Panel Administrativo</h2>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow">
                <div class="card-body">
                    <h6>Pendientes</h6>
                    <h2 class="mb-0">{{ $stats['viajes_pendientes'] }}</h2>
                    <small>Esperando aceptación</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info shadow">
                <div class="card-body">
                    <h6>En Curso</h6>
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
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger shadow">
                <div class="card-body">
                    <h6>Cancelados</h6>
                    <h2 class="mb-0">{{ $stats['viajes_cancelados'] }}</h2>
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
                    <h6 class="text-muted">💵 Total Histórico</h6>
                    <h3 class="text-primary">${{ number_format($stats['ingresos_total'], 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">🚖 Conductores</h6>
                    <h3>{{ $stats['conductores_disponibles'] }} <small class="text-muted">/ {{ $stats['total_conductores'] }}</small></h3>
                    <small>disponibles ahora</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted">🏢 Empresas</h6>
                    <h3>{{ $stats['total_empresas'] }} activas</h3>
                </div>
            </div>
        </div>
    </div>

    @if($solicitudesPendientes->count() > 0)
    <div class="card border-warning shadow-sm mb-4">
        <div class="card-header bg-warning text-dark d-flex justify-content-between">
            <strong>📞 Solicitudes para ser Conductor ({{ $stats['solicitudes_pendientes'] }})</strong>
            <a href="{{ url('/solicitudes-conductor') }}" class="btn btn-sm btn-dark">Ver todas</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead class="table-light">
                    <tr><th>Nombre</th><th>Teléfono</th><th>Email</th><th>Mensaje</th><th>Fecha</th><th>Acción</th></tr>
                </thead>
                <tbody>
                    @foreach($solicitudesPendientes as $s)
                    <tr>
                        <td>{{ $s->nombre_completo }}</td>
                        <td><a href="tel:{{ $s->telefono }}">{{ $s->telefono }}</a></td>
                        <td>{{ $s->email ?? '—' }}</td>
                        <td><small>{{ Str::limit($s->mensaje, 50) }}</small></td>
                        <td><small>{{ \Carbon\Carbon::parse($s->fecha_solicitud)->format('d/m H:i') }}</small></td>
                        <td><a href="{{ url('/solicitudes-conductor/'.$s->id_solicitud) }}" class="btn btn-sm btn-success">✓ Aprobar</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Gráficos --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white"><strong>Distribución de Viajes</strong></div>
                <div class="card-body" style="height: 300px;"><canvas id="chartViajes"></canvas></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white"><strong>Conductores por Empresa</strong></div>
                <div class="card-body" style="height: 300px;"><canvas id="chartEmpresas"></canvas></div>
            </div>
        </div>
    </div>

    {{-- Top performers --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">⭐ Mejor Conductor</div>
                <div class="card-body">
                    @if($mejorConductor && $mejorConductor->usuario)
                        <h5>{{ $mejorConductor->usuario->nombre_completo }}</h5>
                        <p class="mb-1">🏢 {{ $mejorConductor->empresa->nombre ?? '—' }}</p>
                        <p class="mb-1">📋 Licencia: {{ $mejorConductor->licencia_conducir }}</p>
                        <p class="mb-0">⭐ <strong>{{ $mejorConductor->calificacion_promedio }}/5</strong></p>
                    @else
                        <p class="text-muted">Sin datos</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
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

    {{-- Conductores activos --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white d-flex justify-content-between">
            <strong>🟢 Conductores Activos en Tiempo Real</strong>
            <span class="badge bg-light text-dark">{{ $conductoresActivos->count() }} disponibles</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th><th>Nombre Completo</th><th>Empresa</th>
                            <th>Licencia</th><th>Calificación</th><th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conductoresActivos as $c)
                        <tr>
                            <td>{{ $c->id_conductor }}</td>
                            <td><strong>{{ $c->usuario->nombre_completo ?? '—' }}</strong></td>
                            <td><span class="badge bg-info">{{ $c->empresa->nombre ?? '—' }}</span></td>
                            <td><code>{{ $c->licencia_conducir }}</code></td>
                            <td>
                                @php $cal = $c->calificacion_promedio; $est = round($cal); @endphp
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $est)★@else☆@endif
                                    @endfor
                                </span>
                                <small class="ms-1">{{ number_format($cal, 1) }}</small>
                            </td>
                            <td><span class="badge bg-success">🟢 Disponible</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-3">No hay conductores activos en este momento</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Viajes recientes con fechas y empresas --}}
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between">
            <strong>📋 Viajes Recientes</strong>
            <a href="{{ url('viaje') }}" class="btn btn-sm btn-light">Ver todos</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th><th>Fecha</th><th>Pasajero</th><th>Conductor</th>
                            <th>Empresa</th><th>Ruta</th><th>Estado</th><th>Tarifa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($viajesRecientes as $v)
                        <tr>
                            <td>{{ $v->id_viaje }}</td>
                            <td>
                                <small>
                                    @if($v->fecha_solicitud)
                                        {{ \Carbon\Carbon::parse($v->fecha_solicitud)->format('d/m') }}<br>
                                        <span class="text-muted">{{ \Carbon\Carbon::parse($v->fecha_solicitud)->format('H:i') }}</span>
                                    @else — @endif
                                </small>
                            </td>
                            <td>{{ $v->pasajero->usuario->nombre_completo ?? 'N/A' }}</td>
                            <td>{{ $v->conductor->usuario->nombre_completo ?? '—' }}</td>
                            <td><small class="badge bg-info">{{ $v->conductor->empresa->nombre ?? '—' }}</small></td>
                            <td><small>{{ Str::limit($v->origen_descripcion, 20) }} → {{ Str::limit($v->destino_descripcion, 20) }}</small></td>
                            <td>
                                @php $colors = ['solicitado'=>'warning','en_curso'=>'info','completado'=>'success','cancelado'=>'danger']; @endphp
                                <span class="badge bg-{{ $colors[$v->estado] ?? 'secondary' }}">{{ $v->estado }}</span>
                            </td>
                            <td>${{ number_format($v->tarifa_final ?? $v->tarifa_estimada, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted">Sin viajes</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// FIX gráfica: el problema anterior era que el card-body no tenía altura definida
new Chart(document.getElementById('chartViajes').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($viajesPorEstado)) !!},
        datasets: [{
            data: {!! json_encode(array_values($viajesPorEstado)) !!},
            backgroundColor: ['#ffc107', '#0dcaf0', '#198754', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

new Chart(document.getElementById('chartEmpresas').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($conductoresPorEmpresa)) !!},
        datasets: [{
            label: 'Conductores',
            data: {!! json_encode(array_values($conductoresPorEmpresa)) !!},
            backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } }
    }
});
</script>
@endsection
