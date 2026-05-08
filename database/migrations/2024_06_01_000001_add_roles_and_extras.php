<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuario', function (Blueprint $table) {
            if (!Schema::hasColumn('usuario', 'rol')) {
                $table->string('rol', 30)->default('pasajero')->after('email');
            }
        });

        Schema::table('viaje', function (Blueprint $table) {
            if (!Schema::hasColumn('viaje', 'cancelado_por')) {
                $table->string('cancelado_por', 30)->nullable()->after('estado');
            }
            if (!Schema::hasColumn('viaje', 'razon_cancelacion')) {
                $table->text('razon_cancelacion')->nullable()->after('cancelado_por');
            }
        });

        Schema::table('conductor', function (Blueprint $table) {
            if (!Schema::hasColumn('conductor', 'lat_actual')) {
                $table->decimal('lat_actual', 10, 7)->nullable()->after('estado');
            }
            if (!Schema::hasColumn('conductor', 'lng_actual')) {
                $table->decimal('lng_actual', 10, 7)->nullable()->after('lat_actual');
            }
        });

        if (!Schema::hasTable('notificacion')) {
            Schema::create('notificacion', function (Blueprint $table) {
                $table->id('id_notificacion');
                $table->unsignedBigInteger('id_usuario');
                $table->unsignedBigInteger('id_viaje')->nullable();
                $table->string('tipo', 50);
                $table->text('mensaje');
                $table->timestamp('fecha_envio')->useCurrent();
                $table->boolean('leida')->default(false);
                $table->foreign('id_usuario')->references('id_usuario')->on('usuario');
            });
        }
    }

    public function down(): void
    {
        Schema::table('usuario', function (Blueprint $table) {
            $table->dropColumn('rol');
        });
        Schema::table('viaje', function (Blueprint $table) {
            $table->dropColumn(['cancelado_por', 'razon_cancelacion']);
        });
        Schema::table('conductor', function (Blueprint $table) {
            $table->dropColumn(['lat_actual', 'lng_actual']);
        });
        Schema::dropIfExists('notificacion');
    }
};
