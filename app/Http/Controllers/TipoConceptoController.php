<?php

namespace App\Http\Controllers;
use App\Models\TipoConcepto;
use App\Traits\RecordsAudit;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TipoConceptoController extends Controller
{
    use RecordsAudit;
    public function tipoconceptos_index()
    {
        $page_title = 'Tipos Concepto';
        $page_description = 'Some description for the page';
		
		$action = __FUNCTION__;

        $tipos_concepto = TipoConcepto::all();
        return view('tipos_concepto.index', compact('tipos_concepto','page_title', 'page_description','action'));

    }

    public function getTipoConceptos()
    {
        $tipos_conceptos = TipoConcepto::select(['id', 'tipo_concepto']);
        return DataTables::of($tipos_conceptos)
            ->make(true);
    }

    public function create()
    {
        return view('tipos_concepto.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_concepto' => 'required|string|max:20',
        ]);

        $tipoConcepto=TipoConcepto::create($request->all());
        $this->recordAudit('Nuevo', 'tipos concepto creado: ' . $tipoConcepto->id);
        return redirect()->route('tipos_concepto.index');
    }

    public function show(TipoConcepto $tipoConcepto)
    {
        return view('tipos_concepto.show', compact('tipoConcepto'));
    }

    public function edit(TipoConcepto $tipoConcepto)
    {
        return view('tipos_concepto.edit', compact('tipoConcepto'));
    }

    public function update(Request $request, TipoConcepto $tipoConcepto)
    {
        $request->validate([
            'tipo_concepto' => 'required|string|max:20',
        ]);

        $tipoConcepto->update($request->all());
        $this->recordAudit('Editado', 'tipos concepto editado: ' . $tipoConcepto->id);
        return redirect()->route('tipos_concepto.index');
    }

    public function destroy(TipoConcepto $tipoConcepto)
    {
        $tipoConcepto->delete();
        $this->recordAudit('Eliminado', 'tipos concepto eliminado: ' . $tipoConcepto->id);
        return redirect()->route('tipos_concepto.index');
    }
}
