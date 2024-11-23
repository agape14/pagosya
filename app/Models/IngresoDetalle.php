<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngresoDetalle extends Model
{
    use HasFactory;
    protected $table = 'ingresos_detalle';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_ingreso', 'descripcion', 'id_concepto', 'monto', 'creado_por', 'actualizado_por',
    ];

    public function ingreso()
    {
        return $this->belongsTo(Ingreso::class, 'id_ingreso');
    }

    public function concepto()
    {
        return $this->belongsTo(Concepto::class, 'id_concepto');
    }
}
