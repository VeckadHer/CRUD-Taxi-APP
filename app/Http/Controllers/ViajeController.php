<?php

namespace App\Http\Controllers;

use App\Models\Viaje;
use App\Models\Pasajero;
use App\Models\Conductor;
use App\Models\Tarifa;
use Illuminate\Http\Request;

class ViajeController extends Controller
{
    public function index()
    {
        $viajes = Viaje::with(['pasajero', 'conductor', 'tarifa'])->paginate(5);
        return view('viaje.index', compact('viajes'));
    }

    public function create()
    {
        $pasajeros = Pasajero::all();
        $conductores = Conductor::all();
        $tarifas = Tarifa::all();
        return view('viaje.create', compact('pasajeros', 'conductores', 'tarifas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pasajero' => 'required|exists:pasajero,id_pasajero',
            'id_conductor' => 'required|exists:conductor,id_conductor',
            'id_tarifa' => 'required|exists:tarifa,id_tarifa',
            'origen_descripcion' => 'required|string',
            'destino_descripcion' => 'required|string',
            'distancia_km' => 'required|numeric',
            'duracion_minutos' => 'required|integer',
        ]);

        Viaje::create($request->all());
        return redirect('viaje')->with('mensaje', 'Viaje creado exitosamente');
    }

    public function edit($id)
    {
        $viaje = Viaje::findOrFail($id);
        $pasajeros = Pasajero::all();
        $conductores = Conductor::all();
        $tarifas = Tarifa::all();
        return view('viaje.edit', compact('viaje', 'pasajeros', 'conductores', 'tarifas'));
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
}
