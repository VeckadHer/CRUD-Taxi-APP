<?php

namespace App\Http\Controllers;

use App\Models\Tarifa;
use Illuminate\Http\Request;

class TarifaController extends Controller
{
    public function index()
    {
        $tarifas = Tarifa::paginate(5);
        return view('tarifa.index', compact('tarifas'));
    }

    public function create()
    {
        return view('tarifa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_servicio' => 'required|string',
            'tarifa_base' => 'required|numeric',
            'costo_por_km' => 'required|numeric',
            'costo_por_minuto' => 'required|numeric',
            'tarifa_minima' => 'required|numeric',
        ]);

        Tarifa::create($request->all());
        return redirect('tarifa')->with('mensaje', 'Tarifa creada');
    }

    public function edit($id)
    {
        $tarifa = Tarifa::findOrFail($id);
        return view('tarifa.edit', compact('tarifa'));
    }

    public function update(Request $request, $id)
    {
        $tarifa = Tarifa::findOrFail($id);
        $tarifa->update($request->all());
        return redirect('tarifa')->with('mensaje', 'Tarifa actualizada');
    }

    public function destroy($id)
    {
        Tarifa::destroy($id);
        return redirect('tarifa')->with('mensaje', 'Tarifa eliminada');
    }
}
