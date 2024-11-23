<?php

namespace App\Http\Controllers;

use App\Models\Anio;
use App\Models\Mes;
use App\Models\Banco;
use App\Models\InteresBancario;
use App\Traits\RecordsAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class InteresBancarioController extends Controller
{
    use RecordsAudit;
    public function intbancario_index()
    {
        $page_title = 'Interes Bancario';
        $page_description = 'Some description for the page';

		$action = __FUNCTION__;

        $bancos = Banco::all();
         // Leer el archivo JSON de meses
         $meses = Mes::all();
         $anios = Anio::orderBy('anio', 'asc')->get();
        return view('interesbancario.index', compact('bancos','meses','anios','page_title', 'page_description','action'));
    }

    public function getIntbancarios()
    {
        $intbancario = Interesbancario::with('banco:id,nombre')->select(['id',  'nombre', 'monto','mes','anio','tasa_interes',
        'saldo_inicial','saldo_final','banco_id','creado_por','estado']);

        return DataTables::of($intbancario)
            ->addColumn('banco_nombre', function ($row) {
                return $row->banco ? $row->banco->nombre : 'Sin banco';
            })
            ->addColumn('nombre_mes', function ($row) {
                if ($row->mes != 0) {
                    return $row->nombreMes ? $row->nombreMes->nombremes : '';
                } else {
                    return '';
                }
            })
            ->addColumn('activo', function($row) {
                if ($row->estado === 1) {
                    return '<span class="badge light badge-success">Activo</span>';
                } else {
                    return '<span class="badge light badge-danger">Inactivo</span>';
                }

            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="d-flex">';
                if ($row->estado === 1) {
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-primary shadow btn-sm sharp mr-1 editBtn"><i class="fa fa-pencil"></i></a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger shadow btn-sm sharp mr-1 deleteBtn"><i class="fa fa-trash"></i></a>';
                }
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['action','activo'])
            ->make(true);
    }

    public function create()
    {
        return view('interesbancario.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'banco_id' => 'required|int',
            'saldo_final' => 'required',
            'mes' => 'required|int',
            'anio' => 'required|int',
        ]);

        // Actualizar los estados existentes a 0
        Interesbancario::where('estado', 1)
        ->update(['estado' => 0]);

        $intbancario =Interesbancario::create([
            'banco_id' => $request->input('banco_id'),
            'saldo_final' => $request->input('saldo_final'),
            'mes' => $request->input('mes'),
            'anio' => $request->input('anio'),
            'creado_por' => Auth::id(),
        ]);
        $this->recordAudit('Nuevo', 'Interes bancario creado: ' . $intbancario->id);
        return redirect()->route('intbancario')->with('success', 'Interes bancario creado correctamente.');
    }

    public function getIntbancario($id)
    {
        $intbancario = Interesbancario::find($id);
        return response()->json($intbancario);
    }

    public function show(Interesbancario $intbancario)
    {
        return view('intbancario.show', compact('intbancario'));
    }

    public function edit(Interesbancario $intbancario)
    {
        return view('intbancario.edit', compact('intbancario'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'banco_id' => 'required|int',
            'saldo_final' => 'required',
            'mes' => 'required|int',
            'anio' => 'required|int',
        ]);

        $intbancario = Interesbancario::findOrFail($id);
        $intbancario->banco_id = $request->banco_id;
        $intbancario->saldo_final = $request->saldo_final;
        $intbancario->mes = $request->mes;
        $intbancario->anio = $request->anio;
        $intbancario->creado_por = auth()->id(); // Ajustar según sea necesario
        $intbancario->save();
        $this->recordAudit('Editado', 'Interes bancario editado: ' . $intbancario->id);
        return response()->json(['success' => 'Interes bancario actualizado correctamente.']);
    }

    public function destroy($id)
    {
        $intbancario = Interesbancario::findOrFail($id);
        //$intbancario->delete();
        $intbancario->estado = 0;
        $intbancario->save();
        $this->recordAudit('Eliminado', 'Interes bancario eliminado: ' . $intbancario->id);
        return response()->json(['success' => 'Interes bancario eliminada correctamente.']);
    }
}
