@extends('layouts.app')

@section('content')
{{-- Leaflet (mapa gratis sin API key) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="container mt-4">
    <h2>🚕 Solicitar Viaje en Iguala</h2>
    <p class="text-muted">Selecciona origen y destino. La tarifa se calcula según la distancia real.</p>

    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif

    <form action="{{ url('viaje') }}" method="POST" id="formViaje">
        @csrf
        <div class="row">
            {{-- Columna izquierda: Formulario --}}
            <div class="col-md-5">
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label"><strong>📍 Origen</strong></label>
                            <select name="origen_select" id="origen_select" class="form-control" required>
                                <option value="">Selecciona el origen...</option>
                                @foreach($lugares as $i => $l)
                                <option value="{{ $i }}" data-lat="{{ $l['lat'] }}" data-lng="{{ $l['lng'] }}" data-nombre="{{ $l['nombre'] }}">
                                    {{ $l['nombre'] }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>🎯 Destino</strong></label>
                            <select name="destino_select" id="destino_select" class="form-control" required>
                                <option value="">Selecciona el destino...</option>
                                @foreach($lugares as $i => $l)
                                <option value="{{ $i }}" data-lat="{{ $l['lat'] }}" data-lng="{{ $l['lng'] }}" data-nombre="{{ $l['nombre'] }}">
                                    {{ $l['nombre'] }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>🚗 Tipo de Servicio</strong></label>
                            <select name="id_tarifa" id="id_tarifa" class="form-control" required>
                                <option value="">Seleccionar...</option>
                                @foreach($tarifas as $t)
                                <option value="{{ $t->id_tarifa }}">
                                    {{ ucfirst($t->tipo_servicio) }} (Base: ${{ $t->tarifa_base }} | ${{ $t->costo_por_km }}/km)
                                </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Inputs ocultos para enviar coords --}}
                        <input type="hidden" name="origen_descripcion" id="origen_descripcion">
                        <input type="hidden" name="origen_lat" id="origen_lat">
                        <input type="hidden" name="origen_lng" id="origen_lng">
                        <input type="hidden" name="destino_descripcion" id="destino_descripcion">
                        <input type="hidden" name="destino_lat" id="destino_lat">
                        <input type="hidden" name="destino_lng" id="destino_lng">

                        <button type="button" class="btn btn-info w-100 mb-2" onclick="calcularTarifa()">
                            💰 Calcular Tarifa
                        </button>

                        <button type="submit" class="btn btn-primary w-100" id="btnSolicitar" disabled>
                            🚖 Solicitar Viaje
                        </button>

                        <a href="{{ url('dashboard') }}" class="btn btn-link w-100 mt-2">Cancelar</a>
                    </div>
                </div>

                {{-- Resultado del cálculo --}}
                <div class="card shadow-sm" id="resultadoCard" style="display:none;">
                    <div class="card-header bg-success text-white">
                        <strong>📊 Detalles del Viaje</strong>
                    </div>
                    <div class="card-body" id="resultadoBody"></div>
                </div>
            </div>

            {{-- Columna derecha: Mapa --}}
            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">🗺️ Mapa de Iguala</div>
                    <div class="card-body p-0">
                        <div id="map" style="height: 500px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// ============= MAPA DE IGUALA =============
const IGUALA_CENTER = [18.3447, -99.5388];
const map = L.map('map').setView(IGUALA_CENTER, 14);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap',
    maxZoom: 19
}).addTo(map);

let markerOrigen = null, markerDestino = null, ruta = null;

function actualizarMarker(tipo, lat, lng, nombre) {
    const color = tipo === 'origen' ? 'green' : 'red';
    const icon = L.divIcon({
        className: 'custom-marker',
        html: `<div style="background:${color};color:white;padding:5px 10px;border-radius:50%;font-weight:bold;border:2px solid white;box-shadow:0 2px 5px rgba(0,0,0,.3)">${tipo === 'origen' ? 'A' : 'B'}</div>`,
        iconSize: [30, 30]
    });

    if (tipo === 'origen') {
        if (markerOrigen) map.removeLayer(markerOrigen);
        markerOrigen = L.marker([lat, lng], {icon}).addTo(map).bindPopup(`<b>Origen:</b><br>${nombre}`);
    } else {
        if (markerDestino) map.removeLayer(markerDestino);
        markerDestino = L.marker([lat, lng], {icon}).addTo(map).bindPopup(`<b>Destino:</b><br>${nombre}`);
    }

    // Dibujar línea entre origen y destino
    if (markerOrigen && markerDestino) {
        if (ruta) map.removeLayer(ruta);
        const o = markerOrigen.getLatLng();
        const d = markerDestino.getLatLng();
        ruta = L.polyline([o, d], {color: '#0d6efd', weight: 4, opacity: 0.7, dashArray: '10, 10'}).addTo(map);
        map.fitBounds(ruta.getBounds(), {padding: [50, 50]});
    }
}

document.getElementById('origen_select').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (!opt.value) return;
    document.getElementById('origen_lat').value = opt.dataset.lat;
    document.getElementById('origen_lng').value = opt.dataset.lng;
    document.getElementById('origen_descripcion').value = opt.dataset.nombre;
    actualizarMarker('origen', opt.dataset.lat, opt.dataset.lng, opt.dataset.nombre);
});

document.getElementById('destino_select').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (!opt.value) return;
    document.getElementById('destino_lat').value = opt.dataset.lat;
    document.getElementById('destino_lng').value = opt.dataset.lng;
    document.getElementById('destino_descripcion').value = opt.dataset.nombre;
    actualizarMarker('destino', opt.dataset.lat, opt.dataset.lng, opt.dataset.nombre);
});

// ============= CÁLCULO DE TARIFA =============
function calcularTarifa() {
    const data = {
        origen_lat: document.getElementById('origen_lat').value,
        origen_lng: document.getElementById('origen_lng').value,
        destino_lat: document.getElementById('destino_lat').value,
        destino_lng: document.getElementById('destino_lng').value,
        id_tarifa: document.getElementById('id_tarifa').value,
        _token: '{{ csrf_token() }}'
    };

    if (!data.origen_lat || !data.destino_lat || !data.id_tarifa) {
        alert('Selecciona origen, destino y tipo de servicio');
        return;
    }

    fetch('{{ url("viaje/calcular-tarifa") }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': data._token},
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        const card = document.getElementById('resultadoCard');
        const body = document.getElementById('resultadoBody');
        body.innerHTML = `
            <table class="table table-sm mb-0">
                <tr><td>Distancia:</td><td><strong>${res.distancia_km} km</strong></td></tr>
                <tr><td>Duración estimada:</td><td><strong>${res.duracion_min} min</strong></td></tr>
                <tr><td>Tarifa base:</td><td>$${res.tarifa_base}</td></tr>
                <tr><td>Costo distancia:</td><td>$${res.costo_km_total}</td></tr>
                <tr><td>Costo tiempo:</td><td>$${res.costo_min_total}</td></tr>
                ${res.surge_aplicado ? '<tr class="text-warning"><td>⚡ Hora pico (+25%):</td><td>Aplicado</td></tr>' : ''}
                <tr class="table-success"><td><strong>TOTAL:</strong></td><td><h4 class="mb-0">$${res.tarifa_total}</h4></td></tr>
            </table>
        `;
        card.style.display = 'block';
        document.getElementById('btnSolicitar').disabled = false;
    })
    .catch(err => alert('Error al calcular: ' + err));
}
</script>
@endsection
