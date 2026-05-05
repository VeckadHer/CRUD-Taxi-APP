<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasajero', function (Blueprint $table) {
            $table->id('id_pasajero');
            $table->unsignedBigInteger('id_usuario');
            $table->string('metodo_pago_default', 50)->nullable();
            $table->decimal('calificacion_promedio', 3, 2)->nullable();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasajero');
    }
};
