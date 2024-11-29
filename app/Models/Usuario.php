<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Authenticatable
{
    use HasFactory;
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'usuario', 'contrasenia', 'nombres_completos', 'correo_electronico','correo_notificaciones', 'telefono', 'id_perfil',
    ];

    protected $hidden = [
        'contrasenia', 'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->contrasenia;
    }

    public function perfil()
    {
        return $this->belongsTo(Perfil::class, 'id_perfil');
    }

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'permisos_usuarios', 'id_usuario', 'id_permiso');
    }

    public function tienePermiso($permisoId) {
        return $this->permisos()->where('id', $permisoId)->exists();
    }

    public function propietario()
    {
        return $this->hasOne(Propietario::class, 'id_usuario', 'id');
    }

}
