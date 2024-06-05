<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEstadoIdToProgramacionPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('programacion_pagos', function (Blueprint $table) {
            $table->boolean('activo')->default(true)->after('total');
            $table->unsignedBigInteger('estado_id')->after('activo')->default(1);
            $table->foreign('estado_id')->references('id')->on('estados_pagos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('programacion_pagos', function (Blueprint $table) {
            $table->dropColumn('activo');
            $table->dropForeign(['estado_id']);
            $table->dropColumn('estado_id');
        });
    }
}
