<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConceptosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conceptos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tipo_concepto')->constrained('tipos_concepto');
            $table->string('descripcion_concepto', 100);
            $table->integer('mes');
            $table->integer('anio');
            $table->unsignedBigInteger('creado_por')->nullable(); // Campo para el ID de usuario
            $table->unsignedBigInteger('actualizado_por')->nullable(); // Campo para el ID de usuario
            $table->foreign('creado_por')->references('id')->on('usuarios'); // Clave foránea para creado_por
            $table->foreign('actualizado_por')->references('id')->on('usuarios'); // Clave foránea para actualizado_por
            $table->timestamps();
        });

        // Insertar conceptos
        DB::table('conceptos')->insert([
            [
                'id_tipo_concepto' => 1,
                'descripcion_concepto' => 'Pago de cuota mensual',
                'mes' => 4,
                'anio' => 2023,
                'created_at' => now(),
                'creado_por' => 1,
            ],
            [
                'id_tipo_concepto' => 2,
                'descripcion_concepto' => 'Pago de servicios',
                'mes' => 4,
                'anio' => 2023,
                'created_at' => now(),
                'creado_por' => 1,
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conceptos');
    }
}
