<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappLog extends Model
{
    protected $table = 'whatsapp_logs';

    protected $fillable = [
        'vecino_id',
        'tipo',
        'mensaje',
        'telefono',
        'status',
        'error_message',
        'fecha'
    ];

    protected $casts = [
        'fecha' => 'datetime'
    ];

    public function propietario()
    {
        return $this->belongsTo(Propietario::class, 'vecino_id');
    }
}

