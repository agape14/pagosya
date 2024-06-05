<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos_detalle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pago');
            $table->unsignedBigInteger('id_concepto');
            $table->decimal('monto', 10, 2);
            $table->foreign('id_pago')->references('id')->on('pagos')->onDelete('cascade');
            $table->foreign('id_concepto')->references('id')->on('conceptos')->onDelete('cascade');
            $table->unsignedBigInteger('creado_por')->nullable(); // Campo para el ID de usuario
            $table->unsignedBigInteger('actualizado_por')->nullable(); // Campo para el ID de usuario
            $table->foreign('creado_por')->references('id')->on('usuarios'); // Clave foránea para creado_por
            $table->foreign('actualizado_por')->references('id')->on('usuarios'); // Clave foránea para actualizado_por
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pagos_detalle');
    }
}
