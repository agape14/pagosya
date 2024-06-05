<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = ['creado_por', 'evento', 'detalles'];
}
