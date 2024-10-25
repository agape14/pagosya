<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoDetalle extends Model
{
    use HasFactory;
    protected $table = 'pagos_detalle';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_pago','id_concepto','monto','monto_pagado','cuotas_pagadas','activo','estado_id','evidencia_det','creado_por','actualizado_por',
    ];

    /** */
    public function pago()
    {
        return $this->belongsTo(Pago::class, 'id_pago');
    }

    public function concepto()
    {
        return $this->belongsTo(Concepto::class, 'id_concepto');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoPago::class, 'estado_id');
    }
}
