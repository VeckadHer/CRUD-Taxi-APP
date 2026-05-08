<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

Route::prefix('v1')->group(function () {
    Route::get('/viajes', [ApiController::class, 'viajes']);
    Route::get('/viajes/{id}', [ApiController::class, 'viaje']);
    Route::get('/conductores/disponibles', [ApiController::class, 'conductoresDisponibles']);
    Route::get('/tarifas', [ApiController::class, 'tarifas']);
    Route::get('/lugares', [ApiController::class, 'lugares']);
    Route::post('/calcular-tarifa', [ApiController::class, 'calcularTarifa']);
    Route::get('/stats', [ApiController::class, 'stats']);
});
