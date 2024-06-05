<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposSubPropietarios extends Model
{
    use HasFactory;
    protected $table = 'tipos_sub_propietarios';
    protected $primaryKey = 'id';
    protected $fillable = ['tipo'];
}
