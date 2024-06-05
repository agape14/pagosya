<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermisosUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permisos_usuarios', function (Blueprint $table) {
            //$table->integer('id_usuario');
            //$table->integer('id_permiso');
            $table->foreignId('id_usuario')->constrained('usuarios');
            $table->foreignId('id_permiso')->constrained('permisos');
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
        Schema::dropIfExists('permisos_usuarios');
    }
}
