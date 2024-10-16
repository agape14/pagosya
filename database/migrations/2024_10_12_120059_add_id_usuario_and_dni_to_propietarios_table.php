<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdUsuarioAndDniToPropietariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('propietarios', function (Blueprint $table) {
            // Añadir columna id_usuario y dni
            $table->bigInteger('id_usuario')->unsigned()->nullable()->after('id_torre');
            $table->string('dni', 8)->nullable()->after('id_usuario'); // El DNI tiene 8 caracteres en Perú

            // Definir clave foránea para id_usuario
            $table->foreign('id_usuario')
                  ->references('id')
                  ->on('usuarios')
                  ->onDelete('restrict')
                  ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('propietarios', function (Blueprint $table) {
            $table->dropForeign(['id_usuario']);
            $table->dropColumn('id_usuario');
            $table->dropColumn('dni');
        });
    }
}
