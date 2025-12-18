<?php

namespace App\Console\Commands;

use App\Models\Propietario;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class NotificarMorosos extends Command
{
    protected $signature = 'whatsapp:notificar-morosos {--dias=30 : Días de mora mínimo}';
    protected $description = 'Envía notificaciones por WhatsApp a propietarios morosos';

    public function handle()
    {
        $whatsapp = app(WhatsAppService::class);
        
        if (!$whatsapp->isConnected()) {
            $this->error('WhatsApp no está conectado. Configure la conexión primero.');
            return 1;
        }

        $diasMora = $this->option('dias');
        $this->info("Buscando propietarios con mora mayor a {$diasMora} días...");

        // Obtener propietarios morosos (ajusta según tu lógica de negocio)
        $morosos = Propietario::whereHas('pagos', function($q) {
            $q->where('id_estado_pago', 1); // Pendiente
        })->whereNotNull('telefono')->get();

        if ($morosos->isEmpty()) {
            $this->info('No hay propietarios morosos con teléfono registrado.');
            return 0;
        }

        $enviados = 0;
        $fallidos = 0;

        foreach ($morosos as $moroso) {
            $mensaje = "Estimado/a {$moroso->nombre} {$moroso->apellido},\n\n";
            $mensaje .= "Le recordamos que tiene pagos pendientes en el condominio.\n";
            $mensaje .= "Por favor, regularice su situación a la brevedad.\n\n";
            $mensaje .= "Atentamente,\nAdministración del Condominio";

            $result = $whatsapp->sendMessage(
                $moroso->telefono,
                $mensaje,
                $moroso->id,
                'moroso'
            );

            if ($result['success']) {
                $enviados++;
                $this->line("✓ Enviado a {$moroso->nombre} {$moroso->apellido}");
            } else {
                $fallidos++;
                $this->warn("✗ Falló envío a {$moroso->nombre}: " . ($result['error'] ?? 'Error'));
            }

            // Pequeña pausa para no saturar
            sleep(2);
        }

        $this->info("Proceso completado: {$enviados} enviados, {$fallidos} fallidos");
        return 0;
    }
}

