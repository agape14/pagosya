<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TorresPermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Insertar datos en la tabla 'torres'
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Deshabilitar las claves foráneas

        // Truncar la tabla 'torres' para reiniciar los IDs
        DB::table('torres')->truncate();

        DB::table('torres')->insert([
            ['nombre_torre' => 'G01', 'creado_por' => 1],
            ['nombre_torre' => 'G02', 'creado_por' => 1],
            ['nombre_torre' => 'G03', 'creado_por' => 1],
            ['nombre_torre' => 'G04', 'creado_por' => 1],
            ['nombre_torre' => 'G05', 'creado_por' => 1],
            ['nombre_torre' => 'G06', 'creado_por' => 1],
            ['nombre_torre' => 'G07', 'creado_por' => 1],
            ['nombre_torre' => 'G08', 'creado_por' => 1],
            ['nombre_torre' => 'G09', 'creado_por' => 1],
            ['nombre_torre' => 'G10', 'creado_por' => 1],
            ['nombre_torre' => 'G11', 'creado_por' => 1],
            ['nombre_torre' => 'G12', 'creado_por' => 1],
            ['nombre_torre' => 'G13', 'creado_por' => 1],
            ['nombre_torre' => 'G14', 'creado_por' => 1],
            ['nombre_torre' => 'G15', 'creado_por' => 1],
            ['nombre_torre' => 'G16', 'creado_por' => 1],
            ['nombre_torre' => 'G17', 'creado_por' => 1],
            ['nombre_torre' => 'G18', 'creado_por' => 1],
            ['nombre_torre' => 'G19', 'creado_por' => 1],
            ['nombre_torre' => 'G20', 'creado_por' => 1],
            ['nombre_torre' => 'G21', 'creado_por' => 1],
            ['nombre_torre' => 'G22', 'creado_por' => 1],
            ['nombre_torre' => 'G23', 'creado_por' => 1],
            ['nombre_torre' => 'G24', 'creado_por' => 1],
            ['nombre_torre' => 'G25', 'creado_por' => 1],
            ['nombre_torre' => 'G26', 'creado_por' => 1],
            ['nombre_torre' => 'G27', 'creado_por' => 1],
        ]);

        // 2. Insertar datos en la tabla 'permisos'
        DB::table('permisos')->truncate();

        DB::table('permisos')->insert([
            ['nombre_permiso' => 'panelcontrol', 'parent_id' => null],
            ['nombre_permiso' => 'mantenimientos', 'parent_id' => null],
            ['nombre_permiso' => 'torres', 'parent_id' => 2],
            ['nombre_permiso' => 'tipoconcepto', 'parent_id' => 2],
            ['nombre_permiso' => 'conceptos', 'parent_id' => 2],
            ['nombre_permiso' => 'propietarios', 'parent_id' => 2],
            ['nombre_permiso' => 'pagos', 'parent_id' => null],
            ['nombre_permiso' => 'programacion', 'parent_id' => 7],
            ['nombre_permiso' => 'registropagos', 'parent_id' => 7],
            ['nombre_permiso' => 'gastos', 'parent_id' => null],
            ['nombre_permiso' => 'configuracion', 'parent_id' => null],
            ['nombre_permiso' => 'usuarios', 'parent_id' => 11],
            ['nombre_permiso' => 'permisos', 'parent_id' => 11],
            ['nombre_permiso' => 'agregar', 'parent_id' => null],
            ['nombre_permiso' => 'editar', 'parent_id' => null],
            ['nombre_permiso' => 'eliminar', 'parent_id' => null],
            ['nombre_permiso' => 'ingresos', 'parent_id' => null],
            ['nombre_permiso' => 'reportes', 'parent_id' => null],
            ['nombre_permiso' => 'intbancario', 'parent_id' => 2],
            ['nombre_permiso' => 'noticias', 'parent_id' => null],
        ]);

        // 3. Insertar datos en la tabla 'permisos_usuarios'
        DB::table('permisos_usuarios')->truncate();

        $permisos = DB::table('permisos')->pluck('id'); // Obtener todos los IDs de permisos

        foreach ($permisos as $id_permiso) {
            DB::table('permisos_usuarios')->insert([
                'id_usuario' => 1,
                'id_permiso' => $id_permiso
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Rehabilitar las claves foráneas
    }
}
