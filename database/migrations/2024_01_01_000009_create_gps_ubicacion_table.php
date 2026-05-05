<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gps_ubicacion', function (Blueprint $table) {
            $table->id('id_ubicacion');
            $table->unsignedBigInteger('id_conductor')->nullable();
            $table->decimal('latitud', 10, 7);
            $table->decimal('longitud', 10, 7);
            $table->timestamp('fecha_registro')->useCurrent();
            $table->foreign('id_conductor')->references('id_conductor')->on('conductor');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gps_ubicacion');
    }
};
