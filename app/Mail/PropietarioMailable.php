<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PropietarioMailable extends Mailable
{
    public $propietario; // Datos del propietario

    public function __construct($propietario)
    {
        $this->propietario = $propietario;
    }

    public function build()
    {
        return $this->from('sistemas@delacruzdev.tech', 'PagosYA')
            ->subject('Datos de acceso - PagosYA')
            ->view('emails.notificacionuser')
            ->with([
                'nombre' => $this->propietario->nombre,
                'apellido' => $this->propietario->apellido,
                'usuario' => 'dpto' . $this->propietario->departamento,
                'clave' => $this->propietario->dni,
            ]);
    }
}
