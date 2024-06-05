<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->boolean('activo')->default(true)->after('total');
            $table->unsignedBigInteger('estado_id')->after('activo')->default(1);
            $table->unsignedBigInteger('id_programacion')->after('estado_id');

            $table->foreign('estado_id')->references('id')->on('estados_pagos');
            $table->foreign('id_programacion')->references('id')->on('programacion_pagos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['estado_id']);
            $table->dropForeign(['id_programacion']);
            $table->dropColumn('activo');
            $table->dropColumn('estado_id');
            $table->dropColumn('id_programacion');
        });
    }
}
