<?php

namespace App\Http\Controllers;

use App\Models\SolicitudConductor;
use App\Models\Usuario;
use App\Models\Conductor;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SolicitudConductorController extends Controller
{
    public function index(Request $request)
    {
        $query = SolicitudConductor::query();
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        $solicitudes = $query->orderByDesc('fecha_solicitud')->paginate(20);
        return view('admin.solicitudes_index', compact('solicitudes'));
    }

    /**
     * Vista para aceptar (formulario para completar datos del conductor)
     */
    public function show($id)
    {
        $solicitud = SolicitudConductor::findOrFail($id);
        $empresas = Empresa::where('activa', true)->get();
        return view('admin.solicitud_aceptar', compact('solicitud', 'empresas'));
    }

    /**
     * Aprueba la solicitud y crea el conductor
     */
    public function aprobar(Request $request, $id)
    {
        $solicitud = SolicitudConductor::findOrFail($id);

        // Email obligatorio @driver.com
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido_paterno' => 'required|string|max:50',
            'apellido_materno' => 'required|string|max:50',
            'fecha_nacimiento' => 'required|date|before_or_equal:' . date('Y-m-d', strtotime('-18 years')),
            'domicilio' => 'required|string|max:200',
            'codigo_postal' => 'required|string|max:10',
            'email' => [
                'required', 'email', 'unique:usuario,email',
                function ($attr, $value, $fail) {
                    if (!str_ends_with(strtolower($value), '@driver.com')) {
                        $fail('El email del conductor debe terminar en @driver.com');
                    }
                },
            ],
            'password' => 'required|min:6',
            'tipo_vehiculo_operar' => 'required|in:particular,empresa',
            'id_empresa' => 'required|exists:empresa,id_empresa',
            'licencia_conducir' => 'required|string|max:50|unique:conductor,licencia_conducir',
        ], [
            'fecha_nacimiento.before_or_equal' => 'El conductor debe ser mayor de 18 años',
        ]);

        DB::transaction(function () use ($request, $solicitud) {
            $u = Usuario::create([
                'nombre_usuario' => 'cond_' . strtolower(explode('@', $request->email)[0]),
                'hash_contrasena' => Hash::make($request->password),
                'nombre_completo' => trim($request->nombre . ' ' . $request->apellido_paterno . ' ' . $request->apellido_materno),
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,
                'email' => strtolower($request->email),
                'telefono' => $solicitud->telefono,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'domicilio' => $request->domicilio,
                'codigo_postal' => $request->codigo_postal,
                'fecha_creacion' => now(),
                'activo' => true,
                'rol' => 'conductor',
            ]);

            Conductor::create([
                'id_usuario' => $u->id_usuario,
                'id_empresa' => $request->id_empresa,
                'licencia_conducir' => $request->licencia_conducir,
                'calificacion_promedio' => 5.00,
                'disponible' => false,
                'estado' => 'inactivo',
                'tipo_vehiculo_operar' => $request->tipo_vehiculo_operar,
            ]);

            $solicitud->update(['estado' => 'registrado']);
        });

        return redirect('/solicitudes-conductor')->with('mensaje', '✓ Conductor creado. Ya puede iniciar sesión con su email @driver.com');
    }

    public function rechazar($id)
    {
        $solicitud = SolicitudConductor::findOrFail($id);
        $solicitud->update(['estado' => 'rechazado']);
        return back()->with('mensaje', '✓ Solicitud rechazada');
    }

    public function destroy($id)
    {
        SolicitudConductor::destroy($id);
        return back()->with('mensaje', '✓ Solicitud eliminada');
    }
}
