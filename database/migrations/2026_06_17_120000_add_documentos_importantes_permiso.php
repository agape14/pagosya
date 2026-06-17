<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddDocumentosImportantesPermiso extends Migration
{
    public function up()
    {
        $permisos = [
            17 => ['nombre_permiso' => 'ingresos', 'parent_id' => null],
            18 => ['nombre_permiso' => 'reportes', 'parent_id' => null],
            19 => ['nombre_permiso' => 'intbancario', 'parent_id' => 2],
            20 => ['nombre_permiso' => 'noticias', 'parent_id' => null],
            21 => ['nombre_permiso' => 'documentosimportantes', 'parent_id' => null],
        ];

        foreach ($permisos as $id => $data) {
            if (!DB::table('permisos')->where('id', $id)->exists()) {
                DB::table('permisos')->insert(array_merge($data, [
                    'id' => $id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        $nuevosIds = array_keys($permisos);
        if (DB::table('usuarios')->where('id', 1)->exists()) {
            foreach ($nuevosIds as $idPermiso) {
                $yaAsignado = DB::table('permisos_usuarios')
                    ->where('id_usuario', 1)
                    ->where('id_permiso', $idPermiso)
                    ->exists();

                if (!$yaAsignado) {
                    DB::table('permisos_usuarios')->insert([
                        'id_usuario' => 1,
                        'id_permiso' => $idPermiso,
                    ]);
                }
            }
        }
    }

    public function down()
    {
        DB::table('permisos_usuarios')->where('id_permiso', 21)->delete();
        DB::table('permisos')->where('id', 21)->delete();
    }
}
