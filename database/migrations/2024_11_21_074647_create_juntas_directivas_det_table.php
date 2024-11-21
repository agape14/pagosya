<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJuntasDirectivasDetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('juntas_directivas_det', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_junta');
            $table->unsignedBigInteger('id_propietario');
            $table->unsignedBigInteger('id_cargo');
            $table->string('nombres');
            $table->timestamps();

            // Relaciones
            $table->foreign('id_junta')->references('id')->on('juntas_directivas')->onDelete('cascade');
            $table->foreign('id_propietario')->references('id')->on('propietarios')->onDelete('cascade'); // Asegúrate que exista esta tabla
            $table->foreign('id_cargo')->references('id')->on('cargos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('juntas_directivas_det');
    }
}
