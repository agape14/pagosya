<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToGastosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gastos', function (Blueprint $table) {
            $table->decimal('total', 10, 2)->after('id_torre');
            $table->boolean('activo')->default(true)->after('total');
            $table->text('evidencia')->default(null)->after('activo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gastos', function (Blueprint $table) {
            $table->dropColumn('total');
            $table->dropColumn('activo');
            $table->dropColumn('evidencia');
        });
    }
}
