<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoPago extends Model
{
    use HasFactory;
    protected $table = 'estados_pagos';
    protected $fillable = ['nombre'];
}
