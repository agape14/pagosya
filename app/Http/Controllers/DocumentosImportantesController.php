<?php

namespace App\Http\Controllers;

use App\Models\DocumentoImportante;
use App\Models\DocumentoImportanteArchivo;
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

        $documentos = DocumentoImportante::with('archivos')
            ->where('activo', true)
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
            'archivo_pdf' => 'required|array|min:1',
            'archivo_pdf.*' => 'file|mimes:pdf|max:15360',
        ], [
            'archivo_pdf.required' => 'Debe seleccionar al menos un archivo PDF.',
            'archivo_pdf.min' => 'Debe seleccionar al menos un archivo PDF.',
            'archivo_pdf.*.mimes' => 'Todos los archivos deben ser PDF.',
            'archivo_pdf.*.max' => 'Cada archivo PDF no puede superar 15 MB.',
        ]);

        $orden = (int) DocumentoImportante::max('orden') + 1;

        $documento = DocumentoImportante::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'tag' => $request->tag,
            'ruta_pdf' => '',
            'orden' => $orden,
            'activo' => true,
            'created_by' => Auth::id(),
        ]);

        $primeraRuta = null;

        foreach ($request->file('archivo_pdf') as $index => $file) {
            $nombreArchivo = time() . '_' . ($index + 1) . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $ruta = $file->storeAs('documentos_importantes', $nombreArchivo, 'public');

            if ($primeraRuta === null) {
                $primeraRuta = $ruta;
            }

            DocumentoImportanteArchivo::create([
                'documento_importante_id' => $documento->id,
                'nombre_archivo' => $file->getClientOriginalName(),
                'ruta_pdf' => $ruta,
                'orden' => $index + 1,
            ]);
        }

        $documento->update(['ruta_pdf' => $primeraRuta]);

        $cantidad = count($request->file('archivo_pdf'));
        $mensaje = $cantidad > 1
            ? "Documento cargado correctamente con {$cantidad} archivos PDF."
            : 'Documento cargado correctamente.';

        return redirect()->route('documentos-importantes.index')
            ->with('success', $mensaje);
    }

    public function destroy($id)
    {
        if (Auth::user()->id_perfil > 2) {
            abort(403, 'No autorizado.');
        }

        $doc = DocumentoImportante::with('archivos')->findOrFail($id);

        foreach ($doc->archivos as $archivo) {
            if ($archivo->ruta_pdf && Storage::disk('public')->exists($archivo->ruta_pdf)) {
                Storage::disk('public')->delete($archivo->ruta_pdf);
            }
        }

        if ($doc->ruta_pdf && Storage::disk('public')->exists($doc->ruta_pdf)) {
            Storage::disk('public')->delete($doc->ruta_pdf);
        }

        $doc->delete();

        return redirect()->route('documentos-importantes.index')
            ->with('success', 'Documento eliminado.');
    }

    /**
     * Sirve un PDF para visualización (iframe/modal). Solo usuarios autenticados.
     */
    public function ver($id, $archivoId = null)
    {
        $doc = DocumentoImportante::with('archivos')->where('activo', true)->findOrFail($id);

        $archivo = null;

        if ($archivoId) {
            $archivo = $doc->archivos->firstWhere('id', (int) $archivoId);
        } else {
            $archivo = $doc->archivos->first();
        }

        $ruta = $archivo ? $archivo->ruta_pdf : $doc->ruta_pdf;
        $nombre = $archivo ? $archivo->nombre_archivo : ($doc->titulo . '.pdf');

        if (!$ruta) {
            abort(404, 'Archivo no encontrado.');
        }

        $path = storage_path('app/public/' . $ruta);

        if (!file_exists($path)) {
            abort(404, 'Archivo no encontrado.');
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $nombre . '"',
        ]);
    }
}
