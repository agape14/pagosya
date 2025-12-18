<?php

namespace App\Http\Controllers;

use App\Models\Noticia;
use App\Models\Propietario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class NoticiasController extends Controller
{
    public function noticias_index()
    {
        $page_title = 'Noticias';
        $page_description = 'Noticias y Comunicados del Condominio';
        $logo = "images/logo.png";
        $logoText = "images/logo-text.png";
        $action = __FUNCTION__;

        $noticias = Noticia::orderBy('created_at', 'desc')->paginate(9);
        $propietarios = Propietario::with('codigoPais')->whereNotNull('telefono')->get(); // For WhatsApp list

        return view('noticias.index', compact(
            'page_title', 'page_description', 'action', 'logo', 'logoText',
            'noticias', 'propietarios'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $noticia = new Noticia();
        $noticia->titulo = $request->titulo;
        $noticia->contenido = $request->contenido;
        $noticia->created_by = Auth::id();

        if ($request->hasFile('imagen')) {
            $image = $request->file('imagen');
            $name = time().'_'.uniqid().'.jpg'; // Siempre guardar como JPG optimizado

            // Optimizar imagen: redimensionar y comprimir
            $img = Image::make($image);

            // Redimensionar manteniendo proporción (máximo 1200px de ancho)
            $img->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize(); // No agrandar si es más pequeña
            });

            // Guardar con calidad 75% (buen balance entre calidad y peso)
            $path = 'noticias/' . $name;
            Storage::disk('public')->put($path, $img->encode('jpg', 75)->encoded);

            $noticia->imagen = $path;
        }

        $noticia->save();

        return redirect()->back()->with('success', 'Noticia creada correctamente.');
    }

    // Keep the "actualizarTotales" method if it is still routed here in old code,
    // BUT we should move it to FinanzasController and update routes.
    // I will keep a proxy here just in case, but return error.

}
