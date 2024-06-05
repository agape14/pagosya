<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPagosDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagos_detalle', function (Blueprint $table) {
            $table->boolean('activo')->default(true)->after('monto');
            $table->unsignedBigInteger('estado_id')->default(1)->after('activo');

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
        Schema::table('pagos_detalle', function (Blueprint $table) {
            $table->dropForeign(['estado_id']);
            $table->dropColumn('activo');
            $table->dropColumn('estado_id');
        });
    }
}
