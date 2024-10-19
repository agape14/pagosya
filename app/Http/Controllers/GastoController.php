<?php

namespace App\Http\Controllers;

use App\Models\Concepto;
use App\Models\EstadoPago;
use App\Models\Gasto;
use App\Models\GastoDetalle;
use App\Models\Mes;
use App\Models\Propietario;
use App\Models\SubPropietario;
use App\Traits\RecordsAudit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class GastoController extends Controller
{
    use RecordsAudit;
    public function gastos_index()
    {
        $page_title = 'Gastos';
        $page_description = 'Some description for the page';

		$action = __FUNCTION__;
        //$gastos = Gasto::all();

        $idTorre = env('ID_TORRE_SISTEMA', 6);
        // Obtener los IDs de los propietarios que ya tienen subpropietarios
        $idsPropietariosConSubPropietarios = SubPropietario::pluck('sub_propietario_id')->toArray();

        //$propietarios = Propietario::with('torre')->get();
        $conceptos = Concepto::with('nombreMes')->where('id_tipo_concepto','=','2')->where('activo','=','1')->get();

        return view('gastos.index', compact('conceptos', 'page_title', 'page_description','action'));
    }

    public function getTblGastos(Request $request)
{
    // Obtener los parámetros de búsqueda del request
    $concepto = $request->input('concepto');
    $fecha = $request->input('fecha');

    // Iniciar la consulta para obtener los gastos y sus detalles
    $query = Gasto::select(
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

    // Aplicar filtros si existen
    if ($concepto) {
        $query->where('conceptos.id', $concepto);
    }
    if ($fecha) {
        $fecha = Carbon::createFromFormat('d/m/Y', $fecha)->format('Y-m-d');
        $query->whereDate('gastos.fecha', $fecha);
    }

    $gastos = $query->groupBy('gastos.id')->get();
    if ($gastos->isEmpty()) {
        return DataTables::of(collect([]))->make(true);
    }
    return DataTables::of($gastos)
        ->addColumn('detalle', function($row) {
            // Obtener los detalles de gastos para este gasto
            $detalles = GastoDetalle::select('descripcion', 'monto')
                ->where('id_gasto', $row->id)
                ->get();

            // Construir una cadena con los detalles de gastos
            $detalleHtml = '';
            foreach ($detalles as $detalle) {
                $detalleHtml .= $detalle->descripcion . ': ' . number_format($detalle->monto, 2) . '<br>';
            }

            return $detalleHtml;
        })
        ->addColumn('concepto', function($row) {
            // Obtener los detalles de gastos para este gasto
            $detalles = GastoDetalle::select('id_concepto')
            ->where('id_gasto', $row->id)
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
                        // Construir una cadena con los detalles de gastos
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

    public function create()
    {
        return view('gastos.create');
    }

    public function store(Request $request)
    {

        $idTorre = env('ID_TORRE_SISTEMA', 6);
        $codigonuevo = $request->input('gastoId');

        if ($codigonuevo) {

            DB::beginTransaction();

            try {

                // Manejar la carga de la imagen
                if ($request->hasFile('evidencia')) {
                    $image = $request->file('evidencia');
                    $imageName = time() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('evidencias', $imageName, 'public');
                }

                $nuevogasto = Gasto::findOrFail($codigonuevo);
                $nuevogasto->total = $request->txtMontoAdd;
                $nuevogasto->actualizado_por = auth()->id();
                if ($request->hasFile('evidencia')) {
                    $nuevogasto->evidencia = $path;
                }
                $nuevogasto->save();

                GastoDetalle::where('id_gasto', $nuevogasto->id)
                    ->update([
                        'descripcion' => $request->txtDescripcion,
                        'monto' => $request->txtMontoAdd,
                        'actualizado_por' => auth()->id()
                    ]);
                /*
                $nuevogastodet = GastoDetalle::findOrFail($codigonuevo);
                $nuevogastodet->descripcion = $request->txtDescripcion; // Ajusta según el nombre del campo de tu formulario
                $nuevogastodet->monto = $request->txtMontoAdd; // Ajusta según el nombre del campo de tu formulario
                $nuevogastodet->actualizado_por = auth()->id();
                $nuevogastodet->save();
                */
                $this->recordAudit('Editado', 'Gasto editado: ' . $codigonuevo);
                DB::commit();

                return response()->json(['success' => 'Gasto actualizado correctamente.'], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'Error al editar el Gasto.'.$e->getMessage()], 500);
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

                // Crear un nuevo registro en la tabla gastos
                $gasto = new Gasto();
                $gasto->fecha = date('Y-m-d');
                $gasto->id_torre = $idTorre;
                $gasto->total = $request->txtMontoAdd; // Ajusta según el nombre del campo de tu formulario
                $gasto->creado_por = Auth::user()->id; // Ajusta según cómo manejas el usuario autenticado
                $gasto->activo = 1;
                if ($request->hasFile('evidencia')) {
                    $gasto->evidencia = $path;
                }
                $gasto->save();

                // Crear un nuevo registro en la tabla gastos_detalle
                $gastoDetalle = new GastoDetalle();
                $gastoDetalle->id_gasto = $gasto->id;
                $gastoDetalle->id_concepto = $request->cbxConceptoAdd;
                $gastoDetalle->descripcion = $request->txtDescripcion; // Ajusta según el nombre del campo de tu formulario
                $gastoDetalle->monto = $request->txtMontoAdd; // Ajusta según el nombre del campo de tu formulario
                $gastoDetalle->creado_por = Auth::user()->id;
                $gastoDetalle->save();

                // Puedes devolver una respuesta JSON si es necesario
                //return response()->json(['success' => true, 'message' => 'Gasto guardado exitosamente']);
                $this->recordAudit('Nuevo', 'Gasto creado: ' . $gasto->id);
                DB::commit();

                return response()->json(['success' => 'Gasto guardada correctamente.'], 200);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => 'Error al guardar el Gasto.'.$e->getMessage()], 500);
            }
        }

    }

    public function show($id)
    {
        $query = Gasto::select(
            'gastos.id',
            'gastos.fecha',
            'gastos.total',
            'gastos.created_at',
            'creador.nombres_completos as creado_por_nombre',
            'gastos_detalle.id_concepto',
            'gastos_detalle.descripcion',
            'gastos.evidencia',
        )
        ->join('usuarios as creador', 'gastos.creado_por', '=', 'creador.id')
        ->leftJoin('gastos_detalle', 'gastos.id', '=', 'gastos_detalle.id_gasto')
        ->leftJoin('conceptos', 'gastos_detalle.id_concepto', '=', 'conceptos.id')
        //->leftJoin('propietarios', 'conceptos.id_propietario', '=', 'propietarios.id')
        ->where('gastos.activo', '=', 1);

        $query->where('gastos.id', $id);
        $gastos = $query->get();
        // Procesar cada resultado para agregar la URL completa de la evidencia
        $gastos->transform(function ($gasto) {
            $gasto->evidencia_url = asset('storage/' . $gasto->evidencia);
            return $gasto;
        });

        return response()->json(['gastos' => $gastos]);

        /*$gasto = Gasto::findOrFail($id);
        return view('gastos.show', compact('gasto'));*/
    }

    public function edit($id)
    {
        $gasto = Gasto::findOrFail($id);
        return view('gastos.edit', compact('gasto'));
    }

    public function update(Request $request, $id)
    {
        $gasto = Gasto::findOrFail($id);
        $gasto->fecha = $request->fecha;
        $gasto->id_torre = $request->id_torre;
        $gasto->save();

        return redirect()->route('gastos.index')->with('success', 'Gasto actualizado correctamente.');
    }

    public function destroy($id)
    {
        /*
        if (!auth()->user()->can('delete', $gasto)) {
            return response()->json(['error' => 'No tienes permiso para eliminar este gasto.'], 403);
        }
        */

        $gasto = Gasto::findOrFail($id);
        // Registro de auditoría
        $this->recordAudit('Eliminado', 'Gasto eliminado: ' . $gasto->id);
        $gasto->delete();
        return response()->json(['success' => 'Gasto eliminado correctamente.']);
    }
}
