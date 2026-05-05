<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarifa', function (Blueprint $table) {
            $table->id('id_tarifa');
            $table->string('tipo_servicio', 50);
            $table->decimal('tarifa_base', 10, 2);
            $table->decimal('costo_por_km', 10, 2);
            $table->decimal('costo_por_minuto', 10, 2);
            $table->decimal('tarifa_minima', 10, 2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarifa');
    }
};
