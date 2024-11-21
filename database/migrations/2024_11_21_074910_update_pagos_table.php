<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_junta')->after('actualizado_por')->nullable();
            $table->integer('correlativo')->after('id_junta')->nullable();

            // Relación con junta_directiva
            $table->foreign('id_junta')->references('id')->on('juntas_directivas')->onDelete('cascade');
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
            $table->dropForeign(['id_junta']);
            $table->dropColumn(['id_junta', 'correlativo']);
        });
    }
}
