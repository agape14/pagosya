<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTiposSubPropietariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos_sub_propietarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tipo')->unique();
            $table->timestamps();
        });

        // Insertar los tipos de subpropietarios
        DB::table('tipos_sub_propietarios')->insert([
            ['tipo' => 'Esposo'],
            ['tipo' => 'Esposa'],
            ['tipo' => 'Hijo'],
            ['tipo' => 'Hija'],
            ['tipo' => 'Inquilino'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipos_sub_propietarios');
    }
}
