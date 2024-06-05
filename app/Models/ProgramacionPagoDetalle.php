<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramacionPagoDetalle extends Model
{
    use HasFactory;
    protected $table = 'programacion_pagos_detalle';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_programacion','descripcion','id_concepto','monto','creado_por','actualizado_por','estado_id','activo',
    ];
    public function programacion()
    {
        return $this->belongsTo(ProgramacionPago::class, 'id_programacion');
    }
    public function estado()
    {
        return $this->belongsTo(EstadoPago::class, 'estado_id');
    }
}
