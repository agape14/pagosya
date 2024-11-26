<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\Permiso;
use App\Models\PermisoUsuario;
use App\Models\Torre;
use App\Models\Usuario;
use App\Models\Propietario;
use App\Models\Acumulador;
use App\Traits\RecordsAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ConfiguracionController extends Controller
{
    use RecordsAudit;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function perfil_index()
    {
        $page_title = 'Mi Cuenta';
        $page_description = 'Some description for the page';

        $action = __FUNCTION__;
        $usuario= Usuario::with('perfil')->findOrFail(Auth::id());
        $perfiles = Perfil::all();
        return view('perfil.index', compact('page_title', 'page_description','action','usuario','perfiles'));
    }

    public function usuarios_index()
    {
        $page_title = 'Usuarios';
        $page_description = 'Some description for the page';

        $action = __FUNCTION__;
        $torres = Torre::all();
        $perfiles = Perfil::all();
        return view('configuracion.usuarios', compact('torres','perfiles','page_title', 'page_description','action'));
    }

    public function getUsuarios()
    {
        $torres = Usuario::select(
            'usuarios.id',
            'usuarios.nombres_completos',
            'usuarios.correo_electronico',
            'usuarios.telefono',
            'usuarios.id_perfil',
            'perfiles.nombre_perfil',
            'usuarios.activo',
            )
        ->join('perfiles', 'usuarios.id_perfil', '=', 'perfiles.id');
        //->where('usuarios.activo','=','1');
        return DataTables::of($torres)
            ->addColumn('nombre_perfil', function($row){
                if($row->id_perfil==1){
                    return '<span class="badge badge-primary light">'.$row->nombre_perfil.'</span>';
                } elseif($row->id_perfil==2){
                    return '<span class="badge badge-info light">'.$row->nombre_perfil.'</span>';
                }else  {
                    return '<span class="badge badge-dark light">'.$row->nombre_perfil.'</span>';
                }
            })
            ->addColumn('estado', function($row){
                if($row->activo==1){
                    return '<span class="badge light badge-success">Activo</span>';
                } elseif($row->activo==0){
                    return '<span class="badge light badge-danger">Inactivo</span>';
                }
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="d-flex">';
                if($row->activo==1){
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-primary shadow btn-sm sharp mr-1 editBtn"><i class="fa fa-pencil"></i></a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="' . $row->id . '" class="btn btn-danger shadow btn-sm sharp mr-1 deleteBtn"><i class="fa fa-trash"></i></a>';
                }else{
                    $btn .= '<a href="javascript:void(0)" data-id="' . $row->id . '" class="edit btn btn-success shadow btn-sm sharp mr-1 activeBtn"><i class="fa fa-check"></i></a>';
                }
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['nombre_perfil','estado','action'])
            ->make(true);
    }

    public function usuarioshow($id)
    {
        $usuario = Usuario::find($id);
        return response()->json($usuario);
    }

    public function usuariostore(Request $request)
    {
        $idUsuario = $request->input('id');
        $contrasenia = $request->input('contrasenia');
        if($idUsuario){
            $request->validate([
                'nombres_completos' => 'required|string|max:500',
                'correo_electronico' => 'required|string|max:250',
                'telefono' => 'required|string|max:12',
                'id_perfil' => 'required|int',
            ]);

            $edituser = Usuario::findOrFail($idUsuario);
            $edituser->nombres_completos = $request->nombres_completos;
            $edituser->correo_electronico = $request->correo_electronico;
            $edituser->telefono = $request->telefono;
            $edituser->id_perfil = $request->id_perfil;
            if($contrasenia){
                $edituser->contrasenia = bcrypt($request->contrasenia) ;
            }
            $edituser->actualizado_por =  Auth::id();
            $edituser->save();
            $this->recordAudit('Editado', 'Usuario editado: ' . $edituser->id);
            return response()->json(['success' => 'Usuario actualizada correctamente.']);
        }else{

            $request->validate([
                'usuario' => 'required|string|max:150',
                'contrasenia' => 'required|string|max:150',
                'nombres_completos' => 'required|string|max:500',
                'correo_electronico' => 'required|string|max:250',
                'telefono' => 'required|string|max:12',
                'id_perfil' => 'required|int',
            ]);
            $newusuario = new Usuario();
            $newusuario->usuario = $request->usuario;
            $newusuario->nombres_completos = $request->nombres_completos;
            $newusuario->correo_electronico = $request->correo_electronico;
            $newusuario->telefono = $request->telefono;
            $newusuario->id_perfil = $request->id_perfil;
            if($contrasenia){
                $newusuario->contrasenia = bcrypt($request->contrasenia) ;
            }
            $newusuario->creado_por =  Auth::id();
            $newusuario->save();
            $this->recordAudit('Nuevo', 'Usuario creado: ' . $newusuario->id);
            return response()->json(['success' => 'Usuario creado correctamente.'], 200);
        }

    }

    public function destroyuser($id)
    {
        $edituser = Usuario::findOrFail($id);
            $edituser->activo = 0;
            $edituser->actualizado_por =  Auth::id();
            $edituser->save();
            $this->recordAudit('Eliminado', 'Usuario eliminado: ' . $edituser->id);
            return response()->json(['success' => 'Usuario eliminado correctamente.']);
    }

    public function activeuser($id)
    {
        $edituser = Usuario::findOrFail($id);
            $edituser->activo = 1;
            $edituser->actualizado_por =  Auth::id();
            $edituser->save();
            $this->recordAudit('Activado', 'Usuario activado: ' . $edituser->id);
            return response()->json(['success' => 'Usuario activado correctamente.']);
    }

    public function perfiles_index()
    {
        $page_title = 'Perfiles';
        $page_description = 'Some description for the page';
		$torres = Torre::all();
        $action = __FUNCTION__;

        return view('configuracion.perfiles', compact('torres','page_title', 'page_description','action'));
    }

    public function permisos_index()
    {
        $page_title = 'Permisos';
        $page_description = 'Some description for the page';

        $action = __FUNCTION__;
        $permisos = Permiso::with('hijos')->whereNull('parent_id')->get();
        //$permisos = Permiso::all();
        $usuarios = Usuario::all();
        return view('configuracion.permisos', compact('permisos', 'usuarios','page_title', 'page_description','action'));
    }
    public function obtenerPermisosUsuario(Usuario $usuario) {
        return response()->json(['permisos' => $usuario->permisos()->pluck('id')]);
    }

    public function agregarPermiso(Request $request) {

        $usuarioId = $request->input('usuario_id');
        $permisosSeleccionados = $request->input('permisos_seleccionados');

        // Eliminar los permisos existentes del usuario
        PermisoUsuario::where('id_usuario', $usuarioId)->delete();

        // Guardar los permisos para el usuario en la tabla permisos_usuarios
        foreach ($permisosSeleccionados as $permisoId) {
            PermisoUsuario::create([
                'id_usuario' => $usuarioId,
                'id_permiso' => $permisoId
            ]);
        }
        return response()->json(['success' => 'Permisos agregado correctamente.']);

    }

    public function habilitaPopup(Request $request)
    {
        try {
            $acumulador = Acumulador::findOrFail(4);
            // Alternar el valor de correlativo (1 -> 0, 0 -> 1)
            $nuevoEstado = $acumulador->correlativo == 1 ? 0 : 1;
            $mensaje = $nuevoEstado == 1
                ? "Se activó la visualización del popup."
                : "Se desactivó la visualización del popup.";

            // Actualizar el valor y guardar
            $acumulador->correlativo = $nuevoEstado;
            $acumulador->save();

            // Retornar el mensaje de éxito
            return response()->json(['success' => $mensaje]);
        } catch (\Exception $e) {
            // Manejar errores inesperados
            return response()->json(['error' => 'Ocurrió un error al actualizar el estado del popup.']);
        }
    }

    public function habilitaNotifUser(Request $request)
    {
        try {
            $acumulador = Acumulador::findOrFail(5);
            // Alternar el valor de correlativo (1 -> 0, 0 -> 1)
            $nuevoEstado = $acumulador->correlativo == 1 ? 0 : 1;
            $mensaje = $nuevoEstado == 1
                ? "Se activó la notificacion de los usuarios."
                : "Se desactivó la notificacion de los usuarios.";

            // Actualizar el valor y guardar
            $acumulador->correlativo = $nuevoEstado;
            $acumulador->save();

            // Retornar el mensaje de éxito
            return response()->json(['success' => $mensaje]);
        } catch (\Exception $e) {
            // Manejar errores inesperados
            return response()->json(['error' => 'Ocurrió un error al actualizar el estado del popup.']);
        }
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
        $request->validate([
            'nombres_completos' => 'required|string|max:500',
            'telefono' => 'required|string|max:12',
            'correo_electronico' => 'required|string|max:250',
        ]);
        $usuarioupd = Usuario::findOrFail($id);
        $usuarioupd->nombres_completos = $request->nombres_completos;
        $usuarioupd->telefono = $request->telefono;
        $usuarioupd->correo_electronico = $request->correo_electronico;
        $usuarioupd->actualizado_por = auth()->id(); // Ajustar según sea necesario
        // Verificar si se proporcionó una nueva contraseña
        if ($request->filled('contrasenia')) {
            $usuarioupd->contrasenia = bcrypt($request->input('contrasenia'));
        }
        $usuarioupd->save();

        $this->recordAudit('Editado', 'Usuario actualizado: ' . $usuarioupd->id.' asi mismo.');
        return redirect()->back()->with('success', 'Usuario actualizado exitosamente.');
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

    public function crearUsuarioMultiplePropietarios($cantidadPropietario = 120)
    {
        // Establecer la cantidad de propietarios a procesar, con un valor predeterminado de 120
        $idperfil = Perfil::where('nombre_perfil', 'Propietario')->first()->id;

        try {
            // Obtener propietarios que no tienen usuario asociado y un dni diferente de '00000000'
            $propietarios_for = Propietario::whereNull('id_usuario')
                ->where('dni', '<>', '00000000')
                ->orderBy('id', 'asc')
                ->take($cantidadPropietario)
                ->get();

            // Procesar cada propietario para crear el usuario correspondiente
            foreach ($propietarios_for as $propietario) {
                // Crear un nuevo usuario para el propietario
                $newusuario = new Usuario();
                $newusuario->usuario = 'dpto' . $propietario->departamento;
                $newusuario->nombres_completos = $propietario->nombre . " " . $propietario->apellido;
                $newusuario->correo_electronico = $propietario->correo_electronico;
                $newusuario->telefono = $propietario->telefono;
                $newusuario->id_perfil = $idperfil;
                $newusuario->contrasenia = bcrypt($propietario->dni); // Se usa el dni como contraseña temporal
                $newusuario->creado_por = Auth::id();
                $newusuario->save();

                // Asociar el propietario con el nuevo usuario
                $propietario->id_usuario = $newusuario->id;
                $propietario->save();

                // Registrar en la auditoría
                $this->recordAudit('Nuevo', 'Usuario creado: ' . $newusuario->id);
            }

            // Devolver una respuesta de éxito
            return response()->json(['success' => 'Usuarios creados correctamente.'], 200);

        } catch (\Exception $e) {
            // Manejar cualquier error que ocurra durante el proceso
            return response()->json(['error' => 'Ocurrió un error al crear los usuarios: ' . $e->getMessage()], 500);
        }
    }

    public function verificarParametro()
    {
        return response()->json(['ID_TORRE_SISTEMA' => env('ID_TORRE_SISTEMA',6) ], 200);
    }
}
