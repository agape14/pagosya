<?php

namespace App\Http\Controllers;
use App\Models\Concepto;
use App\Models\EstadoPago;
use App\Models\Pago;
use App\Models\PagoDetalle;
use App\Models\ProgramacionPago;
use App\Models\ProgramacionPagoDetalle;
use App\Models\Propietario;
use App\Models\SubPropietario;
use App\Traits\RecordsAudit;
use App\Models\Torre;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
//use Barryvdh\DomPDF\PDF as DomPDF;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client;
use App\Models\JuntaDirectiva;
use App\Models\Acumulador;

class PagoController extends Controller
{
    use RecordsAudit;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pagos_index()
    {
        $page_title = 'Pagos';
        $page_description = 'Some description for the page';

		$action = __FUNCTION__;

        $idTorre = env('ID_TORRE_SISTEMA', 7);
        // Obtener los IDs de los propietarios que ya tienen subpropietarios
        $idsPropietariosConSubPropietarios = SubPropietario::pluck('sub_propietario_id')->toArray();

        $propietarios = Propietario::with('torre')->where('id_torre', $idTorre)
            ->whereNotIn('id', $idsPropietariosConSubPropietarios)
            ->get();
        $estadopagos=EstadoPago::all();
        //$propietarios = Propietario::with('torre')->get();
        $conceptos = Concepto::with('nombreMes')->where('id_tipo_concepto','=','1')->where('activo','=','1')->get();
        //dd($conceptos);
        // Definir las cuotas de 2 a 12
        $cuotas = range(2, 12);
        $pagos=[];
        $cuotas_pagadas=0;
        return view('pagos.index', compact('propietarios', 'conceptos', 'page_title', 'page_description','estadopagos','action', 'cuotas','pagos','cuotas_pagadas'));
    }

    public function gettblPagos(Request $request)
    {
        // Obtener el valor del parámetro 'estado' del request
        //dd($request->input('estado'));
        $estado = $request->input('estado');

        // Si el estado es null o 1, usamos la consulta de ProgramacionPago
        if (is_null($estado) || $estado == 1) {
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
                    'estados_pagos.nombre as estado',
                    'estados_pagos.id as idestado',
                    'pagos.id as pago_id' // Asegúrate de que este campo sea parte de tu consulta
                )
                ->join('propietarios', 'programacion_pagos.id_propietario', '=', 'propietarios.id')
                ->join('programacion_pagos_detalle', 'programacion_pagos.id', '=', 'programacion_pagos_detalle.id_programacion')
                ->join('conceptos', 'programacion_pagos_detalle.id_concepto', '=', 'conceptos.id')
                ->leftJoin('meses', 'conceptos.mes', '=', 'meses.mes')
                ->join('estados_pagos', 'estados_pagos.id', '=', 'programacion_pagos.estado_id')
                ->leftJoin('pagos', 'programacion_pagos.id', '=', 'pagos.id_programacion') // Unir con la tabla pagos
                ->where('programacion_pagos.activo', '=', 1)
                ->where('conceptos.id_tipo_concepto', '=', 1)
                ->where('conceptos.activo', '=', 1);

            // Aplicar filtros si existen
            if ($request->input('concepto')) {
                $query->where('conceptos.id', $request->concepto);
            }
            if ($request->input('propietario')) {
                $query->where('propietarios.id', $request->propietario);
            }
            if ($request->input('fecha')) {
                //$query->where('programacion_pagos.created_at', $request->fecha);
                $fecha = Carbon::createFromFormat('d/m/Y', $request->fecha)->format('Y-m-d');
                $query->whereDate('programacion_pagos.created_at', $fecha);
            }
            if ($estado == 1) {
                $query->where('programacion_pagos.estado_id', $estado);
            }
            //dd($query->toSql());
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
                    }else if($row->idestado===4){
                        return '<span class="badge badge-rounded badge-outline-primary">'.$row->estado.'</span>';
                    }else if($row->idestado===5){
                        return '<span class="badge badge-rounded badge-outline-danger">'.$row->estado.'</span>';
                    }

                })
                ->addColumn('selectgroup', function($row) {
                    return '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheckBox' . $row->id . '" required="">
                        <label class="custom-control-label" for="customCheckBox' . $row->id . '"></label>
                    </div>';
                })
                ->addColumn('acciones', function($row) {
                    $btn = '<div class="d-flex">';
                    if($row->idestado===1){
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" data-idestado="' . $row->idestado . '" class="btn btn-outline-warning shadow btn-sm sharp mr-1 addPago"><i class="fa fa-money fa-2x"></i></a>';
                    }
                    if($row->idestado===2){
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->pago_id  . '" data-idestado="' . $row->idestado . '" class="btn btn-outline-info shadow btn-sm sharp mr-1 verificaPago"><i class="fa fa-check fa-2x"></i></a>';
                    }
                    if($row->idestado===3){
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->pago_id  . '" data-idestado="' . $row->idestado . '" class="btn btn-outline-success shadow btn-sm sharp mr-1 verPdfPago"><i class="fa fa-print fa-2x"></i></a>';
                    }
                    if($row->idestado===4){
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->pago_id . '" data-idestado="' . $row->idestado . '" class="btn btn-outline-primary shadow btn-sm sharp mr-1 addPagoPartes"><i class="fa fa-money fa-2x"></i></a>';
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns([ 'selectgroup','estado', 'acciones'])
                ->make(true);
        }
        // Si el estado es 2 o 3, usamos la consulta de Pago
        else if ($estado == 2 || $estado == 3|| $estado == 4|| $estado == 5) {
            $query = Pago::select(
                    'pagos.id',
                    'propietarios.departamento',
                    'propietarios.nombre',
                    'propietarios.apellido',
                    'conceptos.descripcion_concepto',
                    'meses.nombremes',
                    'pagos.total',
                    'pagos.created_at',
                    'conceptos.anio',
                    'estados_pagos.nombre as estado',
                    'estados_pagos.id as idestado'
                )
                ->join('propietarios', 'pagos.id_propietario', '=', 'propietarios.id')
                ->join('pagos_detalle', 'pagos.id', '=', 'pagos_detalle.id_pago')
                ->join('conceptos', 'pagos_detalle.id_concepto', '=', 'conceptos.id')
                ->join('meses', 'conceptos.mes', '=', 'meses.mes')
                ->join('estados_pagos', 'estados_pagos.id', '=', 'pagos.estado_id')
                ->where('pagos.activo', '=', 1)
                ->where('conceptos.id_tipo_concepto', '=', 1)
                ->where('conceptos.activo', '=', 1);

            // Aplicar filtros si existen
            if ($request->input('concepto')) {
                $query->where('conceptos.id', $request->concepto);
            }
            if ($request->input('propietario')) {
                $query->where('propietarios.id', $request->propietario);
            }
            if ($request->input('fecha')) {
                //$query->where('pagos.created_at', $request->fecha);
                $fecha = Carbon::createFromFormat('d/m/Y', $request->fecha)->format('Y-m-d');
                $query->whereDate('pagos.created_at', $fecha);
            }
            if ($estado) {
                $query->where('pagos.estado_id', $estado);
            }
            $pagos = $query->get();

            return DataTables::of($pagos)
                ->addColumn('propietario', function($row) {
                    return $row->departamento;
                })
                ->addColumn('concepto', function($row) {
                    return $row->descripcion_concepto . ' ' . $row->nombremes . ' ' . $row->anio;
                })
                ->addColumn('total', function($row) {
                    return number_format($row->total, 2);
                })
                ->addColumn('created_at', function($row) {
                    return Carbon::parse($row->created_at)->format('d/m/Y');
                })
                ->addColumn('selectgroup', function($row) {
                    return '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="customCheckBox' . $row->id . '" required="">
                        <label class="custom-control-label" for="customCheckBox' . $row->id . '"></label>
                    </div>';
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
                    $btn = '<div class="d-flex">';
                    if($row->idestado===1){
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" data-idestado="' . $row->idestado . '" class="btn btn-outline-warning shadow btn-sm sharp mr-1 addPago"><i class="fa fa-money fa-2x"></i></a>';
                    }
                    if($row->idestado===2){
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" data-idestado="' . $row->idestado . '" class="btn btn-outline-info shadow btn-sm sharp mr-1 verificaPago"><i class="fa fa-check fa-2x"></i></a>';
                    }
                    if($row->idestado===3){
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" data-idestado="' . $row->idestado . '" class="btn btn-outline-success shadow btn-sm sharp mr-1 verPdfPago"><i class="fa fa-print fa-2x"></i></a>';
                    }
                    if($row->idestado===4){
                        $btn .= '<a href="javascript:void(0)" data-id="' . $row->pago_id . '" data-idestado="' . $row->idestado . '" class="btn btn-outline-primary shadow btn-sm sharp mr-1 addPagoPartes"><i class="fa fa-money fa-2x"></i></a>';
                    }
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns([ 'selectgroup','estado', 'acciones'])
                ->make(true);
        }
        // Si el estado no es válido, devolver una respuesta vacía o un mensaje de error
        else {
            return DataTables::of(collect([]))->make(true);
            return response()->json(['message' => 'Estado no válido'], 400);
        }
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
        $query = Pago::select(
            'pagos.id',
            'propietarios.departamento',
            'propietarios.nombre',
            'propietarios.apellido',
            'conceptos.descripcion_concepto',
            'meses.nombremes',
            'pagos.total',
            'pagos.created_at',
            'conceptos.anio',
            'estados_pagos.nombre as estado',
            'estados_pagos.id as idestado',
            'pagos.evidencia',
        )
        ->join('propietarios', 'pagos.id_propietario', '=', 'propietarios.id')
        ->join('pagos_detalle', 'pagos.id', '=', 'pagos_detalle.id_pago')
        ->join('conceptos', 'pagos_detalle.id_concepto', '=', 'conceptos.id')
        ->join('meses', 'conceptos.mes', '=', 'meses.mes')
        ->join('estados_pagos', 'estados_pagos.id', '=', 'pagos.estado_id')
        ->where('pagos.activo', '=', 1)
        ->where('conceptos.id_tipo_concepto', '=', 1)
        ->where('conceptos.activo', '=', 1);
        $query->where('pagos.id', $id);
        $pagos = $query->get();
        // Procesar cada resultado para agregar la URL completa de la evidencia
        $evidencias = PagoDetalle::where('id_pago', $id)->pluck('evidencia_det');
        $observaciones = PagoDetalle::where('id_pago', $id)->pluck('observacion');
        $pagos->transform(function ($pago) use ($evidencias, $observaciones)  {
            /*$pago->evidencia_url = asset('storage/' . $pago->evidencia);
            return $pago;*/
            // Si tienes URLs concatenadas en un campo, divídelas en un array
            $pago->evidencia_url = $evidencias->map(function ($evidencia) {
                return asset('storage/' . $evidencia);
            });
            $pago->observaciones = $observaciones;
            return $pago;
        });

        return response()->json(['pagos' => $pagos]);
    }

    public function pagoPartes($id)
    {
        $query = Pago::select(
            'pagos.id',
            'propietarios.departamento',
            'propietarios.nombre',
            'propietarios.apellido',
            'conceptos.descripcion_concepto',
            'meses.nombremes',
            'pagos.total',
            'pagos.created_at',
            'conceptos.anio',
            'estados_pagos.nombre as estado',
            'estados_pagos.id as idestado',
            'pagos.evidencia',
            'pagos.cuotas_totales',
            'pagos_detalle.cuotas_pagadas'
        )
        ->join('propietarios', 'pagos.id_propietario', '=', 'propietarios.id')
        ->join('pagos_detalle', 'pagos.id', '=', 'pagos_detalle.id_pago')
        ->join('conceptos', 'pagos_detalle.id_concepto', '=', 'conceptos.id')
        ->join('meses', 'conceptos.mes', '=', 'meses.mes')
        ->join('estados_pagos', 'estados_pagos.id', '=', 'pagos.estado_id')
        ->where('pagos.activo', '=', 1)
        ->where('conceptos.id_tipo_concepto', '=', 1)
        ->where('conceptos.activo', '=', 1);
        $query->where('pagos.id', $id);
        //$sql = $query->toSql();dd($sql);
        $pago = $query->first(); // Solo se obtiene un registro

        // Verificar que el pago exista antes de continuar
        if (!$pago) {
            return response()->json(['error' => 'Pago no encontrado'], 404);
        }
        $verPagosDetAnteriores = PagoDetalle::where('id_pago', $id)->get();
        $totalCuotasPagadas = $verPagosDetAnteriores->sum('cuotas_pagadas');
        $totalMontoPagadas = $verPagosDetAnteriores->sum('monto_pagado');
        $restoApagar=$pago->total-$totalMontoPagadas;
        // Asignar el estado de la cuota basado en cuotas pagadas vs totales
        if ($totalCuotasPagadas == 0) {
            $pago->estado_cuota = "Primera cuota";
        } elseif ($totalCuotasPagadas < $pago->cuotas_totales) {
            $pago->estado_cuota = "Cuota {$totalCuotasPagadas} de {$pago->cuotas_totales}";
        } else {
            $pago->estado_cuota = "Pagado en su totalidad";
        }

        // Añadir la URL completa para la evidencia del pago
        $pago->evidencia_url = $pago->evidencia ? asset('storage/' . $pago->evidencia) : null;

        // Calcular las cuotas faltantes
        $cuotas_faltantes = $pago->cuotas_totales - $totalCuotasPagadas;

        // Devolver la respuesta en formato JSON
        //return response()->json(['pago' => $pago, 'cuotas_faltantes' => $cuotas_faltantes]);
        // Devolver la respuesta en formato JSON number_format($restoApagar, 2)
        return response()->json(['pagos' => $pago,'cuotas_faltantes' => $cuotas_faltantes,'resto_pagar' => number_format($restoApagar, 2)]);
    }

    public function showprogramacion($id)
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
            'estados_pagos.nombre as estado',
            'estados_pagos.id as idestado'
        )
        ->join('propietarios', 'programacion_pagos.id_propietario', '=', 'propietarios.id')
        ->join('programacion_pagos_detalle', 'programacion_pagos.id', '=', 'programacion_pagos_detalle.id_programacion')
        ->join('conceptos', 'programacion_pagos_detalle.id_concepto', '=', 'conceptos.id')
        ->join('meses', 'conceptos.mes', '=', 'meses.mes')
        ->join('estados_pagos', 'estados_pagos.id', '=', 'programacion_pagos.estado_id')
        ->where('programacion_pagos.activo', '=', 1)
        ->where('conceptos.id_tipo_concepto', '=', 1)
        ->where('conceptos.activo', '=', 1);
        $query->where('programacion_pagos.id', $id);
        $pagos = $query->get();
        return response()->json(['pagos' => $pagos,]);
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

    public function guardarEvidencia(Request $request)
    {
        $request->validate([
            'evidencia' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cuotas' => 'nullable|integer|min:2|max:12',
            'icuotasfaltantes' => 'nullable|integer',
        ]);

        DB::beginTransaction();

        try {
            // Manejar la carga de la imagen
            if ($request->hasFile('evidencia')) {
                $image = $request->file('evidencia');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('evidencias', $imageName, 'public');
            }
            $verificaPagosAnteriores = Pago::where('id', $request->id)->first();
            if($verificaPagosAnteriores){
                if(!$request->monto_a_pagar){
                    DB::rollBack();
                    return response()->json(['error' => 'Para realizar pago en partes, debe ingresar el monto a pagar.'], 500);
                }
                $verificaPagosDetAnteriores = PagoDetalle::where('id_pago', $request->id)->get();
                $programacionDets = ProgramacionPagoDetalle::where('id_programacion', $verificaPagosAnteriores->id_programacion)->first();
                //dd($programacionDets->id_concepto);
                // Sumar todas las cuotas pagadas
                $totalCuotasPagadas = $verificaPagosDetAnteriores->sum('cuotas_pagadas');
                $totalMontoPagadas = $verificaPagosDetAnteriores->sum('monto_pagado');
                $restoApagar=$verificaPagosAnteriores->total-$totalMontoPagadas;
                if($request->monto_a_pagar>$restoApagar){
                    DB::rollBack();
                    return response()->json(['error' => 'El monto a pagar no puede ser mayor al restante.'], 500);
                }
                // Calcular las cuotas faltantes
                $cuotas_faltantes = ($verificaPagosAnteriores->cuotas_totales - ($totalCuotasPagadas+1));

                if($cuotas_faltantes==0 ){
                    ProgramacionPago::where('id', $verificaPagosAnteriores->id_programacion)
                    ->update(['estado_id' => 2]);

                    Pago::where('id', $request->id)
                    ->update(['estado_id' => 2]);

                    $pagoDetallePartes = new PagoDetalle();
                    $pagoDetallePartes->id_pago = $request->id;
                    $pagoDetallePartes->id_concepto = $programacionDets->id_concepto;
                    $pagoDetallePartes->monto = $verificaPagosAnteriores->total;
                    $pagoDetallePartes->monto_pagado = $request->monto_a_pagar;
                    $pagoDetallePartes->cuotas_pagadas = 1;
                    $pagoDetallePartes->estado_id = 2;
                    $pagoDetallePartes->evidencia_det = $path;
                    $pagoDetallePartes->observacion = $request->observacion;
                    $pagoDetallePartes->creado_por = auth()->id();
                    $pagoDetallePartes->save();
                }else{
                    ProgramacionPago::where('id', $verificaPagosAnteriores->id_programacion)
                    ->update(['estado_id' => 4]);

                    Pago::where('id', $request->id)
                    ->update(['estado_id' => 4]);

                    $pagoDetallePartes = new PagoDetalle();
                    $pagoDetallePartes->id_pago = $request->id;
                    $pagoDetallePartes->id_concepto = $programacionDets->id_concepto;
                    $pagoDetallePartes->monto = $verificaPagosAnteriores->total;
                    $pagoDetallePartes->monto_pagado = $request->monto_a_pagar;
                    $pagoDetallePartes->cuotas_pagadas = 1;
                    $pagoDetallePartes->estado_id = 4;
                    $pagoDetallePartes->evidencia_det = $path;
                    $pagoDetallePartes->observacion = $request->observacion;
                    $pagoDetallePartes->creado_por = auth()->id();
                    $pagoDetallePartes->save();
                }
                //$cuotas_faltantes = $pago->cuotas_totales - $pago->cuotas_pagadas;
                $this->recordAudit('AddPagos', 'Pago En partes: ' . $request->id);
                DB::commit();

                return response()->json(['success' => 'Voucher guardada correctamente.'], 200);

            }else{
                // Actualización de estados
                $programacionPago = ProgramacionPago::findOrFail($request->id);
                if($request->monto_a_pagar){
                    $estadoNuevo = $request->cuotas ? 4 : 2; // 4 es el estado "PAGO EN PARTES"
                }else{
                    $estadoNuevo =2;
                    $request->monto_a_pagar=$programacionPago->total;
                }

                $programacionPago->estado_id = $estadoNuevo;
                $programacionPago->save();

                ProgramacionPagoDetalle::where('id_programacion', $request->id)
                    ->update(['estado_id' => $estadoNuevo]);

                // Registro en tabla pagos
                $pago = new Pago();
                $pago->id_propietario = $programacionPago->id_propietario;
                $pago->fecha = now();
                $pago->total = $programacionPago->total;
                $pago->cuotas_totales = $request->cuotas ?? 1; // Asignar cuotas totales
                $pago->creado_por = auth()->id();
                $pago->evidencia = $path;
                $pago->estado_id = $estadoNuevo; // "PAGO EN PARTES" o "PAGADO"
                $pago->activo = 1;
                $pago->id_programacion = $programacionPago->id;
                $pago->save();

                // Detalles del pago
                $programacionDetalles = ProgramacionPagoDetalle::where('id_programacion', $request->id)->get();
                foreach ($programacionDetalles as $detalle) {
                    $pagoDetalle = new PagoDetalle();
                    $pagoDetalle->id_pago = $pago->id;
                    $pagoDetalle->id_concepto = $detalle->id_concepto;
                    $pagoDetalle->monto = $programacionPago->total;
                    $pagoDetalle->monto_pagado = $request->monto_a_pagar;
                    $pagoDetalle->cuotas_pagadas = $request->cuotas ? 1 : 0;
                    $pagoDetalle->estado_id = $estadoNuevo;
                    $pagoDetalle->evidencia_det = $path;
                    $pagoDetalle->observacion = $request->observacion;
                    $pagoDetalle->creado_por = auth()->id();
                    $pagoDetalle->save();
                }


                $this->recordAudit('Nuevo', 'Pago creado: ' . $pago->id);
                DB::commit();

                return response()->json(['success' => 'Voucher guardada correctamente.'], 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al guardar el voucher.'.$e->getMessage()], 500);
        }
    }

    public function guardarEvidenciaMultiple(Request $request)
    {
        $request->validate([
            'evidenciamultiple' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cbxConceptoMultiple' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('evidenciamultiple')) {
                $image = $request->file('evidenciamultiple');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('evidencias', $imageName, 'public');
            }
            //$propietarios_for = Propietario::orderBy('id', 'asc')->take(120)->get();
            $juntaActiva = JuntaDirectiva::where('estado', 1)->first();
            $propietarios_for = Propietario::where('dni', '<>', '00000000')->where('id', '<=', 120)->orderBy('id', 'asc')->get();
            $acumulador = Acumulador::find(1);
            $ultimoCorrelativo = $acumulador ? $acumulador->correlativo : 0;
            //$nuevoCorrelativo = $ultimoCorrelativo + 1;

            foreach ($propietarios_for as $propietario) {

                $programacionPago = ProgramacionPago::where('id_propietario', $propietario->id)
                ->where('activo', 1)
                ->where('estado_id', 1)
                ->where('incobrable', 0)
                ->whereHas('detalles', function ($query) use ($request) {
                    $query->where('id_concepto', $request->cbxConceptoMultiple);
                })
                ->first();

                if ($programacionPago) {
                    $request->monto_a_pagar=$programacionPago->total;

                    $programacionPago->estado_id = 3;
                    $programacionPago->save();
                    // Si se encuentra la ProgramacionPago, actualiza su ProgramacionPagoDetalle
                    ProgramacionPagoDetalle::where('id_programacion', $programacionPago->id)
                        ->update(['estado_id' => 3]);

                    // Registro en tabla pagos
                    $pago = new Pago();
                    $pago->id_propietario = $propietario->id;
                    $pago->fecha = now();
                    $pago->total = $programacionPago->total;
                    $pago->cuotas_totales = 1;
                    $pago->creado_por = auth()->id();
                    $pago->evidencia = $path;
                    $pago->estado_id = 3; // "PAGO EN PARTES" o "PAGADO"
                    $pago->activo = 1;
                    $pago->id_programacion = $programacionPago->id;
                    $pago->correlativo = $nuevoCorrelativo++;
                    $pago->id_junta = $juntaActiva->id;

                    $pago->save();

                    // Detalles del pago
                    $programacionDetalles = ProgramacionPagoDetalle::where('id_programacion', $programacionPago->id)->get();
                    foreach ($programacionDetalles as $detalle) {
                        $pagoDetalle = new PagoDetalle();
                        $pagoDetalle->id_pago = $pago->id;
                        $pagoDetalle->id_concepto = $detalle->id_concepto;
                        $pagoDetalle->monto = $programacionPago->total;
                        $pagoDetalle->monto_pagado = $request->monto_a_pagar;
                        $pagoDetalle->cuotas_pagadas = 1;
                        $pagoDetalle->estado_id = 3;
                        $pagoDetalle->evidencia_det = $path;
                        $pagoDetalle->observacion = null;
                        $pagoDetalle->creado_por = auth()->id();
                        $pagoDetalle->save();
                    }
                    $this->recordAudit('Nuevo', 'Pago Multiple Registrado: ' . $pago->id);
                }
            }

            $ultimoCorrelativo = $nuevoCorrelativo - 1;
            Acumulador::actualizarCorrelativo(1, $ultimoCorrelativo);
            DB::commit();

            return response()->json(['success' => 'Pago múltiple registrado correctamente.'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al guardar el pago múltiple.'.$e->getMessage()], 500);
        }
    }

    public function guardarEvidenciaPropietario(Request $request)
    {
        $request->validate([
            'evidencia' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::beginTransaction();

        try {
            if ($request->hasFile('evidencia')) {
                $image = $request->file('evidencia');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('evidencias', $imageName, 'public');
            }
            $juntaActiva = JuntaDirectiva::where('estado', 1)->first();
            $programacionPago = ProgramacionPago::where('id', $request->id)->first();
            $acumulador = Acumulador::find(1);
            $ultimoCorrelativo = $acumulador ? $acumulador->correlativo : 0;
            if ($programacionPago) {
                $request->monto_a_pagar=$programacionPago->total;

                $programacionPago->estado_id = 2;
                $programacionPago->save();
                // Si se encuentra la ProgramacionPago, actualiza su ProgramacionPagoDetalle
                ProgramacionPagoDetalle::where('id_programacion', $programacionPago->id)
                    ->update(['estado_id' => 2]);

                // Registro en tabla pagos
                $pago = new Pago();
                $pago->id_propietario = $programacionPago->id_propietario;
                $pago->fecha = now();
                $pago->total = $programacionPago->total;
                $pago->cuotas_totales = 1;
                $pago->creado_por = auth()->id();
                $pago->evidencia = $path;
                $pago->estado_id = 2; // "PAGO EN PARTES" o "PAGADO"
                $pago->activo = 1;
                $pago->id_programacion = $programacionPago->id;
                $pago->correlativo = $ultimoCorrelativo++;
                $pago->id_junta = $juntaActiva->id;

                $pago->save();

                // Detalles del pago
                $programacionDetalles = ProgramacionPagoDetalle::where('id_programacion', $programacionPago->id)->get();
                foreach ($programacionDetalles as $detalle) {
                    $pagoDetalle = new PagoDetalle();
                    $pagoDetalle->id_pago = $pago->id;
                    $pagoDetalle->id_concepto = $detalle->id_concepto;
                    $pagoDetalle->monto = $programacionPago->total;
                    $pagoDetalle->monto_pagado = $request->monto_a_pagar;
                    $pagoDetalle->cuotas_pagadas = 1;
                    $pagoDetalle->estado_id = 2;
                    $pagoDetalle->evidencia_det = $path;
                    $pagoDetalle->observacion = null;
                    $pagoDetalle->creado_por = auth()->id();
                    $pagoDetalle->save();
                }
                $this->recordAudit('Nuevo', 'Pago Registrado por Propietario : ' . $pago->id);

                $ultimoCorrelativo = $ultimoCorrelativo - 1;
                Acumulador::actualizarCorrelativo(1, $ultimoCorrelativo);
                DB::commit();
                return response()->json(['success' => 'Pago Registrado por Propietario correctamente.'], 200);
            }else{
                return response()->json(['error' => 'No existen datos del pago programado.'], 500);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al guardar el Pago Registrado por Propietario.'.$e->getMessage()], 500);
        }
    }

    public function confirmarEvidencia(Request $request)
    {
        $request->validate([
            'pagoIdEvidencia' => 'required',
        ]);

        DB::beginTransaction();

        try {

            // Actualizar la tabla programacion_pago y programacion_pago_detalles
            if ($request->estadoIdEvidencia == 2) {
                $juntaActiva = JuntaDirectiva::where('estado', 1)->first();
                $pago = Pago::findOrFail($request->pagoIdEvidencia);
                $pago->estado_id = 3;
                $pago->actualizado_por = auth()->id();
                $acumulador = Acumulador::find(1);
                $ultimoCorrelativo = $acumulador ? $acumulador->correlativo : 0;
                $nuevoCorrelativo = $ultimoCorrelativo + 1;
                $pago->correlativo = $nuevoCorrelativo;
                $pago->id_junta = $juntaActiva->id;
                $pago->save();

                Acumulador::actualizarCorrelativo(1, $nuevoCorrelativo);

                PagoDetalle::where('id_pago', $request->pagoIdEvidencia)
                    ->update(['estado_id' => 3,'actualizado_por' => auth()->id()]);

                // Crear registro en la tabla pagos
                $programacion = ProgramacionPago::findOrFail($pago->id_programacion);
                $pago->actualizado_por = auth()->id();
                $programacion->estado_id = 3;
                $programacion->save();

                ProgramacionPagoDetalle::where('id_programacion', $pago->id_programacion)
                        ->update(['estado_id' => 3,'actualizado_por' => auth()->id()]);

                $this->recordAudit('Editado', 'Pago editado: ' . $pago->id);
            }

            DB::commit();

            return response()->json(['success' => 'Se confirmó el voucher, correctamente.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al guardar el voucher.'.$e->getMessage()], 500);
        }
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
    public function generatePDF($id)
    {
        $idTorre = env('ID_TORRE_SISTEMA', 7);
        $juntadirectiva= env('JUNTA_DIRECTIVA', "");
        $delegada = env('DELEGADA', "");
        $tesorera = env('TESORERA', "");
        $torre_trabajo = Torre::where('id',$idTorre)->first();


        $juntas_directivas = JuntaDirectiva::with([
            'detalles', // Relación directa con JuntaDirectivaDet
            'detalles.cargo', // Relación del detalle con Cargo
            'detalles.propietario' // Relación del detalle con Propietario
        ])->where('estado', 1)->first();

        $delegada = $juntas_directivas->detalles
            ->where('cargo.nombre', 'Delegada')
            ->first()?->nombres;

        $tesorera = $juntas_directivas->detalles
            ->where('cargo.nombre', 'Tesorera')
            ->first()?->nombres;

        $pago = Pago::with(['propietario','detalles.concepto.nombreMes','detalles', 'estado', 'programacion'])->findOrFail($id);

        $data = [
            'pago' => $pago,
            'detalles' => $pago->detalles,
            'torre' =>$torre_trabajo,
            'juntadirectiva' => $juntadirectiva,
            'delegada' => $delegada,
            'tesorera' => $tesorera,
            'juntasdirectivas' => $juntas_directivas,
            'delegada' => $delegada,
            'tesorera' => $tesorera,
        ];
        $pdf = DomPDF::loadView('pagos.pdf', $data);
        //$pdf = new DomPDF(); $pdf = $pdf->loadView('pagos.pdf', $data);
        //$pdf = DomPDFPDF::loadView('pagos.pdf', $data);

        return $pdf->stream('pago_' . $id . '.pdf');
    }

    public function generarPDF($id)
    {
        $idTorre = env('ID_TORRE_SISTEMA', 7);
        $juntadirectiva= env('JUNTA_DIRECTIVA', "");
        $delegada = env('DELEGADA', "");
        $tesorera = env('TESORERA', "");
        $torre_trabajo = Torre::where('id',$idTorre)->first();
        $pago = Pago::with(['propietario','detalles.concepto.nombreMes','detalles', 'estado', 'programacion'])->findOrFail($id);

        $data = [
            'pago' => $pago,
            'detalles' => $pago->detalles,
            'torre' =>$torre_trabajo,
            'juntadirectiva' => $juntadirectiva,
            'delegada' => $delegada,
            'tesorera' => $tesorera,
        ];
        return DomPDF::loadView('pagos.pdf', $data);
    }

    public function descargarRecibo($id)
    {
        $pdf = $this->generarPDF($id);
        // Obtener la fecha y hora actual
        $fechaHora = Carbon::now()->format('Ymd_His'); // Formato: YYYYMMDD_HHMMSS
        return $pdf->download('recibo_pago_' . $fechaHora . '.pdf');
    }



    public function enviarConfirmacionPago($idpago)
    {
        // Generar enlace de descarga del PDF (el archivo debe estar accesible públicamente)
        $urlReciboPDF = route('descargar.recibo', $idpago); // Ruta que debe permitir la descarga del PDF

        // Número de WhatsApp del destinatario (debe estar en formato internacional)
        $telefono = 'whatsapp:+51981525451'; // Formato de WhatsApp requerido por Twilio (whatsapp:+código_país + número)

        // Mensaje personalizado con el enlace de descarga del PDF
        $mensaje = "Hola, hemos recibido tu pago. Puedes descargar tu recibo aquí: {$urlReciboPDF}. ¡Gracias por tu pago!";

        // Instanciar Twilio con las credenciales
        $twilioSid = env('TWILIO_SID');
        $twilioAuthToken = env('TWILIO_AUTH_TOKEN');
        $twilioWhatsappFrom = env('TWILIO_WHATSAPP_FROM');

        $client = new Client($twilioSid, $twilioAuthToken);
        dd($client);
        // Enviar el mensaje por WhatsApp usando Twilio
        try {
            $client->messages->create(
                $telefono,
                [
                    'from' => $twilioWhatsappFrom,
                    'body' => $mensaje
                ]
            );

            // Retornar una respuesta exitosa
            return response()->json(['status' => 'success', 'message' => 'Mensaje enviado exitosamente']);
        } catch (\Exception $e) {
            // Manejar errores
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function corregirPagos()
    {
        if (auth()->user()->id !== 1) {
            return response()->json(['error' => 'No autorizado.']);
        }

        // Obtener la junta activa
        $juntaActiva = JuntaDirectiva::where('estado', 1)->first();

        if (!$juntaActiva) {
            return response()->json(['error' => 'No hay una junta directiva activa.']);
        }

        try {
            // Obtener los pagos en estado activo
            $pagos = Pago::where('estado_id', 3)
            ->where(function($query) {
                $query->whereNull('correlativo')
                      ->orWhere('correlativo', '');
            })
            ->orderBy('id')->get();

            // Verificar si no se encontraron pagos
            if ($pagos->isEmpty()) {
                return response()->json(['error' => 'No se encontraron pagos con correlativo vacío o nulo.']);
            }

            // Inicia el correlativo desde 1
            $correlativo = 1;

            foreach ($pagos as $pago) {
                // Actualizar id_junta y correlativo
                $pago->id_junta = $juntaActiva->id;
                $pago->correlativo = $correlativo++;
                $pago->save();
            }
            // Obtener el último correlativo insertado
            $ultimoCorrelativo = $correlativo - 1;

            // Actualizar el acumulador para el código 1 (recibos)
            Acumulador::actualizarCorrelativo(1, $ultimoCorrelativo);
            return response()->json(['success' => 'Pagos actualizados correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error: ' . $e->getMessage()]);
        }
    }
}
