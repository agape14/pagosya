<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('usuario', 150);
            $table->string('contrasenia', 150);
            $table->string('nombres_completos', 500);
            $table->string('correo_electronico', 250);
            $table->string('telefono', 12);
            $table->foreignId('id_perfil')->constrained('perfiles');
            $table->timestamps();
        });

        DB::table('usuarios')->insert([
            [
                'usuario' => 'adelacruz',
                'contrasenia' => bcrypt('123456'), // Encripta la contraseña antes de insertarla
                'nombres_completos' => 'Agapito De la cruz',
                'correo_electronico' => 'adelacruzcarlos@gmail.com', // Corrije el error en el correo electrónico
                'telefono' => '981525451',
                'id_perfil' => 1, // Quita las comillas para que el ID sea un entero, no una cadena
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
