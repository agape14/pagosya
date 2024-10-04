<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodigosPais extends Model
{
    use HasFactory;

    // Define la tabla asociada con el modelo
    protected $table = 'codigo_pais';

    // Define los campos que pueden ser asignados en masa
    protected $fillable = [
        'nombre_pais',
        'codigo_iso',
        'codigo_telefono',
        'bandera',
    ];

    // Puedes definir accesores o mutadores si es necesario para manipular datos
    // Por ejemplo, si quieres siempre devolver el path completo de la bandera
    public function getBanderaAttribute($value)
    {
        return $value ? asset('storage/app/flags/' . $value) : null;
    }

    public function propietarios()
    {
        return $this->hasMany(Propietario::class);
    }

}
