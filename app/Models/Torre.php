<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Torre extends Model
{
    use HasFactory;
    protected $table = 'torres';
    protected $primaryKey = 'id';
    protected $fillable = ['nombre_torre','creado_por','actualizado_por'];

    public function propietarios()
    {
        return $this->hasMany(Propietario::class, 'id');
    }
}
