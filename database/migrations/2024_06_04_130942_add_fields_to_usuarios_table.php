<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->boolean('activo')->default(true)->after('id_perfil');
            $table->unsignedBigInteger('creado_por')->nullable(); // Campo para el ID de usuario
            $table->unsignedBigInteger('actualizado_por')->nullable(); // Campo para el ID de usuario
            $table->foreign('creado_por')->references('id')->on('usuarios'); // Clave foránea para creado_por
            $table->foreign('actualizado_por')->references('id')->on('usuarios'); // Clave foránea para actualizado_por
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('activo');

            $table->dropForeign(['creado_por']);
            $table->dropColumn('creado_por');

            $table->dropForeign(['actualizado_por']);
            $table->dropColumn('actualizado_por');
        });
    }
}
