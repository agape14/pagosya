<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;
    protected $table = 'permisos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nombre_permiso', 'parent_id'
    ];
    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'permisos_usuarios', 'id_permiso', 'id_usuario');
    }
    public function hijos()
    {
        return $this->hasMany(Permiso::class, 'parent_id');
    }
}
