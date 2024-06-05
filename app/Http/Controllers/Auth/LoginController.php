<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Anhskohbo\NoCaptcha\NoCaptcha;
use App\Models\PermisoUsuario;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        $page_title = 'Pagina de Login';
        $page_description = 'Alguna descripcion de la pagina';
		
		$action = __FUNCTION__;

        return view('page.login', compact('page_title', 'page_description','action'));
    }

    public function login(Request $request)
    {
        $credentials = $request->only('usuario', 'contrasenia');

        // Validar los datos
        $request->validate([
            'usuario' => 'required|string',
            'contrasenia' => 'required|string',
          ]);
        /**
         * 
         *   'g-recaptcha-response' => 'required|captcha',
        *], [
        *    'g-recaptcha-response.required' => 'Por favor, completa el reCAPTCHA.',
        *    'g-recaptcha-response.captcha' => 'La validación de reCAPTCHA ha fallado.',
        **/

        // Intentar autenticar al usuario
        if (Auth::attempt(['usuario' => $credentials['usuario'], 'password' => $credentials['contrasenia']])) {
            // Obtener el usuario autenticado
            $user = Auth::user();

            // Guardar información relevante en la sesión
            session([
                'usuario_id' => $user->id,
                'nombre_usuario' => $user->nombres_completos,
                'perfil_usuario' => $user->perfil->nombre_perfil
            ]);
            // Obtener los permisos del usuario
            $permisosUsuario = PermisoUsuario::where('id_usuario', $user->id)->pluck('id_permiso')->toArray();

            // Guardar los permisos del usuario en la sesión
            session(['permisos_usuario' => $permisosUsuario]);

            return redirect()->intended('panel'); // Cambia 'dashboard' por la ruta que desees
        }

        // Si la autenticación falla, redirigir de vuelta con un mensaje de error
        return back()->withErrors([
            'usuario' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login'); // Cambia '/login' por la ruta que desees
    }
}
