<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInteresesBancariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         // Crear la tabla bancos
         Schema::create('bancos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // Nombre del banco
            $table->timestamps(); // created_at y updated_at
        });

        // Insertar bancos predefinidos
        DB::table('bancos')->insert([
            ['nombre' => 'MIBANCO', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'BANCO DE CREDITO', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'SCOTIABANK', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'BBVA', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'CITIBANK', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'BANBIF', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'NACION', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'PICHINCHA', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'INTERBANK', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'GNB', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'SANTANDER', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'ALFIN', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'ICBC', 'created_at' => now(), 'updated_at' => now()],
        ]);

        Schema::create('intereses_bancarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable(); // Nombre o descripción
            $table->decimal('monto', 15, 2)->nullable(); // Monto del interés
            $table->unsignedTinyInteger('mes'); // Mes (1-12)
            $table->unsignedSmallInteger('anio'); // Año (e.g., 2024)
            $table->decimal('tasa_interes', 5, 2)->nullable(); // Tasa de interés
            $table->decimal('saldo_inicial', 15, 2)->nullable(); // Saldo inicial
            $table->decimal('saldo_final', 15, 2)->nullable(); // Saldo final
            $table->unsignedBigInteger('banco_id'); // Relación con bancos
            $table->unsignedBigInteger('creado_por')->nullable(); // Usuario que creó el registro
            $table->boolean('estado')->default(true); // Estado activo por defecto
            $table->timestamps(); // created_at y updated_at

            // Llave foránea para bancos
            $table->foreign('banco_id')->references('id')->on('bancos')->onDelete('cascade');

            // Llave foránea para creado_por (usuarios)
            $table->foreign('creado_por')->references('id')->on('usuarios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intereses_bancarios');
        Schema::dropIfExists('bancos');
    }
}
