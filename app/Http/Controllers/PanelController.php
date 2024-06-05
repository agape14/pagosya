<?php

namespace App\Http\Controllers;

use App\Models\Concepto;
use App\Models\Pago;
use App\Models\PagoDetalle;
use App\Models\Propietario;
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
        return view('panel.index', compact('page_title', 'page_description','action','logo','logoText', 'current_year','conceptos'));
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
        $pagados = $pagos->where('estado_id', 3)->count();
        $deben = $totalPropietarios - $pagados;

        // Calcular los porcentajes
        $porcentajePagados = round(($pagados / $totalPropietarios) * 100, 2);
        $porcentajeDeben = round(($deben / $totalPropietarios) * 100, 2);
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
