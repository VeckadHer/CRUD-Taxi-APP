<?php

namespace App\Http\Controllers;

use App\Models\Viaje;
use App\Models\Pasajero;
use App\Models\Conductor;
use App\Models\Tarifa;
use App\Models\Empresa;
use App\Models\Pago;
use App\Models\Notificacion;
use App\Services\TarifaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViajeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Viaje::with(['pasajero.usuario', 'conductor.usuario', 'conductor.empresa', 'tarifa']);

        if ($user && $user->esPasajero()) {
            $pasajero = Pasajero::where('id_usuario', $user->id_usuario)->first();
            if ($pasajero) $query->where('id_pasajero', $pasajero->id_pasajero);
        } elseif ($user && $user->esConductor()) {
            $conductor = Conductor::where('id_usuario', $user->id_usuario)->first();
            if ($conductor) $query->where('id_conductor', $conductor->id_conductor);
        }

        $viajes = $query->orderByDesc('id_viaje')->paginate(15);
        return view('viaje.index', compact('viajes'));
    }

    public function create()
    {
        $empresas = Empresa::where('activa', true)->get();
        $tarifas = Tarifa::all();
        return view('viaje.create', compact('empresas', 'tarifas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_empresa' => 'required|exists:empresa,id_empresa',
            'id_conductor' => 'required|exists:conductor,id_conductor',
            'id_tarifa' => 'required|exists:tarifa,id_tarifa',
            'origen_descripcion' => 'required|string',
            'destino_descripcion' => 'required|string',
            'origen_lat' => 'required|numeric',
            'origen_lng' => 'required|numeric',
            'destino_lat' => 'required|numeric',
            'destino_lng' => 'required|numeric',
        ]);

        $user = Auth::user();
        $pasajero = Pasajero::where('id_usuario', $user->id_usuario)->first();
        if (!$pasajero) return back()->with('error', 'Necesitas un perfil de pasajero');

        $conductor = Conductor::find($request->id_conductor);
        if (!$conductor || !$conductor->disponible) {
            return back()->with('error', 'Ese conductor ya no está disponible. Intenta con otro.');
        }

        $calculo = TarifaService::calcularDesdeCoordenadas(
            $request->origen_lat, $request->origen_lng,
            $request->destino_lat, $request->destino_lng,
            $request->id_tarifa
        );

        $viaje = Viaje::create([
            'id_pasajero' => $pasajero->id_pasajero,
            'id_conductor' => $request->id_conductor,
            'id_tarifa' => $request->id_tarifa,
            'origen_descripcion' => $request->origen_descripcion,
            'origen_lat' => $request->origen_lat,
            'origen_lng' => $request->origen_lng,
            'destino_descripcion' => $request->destino_descripcion,
            'destino_lat' => $request->destino_lat,
            'destino_lng' => $request->destino_lng,
            'fecha_solicitud' => now(),
            'estado' => 'solicitado',
            'distancia_km' => $calculo['distancia_km'],
            'duracion_minutos' => $calculo['duracion_min'],
            'tarifa_estimada' => $calculo['tarifa_total'],
        ]);

        Notificacion::create([
            'id_usuario' => $conductor->id_usuario,
            'id_viaje' => $viaje->id_viaje,
            'tipo' => 'nuevo_viaje',
            'mensaje' => 'Nueva solicitud: ' . $request->origen_descripcion . ' → ' . $request->destino_descripcion,
        ]);

        return redirect('/dashboard')->with('mensaje', '✓ Viaje solicitado con ' . ($conductor->usuario->nombre_completo ?? 'el conductor'));
    }

    public function aceptar($id)
    {
        $user = Auth::user();
        $conductor = Conductor::where('id_usuario', $user->id_usuario)->first();
        if (!$conductor) return back()->with('error', 'Solo conductores');

        $viaje = Viaje::findOrFail($id);
        if ($viaje->estado !== 'solicitado') return back()->with('error', 'Viaje ya no disponible');
        if ($viaje->id_conductor && $viaje->id_conductor != $conductor->id_conductor) {
            return back()->with('error', 'Este viaje fue asignado a otro conductor');
        }

        $viaje->update([
            'id_conductor' => $conductor->id_conductor,
            'estado' => 'en_curso',
            'fecha_inicio' => now(),
        ]);
        $conductor->update(['disponible' => false, 'estado' => 'en_viaje']);

        if ($viaje->pasajero) {
            Notificacion::create([
                'id_usuario' => $viaje->pasajero->id_usuario,
                'id_viaje' => $viaje->id_viaje,
                'tipo' => 'viaje_aceptado',
                'mensaje' => '¡Tu viaje fue aceptado por ' . ($user->nombre_completo ?? 'el conductor') . '!',
            ]);
        }
        return back()->with('mensaje', '✓ Viaje aceptado');
    }

    public function finalizar($id)
    {
        $viaje = Viaje::findOrFail($id);
        if ($viaje->estado !== 'en_curso') return back()->with('error', 'Viaje no está en curso');

        $viaje->update([
            'estado' => 'completado',
            'fecha_fin' => now(),
            'tarifa_final' => $viaje->tarifa_estimada,
        ]);

        if ($viaje->conductor) {
            $viaje->conductor->update(['disponible' => true, 'estado' => 'disponible']);
        }

        Pago::create([
            'id_viaje' => $viaje->id_viaje,
            'monto' => $viaje->tarifa_final,
            'metodo_pago' => $viaje->pasajero->metodo_pago_default ?? 'efectivo',
            'estado_pago' => 'pagado',
            'fecha_pago' => now(),
            'referencia' => 'AUTO-' . $viaje->id_viaje,
        ]);

        if ($viaje->pasajero) {
            Notificacion::create([
                'id_usuario' => $viaje->pasajero->id_usuario,
                'id_viaje' => $viaje->id_viaje,
                'tipo' => 'viaje_completado',
                'mensaje' => 'Viaje completado. Total: $' . $viaje->tarifa_final,
            ]);
        }
        return back()->with('mensaje', '✓ Viaje completado. Pago registrado.');
    }

    public function cancelar(Request $request, $id)
    {
        $viaje = Viaje::findOrFail($id);
        $user = Auth::user();

        if (!in_array($viaje->estado, ['solicitado', 'en_curso'])) {
            return back()->with('error', 'No se puede cancelar');
        }

        $penalizacion = 0;
        if ($viaje->estado === 'en_curso') {
            $penalizacion = round($viaje->tarifa_estimada * 0.20, 2);
        }

        $viaje->update([
            'estado' => 'cancelado',
            'fecha_fin' => now(),
            'cancelado_por' => $user->rol,
            'razon_cancelacion' => $request->input('razon', 'Sin razón'),
            'tarifa_final' => $penalizacion,
        ]);

        if ($viaje->conductor) {
            $viaje->conductor->update(['disponible' => true, 'estado' => 'disponible']);
        }

        if ($penalizacion > 0) {
            Pago::create([
                'id_viaje' => $viaje->id_viaje,
                'monto' => $penalizacion,
                'metodo_pago' => $viaje->pasajero->metodo_pago_default ?? 'efectivo',
                'estado_pago' => 'pagado',
                'fecha_pago' => now(),
                'referencia' => 'CANCEL-' . $viaje->id_viaje,
            ]);
        }
        return redirect('/dashboard')->with('mensaje', '✓ Viaje cancelado' . ($penalizacion ? ". Penalización: \${$penalizacion}" : ''));
    }

    /**
     * CALCULAR TARIFA - acepta datos JSON o form-data
     * Hace su propio cálculo si TarifaService falla.
     */
    public function calcularTarifa(Request $request)
    {
        // Aceptar tanto JSON como form data
        $data = $request->isJson() ? $request->json()->all() : $request->all();

        $oLat = isset($data['origen_lat']) ? (float)$data['origen_lat'] : null;
        $oLng = isset($data['origen_lng']) ? (float)$data['origen_lng'] : null;
        $dLat = isset($data['destino_lat']) ? (float)$data['destino_lat'] : null;
        $dLng = isset($data['destino_lng']) ? (float)$data['destino_lng'] : null;
        $idTarifa = isset($data['id_tarifa']) ? (int)$data['id_tarifa'] : null;

        if (!$oLat || !$oLng || !$dLat || !$dLng || !$idTarifa) {
            return response()->json([
                'error' => true,
                'mensaje' => 'Faltan datos: ' . json_encode(compact('oLat','oLng','dLat','dLng','idTarifa'))
            ], 422);
        }

        $tarifa = Tarifa::find($idTarifa);
        if (!$tarifa) {
            return response()->json(['error' => true, 'mensaje' => 'Tarifa no encontrada'], 404);
        }

        // Cálculo Haversine inline (independiente del Service por seguridad)
        $earthRadius = 6371;
        $latFrom = deg2rad($oLat); $latTo = deg2rad($dLat);
        $latDelta = deg2rad($dLat - $oLat);
        $lngDelta = deg2rad($dLng - $oLng);
        $a = sin($latDelta / 2) ** 2 + cos($latFrom) * cos($latTo) * sin($lngDelta / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distanciaRecta = $earthRadius * $c;
        $distanciaReal = round($distanciaRecta * 1.3, 2); // factor calles
        $duracion = max(3, round($distanciaReal / 25 * 60)); // ~25km/h

        // Surge pricing
        $hora = (int)now()->format('H');
        $surge = 1.0;
        $surgeAplicado = false;
        if (($hora >= 11 && $hora < 14) || ($hora >= 18 && $hora < 21)) {
            $surge = 1.25;
            $surgeAplicado = true;
        }

        $costoKmTotal = round($distanciaReal * $tarifa->costo_por_km, 2);
        $costoMinTotal = round($duracion * $tarifa->costo_por_minuto, 2);
        $subtotal = $tarifa->tarifa_base + $costoKmTotal + $costoMinTotal;
        $total = round(max($subtotal, $tarifa->tarifa_minima) * $surge, 2);

        return response()->json([
            'error' => false,
            'distancia_km' => $distanciaReal,
            'duracion_min' => $duracion,
            'tarifa_base' => (float)$tarifa->tarifa_base,
            'costo_km_total' => $costoKmTotal,
            'costo_min_total' => $costoMinTotal,
            'tarifa_minima' => (float)$tarifa->tarifa_minima,
            'surge_aplicado' => $surgeAplicado,
            'tarifa_total' => $total,
        ]);
    }

    public function show($id)
    {
        $viaje = Viaje::with(['pasajero.usuario', 'conductor.usuario', 'conductor.empresa', 'tarifa', 'pago'])->findOrFail($id);
        return view('viaje.show', compact('viaje'));
    }

    public function edit($id)
    {
        $viaje = Viaje::findOrFail($id);
        $tarifas = Tarifa::all();
        return view('viaje.edit', compact('viaje', 'tarifas'));
    }

    public function update(Request $request, $id)
    {
        $viaje = Viaje::findOrFail($id);
        $viaje->update($request->all());
        return redirect('viaje')->with('mensaje', 'Viaje actualizado');
    }

    public function destroy($id)
    {
        Viaje::destroy($id);
        return redirect('viaje')->with('mensaje', 'Viaje eliminado');
    }

    public function conductoresEmpresa($idEmpresa)
    {
        $conductores = Conductor::with('usuario:id_usuario,nombre_completo')
            ->where('id_empresa', $idEmpresa)
            ->where('disponible', true)
            ->where('estado', 'disponible')
            ->get()
            ->map(function($c) {
                return [
                    'id_conductor' => $c->id_conductor,
                    'nombre_completo' => $c->usuario->nombre_completo ?? 'N/A',
                    'calificacion_promedio' => (float) $c->calificacion_promedio,
                    'licencia' => $c->licencia_conducir,
                ];
            });
        return response()->json($conductores);
    }
}
