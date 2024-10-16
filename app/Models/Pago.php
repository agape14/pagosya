<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    protected $table = 'pagos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_propietario','fecha','total','creado_por','actualizado_por','activo','estado_id','id_programacion','cuotas_totales','incobrable',
    ];
    /***** */

    public function propietario()
    {
        return $this->belongsTo(Propietario::class, 'id_propietario');
    }

    public function detalles()
    {
        return $this->hasMany(PagoDetalle::class, 'id_pago');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoPago::class, 'estado_id');
    }

    public function programacion()
    {
        return $this->belongsTo(ProgramacionPago::class, 'id_programacion');
    }
}
