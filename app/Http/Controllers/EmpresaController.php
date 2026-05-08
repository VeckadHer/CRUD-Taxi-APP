<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::withCount('conductores')->paginate(10);
        return view('empresa.index', compact('empresas'));
    }

    public function create() { return view('empresa.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'razon_social' => 'nullable|string|max:150',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:200',
        ]);
        Empresa::create(array_merge($request->all(), ['activa' => true]));
        return redirect('/empresa')->with('mensaje', '✓ Empresa creada');
    }

    public function edit($id)
    {
        $empresa = Empresa::findOrFail($id);
        return view('empresa.edit', compact('empresa'));
    }

    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->update($request->all());
        return redirect('/empresa')->with('mensaje', '✓ Empresa actualizada');
    }

    public function destroy($id)
    {
        Empresa::destroy($id);
        return redirect('/empresa')->with('mensaje', '✓ Empresa eliminada');
    }
}
