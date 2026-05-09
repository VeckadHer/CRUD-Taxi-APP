<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ViajeController;
use App\Http\Controllers\ConductorController;
use App\Http\Controllers\TarifaController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\SolicitudConductorController;

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

    // Calcular tarifa (acepta GET y POST para evitar 405)
    Route::match(['get', 'post'], '/viaje/calcular-tarifa', [ViajeController::class, 'calcularTarifa']);

    // Acciones de viaje
    Route::post('/viaje/{id}/aceptar', [ViajeController::class, 'aceptar'])->name('viaje.aceptar');
    Route::post('/viaje/{id}/finalizar', [ViajeController::class, 'finalizar'])->name('viaje.finalizar');
    Route::post('/viaje/{id}/cancelar', [ViajeController::class, 'cancelar'])->name('viaje.cancelar');

    // Toggle disponibilidad conductor
    Route::post('/conductor/toggle-disponibilidad', [ConductorController::class, 'toggleDisponibilidad']);

    // SOLICITUDES DE CONDUCTOR (solo admin) — RESUELVE 404
    Route::get('/solicitudes-conductor', [SolicitudConductorController::class, 'index']);
    Route::get('/solicitudes-conductor/{id}', [SolicitudConductorController::class, 'show']);
    Route::post('/solicitudes-conductor/{id}/aprobar', [SolicitudConductorController::class, 'aprobar']);
    Route::post('/solicitudes-conductor/{id}/rechazar', [SolicitudConductorController::class, 'rechazar']);
    Route::delete('/solicitudes-conductor/{id}', [SolicitudConductorController::class, 'destroy']);

    // CRUDs
    Route::resource('viaje', ViajeController::class);
    Route::resource('conductor', ConductorController::class);
    Route::resource('tarifa', TarifaController::class);
    Route::resource('empresa', EmpresaController::class);
    Route::resource('empleado', EmpleadoController::class);
});
