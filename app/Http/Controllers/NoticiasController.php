<?php

namespace App\Http\Controllers;

use App\Models\Concepto;
use App\Models\EstadoPago;
use App\Models\Ingreso;
use App\Models\IngresoDetalle;
use App\Models\Gasto;
use App\Models\GastoDetalle;
use App\Models\Mes;
use App\Models\Propietario;
use App\Models\SubPropietario;
use App\Traits\RecordsAudit;
use App\Models\Acumulador;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class NoticiasController extends Controller
{
    public function noticias_index()
    {
        $page_title = 'Panel de Control';
        $page_description = 'Some description for the page';
        $logo = "images/logo.png";
        $logoText = "images/logo-text.png";
        $action = __FUNCTION__;
        $current_year = now()->year;
        $query = Ingreso::with('detalles.concepto')->select(
            'ingresos.id',
            'ingresos.fecha',
            'ingresos.total',
            'ingresos.created_at',
            'creador.nombres_completos as creado_por_nombre',
        )
        ->join('usuarios as creador', 'ingresos.creado_por', '=', 'creador.id')
        ->leftJoin('ingresos_detalle', 'ingresos.id', '=', 'ingresos_detalle.id_ingreso')
        ->leftJoin('conceptos', 'ingresos_detalle.id_concepto', '=', 'conceptos.id')
        ->where('ingresos.activo', '=', 1);
        $ingresos = $query->groupBy('ingresos.id', 'ingresos.fecha', 'ingresos.total', 'ingresos.created_at', 'creador.nombres_completos')->get();

        $querygastos = Gasto::with('detalles.concepto')->select(
            'gastos.id',
            'gastos.fecha',
            'gastos.total',
            'gastos.created_at',
            'creador.nombres_completos as creado_por_nombre',
        )
        ->join('usuarios as creador', 'gastos.creado_por', '=', 'creador.id')
        ->leftJoin('gastos_detalle', 'gastos.id', '=', 'gastos_detalle.id_gasto')
        ->leftJoin('conceptos', 'gastos_detalle.id_concepto', '=', 'conceptos.id')
        ->where('gastos.activo', '=', 1);
        $gastos = $querygastos->groupBy('gastos.id', 'gastos.fecha', 'gastos.total', 'gastos.created_at', 'creador.nombres_completos')->get();

        // Calcular totales de ingresos
        $totalPagos = DB::table('pagos')
            ->where('estado_id', 3)
            ->sum('total');

        $totalIngresos = DB::table('ingresos')
            ->where('activo', 1)
            ->sum('total');

        $saldoFinalInteres = DB::table('intereses_bancarios')
            ->where('estado', 1)
            ->value('saldo_final') ?? 0;

        // Obtener acumuladores
        $acumuladores = Acumulador::whereIn('id', [2, 3])->pluck('monto', 'id');
        $totales_ingresos = $acumuladores[2] ?? 0;
        $totales_egresos = $acumuladores[3] ?? 0;

        // Calcular el saldo
        $totales_saldo = $totales_ingresos - $totales_egresos;
        return view('noticias.index', compact('page_title', 'page_description','action','logo','logoText',
        'current_year','ingresos','gastos','totales_ingresos','totales_egresos','totales_saldo',
        'totalPagos','totalIngresos','saldoFinalInteres'
        ));
    }

    public function actualizarTotales(Request $request)
    {
        try {
            // Calcular totales para ingresos
            $totalPagos = DB::table('pagos')
                ->where('estado_id', 3)
                ->sum('total');

            $totalIngresos = DB::table('ingresos')
                ->where('activo', 1)
                ->sum('total');

            $saldoFinalInteres = DB::table('intereses_bancarios')
                ->where('estado', 1)
                ->value('saldo_final'); // Obtiene un solo registro

            $montoIngresos = $totalPagos + $totalIngresos + ($saldoFinalInteres ?? 0);

            // Calcular totales para egresos
            $montoEgresos = DB::table('gastos')
                ->where('activo', 1)
                ->sum('total');

            // Actualizar los registros en la tabla acumuladores
            $ingresos = Acumulador::where('id', 2)->first();
            $egresos = Acumulador::where('id', 3)->first();

            if ($ingresos && $egresos) {
                $ingresos->monto = $montoIngresos;
                $egresos->monto = $montoEgresos;

                $ingresos->save();
                $egresos->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Totales actualizados correctamente.',
                    'data' => [
                        'ingresos' => $ingresos,
                        'egresos' => $egresos
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No se encontraron registros de ingresos o egresos.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al actualizar los totales.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
