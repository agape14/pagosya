<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CodigosPais;

class CodigosPaisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countryCodes = [
            ['nombre_pais' => 'Perú', 'codigo_iso' => 'PE', 'codigo_telefono' => '+51', 'bandera' => 'peru.png', 'longitud_telefono' => 9],
            ['nombre_pais' => 'Estados Unidos', 'codigo_iso' => 'US', 'codigo_telefono' => '+1', 'bandera' => 'eeuu.png', 'longitud_telefono' => 10],
            ['nombre_pais' => 'Argentina', 'codigo_iso' => 'AR', 'codigo_telefono' => '+54', 'bandera' => 'argentina.png', 'longitud_telefono' => 10],
            ['nombre_pais' => 'Colombia', 'codigo_iso' => 'CO', 'codigo_telefono' => '+57', 'bandera' => 'colombia.png', 'longitud_telefono' => 10],
            ['nombre_pais' => 'España', 'codigo_iso' => 'ES', 'codigo_telefono' => '+34', 'bandera' => 'espania.png', 'longitud_telefono' => 9],
            ['nombre_pais' => 'Italia', 'codigo_iso' => 'IT', 'codigo_telefono' => '+39', 'bandera' => 'italia.png', 'longitud_telefono' => 9],

            // Países adicionales de América
            ['nombre_pais' => 'México', 'codigo_iso' => 'MX', 'codigo_telefono' => '+52', 'bandera' => 'mexico.png', 'longitud_telefono' => 10],
            ['nombre_pais' => 'Brasil', 'codigo_iso' => 'BR', 'codigo_telefono' => '+55', 'bandera' => 'brasil.png', 'longitud_telefono' => 11],
            ['nombre_pais' => 'Chile', 'codigo_iso' => 'CL', 'codigo_telefono' => '+56', 'bandera' => 'chile.png', 'longitud_telefono' => 9],

            // Países de Europa
            ['nombre_pais' => 'Francia', 'codigo_iso' => 'FR', 'codigo_telefono' => '+33', 'bandera' => 'francia.png', 'longitud_telefono' => 9],
            ['nombre_pais' => 'Reino Unido', 'codigo_iso' => 'GB', 'codigo_telefono' => '+44', 'bandera' => 'reino_unido.png', 'longitud_telefono' => 10],
            ['nombre_pais' => 'Alemania', 'codigo_iso' => 'DE', 'codigo_telefono' => '+49', 'bandera' => 'alemania.png', 'longitud_telefono' => 11],

            // Países de Asia
            ['nombre_pais' => 'Japón', 'codigo_iso' => 'JP', 'codigo_telefono' => '+81', 'bandera' => 'japon.png', 'longitud_telefono' => 10],
            ['nombre_pais' => 'China', 'codigo_iso' => 'CN', 'codigo_telefono' => '+86', 'bandera' => 'china.png', 'longitud_telefono' => 11],
            ['nombre_pais' => 'India', 'codigo_iso' => 'IN', 'codigo_telefono' => '+91', 'bandera' => 'india.png', 'longitud_telefono' => 10],
            ['nombre_pais' => 'Corea del Sur', 'codigo_iso' => 'KR', 'codigo_telefono' => '+82', 'bandera' => 'corea_sur.png', 'longitud_telefono' => 10]
        ];


        foreach ($countryCodes as $code) {
            CodigosPais::create($code);
        }
    }
}
