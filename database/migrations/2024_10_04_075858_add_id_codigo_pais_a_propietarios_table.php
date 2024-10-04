<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdCodigoPaisAPropietariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('propietarios', function (Blueprint $table) {
            $table->unsignedBigInteger('id_codigo_pais')->nullable()->after('telefono')->default(1);

            // Añadir la clave foránea
            $table->foreign('id_codigo_pais')->references('id')->on('codigo_pais');
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
            $table->dropForeign(['id_codigo_pais']);
            $table->dropColumn('id_codigo_pais');
        });
    }
}
