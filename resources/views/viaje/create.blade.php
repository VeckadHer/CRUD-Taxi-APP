@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<div class="container-fluid mt-4">
    <h2>🚕 Solicitar Viaje</h2>
    <p class="text-muted">Selecciona la empresa, conductor y direcciones de origen y destino.</p>

    @if($errors->any())
    <div class="alert alert-danger">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
    </div>
    @endif
    @if(Session::has('error'))
    <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif

    <form action="{{ url('/viaje') }}" method="POST" id="formViaje">
        @csrf
        <div class="row">
            <div class="col-md-5">

                {{-- Paso 1: Empresa --}}
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white"><strong>1️⃣ Empresa de Servicio</strong></div>
                    <div class="card-body">
                        <select name="id_empresa" id="id_empresa" class="form-control" required>
                            <option value="">— Selecciona empresa —</option>
                            @foreach($empresas as $emp)
                            <option value="{{ $emp->id_empresa }}" 
                                    data-tarifa="{{ $tarifas->where('tipo_servicio', $emp->nombre)->first()->id_tarifa ?? '' }}">
                                {{ $emp->nombre }}
                            </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="id_tarifa" id="id_tarifa">
                    </div>
                </div>

                {{-- Paso 2: Conductor --}}
                <div class="card shadow-sm mb-3" id="cardConductor" style="display:none;">
                    <div class="card-header bg-success text-white d-flex justify-content-between">
                        <strong>2️⃣ Conductor Disponible</strong>
                        <span id="contadorConductores" class="badge bg-light text-dark"></span>
                    </div>
                    <div class="card-body">
                        <select name="id_conductor" id="id_conductor" class="form-control" required>
                            <option value="">— Primero elige una empresa —</option>
                        </select>
                        <small class="text-muted">Solo se muestran conductores disponibles ahora.</small>
                    </div>
                </div>

                {{-- Paso 3: Direcciones --}}
                <div class="card shadow-sm mb-3" id="cardDirecciones" style="display:none;">
                    <div class="card-header bg-warning text-dark"><strong>3️⃣ Direcciones</strong></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">📍 Dirección de Origen</label>
                            <div class="input-group">
                                <input type="text" id="origen_input" class="form-control" 
                                       placeholder="Ej: Av. Insurgentes 100, Iguala" required>
                                <button type="button" class="btn btn-outline-primary" onclick="buscarDireccion('origen')">🔍 Buscar</button>
                            </div>
                            <input type="hidden" name="origen_descripcion" id="origen_descripcion">
                            <input type="hidden" name="origen_lat" id="origen_lat">
                            <input type="hidden" name="origen_lng" id="origen_lng">
                            <small id="origen_status" class="text-muted"></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">🎯 Dirección de Destino</label>
                            <div class="input-group">
                                <input type="text" id="destino_input" class="form-control" 
                                       placeholder="Ej: Catedral de Iguala" required>
                                <button type="button" class="btn btn-outline-primary" onclick="buscarDireccion('destino')">🔍 Buscar</button>
                            </div>
                            <input type="hidden" name="destino_descripcion" id="destino_descripcion">
                            <input type="hidden" name="destino_lat" id="destino_lat">
                            <input type="hidden" name="destino_lng" id="destino_lng">
                            <small id="destino_status" class="text-muted"></small>
                        </div>

                        <div class="alert alert-info mb-0">
                            <small>💡 Sugerencias: <em>"Centro Iguala", "Hospital General Iguala", "Tecnológico de Iguala", "Mercado Hidalgo"</em></small>
                        </div>
                    </div>
                </div>

                {{-- Paso 4: Calcular --}}
                <div class="card shadow-sm mb-3" id="cardCalculo" style="display:none;">
                    <div class="card-header bg-dark text-white"><strong>4️⃣ Calcular Tarifa y Solicitar</strong></div>
                    <div class="card-body">
                        <button type="button" class="btn btn-info w-100 mb-2" onclick="calcularTarifa()">
                            💰 Calcular Tarifa
                        </button>

                        <div id="resultadoTarifa" style="display:none;" class="alert alert-success">
                            <table class="table table-sm mb-0">
                                <tr><td>Distancia:</td><td><strong id="r_distancia"></strong></td></tr>
                                <tr><td>Duración:</td><td><strong id="r_duracion"></strong></td></tr>
                                <tr><td>Tarifa base:</td><td>$<span id="r_base"></span></td></tr>
                                <tr><td>Por km:</td><td>$<span id="r_km"></span></td></tr>
                                <tr><td>Por minuto:</td><td>$<span id="r_min"></span></td></tr>
                                <tr id="r_surge" style="display:none;"><td class="text-warning">⚡ Hora pico:</td><td>+25%</td></tr>
                                <tr class="table-success"><td><strong>TOTAL:</strong></td><td><h3 class="mb-0">$<span id="r_total"></span></h3></td></tr>
                            </table>
                        </div>

                        <button type="submit" class="btn btn-success w-100" id="btnSolicitar" disabled>
                            🚖 Confirmar y Solicitar Viaje
                        </button>
                        <a href="{{ url('/dashboard') }}" class="btn btn-link w-100 mt-2">Cancelar</a>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white">🗺️ Mapa de Iguala de la Independencia</div>
                    <div class="card-body p-0">
                        <div id="map" style="height: 600px; width: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
const IGUALA_CENTER = [18.3447, -99.5388];
const map = L.map('map').setView(IGUALA_CENTER, 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap', maxZoom: 19
}).addTo(map);

let markerOrigen = null, markerDestino = null, ruta = null;

function dentroDeIguala(lat, lng) {
    const distLat = Math.abs(lat - 18.3447);
    const distLng = Math.abs(lng - (-99.5388));
    return distLat < 0.1 && distLng < 0.1;
}

function actualizarMarker(tipo, lat, lng, nombre) {
    const color = tipo === 'origen' ? '#198754' : '#dc3545';
    const letra = tipo === 'origen' ? 'A' : 'B';
    const icon = L.divIcon({
        className: 'custom-marker',
        html: `<div style="background:${color};color:white;padding:6px 11px;border-radius:50%;font-weight:bold;border:2px solid white;box-shadow:0 2px 6px rgba(0,0,0,.4);font-size:14px;">${letra}</div>`,
        iconSize: [30, 30]
    });

    if (tipo === 'origen') {
        if (markerOrigen) map.removeLayer(markerOrigen);
        markerOrigen = L.marker([lat, lng], {icon}).addTo(map).bindPopup(`<b>Origen:</b><br>${nombre}`).openPopup();
    } else {
        if (markerDestino) map.removeLayer(markerDestino);
        markerDestino = L.marker([lat, lng], {icon}).addTo(map).bindPopup(`<b>Destino:</b><br>${nombre}`).openPopup();
    }

    if (markerOrigen && markerDestino) {
        if (ruta) map.removeLayer(ruta);
        const o = markerOrigen.getLatLng(), d = markerDestino.getLatLng();
        ruta = L.polyline([o, d], {color: '#0d6efd', weight: 4, opacity: 0.7, dashArray: '10, 10'}).addTo(map);
        map.fitBounds(ruta.getBounds(), {padding: [50, 50]});
    } else {
        map.setView([lat, lng], 15);
    }
}

function buscarDireccion(tipo) {
    const input = document.getElementById(tipo + '_input').value.trim();
    if (!input) { alert('Escribe una dirección'); return; }
    const status = document.getElementById(tipo + '_status');
    status.textContent = '🔄 Buscando...';
    status.className = 'text-info';

    const query = encodeURIComponent(input + ', Iguala, Guerrero, México');
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&limit=5`)
        .then(r => r.json())
        .then(data => {
            let resultado = data.find(d => dentroDeIguala(parseFloat(d.lat), parseFloat(d.lon)));
            if (!resultado && data.length > 0) resultado = data[0];

            if (!resultado) {
                status.textContent = '❌ No se encontró. Intenta ser más específico.';
                status.className = 'text-danger';
                return;
            }

            const lat = parseFloat(resultado.lat);
            const lng = parseFloat(resultado.lon);
            const nombre = resultado.display_name.split(',').slice(0, 3).join(',');

            if (!dentroDeIguala(lat, lng)) {
                status.textContent = '⚠️ La ubicación parece estar fuera de Iguala';
                status.className = 'text-warning';
            } else {
                status.textContent = '✓ ' + nombre;
                status.className = 'text-success';
            }

            document.getElementById(tipo + '_lat').value = lat;
            document.getElementById(tipo + '_lng').value = lng;
            document.getElementById(tipo + '_descripcion').value = input;
            actualizarMarker(tipo, lat, lng, input);
            chequearProgreso();
        })
        .catch(err => {
            status.textContent = '❌ Error de conexión';
            status.className = 'text-danger';
        });
}

document.getElementById('id_empresa').addEventListener('change', function() {
    const empresaId = this.value;
    const tarifaId = this.options[this.selectedIndex].dataset.tarifa;
    document.getElementById('id_tarifa').value = tarifaId;

    if (!empresaId) {
        document.getElementById('cardConductor').style.display = 'none';
        return;
    }

    document.getElementById('cardConductor').style.display = 'block';
    const sel = document.getElementById('id_conductor');
    sel.innerHTML = '<option>Cargando...</option>';

    fetch(`{{ url('/api/conductores-empresa') }}/${empresaId}`)
        .then(r => r.json())
        .then(data => {
            sel.innerHTML = '<option value="">— Selecciona conductor —</option>';
            if (data.length === 0) {
                sel.innerHTML = '<option value="">No hay conductores disponibles</option>';
                document.getElementById('contadorConductores').textContent = '0 disponibles';
                return;
            }
            data.forEach(c => {
                const cal = '★'.repeat(Math.round(c.calificacion_promedio));
                sel.innerHTML += `<option value="${c.id_conductor}">${c.nombre_completo} - ${cal} (${c.calificacion_promedio})</option>`;
            });
            document.getElementById('contadorConductores').textContent = data.length + ' disponibles';
        });
});

document.getElementById('id_conductor').addEventListener('change', chequearProgreso);

function chequearProgreso() {
    const empresa = document.getElementById('id_empresa').value;
    const conductor = document.getElementById('id_conductor').value;
    const oLat = document.getElementById('origen_lat').value;
    const dLat = document.getElementById('destino_lat').value;

    if (empresa && conductor) {
        document.getElementById('cardDirecciones').style.display = 'block';
    }
    if (empresa && conductor && oLat && dLat) {
        document.getElementById('cardCalculo').style.display = 'block';
    }
}

function calcularTarifa() {
    // FIX: Validar campos antes de enviar
    const oLat = document.getElementById('origen_lat').value;
    const oLng = document.getElementById('origen_lng').value;
    const dLat = document.getElementById('destino_lat').value;
    const dLng = document.getElementById('destino_lng').value;
    const idTarifa = document.getElementById('id_tarifa').value;

    if (!oLat || !oLng || !dLat || !dLng || !idTarifa) {
        alert('Datos faltantes:\n' +
              '- Origen lat: ' + (oLat || 'FALTA') + '\n' +
              '- Origen lng: ' + (oLng || 'FALTA') + '\n' +
              '- Destino lat: ' + (dLat || 'FALTA') + '\n' +
              '- Destino lng: ' + (dLng || 'FALTA') + '\n' +
              '- Tarifa: ' + (idTarifa || 'FALTA') + '\n\n' +
              'Verifica que hayas buscado las direcciones (botón 🔍 Buscar).');
        return;
    }

    // Construir FormData (más compatible que JSON)
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('origen_lat', oLat);
    formData.append('origen_lng', oLng);
    formData.append('destino_lat', dLat);
    formData.append('destino_lng', dLng);
    formData.append('id_tarifa', idTarifa);

    fetch('{{ url("/viaje/calcular-tarifa") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(r => {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    })
    .then(res => {
        if (res.error) {
            alert('Error: ' + res.mensaje);
            return;
        }
        document.getElementById('r_distancia').textContent = res.distancia_km + ' km';
        document.getElementById('r_duracion').textContent = res.duracion_min + ' min';
        document.getElementById('r_base').textContent = res.tarifa_base;
        document.getElementById('r_km').textContent = res.costo_km_total;
        document.getElementById('r_min').textContent = res.costo_min_total;
        document.getElementById('r_total').textContent = res.tarifa_total;
        document.getElementById('r_surge').style.display = res.surge_aplicado ? 'table-row' : 'none';
        document.getElementById('resultadoTarifa').style.display = 'block';
        document.getElementById('btnSolicitar').disabled = false;
    })
    .catch(err => {
        alert('Error al calcular tarifa: ' + err.message + '\n\nVerifica la consola del navegador (F12) para más detalles.');
        console.error(err);
    });
}
</script>
@endsection
