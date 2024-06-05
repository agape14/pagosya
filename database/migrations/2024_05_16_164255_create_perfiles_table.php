<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perfiles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_perfil', 50);
            $table->timestamps();
        });

        // Insertar datos iniciales
        DB::table('perfiles')->insert([
            ['nombre_perfil' => 'Administrador'],
            ['nombre_perfil' => 'Usuario']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perfiles');
    }
}
