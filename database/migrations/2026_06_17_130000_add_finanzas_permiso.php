<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddFinanzasPermiso extends Migration
{
    public function up()
    {
        if (!DB::table('permisos')->where('id', 22)->exists()) {
            DB::table('permisos')->insert([
                'id' => 22,
                'nombre_permiso' => 'finanzas',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (DB::table('usuarios')->where('id', 1)->exists()) {
            $yaAsignado = DB::table('permisos_usuarios')
                ->where('id_usuario', 1)
                ->where('id_permiso', 22)
                ->exists();

            if (!$yaAsignado) {
                DB::table('permisos_usuarios')->insert([
                    'id_usuario' => 1,
                    'id_permiso' => 22,
                ]);
            }
        }
    }

    public function down()
    {
        DB::table('permisos_usuarios')->where('id_permiso', 22)->delete();
        DB::table('permisos')->where('id', 22)->delete();
    }
}
