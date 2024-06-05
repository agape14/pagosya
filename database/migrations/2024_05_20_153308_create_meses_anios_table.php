<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateMesesAniosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meses', function (Blueprint $table) {
            $table->id();
            $table->integer('mes')->unique();
            $table->string('nombremes');
            $table->timestamps();
        });

        Schema::create('anios', function (Blueprint $table) {
            $table->id();
            $table->integer('anio')->unique();
            $table->timestamps();
        });

        // Insertar los datos iniciales en la tabla meses
        DB::table('meses')->insert([
            ['mes' => 1, 'nombremes' => 'Enero'],
            ['mes' => 2, 'nombremes' => 'Febrero'],
            ['mes' => 3, 'nombremes' => 'Marzo'],
            ['mes' => 4, 'nombremes' => 'Abril'],
            ['mes' => 5, 'nombremes' => 'Mayo'],
            ['mes' => 6, 'nombremes' => 'Junio'],
            ['mes' => 7, 'nombremes' => 'Julio'],
            ['mes' => 8, 'nombremes' => 'Agosto'],
            ['mes' => 9, 'nombremes' => 'Septiembre'],
            ['mes' => 10, 'nombremes' => 'Octubre'],
            ['mes' => 11, 'nombremes' => 'Noviembre'],
            ['mes' => 12, 'nombremes' => 'Diciembre'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meses');
        Schema::dropIfExists('anios');
    }
}
