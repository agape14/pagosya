<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;
    protected $table = 'cargos';

    protected $fillable = ['nombre'];

    // Relación con JuntaDirectivaDet (uno a muchos)
    public function detalles()
    {
        return $this->hasMany(JuntaDirectivaDet::class, 'id_cargo');
    }
}
