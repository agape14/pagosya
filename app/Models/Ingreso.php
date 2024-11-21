<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    use HasFactory;
    protected $table = 'ingresos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'fecha', 'id_torre', 'total', 'activo', 'evidencia', 'creado_por', 'actualizado_por',
    ];

    public function detalles()
    {
        return $this->hasMany(IngresoDetalle::class, 'id_ingreso');
    }
}
