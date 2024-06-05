<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermisoUsuario extends Model
{
    use HasFactory;
    protected $table = 'permisos_usuarios';
    protected $fillable = [
        'id_usuario','id_permiso'
    ];
    
}
