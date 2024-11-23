<?php

namespace App\Http\Controllers;

use App\Models\Concepto;
use App\Models\EstadoPago;
use App\Models\Ingreso;
use App\Models\IngresoDetalle;
use App\Models\Mes;
use App\Models\Propietario;
use App\Models\SubPropietario;
use App\Traits\RecordsAudit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class IngresoController extends Controller
{
    use RecordsAudit;
    public function ingresos_index()
    {
        $page_title = 'Ingresos';
        $page_description = 'Some description for the page';

		$action = __FUNCTION__;
        //$ingresos = Ingreso::all();

        $idTorre = env('ID_TORRE_SISTEMA', 6);
        // Obtener los IDs de los propietarios que ya tienen subpropietarios
        $idsPropietariosConSubPropietarios = SubPropietario::pluck('sub_propietario_id')->toArray();

        //$propietarios = Propietario::with('torre')->get();
        $conceptos = Concepto::with('nombreMes')->where('id_tipo_concepto','=','3')->where('activo','=','1')->get();

        return view('ingresos.index', compact('conceptos', 'page_title', 'page_description','action'));
    }

    public function getTblIngresos(Request $request)
    {
        // Obtener los parámetros de búsqueda del request
        $concepto = $request->input('concepto');
        $fecha = $request->input('fecha');

        // Iniciar la consulta para obtener los ingresos y sus detalles
        $query = Ingreso::select(
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

        // Aplicar filtros si existen
        if ($concepto) {
            $query->where('conceptos.id', $concepto);
        }
        if ($fecha) {
            $fecha = Carbon::createFromFormat('d/m/Y', $fecha)->format('Y-m-d');
            $query->whereDate('ingresos.fecha', $fecha);
        }
        $ingresos = $query->groupBy('ingresos.id', 'ingresos.fecha', 'ingresos.total', 'ingresos.created_at', 'creador.nombres_completos')->get();
        //dd($query->toSql(),$ingresos->isEmpty());
        if ($ingresos->isEmpty()) {
            return DataTables::of(collect([]))->make(true);
        }else{
            return DataTables::of($ingresos)
            ->addColumn('detalle', function($row) {
                // Obtener los detalles de ingresos para este ingreso
                $detalles = IngresoDetalle::select('descripcion', 'monto')
                    ->where('id_ingreso', $row->id)
                    ->get();

                // Construir una cadena con los detalles de ingresos
                $detalleHtml = '';
                foreach ($detalles as $detalle) {
                    $detalleHtml .= $detalle->descripcion . ': ' . number_format($detalle->monto, 2) . '<br>';
                }

                return $detalleHtml;
            })
            ->addColumn('concepto', function($row) {
                // Obtener los detalles de ingresos para este ingreso
                $detalles = IngresoDetalle::select('id_concepto')
                ->where('id_ingreso', $row->id)
                ->first(); // Cambiado a first() para obtener un solo resultado

                if ($detalles) {
                    // Obtener el concepto relacionado
                    $concepto = Concepto::select('descripcion_concepto', 'mes', 'anio')
                        ->where('id', $detalles->id_concepto)
                        ->first(); // Cambiado a first() para obtener un solo resultado

                    if ($concepto) {
                        // Obtener el nombre del mes
                        $mes = Mes::select('nombremes')
                            ->where('mes', $concepto->mes)
                            ->first(); // Cambiado a first() para obtener un solo resultado

                        if ($mes) {
                            // Construir una cadena con los detalles de ingresos
                            return $concepto->descripcion_concepto . ' ' . $mes->nombremes . ' ' . $concepto->anio;
                        }else{
                            return $concepto->descripcion_concepto;
                        }
                    }
                }

                return '';
            })
            ->addColumn('total', function($row) {
                return number_format($row->total, 2);
            })
            ->addColumn('fecha', function($row) {
                return \Carbon\Carbon::parse($row->fecha)->format('d/m/Y');
            })
            ->addColumn('creado_por', function($row) {
                return $row->creado_por_nombre;
            })
            ->addColumn('action', function($row) {
                $btn = '<div class="d-flex">';
                $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-primary shadow btn-xs sharp mr-1 editBtn"><i class="fa fa-pencil"></i></a>';
                $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger shadow btn-xs sharp deleteBtn"><i class="fa fa-trash"></i></a>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['detalle', 'action'])
            ->make(true);
        }

    }

    public function create()
    {
        return view('ingresos.create');
    }

    public function store(Request $request)
    {
        $idTorre = env('ID_TORRE_SISTEMA', 6);
        $codigonuevo = $request->input('ingresoId');

        if ($codigonuevo) {

            DB::beginTransaction();

            try {

                // Manejar la carga de la imagen
                if ($request->hasFile('evidencia')) {
                    $image = $request->file('evidencia');
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('evidencias', $imageName, 'public');
                }

                $nuevoingreso = Ingreso::findOrFail($codigonuevo);
                $nuevoingreso->total = $request->txtMontoAdd;
                $nuevoingreso->actualizado_por = auth()->id();
                if ($request->hasFile('evidencia')) {
                    $nuevoingreso->evidencia = $path;
                }
                $nuevoingreso->save();

                IngresoDetalle::where('id_ingreso', $nuevoingreso->id)
                    ->update([
                        'descripcion' => $request->txtDescripcion,
                        'monto' => $request->txtMontoAdd,
                        'actualizado_por' => auth()->id()
                    ]);
                /*
                $nuevoingresodet = IngresoDetalle::findOrFail($codigonuevo);
                $nuevoingresodet->descripcion = $request->txtDescripcion; // Ajusta según el nombre del campo de tu formulario
                $nuevoingresodet->monto = $request->txtMontoAdd; // Ajusta según el nombre del campo de tu formulario
                $nuevoingresodet->actualizado_por = auth()->id();
                $nuevoingresodet->save();
                */
                $this->recordAudit('Editado', 'ingreso editado: ' . $codigonuevo);
                DB::commit();

                return response()->json(['success' => 'ingreso actualizado correctamente.'], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'Error al editar el ingreso.'.$e->getMessage()], 500);
            }
        }else{
            $request->validate([
                'evidencia' => 'required|mimes:pdf|max:2048',
            ]);
            DB::beginTransaction();

            try {


                // Manejar la carga de la imagen
                if ($request->hasFile('evidencia')) {
                    $file = $request->file('evidencia');
                    $filename = time() . '.' . $file->getClientOriginalName();
                    $path = $file->storeAs('evidencias', $filename, 'public');
                }

                // Crear un nuevo registro en la tabla ingresos
                $ingreso = new ingreso();
                $ingreso->fecha = date('Y-m-d');
                $ingreso->id_torre = $idTorre;
                $ingreso->total = $request->txtMontoAdd; // Ajusta según el nombre del campo de tu formulario
                $ingreso->creado_por = Auth::user()->id; // Ajusta según cómo manejas el usuario autenticado
                $ingreso->activo = 1;
                if ($request->hasFile('evidencia')) {
                    $ingreso->evidencia = $path;
                }
                $ingreso->save();

                // Crear un nuevo registro en la tabla ingresos_detalle
                $ingresoDetalle = new IngresoDetalle();
                $ingresoDetalle->id_ingreso = $ingreso->id;
                $ingresoDetalle->id_concepto = $request->cbxConceptoAdd;
                $ingresoDetalle->descripcion = $request->txtDescripcion; // Ajusta según el nombre del campo de tu formulario
                $ingresoDetalle->monto = $request->txtMontoAdd; // Ajusta según el nombre del campo de tu formulario
                $ingresoDetalle->creado_por = Auth::user()->id;
                $ingresoDetalle->save();

                // Puedes devolver una respuesta JSON si es necesario
                //return response()->json(['success' => true, 'message' => 'ingreso guardado exitosamente']);
                $this->recordAudit('Nuevo', 'ingreso creado: ' . $ingreso->id);
                DB::commit();

                return response()->json(['success' => 'ingreso guardada correctamente.'], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'Error al guardar el ingreso.'.$e->getMessage()], 500);
            }
        }

    }

    public function show($id)
    {
        $query = Ingreso::select(
            'ingresos.id',
            'ingresos.fecha',
            'ingresos.total',
            'ingresos.created_at',
            'creador.nombres_completos as creado_por_nombre',
            'ingresos_detalle.id_concepto',
            'ingresos_detalle.descripcion',
            'ingresos.evidencia',
        )
        ->join('usuarios as creador', 'ingresos.creado_por', '=', 'creador.id')
        ->leftJoin('ingresos_detalle', 'ingresos.id', '=', 'ingresos_detalle.id_ingreso')
        ->leftJoin('conceptos', 'ingresos_detalle.id_concepto', '=', 'conceptos.id')
        //->leftJoin('propietarios', 'conceptos.id_propietario', '=', 'propietarios.id')
        ->where('ingresos.activo', '=', 1);

        $query->where('ingresos.id', $id);
        $ingresos = $query->get();
        // Procesar cada resultado para agregar la URL completa de la evidencia
        $ingresos->transform(function ($ingreso) {
            $ingreso->evidencia_url = asset('storage/' . $ingreso->evidencia);
            return $ingreso;
        });

        return response()->json(['ingresos' => $ingresos]);

        /*$ingreso = Ingreso::findOrFail($id);
        return view('ingresos.show', compact('ingreso'));*/
    }

    public function showverpdf($id)
    {
        $ingreso = Ingreso::select('evidencia')
            ->where('id', $id)
            ->where('activo', 1)
            ->firstOrFail();

        // Construir la URL completa de la evidencia
        $evidenciaUrl = asset('storage/' . $ingreso->evidencia);

        return response()->json(['evidencia_url' => $evidenciaUrl]);
    }

    public function edit($id)
    {
        $ingreso = Ingreso::findOrFail($id);
        return view('ingresos.edit', compact('ingreso'));
    }

    public function update(Request $request, $id)
    {
        $ingreso = Ingreso::findOrFail($id);
        $ingreso->fecha = $request->fecha;
        $ingreso->id_torre = $request->id_torre;
        $ingreso->save();

        return redirect()->route('ingresos.index')->with('success', 'ingreso actualizado correctamente.');
    }

    public function destroy($id)
    {
        /*
        if (!auth()->user()->can('delete', $ingreso)) {
            return response()->json(['error' => 'No tienes permiso para eliminar este ingreso.'], 403);
        }
        */

        $ingreso = Ingreso::findOrFail($id);
        // Registro de auditoría
        $this->recordAudit('Eliminado', 'ingreso eliminado: ' . $ingreso->id);
        $ingreso->delete();
        return response()->json(['success' => 'ingreso eliminado correctamente.']);
    }
}
