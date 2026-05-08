<?php

namespace App\Services;

use App\Models\Tarifa;

/**
 * Servicio de cálculo de tarifas para Iguala de la Independencia, Guerrero.
 * Coordenadas centro: 18.3447, -99.5388
 */
class TarifaService
{
    // Lugares principales de Iguala con coordenadas
    public static function lugaresIguala(): array
    {
        return [
            ['nombre' => 'Centro Histórico (Zócalo)', 'lat' => 18.3450, 'lng' => -99.5400],
            ['nombre' => 'Catedral de San Francisco', 'lat' => 18.3454, 'lng' => -99.5398],
            ['nombre' => 'Mercado Hidalgo', 'lat' => 18.3438, 'lng' => -99.5380],
            ['nombre' => 'Hospital General de Iguala', 'lat' => 18.3520, 'lng' => -99.5450],
            ['nombre' => 'Tecnológico de Iguala (TecNM)', 'lat' => 18.3380, 'lng' => -99.5680],
            ['nombre' => 'Central de Autobuses', 'lat' => 18.3500, 'lng' => -99.5350],
            ['nombre' => 'Plaza Galerías Tamarindos', 'lat' => 18.3580, 'lng' => -99.5620],
            ['nombre' => 'Parque Bandera Nacional', 'lat' => 18.3475, 'lng' => -99.5395],
            ['nombre' => 'Av. Salazar (Centro)', 'lat' => 18.3445, 'lng' => -99.5410],
            ['nombre' => 'Colonia 24 de Febrero', 'lat' => 18.3320, 'lng' => -99.5500],
            ['nombre' => 'Universidad Autónoma de Guerrero', 'lat' => 18.3410, 'lng' => -99.5550],
            ['nombre' => 'IMSS Iguala', 'lat' => 18.3490, 'lng' => -99.5470],
            ['nombre' => 'Estadio Juan N. Álvarez', 'lat' => 18.3380, 'lng' => -99.5430],
            ['nombre' => 'Walmart Iguala', 'lat' => 18.3550, 'lng' => -99.5600],
            ['nombre' => 'Bodega Aurrera', 'lat' => 18.3420, 'lng' => -99.5290],
            ['nombre' => 'Colonia Centro', 'lat' => 18.3450, 'lng' => -99.5400],
            ['nombre' => 'Colonia Independencia', 'lat' => 18.3380, 'lng' => -99.5350],
            ['nombre' => 'Colonia Vicente Guerrero', 'lat' => 18.3520, 'lng' => -99.5500],
            ['nombre' => 'Av. Tecnológico', 'lat' => 18.3400, 'lng' => -99.5650],
            ['nombre' => 'Av. Insurgentes', 'lat' => 18.3460, 'lng' => -99.5380],
        ];
    }

    /**
     * Fórmula Haversine - calcula distancia en KM entre dos coordenadas
     */
    public static function calcularDistanciaKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // Radio de la Tierra en km
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        $distancia = $earthRadius * $c;
        // Multiplicador 1.3 para simular ruta por calles (no línea recta)
        return round($distancia * 1.3, 2);
    }

    /**
     * Estimar duración en minutos (velocidad promedio urbana 25 km/h)
     */
    public static function calcularDuracionMin(float $distanciaKm): int
    {
        return (int) ceil(($distanciaKm / 25) * 60);
    }

    /**
     * Calcular tarifa según fórmula:
     * tarifa_final = tarifa_base + (km * costo_por_km) + (minutos * costo_por_minuto)
     */
    public static function calcularTarifa(int $idTarifa, float $distanciaKm, int $duracionMin): array
    {
        $tarifa = Tarifa::find($idTarifa);
        if (!$tarifa) {
            return ['error' => 'Tarifa no encontrada'];
        }

        $costo = $tarifa->tarifa_base
               + ($distanciaKm * $tarifa->costo_por_km)
               + ($duracionMin * $tarifa->costo_por_minuto);

        // Aplicar tarifa mínima
        if ($costo < $tarifa->tarifa_minima) {
            $costo = $tarifa->tarifa_minima;
        }

        // Surge pricing en horas pico (11-14 y 18-21)
        $hora = (int) date('H');
        $surge = false;
        if (($hora >= 11 && $hora < 14) || ($hora >= 18 && $hora < 21)) {
            $costo = $costo * 1.25;
            $surge = true;
        }

        return [
            'distancia_km' => $distanciaKm,
            'duracion_min' => $duracionMin,
            'tarifa_base' => (float) $tarifa->tarifa_base,
            'costo_km_total' => round($distanciaKm * $tarifa->costo_por_km, 2),
            'costo_min_total' => round($duracionMin * $tarifa->costo_por_minuto, 2),
            'tarifa_minima' => (float) $tarifa->tarifa_minima,
            'surge_aplicado' => $surge,
            'tarifa_total' => round($costo, 2),
            'tipo_servicio' => $tarifa->tipo_servicio,
        ];
    }

    /**
     * Calcular todo desde coordenadas
     */
    public static function calcularDesdeCoordenadas(float $lat1, float $lng1, float $lat2, float $lng2, int $idTarifa): array
    {
        $distancia = self::calcularDistanciaKm($lat1, $lng1, $lat2, $lng2);
        $duracion = self::calcularDuracionMin($distancia);
        return self::calcularTarifa($idTarifa, $distancia, $duracion);
    }
}
