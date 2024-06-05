<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiposConceptoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos_concepto', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_concepto', 120);
            $table->unsignedBigInteger('creado_por')->nullable(); // Campo para el ID de usuario
            $table->unsignedBigInteger('actualizado_por')->nullable(); // Campo para el ID de usuario
            $table->foreign('creado_por')->references('id')->on('usuarios'); // Clave foránea para creado_por
            $table->foreign('actualizado_por')->references('id')->on('usuarios'); // Clave foránea para actualizado_por
            $table->timestamps();
        });

        // Insertar datos iniciales
        DB::table('tipos_concepto')->insert([
            ['tipo_concepto' => 'Pago','creado_por'=>1],
            ['tipo_concepto' => 'Gasto','creado_por'=>1]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipos_concepto');
    }
}
