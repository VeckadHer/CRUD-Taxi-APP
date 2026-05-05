<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehiculo', function (Blueprint $table) {
            $table->id('id_vehiculo');
            $table->unsignedBigInteger('id_conductor');
            $table->string('placa', 20);
            $table->string('marca', 50);
            $table->string('modelo', 50);
            $table->integer('anio');
            $table->string('color', 30);
            $table->string('tipo_vehiculo', 50);
            $table->foreign('id_conductor')->references('id_conductor')->on('conductor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculo');
    }
};
