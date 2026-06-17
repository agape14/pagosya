<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoImportanteArchivo extends Model
{
    use HasFactory;

    protected $table = 'documentos_importantes_archivos';

    protected $fillable = [
        'documento_importante_id',
        'nombre_archivo',
        'ruta_pdf',
        'orden',
    ];

    protected $casts = [
        'orden' => 'integer',
    ];

    public function documento()
    {
        return $this->belongsTo(DocumentoImportante::class, 'documento_importante_id');
    }
}
