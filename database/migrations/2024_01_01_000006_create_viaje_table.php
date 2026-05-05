<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('viaje', function (Blueprint $table) {
            $table->id('id_viaje');
            $table->unsignedBigInteger('id_pasajero')->nullable();
            $table->unsignedBigInteger('id_conductor')->nullable();
            $table->unsignedBigInteger('id_tarifa')->nullable();
            $table->string('origen_descripcion', 200)->nullable();
            $table->decimal('origen_lat', 10, 7)->nullable();
            $table->decimal('origen_lng', 10, 7)->nullable();
            $table->string('destino_descripcion', 200)->nullable();
            $table->decimal('destino_lat', 10, 7)->nullable();
            $table->decimal('destino_lng', 10, 7)->nullable();
            $table->timestamp('fecha_solicitud')->nullable();
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();
            $table->string('estado', 30);
            $table->decimal('distancia_km', 10, 2)->nullable();
            $table->integer('duracion_minutos')->nullable();
            $table->decimal('tarifa_estimada', 10, 2)->nullable();
            $table->decimal('tarifa_final', 10, 2)->nullable();
            $table->foreign('id_pasajero')->references('id_pasajero')->on('pasajero');
            $table->foreign('id_conductor')->references('id_conductor')->on('conductor');
            $table->foreign('id_tarifa')->references('id_tarifa')->on('tarifa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('viaje');
    }
};
