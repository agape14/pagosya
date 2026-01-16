<?php

namespace App\Http\Controllers;

use App\Models\Concepto;
use App\Models\EstadoPago;
use App\Models\Ingreso;
use App\Models\Gasto;
use App\Models\Acumulador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FinanzasController extends Controller
{
    public function index()
    {
        $page_title = 'Finanzas';
        $page_description = 'Reporte Financiero';
        $logo = "images/logo.png";
        $logoText = "images/logo-text.png";
        $action = 'finanzas_index';
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
        try {
            Log::info('Iniciando actualización de totales en acumuladores', [
                'usuario_id' => auth()->id(),
                'usuario' => auth()->user()->nombres_completos ?? 'N/A',
                'ip' => $request->ip()
            ]);

            // Calcular total de pagos confirmados (estado_id = 3)
            $totalPagos = DB::table('pagos')
                ->where('estado_id', 3)
                ->where('activo', 1)
                ->sum('total');

            Log::info('Total de pagos calculado', [
                'total_pagos' => $totalPagos,
                'estado_id' => 3
            ]);

            // Calcular total de ingresos activos
            $totalIngresos = DB::table('ingresos')
                ->where('activo', 1)
                ->sum('total');

            Log::info('Total de ingresos calculado', [
                'total_ingresos' => $totalIngresos
            ]);

            // Calcular total de egresos activos
            $totalEgresos = DB::table('gastos')
                ->where('activo', 1)
                ->sum('total');

            Log::info('Total de egresos calculado', [
                'total_egresos' => $totalEgresos
            ]);

            // Actualizar acumulador de ingresos (ID 2)
            $acumuladorIngresos = Acumulador::find(2);
            if ($acumuladorIngresos) {
                $montoAnteriorIngresos = $acumuladorIngresos->monto;
                $acumuladorIngresos->monto = $totalIngresos;
                $acumuladorIngresos->save();
                Log::info('Acumulador de ingresos actualizado', [
                    'acumulador_id' => 2,
                    'monto_anterior' => $montoAnteriorIngresos,
                    'monto_nuevo' => $totalIngresos
                ]);
            } else {
                Log::warning('Acumulador de ingresos (ID 2) no encontrado');
            }

            // Actualizar acumulador de egresos (ID 3)
            $acumuladorEgresos = Acumulador::find(3);
            if ($acumuladorEgresos) {
                $montoAnteriorEgresos = $acumuladorEgresos->monto;
                $acumuladorEgresos->monto = $totalEgresos;
                $acumuladorEgresos->save();
                Log::info('Acumulador de egresos actualizado', [
                    'acumulador_id' => 3,
                    'monto_anterior' => $montoAnteriorEgresos,
                    'monto_nuevo' => $totalEgresos
                ]);
            } else {
                Log::warning('Acumulador de egresos (ID 3) no encontrado');
            }

            // Actualizar acumulador de pagos (ID 6)
            $acumuladorPagos = Acumulador::find(6);
            if ($acumuladorPagos) {
                $montoAnteriorPagos = $acumuladorPagos->monto;
                $acumuladorPagos->monto = $totalPagos;
                $acumuladorPagos->save();
                Log::info('Acumulador de pagos actualizado', [
                    'acumulador_id' => 6,
                    'monto_anterior' => $montoAnteriorPagos,
                    'monto_nuevo' => $totalPagos
                ]);
            } else {
                Log::warning('Acumulador de pagos (ID 6) no encontrado');
            }

            $saldo = $totalIngresos - $totalEgresos;

            Log::info('Actualización de totales completada exitosamente', [
                'total_ingresos' => $totalIngresos,
                'total_egresos' => $totalEgresos,
                'total_pagos' => $totalPagos,
                'saldo' => $saldo
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Totales actualizados correctamente. Ingresos: S/ ' . number_format($totalIngresos, 2) . ', Egresos: S/ ' . number_format($totalEgresos, 2) . ', Pagos: S/ ' . number_format($totalPagos, 2) . ', Saldo: S/ ' . number_format($saldo, 2),
                'data' => [
                    'ingresos' => $totalIngresos,
                    'egresos' => $totalEgresos,
                    'pagos' => $totalPagos,
                    'saldo' => $saldo
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error al actualizar totales en acumuladores', [
                'usuario_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar los totales: ' . $e->getMessage()
            ], 500);
        }
    }
}
