<?php

return [
    'panelcontrol' => 1,
    'mantenimientos' => 2,
    'torres' => 3,
    'tipoconcepto' => 4,
    'conceptos' => 5,
    'propietarios' => 6,
    'pagos' => 7,
    'programacion' => 8,
    'registropagos' => 9,
    'gastos' => 10,
    'configuracion' => 11,
    'usuarios' => 12,
    'permisos' => 13,
    'agregar' => 14,
    'editar' => 15,
    'eliminar' => 16,
    'ingresos' => 17,
    'reportes' => 18,
    'intbancario' => 19,
    'noticias' => 20,
    'documentosimportantes' => 21,
    'finanzas' => 22,

    /*
    | Permisos base asignables masivamente a propietarios/usuarios no administradores.
    | Estado de cuenta (/panel) y Mi cuenta (/cuenta) están siempre disponibles sin permiso.
    */
    'permisos_base_usuario' => [
        'finanzas' => 'Finanzas',
        'noticias' => 'Noticias',
        'documentosimportantes' => 'Documentos importantes',
    ],

    // id_perfil <= este valor se considera administrador (excluido de asignación masiva)
    'perfil_admin_max' => 2,
];
