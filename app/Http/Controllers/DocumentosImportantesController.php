<?php

namespace App\Http\Controllers;

use App\Models\DocumentoImportante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentosImportantesController extends Controller
{
    public function index()
    {
        $page_title = 'Documentos importantes';
        $page_description = 'Documentos PDF del condominio para consulta.';
        $action = __FUNCTION__;
        $logo = 'images/logo.png';
        $logoText = 'images/logo-text.png';

        $documentos = DocumentoImportante::where('activo', true)
            ->orderBy('orden')
            ->orderBy('tag')
            ->orderBy('titulo')
            ->get();

        $esAdmin = Auth::user()->id_perfil <= 2;

        return view('documentos-importantes.index', compact(
            'page_title', 'page_description', 'action', 'logo', 'logoText',
            'documentos', 'esAdmin'
        ));
    }

    public function store(Request $request)
    {
        if (Auth::user()->id_perfil > 2) {
            abort(403, 'No autorizado.');
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:2000',
            'tag' => 'nullable|string|max:100',
            'archivo_pdf' => 'required|file|mimes:pdf|max:15360', // 15MB
        ]);

        $file = $request->file('archivo_pdf');
        $nombreArchivo = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
        $ruta = $file->storeAs('documentos_importantes', $nombreArchivo, 'public');

        $orden = (int) DocumentoImportante::max('orden') + 1;

        DocumentoImportante::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'tag' => $request->tag,
            'ruta_pdf' => $ruta,
            'orden' => $orden,
            'activo' => true,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('documentos-importantes.index')
            ->with('success', 'Documento cargado correctamente.');
    }

    public function destroy($id)
    {
        if (Auth::user()->id_perfil > 2) {
            abort(403, 'No autorizado.');
        }

        $doc = DocumentoImportante::findOrFail($id);
        if ($doc->ruta_pdf && Storage::disk('public')->exists($doc->ruta_pdf)) {
            Storage::disk('public')->delete($doc->ruta_pdf);
        }
        $doc->delete();

        return redirect()->route('documentos-importantes.index')
            ->with('success', 'Documento eliminado.');
    }

    /**
     * Sirve el PDF para visualización (iframe/modal). Solo usuarios autenticados.
     */
    public function ver($id)
    {
        $doc = DocumentoImportante::where('activo', true)->findOrFail($id);
        $path = storage_path('app/public/' . $doc->ruta_pdf);

        if (!file_exists($path)) {
            abort(404, 'Archivo no encontrado.');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $doc->titulo . '.pdf"',
        ]);
    }
}
