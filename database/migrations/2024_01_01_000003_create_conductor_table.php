<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conductor', function (Blueprint $table) {
            $table->id('id_conductor');
            $table->unsignedBigInteger('id_usuario');
            $table->string('licencia_conducir', 50)->nullable();
            $table->decimal('calificacion_promedio', 3, 2)->nullable();
            $table->boolean('disponible')->default(true);
            $table->string('estado', 30)->default('activo');
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conductor');
    }
};
