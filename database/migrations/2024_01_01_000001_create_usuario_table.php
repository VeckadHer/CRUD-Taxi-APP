<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nombre_usuario', 50)->unique();
            $table->string('hash_contrasena', 255);
            $table->string('nombre_completo', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->boolean('activo')->default(true);
            $table->timestamp('ultimo_acceso')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
