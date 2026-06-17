<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosImportantesArchivosTable extends Migration
{
    public function up()
    {
        Schema::create('documentos_importantes_archivos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('documento_importante_id');
            $table->string('nombre_archivo');
            $table->string('ruta_pdf');
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();

            $table->foreign('documento_importante_id')
                ->references('id')
                ->on('documentos_importantes')
                ->onDelete('cascade');
        });

        if (Schema::hasTable('documentos_importantes')) {
            $documentos = DB::table('documentos_importantes')
                ->whereNotNull('ruta_pdf')
                ->where('ruta_pdf', '!=', '')
                ->get();

            foreach ($documentos as $doc) {
                DB::table('documentos_importantes_archivos')->insert([
                    'documento_importante_id' => $doc->id,
                    'nombre_archivo' => basename($doc->ruta_pdf),
                    'ruta_pdf' => $doc->ruta_pdf,
                    'orden' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('documentos_importantes_archivos');
    }
}
