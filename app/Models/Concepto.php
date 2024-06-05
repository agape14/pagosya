<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concepto extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = ['id_tipo_concepto', 'descripcion_concepto', 'mes', 'anio', 'creado_por', 'actualizado_por', 'created_at', 'updated_at','activo'];

    public function tipoConcepto()
    {
        return $this->belongsTo(TipoConcepto::class, 'id_tipo_concepto','id');
    }

    public function nombreMes()
    {
        return $this->belongsTo(Mes::class, 'mes','mes');
    }

    public function detalles()
    {
        return $this->hasMany(PagoDetalle::class, 'id_concepto');
    }
}
