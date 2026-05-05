<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calificacion', function (Blueprint $table) {
            $table->id('id_calificacion');
            $table->unsignedBigInteger('id_viaje')->nullable();
            $table->unsignedBigInteger('id_evaluador')->nullable();
            $table->unsignedBigInteger('id_evaluado')->nullable();
            $table->integer('puntuacion');
            $table->text('comentario')->nullable();
            $table->timestamp('fecha')->nullable();
            $table->string('tipo', 50);
            $table->foreign('id_viaje')->references('id_viaje')->on('viaje');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calificacion');
    }
};
