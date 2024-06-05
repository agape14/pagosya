<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPadreToPermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permisos', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->after('nombre_permiso');
            $table->foreign('parent_id')->references('id')->on('permisos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permisos', function (Blueprint $table) {
            $table->dropForeign(['padre_id']);
            $table->dropColumn('padre_id');
        });
    }
}
