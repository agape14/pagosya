<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acumulador extends Model
{
    use HasFactory;
    // Nombre de la tabla asociada (opcional si el nombre coincide con plural en inglés)
    protected $table = 'acumuladores';

    // Campos que se pueden llenar masivamente
    protected $fillable = ['descripcion', 'monto', 'correlativo'];

    public static function actualizarCorrelativo(int $codigo, int $nuevoCorrelativo): void
    {
        $acumulador = self::where('id', $codigo)->first();

        if ($acumulador) {
            $acumulador->correlativo = $nuevoCorrelativo;
            $acumulador->save();
        }
    }
}
