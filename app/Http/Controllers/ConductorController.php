<?php

namespace App\Http\Controllers;

use App\Models\Conductor;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ConductorController extends Controller
{
    public function index()
{
    $conductores = Conductor::paginate(5);
    return view('conductor.index', compact('conductores'));
}

    public function create()
    {
        $usuarios = Usuario::all();
        return view('conductor.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|exists:usuario,id_usuario',
            'licencia_conducir' => 'required|string',
            'estado' => 'required|string',
        ]);

        Conductor::create($request->all());
        return redirect('conductor')->with('mensaje', 'Conductor creado');
    }

    public function edit($id)
    {
        $conductor = Conductor::findOrFail($id);
        $usuarios = Usuario::all();
        return view('conductor.edit', compact('conductor', 'usuarios'));
    }

    public function update(Request $request, $id)
    {
        $conductor = Conductor::findOrFail($id);
        $conductor->update($request->all());
        return redirect('conductor')->with('mensaje', 'Conductor actualizado');
    }

    public function destroy($id)
    {
        Conductor::destroy($id);
        return redirect('conductor')->with('mensaje', 'Conductor eliminado');
    }
}
