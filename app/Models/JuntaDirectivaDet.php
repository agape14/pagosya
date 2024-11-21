<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JuntaDirectivaDet extends Model
{
    use HasFactory;
    protected $table = 'juntas_directivas_det';

    protected $fillable = ['id_junta', 'id_propietario', 'id_cargo', 'nombres'];

    // Relación con JuntaDirectiva (muchos a uno)
    public function juntaDirectiva()
    {
        return $this->belongsTo(JuntaDirectiva::class, 'id_junta');
    }

    // Relación con Cargo (muchos a uno)
    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'id_cargo');
    }

    // Relación con Propietario (ajustar el modelo según tu estructura)
    public function propietario()
    {
        return $this->belongsTo(Propietario::class, 'id_propietario');
    }
}
