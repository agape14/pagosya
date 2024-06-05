<?php

namespace App\Http\Controllers;

use App\Models\Propietario;
use App\Models\SubPropietario;
use App\Models\TiposSubPropietarios;
use App\Models\Torre;
use App\Traits\RecordsAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class PropietarioController extends Controller
{
    use RecordsAudit;
    public function propietarios_index()
    {
        $page_title = 'Propietarios';
        $page_description = 'Some description for the page';
		
		$action = __FUNCTION__;

        $propietarios = Propietario::with('torre')->get();
        $torres = Torre::all();
        $tipos_subprop = TiposSubPropietarios::all(); 
        return view('propietarios.index', compact('propietarios', 'torres','tipos_subprop', 'page_title', 'page_description','action'));
    }

    public function getPropietarios()
    {
        $idTorre = env('ID_TORRE_SISTEMA', 6); 
        // Obtener los IDs de los propietarios que ya tienen subpropietarios
        $idsPropietariosConSubPropietarios = SubPropietario::pluck('sub_propietario_id')->toArray();

        $propietarios = Propietario::where('id_torre', $idTorre)
            ->whereNotIn('id', $idsPropietariosConSubPropietarios)
            ->select('propietarios.id','propietarios.nombre', 'propietarios.apellido', 'propietarios.correo_electronico', 'propietarios.telefono', 'propietarios.departamento')
            ->get();
        return DataTables::of($propietarios)
            ->addColumn('action', function ($row) {
                $btn = '<div class="d-flex">';
                $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-primary shadow btn-sm sharp mr-1 editBtn"><i class="fa fa-pencil"></i></a>';
                $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-secondary shadow btn-sm sharp mr-1 addsubpropBtn"><i class="fa fa-user"></i></a>';
                $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn light btn-info shadow btn-sm sharp mr-1 viewsubpropBtn"><i class="fa fa-eye"></i></a>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $torres = Torre::all();
        return view('propietarios.create', compact('torres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'correo_electronico' => 'required|string|email|max:100',
            'telefono' => 'required|string|max:15',
            'departamento' => 'required|integer',
            'id_torre' => 'required|integer|exists:torres,id_torre',
        ]);

        $propietario=Propietario::create($request->all());
        $this->recordAudit('Nuevo', 'Propietario creado: ' . $propietario->id);
        return redirect()->route('propietarios.index');
    }

    public function getSubPropietarios($propietarioId)
    {
        $propietario = Propietario::find($propietarioId);
        $subPropietarios = SubPropietario::where('propietario_id', $propietarioId)->get();
        return response()->json(['propietario' => $propietario, 'subPropietarios' => $subPropietarios]);
    }

    public function getEditSubPropietarios($propietarioId)
    {
        $subpropietario = SubPropietario::findOrFail($propietarioId);
        $propietario = Propietario::where('id', $subpropietario->sub_propietario_id)->firstOrFail();

        return response()->json(['propietario' => $propietario, 'subPropietarios' => $subpropietario]);
    }

    public function getTblSubPropietarios($id)
    {
        
        $propietarioId = $id;

        // Obtener los subpropietarios del propietario especificado
        $subPropietarios = SubPropietario::with(['subPropietario', 'tipoSubPropietario'])
            ->where('sub_propietarios.propietario_id', $propietarioId)
            ->get();
            //dd($subPropietarios->sub_propietario_id);
        return DataTables::of($subPropietarios)
            ->addColumn('nombre', function ($row) {
                return $row->subPropietario->nombre;
            })
            ->addColumn('apellido', function ($row) {
                return $row->subPropietario->apellido;
            })
            ->addColumn('correo_electronico', function ($row) {
                return $row->subPropietario->correo_electronico;
            })
            ->addColumn('telefono', function ($row) {
                return $row->subPropietario->telefono;
            })
            ->addColumn('tipo_sub_propietario', function ($row) {
                return $row->tipoSubPropietario ? $row->tipoSubPropietario->nombre : '';
            })
            ->addColumn('tipo_subpropietario', function ($row) {
                return $row->tipoSubPropietario ? $row->tipoSubPropietario->tipo : '';
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="d-flex">';
                $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-primary shadow btn-xs sharp mr-1 editBtnSubProp"><i class="fa fa-pencil"></i></a>';
                $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger shadow btn-xs sharp mr-1 deleteBtnSubProp"><i class="fa fa-trash"></i></a>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function storeSubPropietario(Request $request)
    {
        //dd($request->all());
        $validatedData = $request->validate([
            'propietario_id' => 'required|exists:propietarios,id',
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'correo_electronico' => 'required|string|email|max:100',
            'telefono' => 'required|string|max:15',
            'tipo_sub_propietario' => 'required|string|max:50',
        ]);
        $codigonuevo = $request->input('id');
        
        if ($codigonuevo) {
            

            $subpropietarioupd = SubPropietario::findOrFail($codigonuevo);
            $subpropietarioupd->tipo_sub_propietario_id = $validatedData['tipo_sub_propietario'];
            $subpropietarioupd->actualizado_por = auth()->id();
            $subpropietarioupd->save();

            $propietarioupd = Propietario::findOrFail($subpropietarioupd->sub_propietario_id);
            $propietarioupd->nombre = $request->input('nombre');
            $propietarioupd->apellido = $request->input('apellido');
            $propietarioupd->correo_electronico = $request->input('correo_electronico');
            $propietarioupd->telefono = $request->input('telefono');
            $propietarioupd->actualizado_por = auth()->id(); // Ajustar según sea necesario
            $propietarioupd->save();
            $this->recordAudit('Editado', 'Sub Propietario editado: ' . $propietarioupd->id);
            return response()->json(['success' => 'Sub Propietario actualizado correctamente.']);
        }else{
            $propietario = Propietario::findOrFail($validatedData['propietario_id']);
            //'nombre', 'apellido', 'correo_electronico', 'telefono', 'departamento', 'id_torre'
            $nuevoSubPropietario = Propietario::create([ 
                'nombre' => $request->input('nombre'),
                'apellido' => $request->input('apellido'),
                'correo_electronico' => $request->input('correo_electronico'),
                'telefono' => $request->input('telefono'),
                'departamento' => $propietario->departamento,
                'id_torre' => $propietario->id_torre,
                'creado_por' => Auth::id(),
            ]);


            $subPropietario = new SubPropietario();
            $subPropietario->propietario_id = $validatedData['propietario_id'];
            $subPropietario->sub_propietario_id = $nuevoSubPropietario->id; // Assuming this is the ID of the new sub-owner
            $subPropietario->tipo_sub_propietario_id = $validatedData['tipo_sub_propietario'];
            $subPropietario->creado_por = auth()->id();
            $subPropietario->save();
            $this->recordAudit('Nuevo', 'Sub Propietario creado: ' . $propietario->id);
            return response()->json(['success' => 'Sub Propietario registrado correctamente.']);
        }
    }

    public function getTiposSubPropietarios()
    {
        $tipos = TiposSubPropietarios::all(); // Asegúrate de tener el modelo correspondiente
        return response()->json($tipos);
    }

    public function show(Propietario $propietario)
    {
        return view('propietarios.show', compact('propietario'));
    }

    public function getPropietario($id)
    {
        $propietarios = Propietario::findOrFail($id);
        return response()->json($propietarios);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:50',
            'apellido' => 'required|string|max:50',
            'correo_electronico' => 'required|string|email|max:100',
            'telefono' => 'required|string|max:15',
            'departamento' => 'required|integer',
        ]);

        $propietario = Propietario::findOrFail($id);
        $propietario->nombre = $request->nombre;
        $propietario->apellido = $request->apellido;
        $propietario->correo_electronico = $request->correo_electronico;
        $propietario->telefono = $request->telefono;
        $propietario->actualizado_por = auth()->id(); // Ajustar según sea necesario
        $propietario->save();
        $this->recordAudit('Editado', 'Propietario editado: ' . $propietario->id);
        //return redirect()->route('propietarios.index');
        return response()->json(['success' => 'Propietario actualizada correctamente.']);
    }

    public function destroy($id)
    {
        try {
            // Iniciar una transacción
            DB::beginTransaction();
    
            // Encontrar el subpropietario por ID
            $subpropietario = SubPropietario::findOrFail($id);
    
            // Encontrar el propietario relacionado
            $propietario = Propietario::where('id', $subpropietario->sub_propietario_id)->firstOrFail();
            
            // Eliminar el subpropietario
            $subpropietario->delete();
    
            // Eliminar el propietario
            $propietario->delete();
            $this->recordAudit('Eliminado', 'Sub Propietario eliminado: ' . $propietario->id);
            // Confirmar la transacción
            DB::commit();
    
            return response()->json(['success' => 'Sub Propietario y Propietario eliminados correctamente.']);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
    
            return response()->json(['error' => 'Error al eliminar el Sub Propietario y el Propietario. '. $subpropietario->sub_propietario_id, 'message' => $e->getMessage()], 500);
        }
    }
}
