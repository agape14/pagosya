<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramacionPago extends Model
{
    use HasFactory;
    protected $table = 'programacion_pagos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_propietario','fecha_inicio','fecha_fin','total','creado_por','actualizado_por','estado_id','activo',
    ];
    public function detalles()
    {
        return $this->hasMany(ProgramacionPagoDetalle::class, 'id_programacion');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoPago::class, 'estado_id');
    }
}
