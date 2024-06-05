<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoConcepto extends Model
{
    use HasFactory;
    protected $table = 'tipos_concepto';
    protected $primaryKey = 'id';
    protected $fillable = ['tipo_concepto'];
}
