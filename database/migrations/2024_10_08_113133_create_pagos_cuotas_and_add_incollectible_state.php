<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosCuotasAndAddIncollectibleState extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Modificar la tabla 'programacion_pagos' si es necesario (añadir columna 'incobrable')
        if (!Schema::hasColumn('programacion_pagos', 'incobrable')) {
            Schema::table('programacion_pagos', function (Blueprint $table) {
                $table->boolean('incobrable')->default(false)->comment('Marcado como incobrable');
            });
        }

        if (!Schema::hasColumn('pagos', 'incobrable')) {
            Schema::table('pagos', function (Blueprint $table) {
                $table->tinyInteger('cuotas_totales')->default(1)->after('evidencia');
                $table->boolean('incobrable')->default(false)->after('cuotas_totales')->comment('Marcado como incobrable');
            });
        }

        // 2. Modificar la tabla 'pagos_detalle' si es necesario (no hace falta crearla)
        if (!Schema::hasColumn('pagos_detalle', 'monto_pagado')) {
            Schema::table('pagos_detalle', function (Blueprint $table) {
                $table->decimal('monto_pagado', 18, 2)->after('monto')->default(0)->comment('Monto pagado de la cuota');
                $table->tinyInteger('cuotas_pagadas')->default(0)->after('monto_pagado');
            });
        }

        // 3. Insertar nuevo estado 'Pago en Partes','Incobrable' en la tabla de 'estados_pagos'
        $pagoPartes = DB::table('estados_pagos')
            ->where('nombre', 'Pago en Partes')
            ->first();

        $estadoIncobrable = DB::table('estados_pagos')
        ->where('nombre', 'Incobrable')
        ->first();


        if (!$pagoPartes) {
            DB::table('estados_pagos')->insert([
                'nombre' => 'Pago en Partes',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!$estadoIncobrable) {
            DB::table('estados_pagos')->insert([
                'nombre' => 'Incobrable',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         // Revertir los cambios si es necesario

        // Eliminar columna 'incobrable' de 'programacion_pagos' si existe
        if (Schema::hasColumn('programacion_pagos', 'incobrable')) {
            Schema::table('programacion_pagos', function (Blueprint $table) {
                $table->dropColumn('incobrable');
            });
        }

        if (Schema::hasColumn('pagos', 'incobrable')) {
            Schema::table('pagos', function (Blueprint $table) {
                $table->dropColumn('cuotas_totales');
                $table->dropColumn('incobrable');
            });
        }

        // Eliminar columna 'monto_pagado' de 'pagos_detalle' si existe
        if (Schema::hasColumn('pagos_detalle', 'monto_pagado')) {
            Schema::table('pagos_detalle', function (Blueprint $table) {
                $table->dropColumn('monto_pagado');
                $table->dropColumn('cuotas_pagadas');
            });
        }

        // Eliminar el estado 'Incobrable' de la tabla 'estados_pagos'
        DB::table('estados_pagos')->where('nombre', 'Incobrable')->delete();
        DB::table('estados_pagos')->where('nombre', 'Pago en Partes')->delete();
    }
}
