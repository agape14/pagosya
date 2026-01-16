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
use App\Models\SubPropietario;
use App\Services\WhatsAppService;
use App\Jobs\NotifyMorosidadCritica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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

        // Calcular Semáforo de Morosidad (solo para admin)
        $semaforo_morosidad = null;
        if ($user->id_perfil == 1 || $user->id_perfil == 2) {
            $semaforo_morosidad = $this->calcularSemaforoMorosidad();
        }

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
            'saldo_general',
            'semaforo_morosidad'
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

    /**
     * Calcula el semáforo de morosidad de los vecinos
     * Retorna: verde (al día), amarillo (deuda < 3 meses), rojo (deuda >= 3 meses)
     */
    public function calcularSemaforoMorosidad()
    {
        $idTorre = env('ID_TORRE_SISTEMA', 7);
        
        // Obtener IDs de propietarios que tienen subpropietarios (excluir)
        $idsPropietariosConSubPropietarios = SubPropietario::pluck('sub_propietario_id')->toArray();

        // Obtener todos los propietarios activos (excluyendo subpropietarios)
        $propietarios = Propietario::where('id_torre', $idTorre)
            ->whereNotIn('id', $idsPropietariosConSubPropietarios)
            ->get();

        $totalPropietarios = $propietarios->count();
        $alDia = [];
        $enMora = [];
        $critico = [];

        // Fecha actual para comparaciones
        $fechaActual = Carbon::now();
        $fecha3MesesAtras = $fechaActual->copy()->subMonths(3);

        // Optimización: Pre-calcular totales por propietario con una sola consulta
        $totalesCuotas = DB::table('programacion_pagos')
            ->select('programacion_pagos.id_propietario', DB::raw('COALESCE(SUM(programacion_pagos.total), 0) as total_cuotas'))
            ->join('programacion_pagos_detalle', 'programacion_pagos.id', '=', 'programacion_pagos_detalle.id_programacion')
            ->join('conceptos', 'programacion_pagos_detalle.id_concepto', '=', 'conceptos.id')
            ->whereIn('programacion_pagos.id_propietario', $propietarios->pluck('id'))
            ->where('programacion_pagos.activo', 1)
            ->where('conceptos.id_tipo_concepto', 1)
            ->where('conceptos.activo', 1)
            ->whereDate('programacion_pagos.created_at', '<=', $fechaActual)
            ->groupBy('programacion_pagos.id_propietario')
            ->pluck('total_cuotas', 'id_propietario');

        $totalesAbonados = DB::table('pagos')
            ->select('pagos.id_propietario', DB::raw('COALESCE(SUM(pagos_detalle.monto_pagado), 0) as total_abonado'))
            ->join('pagos_detalle', 'pagos.id', '=', 'pagos_detalle.id_pago')
            ->whereIn('pagos.id_propietario', $propietarios->pluck('id'))
            ->where('pagos.activo', 1)
            ->groupBy('pagos.id_propietario')
            ->pluck('total_abonado', 'id_propietario');

        // Pre-calcular deudas más antiguas para todos los propietarios
        $deudasAntiguas = DB::table('programacion_pagos')
            ->select(
                'programacion_pagos.id_propietario',
                DB::raw('MIN(programacion_pagos.created_at) as fecha_antigua'),
                DB::raw('MIN(conceptos.mes) as mes_antiguo'),
                DB::raw('MIN(conceptos.anio) as anio_antiguo')
            )
            ->join('programacion_pagos_detalle', 'programacion_pagos.id', '=', 'programacion_pagos_detalle.id_programacion')
            ->join('conceptos', 'programacion_pagos_detalle.id_concepto', '=', 'conceptos.id')
            ->whereIn('programacion_pagos.id_propietario', $propietarios->pluck('id'))
            ->where('programacion_pagos.activo', 1)
            ->where('conceptos.id_tipo_concepto', 1)
            ->where('conceptos.activo', 1)
            ->whereRaw('COALESCE((SELECT SUM(pd2.monto_pagado) FROM pagos p2 
                                    JOIN pagos_detalle pd2 ON p2.id = pd2.id_pago 
                                    WHERE p2.id_programacion = programacion_pagos.id 
                                    AND p2.activo = 1), 0) < programacion_pagos.total')
            ->groupBy('programacion_pagos.id_propietario')
            ->get()
            ->keyBy('id_propietario');

        foreach ($propietarios as $propietario) {
            // Obtener totales desde las colecciones pre-calculadas
            $totalCuotas = $totalesCuotas->get($propietario->id, 0);
            $totalAbonado = $totalesAbonados->get($propietario->id, 0);

            // Calcular deuda pendiente
            $deudaPendiente = max(0, $totalCuotas - $totalAbonado);

            // Si no tiene deuda o está al día
            if ($deudaPendiente <= 0 || abs($deudaPendiente) < 0.01) {
                $alDia[] = [
                    'id' => $propietario->id,
                    'departamento' => $propietario->departamento,
                    'nombre' => $propietario->nombre,
                    'apellido' => $propietario->apellido,
                    'deuda' => 0
                ];
            } else {
                // Obtener fecha de deuda antigua desde la colección pre-calculada
                $deudaAntigua = $deudasAntiguas->get($propietario->id);
                
                $fechaDeudaAntigua = null;
                if ($deudaAntigua) {
                    // Construir fecha basada en mes y año del concepto
                    if ($deudaAntigua->anio_antiguo && $deudaAntigua->mes_antiguo) {
                        $fechaDeudaAntigua = Carbon::create($deudaAntigua->anio_antiguo, $deudaAntigua->mes_antiguo, 1)->endOfMonth();
                    } else {
                        $fechaDeudaAntigua = Carbon::parse($deudaAntigua->fecha_antigua);
                    }
                }

                $infoPropietario = [
                    'id' => $propietario->id,
                    'departamento' => $propietario->departamento,
                    'nombre' => $propietario->nombre,
                    'apellido' => $propietario->apellido,
                    'deuda' => $deudaPendiente,
                    'fecha_deuda_antigua' => $fechaDeudaAntigua ? $fechaDeudaAntigua->format('Y-m-d') : null
                ];

                // Clasificar por tiempo de mora
                if ($fechaDeudaAntigua && $fechaDeudaAntigua->lt($fecha3MesesAtras)) {
                    // Crítico: deuda de 3 meses o más
                    $critico[] = $infoPropietario;
                } else {
                    // En mora: deuda menor a 3 meses
                    $enMora[] = $infoPropietario;
                }
            }
        }

        // Calcular monto total de deuda pendiente del grupo amarillo
        $montoTotalAmarillo = collect($enMora)->sum('deuda');

        return [
            'verde' => [
                'cantidad' => count($alDia),
                'porcentaje' => $totalPropietarios > 0 ? round((count($alDia) / $totalPropietarios) * 100, 1) : 0,
                'propietarios' => $alDia
            ],
            'amarillo' => [
                'cantidad' => count($enMora),
                'porcentaje' => $totalPropietarios > 0 ? round((count($enMora) / $totalPropietarios) * 100, 1) : 0,
                'monto_total' => $montoTotalAmarillo,
                'propietarios' => $enMora
            ],
            'rojo' => [
                'cantidad' => count($critico),
                'porcentaje' => $totalPropietarios > 0 ? round((count($critico) / $totalPropietarios) * 100, 1) : 0,
                'propietarios' => $critico
            ],
            'total_propietarios' => $totalPropietarios
        ];
    }

    /**
     * Obtiene los IDs de propietarios filtrados por estado de morosidad
     */
    public function obtenerIdsPropietariosPorEstado(Request $request)
    {
        $estado = $request->get('estado'); // verde, amarillo, rojo
        
        if (!$estado) {
            return response()->json(['ids' => []]);
        }

        $semaforo = $this->calcularSemaforoMorosidad();
        
        $ids = [];
        if (isset($semaforo[$estado]['propietarios'])) {
            $ids = collect($semaforo[$estado]['propietarios'])->pluck('id')->toArray();
        }

        return response()->json(['ids' => $ids]);
    }

    /**
     * Vista para notificación masiva de vecinos en morosidad crítica
     */
    public function notificacionMasivaCriticos()
    {
        $page_title = 'Notificación Masiva - Morosidad Crítica';
        $page_description = 'Envíe notificaciones WhatsApp a vecinos con deuda crítica';
        $action = __FUNCTION__;

        $semaforo = $this->calcularSemaforoMorosidad();
        $propietariosCriticos = $semaforo['rojo']['propietarios'] ?? [];

        // Obtener propietarios completos para la vista (solo una consulta)
        $idsPropietarios = collect($propietariosCriticos)->pluck('id')->toArray();
        $propietariosCompletos = Propietario::whereIn('id', $idsPropietarios)
            ->get()
            ->keyBy('id');

        // Agregar información de teléfono a cada propietario crítico
        foreach ($propietariosCriticos as &$propietario) {
            $propCompleto = $propietariosCompletos->get($propietario['id']);
            $propietario['telefono'] = $propCompleto->telefono ?? null;
            $propietario['telefono_valido'] = $propCompleto && !empty($propCompleto->telefono) && 
                preg_replace('/[^0-9]/', '', $propCompleto->telefono) && 
                !preg_match('/^0+$/', preg_replace('/[^0-9]/', '', $propCompleto->telefono));
        }

        // Verificar conexión WhatsApp
        $whatsappService = new WhatsAppService();
        $whatsappConnected = $whatsappService->isConnected();

        return view('panel.notificacion-masiva-criticos', compact(
            'page_title',
            'page_description',
            'action',
            'propietariosCriticos',
            'whatsappConnected'
        ));
    }

    /**
     * Enviar notificaciones masivas a vecinos en morosidad crítica
     */
    public function enviarNotificacionMasivaCriticos(Request $request)
    {
        $request->validate([
            'mensaje' => 'required|string|min:10',
        ]);

        $whatsappService = new WhatsAppService();
        
        if (!$whatsappService->isConnected()) {
            return response()->json([
                'success' => false,
                'error' => 'WhatsApp no está conectado. Por favor, conéctelo primero desde la configuración.'
            ], 400);
        }

        $semaforo = $this->calcularSemaforoMorosidad();
        $propietariosCriticos = $semaforo['rojo']['propietarios'] ?? [];

        if (empty($propietariosCriticos)) {
            return response()->json([
                'success' => false,
                'error' => 'No hay propietarios en morosidad crítica para notificar.'
            ], 400);
        }

        $mensaje = $request->mensaje;
        $enviados = 0;
        $fallidos = 0;
        $errores = [];

        foreach ($propietariosCriticos as $propietarioData) {
            $propietario = Propietario::find($propietarioData['id']);
            
            if (!$propietario || empty($propietario->telefono)) {
                $fallidos++;
                $errores[] = "Propietario {$propietarioData['departamento']}: Sin teléfono válido";
                continue;
            }

            // Validar teléfono
            $telefonoLimpio = preg_replace('/[^0-9]/', '', $propietario->telefono);
            if (empty($telefonoLimpio) || preg_match('/^0+$/', $telefonoLimpio)) {
                $fallidos++;
                $errores[] = "Propietario {$propietarioData['departamento']}: Teléfono inválido";
                continue;
            }

            // Formatear teléfono con prefijo 51
            if (!str_starts_with($telefonoLimpio, '51')) {
                $telefonoLimpio = '51' . $telefonoLimpio;
            }

            // Personalizar mensaje con información del propietario
            $mensajePersonalizado = str_replace(
                ['{departamento}', '{nombre}', '{deuda}'],
                [
                    $propietario->departamento,
                    $propietario->nombre . ' ' . $propietario->apellido,
                    number_format($propietarioData['deuda'], 2)
                ],
                $mensaje
            );

            try {
                $result = $whatsappService->sendMessage(
                    $telefonoLimpio,
                    $mensajePersonalizado,
                    $propietario->id,
                    'morosidad_critica'
                );

                if ($result['success'] ?? false) {
                    $enviados++;
                } else {
                    $fallidos++;
                    $errores[] = "Propietario {$propietarioData['departamento']}: " . ($result['error'] ?? 'Error desconocido');
                }
            } catch (\Exception $e) {
                $fallidos++;
                $errores[] = "Propietario {$propietarioData['departamento']}: " . $e->getMessage();
                Log::error("Error enviando notificación masiva: " . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'enviados' => $enviados,
            'fallidos' => $fallidos,
            'total' => count($propietariosCriticos),
            'errores' => array_slice($errores, 0, 10) // Limitar a 10 errores para no sobrecargar
        ]);
    }
}
