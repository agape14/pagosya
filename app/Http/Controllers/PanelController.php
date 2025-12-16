<?php

namespace App\Http\Controllers;

use App\Models\Concepto;
use App\Models\Pago;
use App\Models\PagoDetalle;
use App\Models\Propietario;
use App\Models\Acumulador;
use App\Models\ProgramacionPago;
use App\Models\Ingreso;
use App\Models\Gasto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{
    public function panel_index()
    {
        $page_title = 'Panel de Control';
        $page_description = 'Some description for the page';
        $logo = "images/logo.png";
        $logoText = "images/logo-text.png";
        $action = __FUNCTION__;
        $current_year = now()->year;

        $conceptos = Concepto::with('nombreMes')->where('id_tipo_concepto','=','1')->where('activo','=','1')->get();
        $user = auth()->user();
        $propietario_user = Propietario::where('id_usuario', $user->id)->first();
        $contdeuda = 0;

        $query = ProgramacionPago::select(
            'programacion_pagos.id',
            'propietarios.departamento',
            'propietarios.nombre',
            'propietarios.apellido',
            'conceptos.descripcion_concepto',
            'meses.nombremes',
            'programacion_pagos.total',
            'programacion_pagos.created_at',
            'conceptos.anio',
            'estados_pagos.nombre  as estado',
            'estados_pagos.id as idestado',
        )
        ->join('propietarios', 'programacion_pagos.id_propietario', '=', 'propietarios.id')
        ->join('programacion_pagos_detalle', 'programacion_pagos.id', '=', 'programacion_pagos_detalle.id_programacion')
        ->join('conceptos', 'programacion_pagos_detalle.id_concepto', '=', 'conceptos.id')
        ->leftJoin('meses', 'conceptos.mes', '=', 'meses.mes')
        ->join('estados_pagos', 'estados_pagos.id', '=', 'programacion_pagos.estado_id')
        ->where('programacion_pagos.activo', '=', 1)
        ->where('conceptos.id_tipo_concepto', '=', 1)
        ->where('conceptos.activo', '=', 1);

        if($propietario_user ){
            $query->where('propietarios.id',$propietario_user->id);
        }else{
            if($user->id_perfil == 3) { // Fallback if no propietario linked but role is resident
                 $query->where('propietarios.id', 0); // Force empty
            }
        }

        $queryContador = clone $query;
        $queryContador->whereIn('estados_pagos.id', [1, 2, 4, 5]);
        $contdeuda = $queryContador->count();

        $query->whereIn('estados_pagos.id', [1,2,3,4,5])->orderBy('programacion_pagos.created_at', 'desc');
        $detdeudas = $query->get();

        $detdeudas_con_observacion = collect();

        foreach ($detdeudas as $deuda) {
            $pago = Pago::where('id_programacion', $deuda->id)->first();
            $observacion = null;
            $idpago=null;
            if ($pago) {
                $idpago=$pago->id;
                $pago_detalle_obs = PagoDetalle::where('id_pago', $pago->id)->pluck('observacion');
                if ($pago_detalle_obs->isNotEmpty()) {
                    $observacion = $pago_detalle_obs->implode('/ ');
                }
            }
            $deuda->observacion = $observacion;
            $deuda->idpago = $idpago;
            $detdeudas_con_observacion->push($deuda);
        }

        // --- Live Financial Summary Calculation ---
        $pagosprop = DB::table('pagos')->where('estado_id', 3)->sum('total');
        $ingresos = DB::table('ingresos')->where('activo', 1)->sum('total');
        $egresos = DB::table('gastos')->where('activo', 1)->sum('total');
        $int_bancario = DB::table('intereses_bancarios')->where('estado', 1)->value('saldo_final') ?? 0;

        $total_pagos_prop = $pagosprop;
        $total_ingresos_extra = $ingresos + $int_bancario; // Usually interes bancario counts as extra income
        $total_egresos = $egresos;
        $saldo_general = ($total_pagos_prop + $total_ingresos_extra) - $total_egresos;

        return view('panel.index', compact(
            'page_title',
            'page_description',
            'action',
            'logo',
            'logoText',
            'current_year',
            'conceptos',
            'contdeuda',
            'detdeudas',
            'detdeudas_con_observacion',
            'total_pagos_prop',
            'total_ingresos_extra',
            'total_egresos',
            'saldo_general'
        ));
    }

    public function obtenerDatosPorConcepto(Request $request)
    {
        $idConcepto = $request->idConcepto;

        if (!$idConcepto) {
            return response()->json([
                'error' => 'ID de concepto no proporcionado'
            ], 400);
        }

        // 1. Get ALL payments for this Concept PRIMERO (usando exactamente la misma lógica que PagoController)
        // Consultar pagos activos con estado_id = 3 (pagado) para este concepto específico
        // NO filtrar por propietarios aquí, obtener TODOS los pagos del concepto
        $pagos = Pago::select(
                'pagos.id',
                'pagos.id_propietario',
                'pagos.estado_id',
                'pagos.total'
            )
            ->join('pagos_detalle', 'pagos.id', '=', 'pagos_detalle.id_pago')
            ->join('conceptos', 'pagos_detalle.id_concepto', '=', 'conceptos.id')
            ->where('pagos.activo', '=', 1)
            ->where('pagos.estado_id', '=', 3) // Solo pagos confirmados (pagado)
            ->where('conceptos.id', '=', $idConcepto) // Usar conceptos.id como en PagoController
            ->where('conceptos.id_tipo_concepto', '=', 1)
            ->where('conceptos.activo', '=', 1)
            ->get()
            ->unique('id')
            ->values();

        // 2. Obtener propietarios que tienen programación para este concepto
        $propietariosIdsProgramacion = ProgramacionPago::select('programacion_pagos.id_propietario')
            ->join('programacion_pagos_detalle', 'programacion_pagos.id', '=', 'programacion_pagos_detalle.id_programacion')
            ->where('programacion_pagos.activo', 1)
            ->where('programacion_pagos_detalle.id_concepto', $idConcepto)
            ->distinct()
            ->pluck('id_propietario');

        // 3. Obtener IDs de propietarios que tienen pagos
        $propietariosIdsConPagos = $pagos->pluck('id_propietario')->unique();

        // 4. Combinar ambos: propietarios de programación + propietarios con pagos
        $todosPropietariosIds = $propietariosIdsProgramacion->merge($propietariosIdsConPagos)->unique();

        // 5. Obtener todos los propietarios (de programación + con pagos)
        if ($todosPropietariosIds->isEmpty()) {
            $propietarios = collect();
        } else {
            $propietarios = Propietario::whereIn('id', $todosPropietariosIds)
                ->orderBy('departamento', 'asc')
                ->get();
        }

        // 6. Group by Floor and convert to array format for JSON
        // El cálculo del piso: floor(departamento / 100)
        // Ejemplo: 1402 -> floor(1402/100) = 14, 1305 -> floor(1305/100) = 13
        $propietariosPorPiso = $propietarios->groupBy(function ($item) {
            return (string)floor($item->departamento / 100);
        })->map(function ($group) {
            return $group->map(function ($propietario) {
                return [
                    'id' => $propietario->id,
                    'departamento' => $propietario->departamento,
                    'nombre' => $propietario->nombre,
                    'apellido' => $propietario->apellido ?? ''
                ];
            })->values();
        });

        // Convert to array ensuring proper JSON serialization
        $propietariosPorPisoArray = [];
        foreach ($propietariosPorPiso as $piso => $propietariosGroup) {
            $propietariosPorPisoArray[$piso] = $propietariosGroup->toArray();
        }

        // Count Paid vs Due
        $totalPropietarios = $propietarios->count();
        // Contar propietarios únicos que tienen pagos confirmados para este concepto
        $pagados = $pagos->unique('id_propietario')->count();
        $deben = $totalPropietarios - $pagados;

        // Calculate Totals for this Concept (Filtered) - Solo pagos confirmados
        // Asegurar que total sea numérico
        $total_pagos_prop = $pagos->sum(function($pago) {
            return (float)$pago->total;
        });

        // Calculate other incomes related to this concept
        $total_ingresos_extra = DB::table('ingresos_detalle')
            ->join('ingresos', 'ingresos_detalle.id_ingreso', '=', 'ingresos.id')
            ->where('ingresos_detalle.id_concepto', $idConcepto)
            ->where('ingresos.activo', 1)
            ->sum('ingresos_detalle.monto');

        // Calculate expenses related to this concept
        $total_egresos = DB::table('gastos_detalle')
            ->join('gastos', 'gastos_detalle.id_gasto', '=', 'gastos.id')
            ->where('gastos_detalle.id_concepto', $idConcepto)
            ->where('gastos.activo', 1)
            ->sum('gastos_detalle.monto');

        // Calculate interest for this concept period (if applicable)
        $concepto = Concepto::find($idConcepto);
        $int_bancario = 0;
        if ($concepto) {
            $int_bancario = DB::table('intereses_bancarios')
                ->where('estado', 1)
                ->where('anio', $concepto->anio ?? now()->year)
                ->where('mes', $concepto->mes ?? now()->month)
                ->sum('saldo_final') ?? 0;
        }

        $total_ingresos_extra += $int_bancario;
        $saldo_general = ($total_pagos_prop + $total_ingresos_extra) - $total_egresos;

        $porcentajePagados = $totalPropietarios > 0 ? round(($pagados / $totalPropietarios) * 100, 2) : 0;
        $porcentajeDeben = $totalPropietarios > 0 ? round(($deben / $totalPropietarios) * 100, 2) : 0;

        return response()->json([
            'propietariosPorPiso' => $propietariosPorPisoArray,
            'pagos' => $pagos->map(function ($pago) {
                return [
                    'id' => $pago->id,
                    'id_propietario' => $pago->id_propietario,
                    'estado_id' => (int)$pago->estado_id,
                    'total' => (float)$pago->total
                ];
            })->values()->toArray(),
            'porcentajePagados' => $porcentajePagados,
            'porcentajeDeben' => $porcentajeDeben,
            'total_pagos_prop' => $total_pagos_prop,
            'total_ingresos_extra' => $total_ingresos_extra,
            'total_egresos' => $total_egresos,
            'saldo_general' => $saldo_general
        ]);
    }
}
