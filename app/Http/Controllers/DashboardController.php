<?php

namespace App\Http\Controllers;

use App\Models\Viaje;
use App\Models\Conductor;
use App\Models\Pasajero;
use App\Models\Pago;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) return redirect('/login');

        if ($user->esAdmin())     return $this->admin();
        if ($user->esConductor()) return $this->conductor();
        return $this->pasajero();
    }

    // ============= DASHBOARD ADMIN =============
    private function admin()
    {
        $hoy = date('Y-m-d');

        $stats = [
            'viajes_pendientes'  => Viaje::where('estado', 'solicitado')->count(),
            'viajes_en_curso'    => Viaje::where('estado', 'en_curso')->count(),
            'viajes_completados' => Viaje::where('estado', 'completado')->count(),
            'viajes_cancelados'  => Viaje::where('estado', 'cancelado')->count(),
            'total_conductores'  => Conductor::count(),
            'conductores_disponibles' => Conductor::where('disponible', true)->count(),
            'total_pasajeros'    => Pasajero::count(),
            'ingresos_hoy'       => Pago::where('estado_pago', 'pagado')
                                    ->whereDate('fecha_pago', $hoy)
                                    ->sum('monto'),
            'ingresos_total'     => Pago::where('estado_pago', 'pagado')->sum('monto'),
        ];

        // Mejor conductor por calificación
        $mejorConductor = Conductor::with('usuario')
            ->orderByDesc('calificacion_promedio')
            ->first();

        // Pasajero con más viajes
        $pasajeroTop = DB::table('pasajero')
            ->leftJoin('viaje', 'pasajero.id_pasajero', '=', 'viaje.id_pasajero')
            ->leftJoin('usuario', 'pasajero.id_usuario', '=', 'usuario.id_usuario')
            ->select('usuario.nombre_completo', DB::raw('COUNT(viaje.id_viaje) as total'))
            ->groupBy('pasajero.id_pasajero', 'usuario.nombre_completo')
            ->orderByDesc('total')
            ->first();

        // Viajes recientes (todos los estados)
        $viajesRecientes = Viaje::with(['pasajero.usuario', 'conductor.usuario', 'tarifa'])
            ->orderByDesc('id_viaje')
            ->limit(10)
            ->get();

        // Datos para gráfica: viajes por estado
        $viajesPorEstado = [
            'Pendientes' => $stats['viajes_pendientes'],
            'En Curso' => $stats['viajes_en_curso'],
            'Completados' => $stats['viajes_completados'],
            'Cancelados' => $stats['viajes_cancelados'],
        ];

        // Métodos de pago
        $metodosPago = Pago::select('metodo_pago', DB::raw('count(*) as total'))
            ->groupBy('metodo_pago')
            ->pluck('total', 'metodo_pago')
            ->toArray();

        return view('dashboard.admin', compact(
            'stats', 'mejorConductor', 'pasajeroTop',
            'viajesRecientes', 'viajesPorEstado', 'metodosPago'
        ));
    }

    // ============= DASHBOARD CONDUCTOR =============
    private function conductor()
    {
        $user = Auth::user();
        $conductor = Conductor::where('id_usuario', $user->id_usuario)->first();

        if (!$conductor) {
            return redirect('/')->with('error', 'No tienes perfil de conductor');
        }

        // Viaje activo del conductor
        $viajeActivo = Viaje::with(['pasajero.usuario', 'tarifa'])
            ->where('id_conductor', $conductor->id_conductor)
            ->whereIn('estado', ['en_curso'])
            ->first();

        // Solicitudes pendientes (sin conductor asignado o asignado a este)
        $solicitudes = Viaje::with(['pasajero.usuario', 'tarifa'])
            ->where('estado', 'solicitado')
            ->where(function($q) use ($conductor) {
                $q->whereNull('id_conductor')
                  ->orWhere('id_conductor', $conductor->id_conductor);
            })
            ->orderByDesc('id_viaje')
            ->limit(10)
            ->get();

        // Historial del día
        $hoy = date('Y-m-d');
        $historialHoy = Viaje::with(['pasajero.usuario'])
            ->where('id_conductor', $conductor->id_conductor)
            ->whereDate('fecha_fin', $hoy)
            ->orderByDesc('fecha_fin')
            ->get();

        $stats = [
            'viajes_completados_hoy' => $historialHoy->where('estado', 'completado')->count(),
            'ingresos_hoy' => $historialHoy->where('estado', 'completado')->sum('tarifa_final'),
            'viajes_totales' => Viaje::where('id_conductor', $conductor->id_conductor)
                                    ->where('estado', 'completado')->count(),
            'calificacion' => $conductor->calificacion_promedio,
            'disponible' => $conductor->disponible,
            'estado' => $conductor->estado,
        ];

        return view('dashboard.conductor', compact(
            'conductor', 'viajeActivo', 'solicitudes', 'historialHoy', 'stats'
        ));
    }

    // ============= DASHBOARD PASAJERO =============
    private function pasajero()
    {
        $user = Auth::user();
        $pasajero = Pasajero::where('id_usuario', $user->id_usuario)->first();

        if (!$pasajero) {
            return redirect('/')->with('error', 'No tienes perfil de pasajero');
        }

        // Viaje activo
        $viajeActivo = Viaje::with(['conductor.usuario', 'tarifa'])
            ->where('id_pasajero', $pasajero->id_pasajero)
            ->whereIn('estado', ['solicitado', 'en_curso'])
            ->first();

        // Histórico de viajes
        $historial = Viaje::with(['conductor.usuario', 'tarifa'])
            ->where('id_pasajero', $pasajero->id_pasajero)
            ->orderByDesc('id_viaje')
            ->limit(10)
            ->get();

        $stats = [
            'viajes_completados' => Viaje::where('id_pasajero', $pasajero->id_pasajero)
                                        ->where('estado', 'completado')->count(),
            'viajes_cancelados' => Viaje::where('id_pasajero', $pasajero->id_pasajero)
                                       ->where('estado', 'cancelado')->count(),
            'gastado_total' => Pago::whereHas('viaje', function($q) use ($pasajero) {
                                    $q->where('id_pasajero', $pasajero->id_pasajero);
                                })->where('estado_pago', 'pagado')->sum('monto'),
            'calificacion' => $pasajero->calificacion_promedio,
        ];

        return view('dashboard.pasajero', compact(
            'pasajero', 'viajeActivo', 'historial', 'stats'
        ));
    }
}
