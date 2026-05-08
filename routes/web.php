<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ViajeController;
use App\Http\Controllers\ConductorController;
use App\Http\Controllers\TarifaController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EmpleadoController;

Route::get('/', function () {
    return redirect(auth()->check() ? '/dashboard' : '/login');
});

// AUTH
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [LoginController::class, 'showRegister'])->name('register');
Route::post('/register', [LoginController::class, 'register']);

// Solicitud para ser conductor (público)
Route::get('/solicitud-conductor', [LoginController::class, 'showSolicitudConductor']);
Route::post('/solicitud-conductor', [LoginController::class, 'storeSolicitudConductor']);

// API pública (no necesita auth)
Route::get('/api/conductores-empresa/{idEmpresa}', [ViajeController::class, 'conductoresEmpresa']);

// PROTEGIDAS
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', [DashboardController::class, 'index']);

    // Viajes - Acciones
    Route::post('/viaje/calcular-tarifa', [ViajeController::class, 'calcularTarifa']);
    Route::post('/viaje/{id}/aceptar', [ViajeController::class, 'aceptar'])->name('viaje.aceptar');
    Route::post('/viaje/{id}/finalizar', [ViajeController::class, 'finalizar'])->name('viaje.finalizar');
    Route::post('/viaje/{id}/cancelar', [ViajeController::class, 'cancelar'])->name('viaje.cancelar');

    // Conductor - toggle disponibilidad
    Route::post('/conductor/toggle-disponibilidad', [ConductorController::class, 'toggleDisponibilidad']);

    // CRUDs
    Route::resource('viaje', ViajeController::class);
    Route::resource('conductor', ConductorController::class);
    Route::resource('tarifa', TarifaController::class);
    Route::resource('empresa', EmpresaController::class);
    Route::resource('empleado', EmpleadoController::class);
});
