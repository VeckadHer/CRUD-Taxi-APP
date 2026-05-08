<?php

namespace App\Http\Controllers;

use App\Models\Conductor;
use App\Models\Usuario;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ConductorController extends Controller
{
    public function index(Request $request)
    {
        $query = Conductor::with(['usuario', 'empresa']);

        // Filtros opcionales
        if ($request->filled('empresa')) {
            $query->where('id_empresa', $request->empresa);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        $conductores = $query->orderByDesc('id_conductor')->paginate(15);
        $empresas = Empresa::all();

        return view('conductor.index', compact('conductores', 'empresas'));
    }

    public function create()
    {
        $empresas = Empresa::where('activa', true)->get();
        return view('conductor.create', compact('empresas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido_paterno' => 'required|string|max:50',
            'apellido_materno' => 'required|string|max:50',
            'fecha_nacimiento' => 'required|date|before_or_equal:' . date('Y-m-d', strtotime('-18 years')),
            'domicilio' => 'required|string|max:200',
            'codigo_postal' => 'required|string|max:10',
            'email' => 'required|email|unique:usuario,email',
            'telefono' => 'required|string|max:20',
            'password' => 'required|min:6',
            'tipo_vehiculo_operar' => 'required|in:particular,empresa',
            'id_empresa' => 'required|exists:empresa,id_empresa',
            'licencia_conducir' => 'required|string|max:50|unique:conductor,licencia_conducir',
        ], [
            'fecha_nacimiento.before_or_equal' => 'El conductor debe ser mayor de 18 años',
            'email.unique' => 'Este correo ya está registrado',
            'licencia_conducir.unique' => 'Esta licencia ya existe',
        ]);

        DB::transaction(function () use ($request) {
            // Crear usuario
            $u = Usuario::create([
                'nombre_usuario' => 'cond_' . strtolower(explode('@', $request->email)[0]),
                'hash_contrasena' => Hash::make($request->password),
                'nombre_completo' => trim($request->nombre . ' ' . $request->apellido_paterno . ' ' . $request->apellido_materno),
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'domicilio' => $request->domicilio,
                'codigo_postal' => $request->codigo_postal,
                'fecha_creacion' => now(),
                'activo' => true,
                'rol' => 'conductor',
            ]);

            // Crear conductor (sin estado/disponibilidad, lo controla el conductor)
            Conductor::create([
                'id_usuario' => $u->id_usuario,
                'id_empresa' => $request->id_empresa,
                'licencia_conducir' => $request->licencia_conducir,
                'calificacion_promedio' => 5.00,
                'disponible' => false,
                'estado' => 'inactivo',
                'tipo_vehiculo_operar' => $request->tipo_vehiculo_operar,
            ]);
        });

        return redirect('/conductor')->with('mensaje', '✓ Conductor registrado exitosamente');
    }

    public function show($id)
    {
        $conductor = Conductor::with(['usuario', 'empresa', 'viajes'])->findOrFail($id);
        return view('conductor.show', compact('conductor'));
    }

    public function edit($id)
    {
        $conductor = Conductor::with('usuario')->findOrFail($id);
        $empresas = Empresa::all();
        return view('conductor.edit', compact('conductor', 'empresas'));
    }

    public function update(Request $request, $id)
    {
        $conductor = Conductor::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string',
            'apellido_paterno' => 'required|string',
            'apellido_materno' => 'required|string',
            'telefono' => 'required',
            'domicilio' => 'required',
            'codigo_postal' => 'required',
            'id_empresa' => 'required|exists:empresa,id_empresa',
            'licencia_conducir' => 'required',
            'tipo_vehiculo_operar' => 'required|in:particular,empresa',
        ]);

        DB::transaction(function () use ($request, $conductor) {
            $conductor->usuario->update([
                'nombre_completo' => trim($request->nombre . ' ' . $request->apellido_paterno . ' ' . $request->apellido_materno),
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,
                'telefono' => $request->telefono,
                'domicilio' => $request->domicilio,
                'codigo_postal' => $request->codigo_postal,
            ]);

            $conductor->update([
                'id_empresa' => $request->id_empresa,
                'licencia_conducir' => $request->licencia_conducir,
                'tipo_vehiculo_operar' => $request->tipo_vehiculo_operar,
            ]);
        });

        return redirect('/conductor')->with('mensaje', '✓ Conductor actualizado');
    }

    public function destroy($id)
    {
        $conductor = Conductor::findOrFail($id);
        DB::transaction(function () use ($conductor) {
            $usuario = $conductor->usuario;
            $conductor->delete();
            if ($usuario) $usuario->delete();
        });
        return redirect('/conductor')->with('mensaje', '✓ Conductor eliminado');
    }

    /**
     * Toggle disponibilidad (lo usa el conductor desde su dashboard)
     */
    public function toggleDisponibilidad(Request $request)
    {
        $user = auth()->user();
        $conductor = Conductor::where('id_usuario', $user->id_usuario)->first();
        if (!$conductor) return back()->with('error', 'No eres conductor');

        $request->validate(['estado' => 'required|in:disponible,inactivo,en_viaje']);

        $conductor->update([
            'estado' => $request->estado,
            'disponible' => $request->estado === 'disponible',
        ]);

        return back()->with('mensaje', '✓ Estado actualizado a: ' . $request->estado);
    }
}
