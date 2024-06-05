<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GastoDetalle extends Model
{
    use HasFactory;
    protected $table = 'gastos_detalle';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_gasto','descripcion','id_concepto','monto','creado_por','actualizado_por',
    ];
    public function gasto()
    {
        return $this->belongsTo(Gasto::class, 'id_gasto');
    }
}
