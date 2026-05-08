@extends('layouts.app')
@section('content')
<div class="container-fluid mt-4">
    @if(Session::has('mensaje'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ Session::get('mensaje') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>🚖 Conductores</h2>
        <a href="{{ url('/conductor/create') }}" class="btn btn-success">+ Nuevo Conductor</a>
    </div>

    {{-- Filtros --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">Empresa</label>
                    <select name="empresa" class="form-control">
                        <option value="">Todas</option>
                        @foreach($empresas as $e)
                        <option value="{{ $e->id_empresa }}" {{ request('empresa') == $e->id_empresa ? 'selected' : '' }}>
                            {{ $e->nombre }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-control">
                        <option value="">Todos</option>
                        <option value="disponible" {{ request('estado') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        <option value="en_viaje" {{ request('estado') == 'en_viaje' ? 'selected' : '' }}>En Viaje</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">🔍 Filtrar</button>
                    <a href="{{ url('/conductor') }}" class="btn btn-link">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Empresa</th>
                            <th>Licencia</th>
                            <th>Vehículo</th>
                            <th>Calificación</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($conductores as $c)
                        <tr>
                            <td>{{ $c->id_conductor }}</td>
                            <td>{{ $c->usuario->nombre_completo ?? '—' }}</td>
                            <td><span class="badge bg-info">{{ $c->empresa->nombre ?? '—' }}</span></td>
                            <td><code>{{ $c->licencia_conducir }}</code></td>
                            <td>
                                @if($c->tipo_vehiculo_operar == 'particular')
                                    <span class="badge bg-secondary">Particular</span>
                                @else
                                    <span class="badge bg-primary">Empresa</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $cal = $c->calificacion_promedio ?? 0;
                                    $estrellas = round($cal);
                                @endphp
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $estrellas)★@else☆@endif
                                    @endfor
                                </span>
                                <small>{{ number_format($cal, 1) }}</small>
                            </td>
                            <td>
                                @php $color = ['disponible'=>'success','en_viaje'=>'warning','inactivo'=>'secondary']; @endphp
                                <span class="badge bg-{{ $color[$c->estado] ?? 'secondary' }}">{{ $c->estado }}</span>
                            </td>
                            <td>
                                <a href="{{ url('/conductor/'.$c->id_conductor.'/edit') }}" class="btn btn-sm btn-warning">✏️</a>
                                <form action="{{ url('/conductor/'.$c->id_conductor) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar conductor?')">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center py-3 text-muted">Sin conductores</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">{{ $conductores->withQueryString()->links() }}</div>
</div>
@endsection
