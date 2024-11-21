<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JuntaDirectiva extends Model
{
    use HasFactory;
    protected $table = 'juntas_directivas';

    protected $fillable = ['nombre', 'fecha', 'estado'];

    // Relación con JuntaDirectivaDet (uno a muchos)
    public function detalles()
    {
        return $this->hasMany(JuntaDirectivaDet::class, 'id_junta');
    }
}
