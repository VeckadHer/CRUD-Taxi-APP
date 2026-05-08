<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Viaje;
use App\Models\Conductor;
use App\Models\Tarifa;
use App\Services\TarifaService;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    // GET /api/viajes
    public function viajes()
    {
        return response()->json(
            Viaje::with(['pasajero.usuario:id_usuario,nombre_completo',
                         'conductor.usuario:id_usuario,nombre_completo',
                         'tarifa'])
                ->orderByDesc('id_viaje')
                ->limit(50)
                ->get()
        );
    }

    // GET /api/viajes/{id}
    public function viaje($id)
    {
        $v = Viaje::with(['pasajero.usuario','conductor.usuario','tarifa','pago'])->find($id);
        if (!$v) return response()->json(['error' => 'No encontrado'], 404);
        return response()->json($v);
    }

    // GET /api/conductores/disponibles
    public function conductoresDisponibles()
    {
        return response()->json(
            Conductor::with('usuario:id_usuario,nombre_completo,telefono')
                ->where('disponible', true)
                ->where('estado', 'activo')
                ->get()
        );
    }

    // GET /api/tarifas
    public function tarifas()
    {
        return response()->json(Tarifa::all());
    }

    // GET /api/lugares
    public function lugares()
    {
        return response()->json(TarifaService::lugaresIguala());
    }

    // POST /api/calcular-tarifa
    public function calcularTarifa(Request $request)
    {
        $request->validate([
            'origen_lat' => 'required|numeric',
            'origen_lng' => 'required|numeric',
            'destino_lat' => 'required|numeric',
            'destino_lng' => 'required|numeric',
            'id_tarifa' => 'required|exists:tarifa,id_tarifa',
        ]);

        return response()->json(TarifaService::calcularDesdeCoordenadas(
            $request->origen_lat, $request->origen_lng,
            $request->destino_lat, $request->destino_lng,
            $request->id_tarifa
        ));
    }

    // GET /api/stats
    public function stats()
    {
        return response()->json([
            'viajes' => [
                'pendientes' => Viaje::where('estado', 'solicitado')->count(),
                'en_curso' => Viaje::where('estado', 'en_curso')->count(),
                'completados' => Viaje::where('estado', 'completado')->count(),
                'cancelados' => Viaje::where('estado', 'cancelado')->count(),
            ],
            'conductores_disponibles' => Conductor::where('disponible', true)->count(),
        ]);
    }
}
