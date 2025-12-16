<?php

namespace App\Http\Controllers;

use App\Models\Concepto;
use App\Models\EstadoPago;
use App\Models\Ingreso;
use App\Models\Gasto;
use App\Models\Acumulador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanzasController extends Controller
{
    public function index()
    {
        $page_title = 'Finanzas';
        $page_description = 'Reporte Financiero';
        $logo = "images/logo.png";
        $logoText = "images/logo-text.png";
        $action = __FUNCTION__;
        $current_year = now()->year;

        // Fetch Ingresos
        $query = Ingreso::with('detalles.concepto')->select(
            'ingresos.id',
            'ingresos.fecha',
            'ingresos.total',
            'ingresos.created_at',
            'creador.nombres_completos as creado_por_nombre',
        )
        ->join('usuarios as creador', 'ingresos.creado_por', '=', 'creador.id')
        ->where('ingresos.activo', '=', 1);
        $ingresos = $query->get();

        // Fetch Gastos
        $querygastos = Gasto::with('detalles.concepto')->select(
            'gastos.id',
            'gastos.fecha',
            'gastos.total',
            'gastos.created_at',
            'creador.nombres_completos as creado_por_nombre',
        )
        ->join('usuarios as creador', 'gastos.creado_por', '=', 'creador.id')
        ->where('gastos.activo', '=', 1);
        $gastos = $querygastos->get();

        $totalIngresos = DB::table('ingresos')->where('activo', 1)->sum('total');
        $saldoFinalInteres = DB::table('intereses_bancarios')->where('estado', 1)->value('saldo_final') ?? 0;

        // Obtener acumuladores
        $acumuladores = Acumulador::whereIn('id', [2, 3, 6])->pluck('monto', 'id');
        $totales_ingresos = $acumuladores[2] ?? 0;
        $totales_egresos = $acumuladores[3] ?? 0;
        $totalPagos = $acumuladores[6] ?? 0;
        
        $totales_saldo = $totales_ingresos - $totales_egresos;

        return view('finanzas.index', compact(
            'page_title', 'page_description','action','logo','logoText',
            'current_year','ingresos','gastos','totales_ingresos','totales_egresos','totales_saldo',
            'totalPagos','totalIngresos','saldoFinalInteres'
        ));
    }

    public function actualizarTotales(Request $request) {
         // Re-implement logic if needed, or remove if handled elsewhere
         // For now, keeping the logic simplified
         return response()->json(['message' => 'Deprecated']);
    }
}
