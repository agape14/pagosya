<?php

namespace App\Http\Controllers;

use App\Models\Concepto;
use App\Models\Pago;
use App\Models\PagoDetalle;
use App\Models\Propietario;
use App\Models\ProgramacionPago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PanelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
            $query->where('propietarios.id',2);
        }

        $queryContador = clone $query;
        $queryContador->whereIn('estados_pagos.id', [1, 2, 4, 5]);
        $contdeuda = $queryContador->count();

        $query->whereIn('estados_pagos.id', [1,2,3,4,5]);
        $detdeudas = $query->get();

        $detdeudas_con_observacion = collect();

        foreach ($detdeudas as $deuda) {
            $pago = Pago::where('id_programacion', $deuda->id)->first();
            $observacion = null;

            if ($pago) {
                $pago_detalle_obs = PagoDetalle::where('id_pago', $pago->id)->pluck('observacion');
                if ($pago_detalle_obs->isNotEmpty()) {
                    $observacion = $pago_detalle_obs->implode('/ ');
                }
            }
            $deuda->observacion = $observacion;
            $detdeudas_con_observacion->push($deuda);
        }

        return view('panel.index', compact('page_title', 'page_description','action','logo','logoText', 'current_year','conceptos','contdeuda','detdeudas','detdeudas_con_observacion'));
    }

    public function obtenerDatosPorConcepto(Request $request)
    {
        $idConcepto = $request->idConcepto;

        // Obtener los primeros 120 registros
        $propietarios = Propietario::orderBy('id', 'asc')->take(120)->get();

        // Agrupar los propietarios por piso
        $propietariosPorPiso = $propietarios->groupBy(function ($item) {
            return floor($item->departamento / 100);
        });

        $pagos = Pago::whereIn('id_propietario', $propietarios->pluck('id'))
        ->with(['detalles' => function ($query) use ($idConcepto) {
            $query->where('id_concepto', $idConcepto);
        }])
        ->get();

        // Calcular el porcentaje de propietarios que han pagado y los que deben
        $totalPropietarios = $propietarios->count();
        //$pagados = $pagos->where('estado_id', 3)->count();
         // Filtrar los pagos donde los detalles tengan el idConcepto y el estado_id sea 3
        $pagados = $pagos->filter(function ($pago) use ($idConcepto) {
            return $pago->estado_id == 3 && $pago->detalles->contains('id_concepto', $idConcepto);
        })->count();
        $deben = $totalPropietarios - $pagados;

        // Calcular los porcentajes
        //$porcentajePagados = round(($pagados / $totalPropietarios) * 100, 2);
        //$porcentajeDeben = round(($deben / $totalPropietarios) * 100, 2);

        $porcentajePagados = $totalPropietarios > 0 ? round(($pagados / $totalPropietarios) * 100, 2) : 0;
        $porcentajeDeben = $totalPropietarios > 0 ? round(($deben / $totalPropietarios) * 100, 2) : 0;
        return response()->json([
            'propietariosPorPiso' => $propietariosPorPiso,
            'pagos' => $pagos,
            'porcentajePagados' => $porcentajePagados,
            'porcentajeDeben' => $porcentajeDeben
        ]);
    }

    public function obtenerDatosPorcentajeConcepto(Request $request)
    {
        $idConcepto = $request->idConcepto;

        // Obtener los primeros 120 registros
        $propietarios = Propietario::orderBy('id', 'asc')->take(120)->get();

        // Agrupar los propietarios por piso
        $propietariosPorPiso = $propietarios->groupBy(function ($item) {
            return floor($item->departamento / 100);
        });

        // Obtener los pagos para el concepto dado
        $pagos = Pago::whereIn('id_propietario', $propietarios->pluck('id'))
            ->with(['detalles' => function ($query) use ($idConcepto) {
                $query->where('id_concepto', $idConcepto);
            }])
            ->get();

        // Calcular el porcentaje de propietarios que han pagado y los que deben
        $totalPropietarios = $propietarios->count();
        $pagados = $pagos->where('estado_id', 3)->count();
        $deben = $totalPropietarios - $pagados;

        // Calcular los porcentajes
        $porcentajePagados = ($pagados / $totalPropietarios) * 100;
        $porcentajeDeben = ($deben / $totalPropietarios) * 100;

        return response()->json([
            'propietariosPorPiso' => $propietariosPorPiso,
            'porcentajePagados' => $porcentajePagados,
            'porcentajeDeben' => $porcentajeDeben
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
