<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Controller heredado del CRUD original de empleados.
 * Se mantiene por compatibilidad pero el sistema ahora gira
 * alrededor de Conductores, Pasajeros y Empresas.
 */
class EmpleadoController extends Controller
{
    public function index()    { return redirect('/dashboard'); }
    public function create()   { return redirect('/dashboard'); }
    public function store(Request $r)  { return redirect('/dashboard'); }
    public function show($id)  { return redirect('/dashboard'); }
    public function edit($id)  { return redirect('/dashboard'); }
    public function update(Request $r, $id) { return redirect('/dashboard'); }
    public function destroy($id) { return redirect('/dashboard'); }
}
