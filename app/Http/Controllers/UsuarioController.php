<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Acumulador;
use App\Models\Propietario;
use App\Models\Usuario;
use App\Mail\PropietarioMailable;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function enviarNotificacionUsuario($id)
    {
        try {
            // Obtener el valor de 'correlativo' desde la tabla Acumulador
            $notificarTodosUsuario = Acumulador::where('id', 5)->first()?->correlativo ?? 0;

            // Colección de propietarios para notificar
            $propietarios = collect();

            if ($notificarTodosUsuario == 0) {
                return response()->json(['message' => 'La configuración de notificación de correo está inactiva.'], 500);
            } elseif ($notificarTodosUsuario == 1) {
                $propietarios = Propietario::where('id', $id)->get();
            }

            // Validar si hay propietarios para notificar
            if ($propietarios->isEmpty()) {
                return response()->json(['message' => 'No hay propietario para notificar.'], 500);
            }

            // Dirección de correo en copia desde el archivo .env
            $emailCopia = env('MAIL_FROM_ADDRESS');

            foreach ($propietarios as $propietario) {
                // Validar que el correo del propietario sea válido antes de enviar

                    $email = new PropietarioMailable($propietario);

                    // Enviar correo con copia si está configurado
                    if ($emailCopia) {
                        Mail::to($propietario->correo_electronico)
                            ->cc($emailCopia)
                            ->send($email);
                    } else {
                        Mail::to($propietario->correo_electronico)
                            ->send($email);
                    }

                    \Log::warning("correo_electronico inválido para el propietario notifcado: {$propietario->correo_electronico}");


                // Validar si existe el usuario antes de actualizar
                $usuario = Usuario::find($propietario->id_usuario);
                if ($usuario) {
                    $usuario->contrasenia = bcrypt($propietario->dni);
                    $usuario->correo_notificaciones = 1;
                    $usuario->actualizado_por = auth()->id(); // Ajustar según el contexto de autenticación
                    $usuario->save();
                } else {
                    \Log::warning("No se encontró el usuario relacionado para el propietario con DNI: {$propietario->dni}");
                }
            }

            return response()->json(['message' => 'Correos enviados con éxito.'], 200);
        } catch (\Exception $e) {
            \Log::error('Error al enviar notificaciones: ' . $e->getMessage());
            return response()->json(['message' => 'Ocurrió un error al enviar los correos.', 'error' => $e->getMessage()], 500);
        }
    }


}
