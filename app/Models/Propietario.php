<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Propietario extends Model
{
    use HasFactory;
    protected $table = 'propietarios';
    protected $primaryKey = 'id';
    protected $fillable = ['nombre', 'apellido', 'correo_electronico', 'telefono', 'departamento', 'id_torre', 'creado_por','actualizado_por'];

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_propietario');
    }
    
    public function torre()
    {
        return $this->belongsTo(Torre::class, 'id_torre');
    }

    public function subPropietarios()
    {
        return $this->hasMany(SubPropietario::class, 'propietario_id');
    }
}
