<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InteresBancario extends Model
{
    use HasFactory;
    protected $table = 'intereses_bancarios';

    protected $fillable = [
        'nombre',
        'monto',
        'mes',
        'anio',
        'tasa_interes',
        'saldo_inicial',
        'saldo_final',
        'banco_id',
        'creado_por',
        'estado',
    ];


    /**
     * Relación con el usuario que creó el registro.
     */
    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    public function banco()
    {
        return $this->belongsTo(Banco::class, 'banco_id');
    }

    public function nombreMes()
    {
        return $this->belongsTo(Mes::class, 'mes','mes');
    }
}
