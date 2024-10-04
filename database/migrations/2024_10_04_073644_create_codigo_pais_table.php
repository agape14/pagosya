<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodigoPaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codigo_pais', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_pais');
            $table->string('codigo_iso', 3)->default('PE'); // Código ISO del país (ej. US, PE)
            $table->string('codigo_telefono')->default('+51'); // Código de teléfono (ej. +1, +51)
            $table->string('bandera')->nullable(); // URL o nombre del archivo de la bandera
            $table->integer('longitud_telefono')->default(9); // Longitud del número de teléfono
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
        Schema::dropIfExists('codigo_pais');
    }
}
