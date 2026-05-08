<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla empresa
        if (!Schema::hasTable('empresa')) {
            Schema::create('empresa', function (Blueprint $table) {
                $table->id('id_empresa');
                $table->string('nombre', 100);
                $table->string('razon_social', 150)->nullable();
                $table->string('telefono', 20)->nullable();
                $table->string('direccion', 200)->nullable();
                $table->boolean('activa')->default(true);
                $table->timestamp('fecha_registro')->useCurrent();
            });
        }

        // Agregar campos a usuario
        Schema::table('usuario', function (Blueprint $table) {
            if (!Schema::hasColumn('usuario', 'apellido_paterno')) $table->string('apellido_paterno', 50)->nullable()->after('nombre_completo');
            if (!Schema::hasColumn('usuario', 'apellido_materno')) $table->string('apellido_materno', 50)->nullable()->after('apellido_paterno');
            if (!Schema::hasColumn('usuario', 'fecha_nacimiento')) $table->date('fecha_nacimiento')->nullable();
            if (!Schema::hasColumn('usuario', 'domicilio')) $table->string('domicilio', 200)->nullable();
            if (!Schema::hasColumn('usuario', 'codigo_postal')) $table->string('codigo_postal', 10)->nullable();
        });

        // Agregar campos a conductor
        Schema::table('conductor', function (Blueprint $table) {
            if (!Schema::hasColumn('conductor', 'id_empresa')) $table->unsignedBigInteger('id_empresa')->nullable()->after('id_usuario');
            if (!Schema::hasColumn('conductor', 'tipo_vehiculo_operar')) $table->string('tipo_vehiculo_operar', 50)->default('particular');
        });

        // Solicitudes de conductor (cuando alguien deja su tel para ser conductor)
        if (!Schema::hasTable('solicitud_conductor')) {
            Schema::create('solicitud_conductor', function (Blueprint $table) {
                $table->id('id_solicitud');
                $table->string('nombre_completo', 150);
                $table->string('telefono', 20);
                $table->string('email', 100)->nullable();
                $table->text('mensaje')->nullable();
                $table->string('estado', 30)->default('pendiente'); // pendiente, contactado, registrado, rechazado
                $table->timestamp('fecha_solicitud')->useCurrent();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitud_conductor');
        Schema::dropIfExists('empresa');
    }
};
