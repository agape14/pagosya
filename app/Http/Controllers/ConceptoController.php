<?php

namespace App\Http\Controllers;

use App\Models\Anio;
use App\Models\Concepto;
use App\Models\Mes;
use App\Models\TipoConcepto;
use App\Traits\RecordsAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ConceptoController extends Controller
{
    use RecordsAudit;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function conceptos_index()
    {
        $page_title = 'Tipos Concepto';
        $page_description = 'Some description for the page';
		
		$action = __FUNCTION__;

        $conceptos = Concepto::all();
        $tipos_concepto = TipoConcepto::all();
        
        // Leer el archivo JSON de meses
        $meses = Mes::all();
        $anios = Anio::all();
        return view('conceptos.index', compact('conceptos', 'tipos_concepto','meses','anios', 'page_title', 'page_description','action'));
    }

    
    public function getConceptos()
    {
        $conceptos = Concepto::select(['id', 'id_tipo_concepto', 'descripcion_concepto', 'mes', 'anio','activo']);
        return DataTables::of($conceptos)
            ->addColumn('nombre_concepto', function ($row) {
                return $row->tipoConcepto ? $row->tipoConcepto->tipo_concepto : '';
            })
            ->addColumn('nombre_mes', function ($row) {
                if ($row->mes != 0) {
                    return $row->nombreMes ? $row->nombreMes->nombremes : '';
                } else {
                    return '';
                }
            })
            ->addColumn('anio', function ($row) {
                if ($row->anio != 0) {
                    return $row->anio;
                } else {
                    return '';
                }
            })
            ->addColumn('activo', function($row) {
                if ($row->activo === 1) {
                    return '<span class="badge light badge-success">Activo</span>';
                } else {
                    return '<span class="badge light badge-danger">Inactivo</span>';
                }
                
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="d-flex">';
                $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-primary shadow btn-sm sharp mr-1 editBtn"><i class="fa fa-pencil"></i></a>';
                $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger shadow btn-sm sharp mr-1 deleteBtn"><i class="fa fa-trash"></i></a>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['action','activo'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('conceptos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'id_tipo_concepto' => 'required|exists:tipos_concepto,id',
            'descripcion_concepto' => 'required|string|max:100',
            'mes' => 'integer|max:12',
            'anio' => 'integer|min:1900|max:' . (date('Y') + 1),
        ]);
         // Verificar y ajustar los valores de mes y anio si no son válidos
        $mes = $request->input('mes');
        $anio = $request->input('anio');
        
        if (!is_numeric($mes) || $mes < 1 || $mes > 12) {
            $mes = 0;
        }
        
        if (!is_numeric($anio) || $anio < 1900 || $anio > (date('Y') + 1)) {
            $anio = 0;
        }
        $concepto=Concepto::create([ 
            'id_tipo_concepto' => $request->input('id_tipo_concepto'),
            'descripcion_concepto' => $request->input('descripcion_concepto'),
            'mes' => $mes,
            'anio' => $anio,
            'creado_por' => Auth::id(),
        ]);
        $this->recordAudit('Nuevo', 'Concepto creado: ' . $concepto->id);
        return response()->json(['success' => 'Concepto creado correctamente.']);
        //return redirect()->route('conceptos')->with('success', 'Concepto creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getConcepto($id)
    {
        $concepto = Concepto::findOrFail($id);
        return response()->json($concepto);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $concepto = Concepto::findOrFail($id);
        return view('conceptos.edit', compact('concepto'));
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

        $request->validate([
            'id_tipo_concepto' => 'required|exists:tipos_concepto,id',
            'descripcion_concepto' => 'required|string|max:100',
            'mes' => 'integer|max:12',
            'anio' => 'integer',
        ]);

        $concepto = Concepto::findOrFail($id);
        $concepto->id_tipo_concepto = $request->id_tipo_concepto;
        $concepto->descripcion_concepto = $request->descripcion_concepto;
        $concepto->mes = $request->mes;
        $concepto->anio = $request->anio;
        $concepto->activo = $request->activo;
        $concepto->creado_por = auth()->id(); // Ajustar según sea necesario
        $concepto->save();
        $this->recordAudit('Editado', 'Concepto editado: ' . $concepto->id);
        return response()->json(['success' => 'Concepto actualizado correctamente.']);
        //return redirect()->route('conceptos')->with('success', 'Concepto actualizado correctamente.');
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $concepto = Concepto::findOrFail($id);
        $this->recordAudit('Eliminado', 'Concepto eliminado: ' . $concepto->id);
        $concepto->delete();
        return response()->json(['success' => 'Concepto eliminado correctamente.']);
    }
}
