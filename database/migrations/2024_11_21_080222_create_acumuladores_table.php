<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcumuladoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acumuladores', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion')->unique(); // Descripción única
            $table->decimal('monto', 15, 2)->nullable(); // Monto con 2 decimales, puede ser null
            $table->integer('correlativo')->nullable(); // Correlativo, puede ser null
            $table->timestamps();
        });

         // Insertar valores iniciales
         DB::table('acumuladores')->insert([
            ['descripcion' => 'recibos', 'monto' => null, 'correlativo' => 67],
            ['descripcion' => 'ingresos', 'monto' => 2500.36, 'correlativo' => null],
            ['descripcion' => 'egresos', 'monto' => 1785.12, 'correlativo' => null],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acumuladores');
    }
}
