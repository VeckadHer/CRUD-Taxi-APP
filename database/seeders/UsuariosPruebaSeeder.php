<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use App\Models\Conductor;
use App\Models\Pasajero;

class UsuariosPruebaSeeder extends Seeder
{
    public function run(): void
    {
        // Eliminar usuarios de prueba previos
        DB::table('conductor')->whereIn('id_usuario', function($q) {
            $q->select('id_usuario')->from('usuario')->where('email', 'like', '%@iguala.app');
        })->delete();

        DB::table('pasajero')->whereIn('id_usuario', function($q) {
            $q->select('id_usuario')->from('usuario')->where('email', 'like', '%@iguala.app');
        })->delete();

        DB::table('usuario')->where('email', 'like', '%@iguala.app')->delete();

        // Crear usuarios de prueba
        $usuarios = [
            ['admin', 'Administrador del Sistema', 'admin@iguala.app', '7331112233', 'admin'],
            ['conductor1', 'Carlos Conductor Pérez', 'conductor@iguala.app', '7331234567', 'conductor'],
            ['conductor2', 'Roberto Hernández', 'conductor2@iguala.app', '7332345678', 'conductor'],
            ['pasajero1', 'Ana Pasajera López', 'pasajero@iguala.app', '7333456789', 'pasajero'],
            ['pasajero2', 'Luis García', 'pasajero2@iguala.app', '7334567890', 'pasajero'],
        ];

        foreach ($usuarios as [$nick, $nombre, $email, $tel, $rol]) {
            $u = Usuario::create([
                'nombre_usuario' => $nick,
                'hash_contrasena' => Hash::make('123456'),
                'nombre_completo' => $nombre,
                'email' => $email,
                'telefono' => $tel,
                'fecha_creacion' => now(),
                'activo' => true,
                'rol' => $rol,
            ]);

            if ($rol === 'conductor') {
                Conductor::create([
                    'id_usuario' => $u->id_usuario,
                    'licencia_conducir' => 'LIC-' . $u->id_usuario,
                    'calificacion_promedio' => 4.8,
                    'disponible' => true,
                    'estado' => 'activo',
                    'lat_actual' => 18.3447,
                    'lng_actual' => -99.5388,
                ]);
            } elseif ($rol === 'pasajero') {
                Pasajero::create([
                    'id_usuario' => $u->id_usuario,
                    'metodo_pago_default' => 'tarjeta',
                    'calificacion_promedio' => 4.9,
                ]);
            }
        }

        $this->command->info('✓ Usuarios de prueba creados:');
        $this->command->info('  admin@iguala.app / 123456 (admin)');
        $this->command->info('  conductor@iguala.app / 123456 (conductor)');
        $this->command->info('  conductor2@iguala.app / 123456 (conductor)');
        $this->command->info('  pasajero@iguala.app / 123456 (pasajero)');
        $this->command->info('  pasajero2@iguala.app / 123456 (pasajero)');
    }
}
