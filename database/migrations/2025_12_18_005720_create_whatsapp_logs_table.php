<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('whatsapp_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vecino_id')->nullable();
            $table->string('tipo', 50); // noticia, moroso, recibo, etc.
            $table->text('mensaje');
            $table->string('telefono', 20)->nullable();
            $table->string('status', 20); // enviado, fallido, pendiente
            $table->text('error_message')->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->timestamps();

            $table->index(['vecino_id', 'fecha']);
            $table->index('tipo');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('whatsapp_logs');
    }
};
