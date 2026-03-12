<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoImportante extends Model
{
    use HasFactory;

    protected $table = 'documentos_importantes';

    protected $fillable = [
        'titulo',
        'descripcion',
        'tag',
        'ruta_pdf',
        'orden',
        'activo',
        'created_by',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'orden' => 'integer',
    ];
}
