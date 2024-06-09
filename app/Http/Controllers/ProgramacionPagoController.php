<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\Propietario;
use App\Models\Concepto;
use App\Models\ProgramacionPago;
use App\Models\ProgramacionPagoDetalle;
use App\Models\SubPropietario;
use App\Traits\RecordsAudit;

class ProgramacionPagoController extends Controller
{
    use RecordsAudit;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function programacion_index()
    {
        $page_title = 'Programacion';
        $page_description = 'Some description for the page';
		
		$action = __FUNCTION__;

        $idTorre = env('ID_TORRE_SISTEMA', 6); 
        // Obtener los IDs de los propietarios que ya tienen subpropietarios
        $idsPropietariosConSubPropietarios = SubPropietario::pluck('sub_propietario_id')->toArray();

        $propietarios = Propietario::with('torre')->where('id_torre', $idTorre)
            ->whereNotIn('id', $idsPropietariosConSubPropietarios)
            ->get();
        //$propietarios = Propietario::with('torre')->get(); 
        $conceptos = Concepto::with('nombreMes')->where('id_tipo_concepto','=','1')->where('activo','=','1')->get();
        //dd($conceptos);
        return view('programacion.index', compact('propietarios', 'conceptos', 'page_title', 'page_description','action'));
    }

    public function getData(Request $request)
    {
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
            //->get();
            // Aplicar filtros si existen
            if ($request->filled('concepto')) {
                $query->where('conceptos.id', $request->concepto);
            }
            if ($request->filled('propietario')) {
                $query->where('propietarios.id', $request->propietario);
            }

        $programaciones = $query->get();
        return DataTables::of($programaciones)
            ->addColumn('propietario', function($row) {
                return $row->departamento;
            })
            ->addColumn('concepto', function($row) {
                $anios="";
                if ($row->anio != 0) {
                    $anios=$row->anio;
                }
                return $row->descripcion_concepto . ' ' . $row->nombremes . ' ' . $anios;
            })
            ->addColumn('total', function($row) {
                return number_format($row->total, 2);
            })
            ->addColumn('created_at', function($row) {
                return \Carbon\Carbon::parse($row->created_at)->format('d/m/Y');
            })
            ->addColumn('estado', function($row) {
                if($row->idestado===1){
                    return '<span class="badge badge-rounded badge-outline-warning">'.$row->estado.'</span>';
                }else if($row->idestado===2){
                    return '<span class="badge badge-rounded badge-outline-info">'.$row->estado.'</span>';
                }else if($row->idestado===3){
                    return '<span class="badge badge-rounded badge-outline-success">'.$row->estado.'</span>';
                }
            })
            ->addColumn('acciones', function($row) {
                if($row->idestado===1){
                    return '
                    <div class="dropdown">
                        <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                            <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item btnEditarProgramacion" data-id="' . $row->id . '">Editar</a>
                            <a class="dropdown-item btnEliminarProgramacion text-danger" data-id="' . $row->id . '">Eliminar</a>
                        </div>
                    </div>';
                }
            })
            ->rawColumns(['estado', 'acciones'])
            ->make(true);
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
    public function storeProgramacion(Request $request)
    {
        $data = $request->all();
        $fechaInicio = Carbon::now();
        $fechaFin = Carbon::now()->endOfYear();
        $monto = (float) str_replace(['S/', ','], ['', ''], $data['txtMonto']);
        $creadoPor = Auth::id();

        $codprogramacion = $data['id_programacion'];
        
        if ($codprogramacion) {
            $programacionPago = ProgramacionPago::findOrFail($codprogramacion);
            $programacionPago->total = $monto;
            $programacionPago->actualizado_por = auth()->id();
            $programacionPago->save();

            $propietarioupd = ProgramacionPagoDetalle::where('id_programacion', $codprogramacion)->firstOrFail();
            //$propietarioupd->id_concepto = $data['cbxConcepto'];
            $propietarioupd->monto = $monto;
            $propietarioupd->actualizado_por = auth()->id(); // Ajustar según sea necesario
            $propietarioupd->save();
            $this->recordAudit('Nuevo', 'Programación creado: ' . $programacionPago->id);
            return response()->json(['success' => 'Programación de pago actualizado correctamente.']);
        }else{
            if (isset($data['chkGrupal']) && $data['chkGrupal'] === 'on') {
                // Obtener todos los propietarios (ID del 1 al 120)
                $propietarios = Propietario::whereBetween('id', [1, 120])->get();
    
                foreach ($propietarios as $propietario) {
                    $programacionPago = ProgramacionPago::create([
                        'id_propietario' => $propietario->id,
                        'fecha_inicio' => $fechaInicio,
                        'fecha_fin' => $fechaFin,
                        'total' => $monto,
                        'creado_por' => $creadoPor,
                    ]);
    
                    ProgramacionPagoDetalle::create([
                        'id_programacion' => $programacionPago->id,
                        'descripcion' => 'Detalle del pago', // Puedes ajustar la descripción según sea necesario
                        'id_concepto' => $data['cbxConcepto'],
                        'monto' => $monto,
                        'creado_por' => $creadoPor,
                    ]);
                    $this->recordAudit('Nuevo', 'Programación creado: ' . $programacionPago->id);
                }
            } else {
                // Logica para un solo propietario
                $programacionPago = ProgramacionPago::create([
                    'id_propietario' => $data['cbxPropietario'],
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin,
                    'total' => $monto,
                    'creado_por' => $creadoPor,
                ]);
    
                ProgramacionPagoDetalle::create([
                    'id_programacion' => $programacionPago->id,
                    'descripcion' => 'Detalle del pago',
                    'id_concepto' => $data['cbxConcepto'],
                    'monto' => $monto,
                    'creado_por' => $creadoPor,
                ]);
                $this->recordAudit('Nuevo', 'Programación creado: ' . $programacionPago->id);
            }
            return response()->json(['success' => 'Programación de pago creada exitosamente']);  
        }
              
    }

    /**
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $programaciones = ProgramacionPago::findOrFail($id);
        $programacionesdet = ProgramacionPagoDetalle::where('id_programacion', $id)->firstOrFail();
        return response()->json(['programaciones' => $programaciones, 'programacionesdet' => $programacionesdet]);
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
        try {
            // Iniciar una transacción
            DB::beginTransaction();
            
            $programaciones = ProgramacionPago::findOrFail($id);
            $programacionesdet = ProgramacionPagoDetalle::where('id_programacion', $id)->firstOrFail();

            $programaciones->activo=0;
            $programacionesdet->activo=0;

            $programaciones->save();
            $programacionesdet->save();    
            $this->recordAudit('Eliminado', 'Programacion eliminado: ' . $programaciones->id);
            // Confirmar la transacción
            DB::commit();
    
            return response()->json(['success' => 'Programacion Pago eliminado correctamente.']); 
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
    
            return response()->json(['error' => 'Error al eliminar el Programacion Pago. ', 'message' => $e->getMessage()], 500);
        }

          
    }
}
