<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;
use App\Models\Conductor;
use App\Models\Pasajero;
use App\Models\Empresa;

class TaxiCompletoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Iniciando seed completo...');

        // ============= LIMPIEZA =============
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

        // ============= EMPRESAS =============
        $empresas = [
            ['nombre' => 'TAXIS Tigres',  'razon_social' => 'Taxis Tigres de Iguala S.A. de C.V.', 'telefono' => '7331001001', 'direccion' => 'Av. Insurgentes 100, Iguala, Gro.'],
            ['nombre' => 'TAXIS Tupi',    'razon_social' => 'Transportes Tupi de Iguala S.C.',     'telefono' => '7331002002', 'direccion' => 'Calle Hidalgo 250, Iguala, Gro.'],
            ['nombre' => 'TAXIS Alfa',    'razon_social' => 'Servicios Alfa de Guerrero S.A.',     'telefono' => '7331003003', 'direccion' => 'Av. Salazar 410, Iguala, Gro.'],
            ['nombre' => 'TAXIS Serti',   'razon_social' => 'Servicios Serti S.A. de C.V.',        'telefono' => '7331004004', 'direccion' => 'Calle Juárez 75, Iguala, Gro.'],
        ];

        $empresasIds = [];
        foreach ($empresas as $e) {
            $emp = Empresa::create($e);
            $empresasIds[] = $emp->id_empresa;
        }
        $this->command->info('✓ ' . count($empresasIds) . ' empresas creadas');

        // ============= ADMIN =============
        $admin = Usuario::create([
            'nombre_usuario' => 'admin',
            'hash_contrasena' => Hash::make('123456'),
            'nombre_completo' => 'Administrador del Sistema',
            'apellido_paterno' => 'Admin',
            'apellido_materno' => 'Sistema',
            'email' => 'admin@iguala.app',
            'telefono' => '7331112233',
            'fecha_nacimiento' => '1985-05-15',
            'domicilio' => 'Centro, Iguala, Gro.',
            'codigo_postal' => '40000',
            'fecha_creacion' => now(),
            'activo' => true,
            'rol' => 'admin',
        ]);
        $this->command->info('✓ Admin creado: admin@iguala.app / 123456');

        // ============= 80 CONDUCTORES (20 POR EMPRESA) =============
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

        $direcciones = [
            'Col. Centro, Iguala', 'Col. 24 de Febrero, Iguala', 'Col. Vicente Guerrero, Iguala',
            'Col. Independencia, Iguala', 'Col. Reforma, Iguala', 'Col. Las Brisas, Iguala',
            'Col. Tamarindos, Iguala', 'Col. La Floresta, Iguala', 'Col. Pemex, Iguala',
            'Col. Burócratas, Iguala', 'Col. Magisterial, Iguala', 'Col. Jardines, Iguala',
        ];

        $codigosPostales = ['40000','40010','40020','40030','40040','40050','40060','40070'];

        $contador = 0;
        for ($i = 0; $i < 80; $i++) {
            $nom = $nombres[$i % count($nombres)];
            $empresaId = $empresasIds[$i % 4]; // distribuye 20 por empresa
            $edad = rand(22, 55);
            $year = date('Y') - $edad;
            $fechaNac = sprintf('%d-%02d-%02d', $year, rand(1,12), rand(1,28));

            // Email único
            $email = strtolower(
                str_replace(' ', '', explode(' ', $nom[0])[0]) . '.' .
                str_replace(' ', '', $nom[1]) .
                ($i+1) . '@taxi.iguala'
            );
            $email = preg_replace('/[áéíóúñ]/u', '', $email);

            $u = Usuario::create([
                'nombre_usuario' => 'cond' . ($i+1),
                'hash_contrasena' => Hash::make('123456'),
                'nombre_completo' => trim($nom[0] . ' ' . $nom[1] . ' ' . $nom[2]),
                'apellido_paterno' => $nom[1],
                'apellido_materno' => $nom[2],
                'email' => $email,
                'telefono' => '733' . str_pad(rand(1000000, 9999999), 7, '0', STR_PAD_LEFT),
                'fecha_nacimiento' => $fechaNac,
                'domicilio' => $direcciones[array_rand($direcciones)],
                'codigo_postal' => $codigosPostales[array_rand($codigosPostales)],
                'fecha_creacion' => now(),
                'activo' => true,
                'rol' => 'conductor',
            ]);

            // Disponibilidad: 60% disponibles, 25% inactivos, 15% en viaje
            $r = rand(1, 100);
            if ($r <= 60) { $disp = true; $est = 'disponible'; }
            elseif ($r <= 85) { $disp = false; $est = 'inactivo'; }
            else { $disp = false; $est = 'en_viaje'; }

            // Licencia con formato real (mexicano: letras + números)
            $licencia = strtoupper(substr($nom[1],0,1) . substr($nom[2],0,1)) . rand(100000, 999999) . 'GRO';

            Conductor::create([
                'id_usuario' => $u->id_usuario,
                'id_empresa' => $empresaId,
                'licencia_conducir' => $licencia,
                'calificacion_promedio' => round(rand(35, 50) / 10, 2), // 3.5 a 5.0
                'disponible' => $disp,
                'estado' => $est,
                'lat_actual' => 18.3447 + (rand(-200, 200) / 10000),
                'lng_actual' => -99.5388 + (rand(-200, 200) / 10000),
                'tipo_vehiculo_operar' => rand(1,2) === 1 ? 'particular' : 'empresa',
            ]);
            $contador++;
        }
        $this->command->info("✓ $contador conductores creados (20 por empresa)");

        // ============= 5 PASAJEROS =============
        $pasajerosData = [
            ['Ana','López','Hernández','ana.lopez@iguala.app','7335001001'],
            ['María','García','Rodríguez','maria.garcia@iguala.app','7335002002'],
            ['Lucía','Martínez','Sánchez','lucia.martinez@iguala.app','7335003003'],
            ['Carmen','Pérez','Torres','carmen.perez@iguala.app','7335004004'],
            ['Pasajero','Demo','Prueba','pasajero@iguala.app','7335005005'],
        ];
        foreach ($pasajerosData as $p) {
            $u = Usuario::create([
                'nombre_usuario' => strtolower(explode('@', $p[3])[0]),
                'hash_contrasena' => Hash::make('123456'),
                'nombre_completo' => trim("{$p[0]} {$p[1]} {$p[2]}"),
                'apellido_paterno' => $p[1],
                'apellido_materno' => $p[2],
                'email' => $p[3],
                'telefono' => $p[4],
                'fecha_nacimiento' => '1995-03-15',
                'domicilio' => $direcciones[array_rand($direcciones)],
                'codigo_postal' => $codigosPostales[array_rand($codigosPostales)],
                'fecha_creacion' => now(),
                'activo' => true,
                'rol' => 'pasajero',
            ]);

            Pasajero::create([
                'id_usuario' => $u->id_usuario,
                'metodo_pago_default' => ['efectivo', 'tarjeta', 'paypal'][rand(0,2)],
                'calificacion_promedio' => round(rand(40, 50) / 10, 2),
            ]);
        }
        $this->command->info('✓ 5 pasajeros creados');

        // ============= TARIFAS POR EMPRESA =============
        // (Las tarifas las dejamos como base — tipo_servicio = nombre de empresa)
        DB::table('tarifa')->truncate();
        foreach ($empresas as $idx => $e) {
            DB::table('tarifa')->insert([
                'id_tarifa' => $idx + 1,
                'tipo_servicio' => $e['nombre'],
                'tarifa_base' => 25 + ($idx * 5),
                'costo_por_km' => 6 + ($idx * 0.5),
                'costo_por_minuto' => 1.5 + ($idx * 0.25),
                'tarifa_minima' => 35 + ($idx * 5),
            ]);
        }
        $this->command->info('✓ 4 tarifas (una por empresa)');

        $this->command->info('');
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('🎉 SEED COMPLETO');
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('CREDENCIALES (todas password: 123456):');
        $this->command->info('  👤 admin@iguala.app  → ADMIN');
        $this->command->info('  🧑 pasajero@iguala.app → PASAJERO');
        $this->command->info('  🚖 80 conductores en BD');
        $this->command->info('═══════════════════════════════════════');
    }
}
