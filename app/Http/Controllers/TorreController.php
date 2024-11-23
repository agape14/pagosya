<?php

namespace App\Http\Controllers;

use App\Models\Torre;
use App\Traits\RecordsAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TorreController extends Controller
{
    use RecordsAudit;
    public function torres_index()
    {
        $page_title = 'Torres';
        $page_description = 'Some description for the page';

		$action = __FUNCTION__;

        $torres = Torre::all();
        return view('torres.index', compact('torres','page_title', 'page_description','action'));
    }

    public function getTorres()
    {
        $torres = Torre::select(['id', 'nombre_torre']);
        return DataTables::of($torres)
            ->addColumn('action', function ($row) {
                $btn = '<div class="d-flex">';
                $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-primary shadow btn-sm sharp mr-1 editBtn"><i class="fa fa-pencil"></i></a>';
                $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger shadow btn-sm sharp mr-1 deleteBtn"><i class="fa fa-trash"></i></a>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('torres.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'nombre_torre' => 'required|string|max:5',
        ]);
        $torre =Torre::create([
            'nombre_torre' => $request->input('nombre_torre'),
            'creado_por' => Auth::id(),
        ]);
        $this->recordAudit('Nuevo', 'Torre creado: ' . $torre->id);
        return redirect()->route('torres')->with('success', 'Torre creada correctamente.');
    }

    public function getTorre($id)
    {
        $torre = Torre::find($id);
        return response()->json($torre);
    }

    public function show(Torre $torre)
    {
        return view('torres.show', compact('torre'));
    }

    public function edit(Torre $torre)
    {
        return view('torres.edit', compact('torre'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_torre' => 'required|string|max:5',
        ]);

        $torre = Torre::findOrFail($id);
        $torre->nombre_torre = $request->nombre_torre;
        $torre->creado_por = auth()->id(); // Ajustar según sea necesario
        $torre->save();
        $this->recordAudit('Editado', 'Torre editado: ' . $torre->id);
        return response()->json(['success' => 'Torre actualizada correctamente.']);
    }

    public function destroy($id)
    {
        $torre = Torre::findOrFail($id);
        $torre->delete();
        $this->recordAudit('Eliminado', 'Torre eliminado: ' . $torre->id);
        return response()->json(['success' => 'Torre eliminada correctamente.']);
    }
}
