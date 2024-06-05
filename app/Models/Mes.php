<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mes extends Model
{
    use HasFactory;
    protected $table = 'meses';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'mes', 
        'nombremes'
    ];
    public function conceptos()
    {
        return $this->hasMany(Concepto::class, 'mes');
    }
}
