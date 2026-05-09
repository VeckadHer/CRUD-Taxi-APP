<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Conductor;
use App\Models\Pasajero;
use App\Models\Empresa;
use App\Models\Viaje;
use App\Models\Pago;

class TaxiCompletoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Iniciando seed completo V3.1...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('notificacion')->truncate();
        DB::table('pago')->truncate();
        DB::table('calificacion')->truncate();
        DB::table('viaje')->truncate();
        DB::table('vehiculo')->truncate();
        DB::table('gps_ubicacion')->truncate();
        DB::table('conductor')->truncate();
        DB::table('pasajero')->truncate();
        DB::table('usuario')->truncate();
        DB::table('empresa')->truncate();
        DB::table('solicitud_conductor')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // EMPRESAS
        $empresas = [
            ['nombre' => 'TAXIS Tigres', 'razon_social' => 'Taxis Tigres de Iguala S.A. de C.V.', 'telefono' => '7331001001', 'direccion' => 'Av. Insurgentes 100, Iguala, Gro.'],
            ['nombre' => 'TAXIS Tupi',   'razon_social' => 'Transportes Tupi de Iguala S.C.',     'telefono' => '7331002002', 'direccion' => 'Calle Hidalgo 250, Iguala, Gro.'],
            ['nombre' => 'TAXIS Alfa',   'razon_social' => 'Servicios Alfa de Guerrero S.A.',     'telefono' => '7331003003', 'direccion' => 'Av. Salazar 410, Iguala, Gro.'],
            ['nombre' => 'TAXIS Serti',  'razon_social' => 'Servicios Serti S.A. de C.V.',        'telefono' => '7331004004', 'direccion' => 'Calle Juárez 75, Iguala, Gro.'],
        ];
        $empresasIds = [];
        foreach ($empresas as $e) $empresasIds[] = Empresa::create($e)->id_empresa;

        // ADMIN
        Usuario::create([
            'nombre_usuario' => 'admin',
            'hash_contrasena' => Hash::make('123456'),
            'nombre_completo' => 'Administrador del Sistema',
            'apellido_paterno' => 'Admin', 'apellido_materno' => 'Sistema',
            'email' => 'admin@iguala.app', 'telefono' => '7331112233',
            'fecha_nacimiento' => '1985-05-15', 'domicilio' => 'Centro, Iguala', 'codigo_postal' => '40000',
            'fecha_creacion' => now(), 'activo' => true, 'rol' => 'admin',
        ]);

        $nombres = [
            ['Juan Carlos','Pérez','García'],['Roberto','Hernández','López'],['Miguel Ángel','Rodríguez','Martínez'],
            ['José Luis','González','Sánchez'],['Francisco','Ramírez','Torres'],['Pedro','Flores','Vásquez'],
            ['Antonio','Díaz','Reyes'],['Manuel','Cruz','Morales'],['Jesús','Ortiz','Jiménez'],
            ['Carlos Alberto','Castillo','Romero'],['Alejandro','Mendoza','Ruiz'],['Eduardo','Aguilar','Silva'],
            ['Ricardo','Vargas','Castro'],['Sergio','Estrada','Ortega'],['Daniel','Salazar','Núñez'],
            ['Fernando','Rojas','Delgado'],['Javier','Medina','Guerrero'],['Luis Fernando','Cortés','Soto'],
            ['Hugo','Ríos','Navarro'],['Arturo','Padilla','Ibarra'],['Raúl','Cabrera','Espinoza'],
            ['Enrique','Valdez','Cervantes'],['Mario','Acosta','Maldonado'],['Adrián','Solís','Pacheco'],
            ['Víctor','Camacho','Lara'],['Gerardo','Alvarado','Fuentes'],['Gabriel','Carrillo','Carrasco'],
            ['Ismael','Tapia','Zamora'],['Rubén','Vega','Salinas'],['Óscar','Pineda','Cisneros'],
            ['Pablo','Bautista','Ramos'],['Héctor','Mora','Andrade'],['Eric','Rivera','Solorio'],
            ['Diego','Beltrán','Quintero'],['Andrés','Castañeda','Gallegos'],['Iván','Magaña','Bolaños'],
            ['Salvador','Galván','Henríquez'],['Ernesto','Lemus','Barrera'],['Tomás','Cordero','Anguiano'],
            ['Marco Antonio','Núñez','Olvera'],['Rodolfo','Salgado','Toledo'],['Felipe','Valenzuela','Alarcón'],
            ['Cristóbal','Bermúdez','Casas'],['Alfredo','Santiago','Rangel'],['Bernardo','Peralta','Manzo'],
            ['Octavio','Murillo','Téllez'],['Ramón','Guzmán','Plasencia'],['Cristian','Ávila','Carmona'],
            ['Esteban','Loera','Treviño'],['Joaquín','Coronado','Briseño'],['Marcos','Lozano','Esquivel'],
            ['Nicolás','Valencia','Becerra'],['Fabián','Pulido','Granados'],['Iván','Téllez','Cuéllar'],
            ['Eliseo','Pizarro','Ojeda'],['Lázaro','Macías','Chávez'],['Damián','Bravo','Avilés'],
            ['Genaro','Vera','Cuevas'],['Heriberto','Maya','Suárez'],['Saúl','Esparza','Tovar'],
            ['Ulises','Olmedo','Reséndiz'],['Vicente','Alcántara','Quintana'],['Wilfrido','Linares','Bonilla'],
            ['Yair','Negrete','Polanco'],['Zacarías','Valdivia','Carbajal'],['Aurelio','Bañuelos','Frías'],
            ['Bonifacio','Calderón','Cantú'],['Dionisio','Felipe','Aragón'],['Eladio','Gaytán','Almanza'],
            ['Florencio','Higareda','Robles'],['Gilberto','Iturbe','Saucedo'],['Hilario','Jasso','Téllez'],
            ['Isidro','Lerma','Uribe'],['Joaquín','Méndez','Vázquez'],['Lauro','Nájera','Wong'],
            ['Mariano','Olivares','Yáñez'],['Nemesio','Páramo','Zaldívar'],['Osvaldo','Quiroz','Aldama'],
            ['Plutarco','Renteria','Bolívar'],['Quirino','Sandoval','Cisneros'],
        ];

        $direcciones = ['Col. Centro, Iguala', 'Col. 24 de Febrero, Iguala', 'Col. Vicente Guerrero, Iguala',
            'Col. Independencia, Iguala', 'Col. Reforma, Iguala', 'Col. Las Brisas, Iguala',
            'Col. Tamarindos, Iguala', 'Col. La Floresta, Iguala', 'Col. Pemex, Iguala',
            'Col. Burócratas, Iguala', 'Col. Magisterial, Iguala', 'Col. Jardines, Iguala'];
        $cps = ['40000','40010','40020','40030','40040','40050','40060','40070'];

        // 80 CONDUCTORES con distribución por empresa: 6 disponibles + 10 en_viaje + 4 inactivos = 20
        $conductoresIds = [];
        $empresaConductorMap = []; // [empresaId => [conductorId, conductorId, ...]]

        for ($empIdx = 0; $empIdx < 4; $empIdx++) {
            $empresaId = $empresasIds[$empIdx];
            $empresaConductorMap[$empresaId] = ['disponibles' => [], 'en_viaje' => [], 'todos' => []];

            for ($i = 0; $i < 20; $i++) {
                $globalIdx = $empIdx * 20 + $i;
                $nom = $nombres[$globalIdx % count($nombres)];
                $edad = rand(22, 55);
                $year = date('Y') - $edad;
                $fechaNac = sprintf('%d-%02d-%02d', $year, rand(1,12), rand(1,28));

                // EMAIL @driver.com (NUEVO)
                $emailBase = strtolower(
                    str_replace(' ', '', explode(' ', $nom[0])[0]) . '.' .
                    str_replace(' ', '', $nom[1]) . ($globalIdx + 1)
                );
                $emailBase = preg_replace('/[áéíóúñ]/u', '', $emailBase);
                $email = $emailBase . '@driver.com';

                // Distribución: primeros 6 = disponibles, siguientes 10 = en_viaje, últimos 4 = inactivos
                if ($i < 6) { $disp = true; $est = 'disponible'; }
                elseif ($i < 16) { $disp = false; $est = 'en_viaje'; }
                else { $disp = false; $est = 'inactivo'; }

                $u = Usuario::create([
                    'nombre_usuario' => 'cond' . ($globalIdx + 1),
                    'hash_contrasena' => Hash::make('123456'),
                    'nombre_completo' => trim($nom[0] . ' ' . $nom[1] . ' ' . $nom[2]),
                    'apellido_paterno' => $nom[1], 'apellido_materno' => $nom[2],
                    'email' => $email,
                    'telefono' => '733' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                    'fecha_nacimiento' => $fechaNac,
                    'domicilio' => $direcciones[array_rand($direcciones)],
                    'codigo_postal' => $cps[array_rand($cps)],
                    'fecha_creacion' => now(), 'activo' => true, 'rol' => 'conductor',
                ]);

                $licencia = strtoupper(substr($nom[1],0,1) . substr($nom[2],0,1)) . rand(100000, 999999) . 'GRO';

                $cond = Conductor::create([
                    'id_usuario' => $u->id_usuario,
                    'id_empresa' => $empresaId,
                    'licencia_conducir' => $licencia,
                    'calificacion_promedio' => round(rand(35, 50) / 10, 2),
                    'disponible' => $disp,
                    'estado' => $est,
                    'lat_actual' => 18.3447 + (rand(-200, 200) / 10000),
                    'lng_actual' => -99.5388 + (rand(-200, 200) / 10000),
                    'tipo_vehiculo_operar' => rand(1,2) === 1 ? 'particular' : 'empresa',
                ]);

                $conductoresIds[] = $cond->id_conductor;
                $empresaConductorMap[$empresaId]['todos'][] = $cond->id_conductor;
                if ($est === 'disponible') $empresaConductorMap[$empresaId]['disponibles'][] = $cond->id_conductor;
                if ($est === 'en_viaje') $empresaConductorMap[$empresaId]['en_viaje'][] = $cond->id_conductor;
            }
        }
        $this->command->info('✓ 80 conductores creados (6 disp + 10 en_viaje + 4 inactivos por empresa)');

        // PASAJEROS - emails @gmail.com (NUEVO)
        $pasajerosData = [
            ['Ana','López','Hernández','ana.lopez@gmail.com','7335001001'],
            ['María','García','Rodríguez','maria.garcia@gmail.com','7335002002'],
            ['Lucía','Martínez','Sánchez','lucia.martinez@gmail.com','7335003003'],
            ['Carmen','Pérez','Torres','carmen.perez@gmail.com','7335004004'],
            ['Pasajero','Demo','Prueba','pasajero@gmail.com','7335005005'],
            ['Sofía','Ramírez','Ortiz','sofia.ramirez@gmail.com','7335006006'],
            ['Valeria','Mendoza','Cruz','valeria.mendoza@gmail.com','7335007007'],
            ['Isabel','Fuentes','Romero','isabel.fuentes@gmail.com','7335008008'],
        ];
        $pasajerosIds = [];
        foreach ($pasajerosData as $p) {
            $u = Usuario::create([
                'nombre_usuario' => strtolower(explode('@', $p[3])[0]),
                'hash_contrasena' => Hash::make('123456'),
                'nombre_completo' => trim("{$p[0]} {$p[1]} {$p[2]}"),
                'apellido_paterno' => $p[1], 'apellido_materno' => $p[2],
                'email' => $p[3], 'telefono' => $p[4],
                'fecha_nacimiento' => '1995-03-15',
                'domicilio' => $direcciones[array_rand($direcciones)],
                'codigo_postal' => $cps[array_rand($cps)],
                'fecha_creacion' => now(), 'activo' => true, 'rol' => 'pasajero',
            ]);
            $pas = Pasajero::create([
                'id_usuario' => $u->id_usuario,
                'metodo_pago_default' => ['efectivo', 'tarjeta', 'paypal'][rand(0,2)],
                'calificacion_promedio' => round(rand(40, 50) / 10, 2),
            ]);
            $pasajerosIds[] = $pas->id_pasajero;
        }

        // TARIFAS POR EMPRESA
        DB::table('tarifa')->truncate();
        $tarifasIds = [];
        foreach ($empresas as $idx => $e) {
            $id = DB::table('tarifa')->insertGetId([
                'tipo_servicio' => $e['nombre'],
                'tarifa_base' => 25 + ($idx * 5),
                'costo_por_km' => 6 + ($idx * 0.5),
                'costo_por_minuto' => 1.5 + ($idx * 0.25),
                'tarifa_minima' => 35 + ($idx * 5),
            ]);
            $tarifasIds[$e['nombre']] = $id;
        }

        // ============== VIAJES FAKE ==============
        $rutas = [
            ['Centro Iguala', 18.3447, -99.5388, 'Hospital General Iguala', 18.3520, -99.5450],
            ['Mercado Hidalgo', 18.3445, -99.5395, 'Tecnológico de Iguala', 18.3340, -99.5300],
            ['Catedral de Iguala', 18.3450, -99.5390, 'Central de Autobuses', 18.3380, -99.5420],
            ['Col. Centro', 18.3447, -99.5388, 'Col. 24 de Febrero', 18.3500, -99.5350],
            ['Col. Reforma', 18.3420, -99.5350, 'Col. Las Brisas', 18.3470, -99.5430],
            ['Plaza Centenario', 18.3438, -99.5392, 'Col. Pemex', 18.3510, -99.5360],
            ['Iguala Centro', 18.3447, -99.5388, 'Col. Vicente Guerrero', 18.3530, -99.5410],
            ['Mercado Municipal', 18.3445, -99.5395, 'Col. Tamarindos', 18.3490, -99.5320],
            ['ITC Iguala', 18.3380, -99.5420, 'Centro Iguala', 18.3447, -99.5388],
            ['Col. Magisterial', 18.3460, -99.5380, 'Hospital General', 18.3520, -99.5450],
        ];

        $viajesCreados = 0;

        // 1) Viajes EN CURSO (uno por cada conductor en_viaje, total 40)
        foreach ($empresaConductorMap as $empId => $data) {
            $tarifaId = $tarifasIds[Empresa::find($empId)->nombre];
            foreach ($data['en_viaje'] as $condId) {
                $ruta = $rutas[array_rand($rutas)];
                $dist = round(rand(15, 80) / 10, 2); // 1.5 a 8 km
                $dur = round($dist * 2.4); // ~25 km/h
                $tarifa = round(30 + ($dist * 7) + ($dur * 1.8), 2);

                Viaje::create([
                    'id_pasajero' => $pasajerosIds[array_rand($pasajerosIds)],
                    'id_conductor' => $condId,
                    'id_tarifa' => $tarifaId,
                    'origen_descripcion' => $ruta[0],
                    'origen_lat' => $ruta[1], 'origen_lng' => $ruta[2],
                    'destino_descripcion' => $ruta[3],
                    'destino_lat' => $ruta[4], 'destino_lng' => $ruta[5],
                    'fecha_solicitud' => now()->subMinutes(rand(5, 25)),
                    'fecha_inicio' => now()->subMinutes(rand(1, 4)),
                    'estado' => 'en_curso',
                    'distancia_km' => $dist,
                    'duracion_minutos' => $dur,
                    'tarifa_estimada' => $tarifa,
                ]);
                $viajesCreados++;
            }
        }

        // 2) Viajes COMPLETADOS (30, repartidos hoy y ayer)
        for ($i = 0; $i < 30; $i++) {
            $condId = $conductoresIds[array_rand($conductoresIds)];
            $cond = Conductor::find($condId);
            $tarifaId = $tarifasIds[$cond->empresa->nombre];
            $ruta = $rutas[array_rand($rutas)];
            $dist = round(rand(15, 100) / 10, 2);
            $dur = round($dist * 2.4);
            $tarifa = round(30 + ($dist * 7) + ($dur * 1.8), 2);

            // 50% hoy, 50% ayer
            $base = $i < 15 ? now()->subHours(rand(1, 12)) : now()->subDays(1)->subHours(rand(0, 23));
            $solicitado = $base->copy()->subMinutes($dur + rand(2, 8));
            $iniciado = $solicitado->copy()->addMinutes(rand(2, 5));
            $fin = $iniciado->copy()->addMinutes($dur);

            $viaje = Viaje::create([
                'id_pasajero' => $pasajerosIds[array_rand($pasajerosIds)],
                'id_conductor' => $condId,
                'id_tarifa' => $tarifaId,
                'origen_descripcion' => $ruta[0],
                'origen_lat' => $ruta[1], 'origen_lng' => $ruta[2],
                'destino_descripcion' => $ruta[3],
                'destino_lat' => $ruta[4], 'destino_lng' => $ruta[5],
                'fecha_solicitud' => $solicitado,
                'fecha_inicio' => $iniciado,
                'fecha_fin' => $fin,
                'estado' => 'completado',
                'distancia_km' => $dist,
                'duracion_minutos' => $dur,
                'tarifa_estimada' => $tarifa,
                'tarifa_final' => $tarifa,
            ]);

            Pago::create([
                'id_viaje' => $viaje->id_viaje,
                'monto' => $tarifa,
                'metodo_pago' => ['efectivo','tarjeta','paypal'][rand(0,2)],
                'estado_pago' => 'pagado',
                'fecha_pago' => $fin,
                'referencia' => 'AUTO-' . $viaje->id_viaje,
            ]);
            $viajesCreados++;
        }

        // 3) Viajes PENDIENTES/SOLICITADOS (5, dirigidos a conductores disponibles)
        $disponiblesAll = [];
        foreach ($empresaConductorMap as $data) {
            foreach ($data['disponibles'] as $c) $disponiblesAll[] = $c;
        }
        for ($i = 0; $i < 5; $i++) {
            $condId = $disponiblesAll[array_rand($disponiblesAll)];
            $cond = Conductor::find($condId);
            $tarifaId = $tarifasIds[$cond->empresa->nombre];
            $ruta = $rutas[array_rand($rutas)];
            $dist = round(rand(15, 60) / 10, 2);
            $dur = round($dist * 2.4);
            $tarifa = round(30 + ($dist * 7) + ($dur * 1.8), 2);

            Viaje::create([
                'id_pasajero' => $pasajerosIds[array_rand($pasajerosIds)],
                'id_conductor' => $condId,
                'id_tarifa' => $tarifaId,
                'origen_descripcion' => $ruta[0],
                'origen_lat' => $ruta[1], 'origen_lng' => $ruta[2],
                'destino_descripcion' => $ruta[3],
                'destino_lat' => $ruta[4], 'destino_lng' => $ruta[5],
                'fecha_solicitud' => now()->subMinutes(rand(1, 8)),
                'estado' => 'solicitado',
                'distancia_km' => $dist,
                'duracion_minutos' => $dur,
                'tarifa_estimada' => $tarifa,
            ]);
            $viajesCreados++;
        }

        // 4) Viajes CANCELADOS (3)
        for ($i = 0; $i < 3; $i++) {
            $condId = $conductoresIds[array_rand($conductoresIds)];
            $cond = Conductor::find($condId);
            $tarifaId = $tarifasIds[$cond->empresa->nombre];
            $ruta = $rutas[array_rand($rutas)];
            $dist = round(rand(15, 60) / 10, 2);
            $dur = round($dist * 2.4);
            $tarifa = round(30 + ($dist * 7) + ($dur * 1.8), 2);
            $base = now()->subHours(rand(1, 24));

            Viaje::create([
                'id_pasajero' => $pasajerosIds[array_rand($pasajerosIds)],
                'id_conductor' => $condId,
                'id_tarifa' => $tarifaId,
                'origen_descripcion' => $ruta[0],
                'origen_lat' => $ruta[1], 'origen_lng' => $ruta[2],
                'destino_descripcion' => $ruta[3],
                'destino_lat' => $ruta[4], 'destino_lng' => $ruta[5],
                'fecha_solicitud' => $base->copy()->subMinutes(5),
                'fecha_fin' => $base,
                'estado' => 'cancelado',
                'distancia_km' => $dist,
                'duracion_minutos' => $dur,
                'tarifa_estimada' => $tarifa,
                'tarifa_final' => 0,
                'cancelado_por' => 'pasajero',
                'razon_cancelacion' => 'Cambió de planes',
            ]);
            $viajesCreados++;
        }

        // SOLICITUD DE CONDUCTOR DEMO
        DB::table('solicitud_conductor')->insert([
            'nombre_completo' => 'Demo Solicitante',
            'telefono' => '7339998877',
            'email' => 'demo.solicitante@gmail.com',
            'mensaje' => 'Solicitud de prueba para demostración del sistema',
            'estado' => 'pendiente',
            'fecha_solicitud' => now(),
        ]);

        $this->command->info("✓ $viajesCreados viajes creados (40 en curso + 30 completados + 5 pendientes + 3 cancelados)");
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('🎉 SEED V3.1 COMPLETO');
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('CREDENCIALES (todas password: 123456):');
        $this->command->info('  👤 admin@iguala.app           → ADMIN');
        $this->command->info('  🧑 pasajero@gmail.com         → PASAJERO (debe ser @gmail.com)');
        $this->command->info('  🚖 [nombre]@driver.com        → CONDUCTORES (deben ser @driver.com)');
        $this->command->info('═══════════════════════════════════════');
    }
}
