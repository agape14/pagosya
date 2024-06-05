<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdTiposSubPropietariosToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_propietarios', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_sub_propietario_id')->nullable();

            // Añadir la clave foránea
            $table->foreign('tipo_sub_propietario_id')
                  ->references('id')
                  ->on('tipos_sub_propietarios')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_propietarios', function (Blueprint $table) {
            $table->dropForeign(['tipo_sub_propietario_id']);
            $table->dropColumn('tipo_sub_propietario_id');
        });
    }
}
