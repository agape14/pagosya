<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Propietario;

class NotificarPropietarios extends Mailable
{
    use Queueable, SerializesModels;

    protected $propietario;

    public function __construct(Propietario $propietario)
    {
        $this->propietario = $propietario;
    }

    public function build()
    {
        return $this->view('emails.notificacionuser')
                    ->subject(env('Notificación de PagosYA'))
                    ->with([
                        'propietario' => $this->propietario,
                    ])
                    ->withSwiftMessage(function ($message) {
                        $message->getHeaders()->addTextHeader('Content-Type', 'text/html; charset=UTF-8');
                    });
    }
}
