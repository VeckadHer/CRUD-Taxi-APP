<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ViajeController;
use App\Http\Controllers\ConductorController;
use App\Http\Controllers\TarifaController;

Route::get('/', function () {
    return view('empleado.index');
});

/*Route::get('/empleado', function () {
    return view('empleado.index');
});

Route::get('/empleado', function () {
    return view('empleado.index');
});

Route::get('/empleado/create',[EmpleadoController::class,'create']);*/

Route::resource('empleado', EmpleadoController::class)->middleware('auth');

Auth::routes(['register'=>false, 'reset'=>false]);

Route::get('/home', [EmpleadoController::class, 'index'])->name('home');

Route::group(['middleware'=>'auth'], function () {
    Route::get('/', [EmpleadoController::class, 'index'])->name('home');

// Rutas del sistema de Taxi
Route::resource('viaje', ViajeController::class);
Route::resource('conductor', ConductorController::class);
Route::resource('tarifa', TarifaController::class);

});