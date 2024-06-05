<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubPropietario extends Model
{
    use HasFactory;
    protected $table = 'sub_propietarios';
    protected $primaryKey = 'id';
    protected $fillable = [
        'propietario_id', 'sub_propietario_id', 'creado_por', 'actualizado_por', 'tipo_sub_propietario_id'
    ];

    public function propietario()
    {
        return $this->belongsTo(Propietario::class, 'propietario_id');
    }

    public function subPropietario()
    {
        return $this->belongsTo(Propietario::class, 'sub_propietario_id');
    }

    public function tipoSubPropietario()
    {
        return $this->belongsTo(TiposSubPropietarios::class, 'tipo_sub_propietario_id', 'id');
    }
    
}
