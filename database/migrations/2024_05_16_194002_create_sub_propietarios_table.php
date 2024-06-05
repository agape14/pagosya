<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubPropietariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_propietarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propietario_id')->constrained('propietarios');
            $table->foreignId('sub_propietario_id')->constrained('propietarios');
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
        Schema::dropIfExists('sub_propietarios');
    }
}
