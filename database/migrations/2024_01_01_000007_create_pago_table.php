<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pago', function (Blueprint $table) {
            $table->id('id_pago');
            $table->unsignedBigInteger('id_viaje');
            $table->decimal('monto', 10, 2);
            $table->string('metodo_pago', 50);
            $table->string('estado_pago', 30);
            $table->timestamp('fecha_pago')->nullable();
            $table->string('referencia', 100)->nullable();
            $table->foreign('id_viaje')->references('id_viaje')->on('viaje');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pago');
    }
};
