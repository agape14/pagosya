<?php

namespace App\Jobs;

use App\Models\Pago;
use App\Models\PagoDetalle;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyPaymentViaWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pagoId;
    protected $pagoDetalleId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $pagoId, int $pagoDetalleId)
    {
        $this->pagoId = $pagoId;
        $this->pagoDetalleId = $pagoDetalleId;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $pago = Pago::with(['propietario', 'detalles.concepto'])->find($this->pagoId);

            if (!$pago || !$pago->propietario) {
                Log::warning("NotifyPaymentViaWhatsApp: Pago o propietario no encontrado - Pago ID: {$this->pagoId}");
                return;
            }

            $propietario = $pago->propietario;
            $telefono = $propietario->telefono;

            if (empty($telefono)) {
                Log::warning("NotifyPaymentViaWhatsApp: Propietario sin teléfono - ID: {$propietario->id}");
                return;
            }

            // Validar teléfono
            if (!$this->esTelefonoValido($telefono)) {
                Log::info("NotifyPaymentViaWhatsApp: Teléfono inválido - Propietario ID: {$propietario->id}");
                return;
            }

            // Formatear teléfono con prefijo 51 (Perú)
            $telefonoFormateado = $this->formatearTelefono($telefono);

            // Calcular total abonado sumando todos los detalles del pago
            $totalAbonado = PagoDetalle::where('id_pago', $this->pagoId)->sum('monto_pagado');
            $montoTotal = $pago->total;
            $saldoPendiente = $montoTotal - $totalAbonado;

            // Obtener concepto del detalle actual
            $pagoDetalle = PagoDetalle::with('concepto')->find($this->pagoDetalleId);
            $concepto = $pagoDetalle && $pagoDetalle->concepto
                ? $pagoDetalle->concepto->descripcion_concepto
                : 'Cuota';

            // Obtener monto del abono actual
            $montoAbonado = $pagoDetalle ? $pagoDetalle->monto_pagado : 0;

            // Construir mensaje
            if ($saldoPendiente <= 0) {
                $mensaje = "✅ Pago Confirmado: Se registró su pago total de S/" . number_format($totalAbonado, 2) . " para {$concepto}. Su saldo es S/0.00. ¡Gracias!";
            } else {
                $mensaje = "📝 Abono Registrado: Recibimos S/" . number_format($montoAbonado, 2) . ". Para completar su cuota de {$concepto}, resta un saldo de S/" . number_format($saldoPendiente, 2) . ". Por favor, regularice a la brevedad.";
            }

            // Enviar mensaje vía WhatsAppService
            $whatsappService = new WhatsAppService();
            $whatsappService->sendMessage(
                $telefonoFormateado,
                $mensaje,
                $propietario->id,
                'pago'
            );

        } catch (\Exception $e) {
            Log::error("NotifyPaymentViaWhatsApp Error: " . $e->getMessage());
        }
    }

    /**
     * Validar si el teléfono es válido
     */
    private function esTelefonoValido(?string $telefono): bool
    {
        if (empty($telefono)) {
            return false;
        }

        $telefonoLimpio = preg_replace('/[^0-9]/', '', $telefono);

        // Excluir teléfonos vacíos o solo ceros
        if (empty($telefonoLimpio) || preg_match('/^0+$/', $telefonoLimpio)) {
            return false;
        }

        return true;
    }

    /**
     * Formatear teléfono con prefijo 51
     */
    private function formatearTelefono(string $telefono): string
    {
        // Limpiar caracteres no numéricos
        $telefono = preg_replace('/[^0-9]/', '', $telefono);

        // Agregar prefijo 51 si no lo tiene
        if (!str_starts_with($telefono, '51')) {
            $telefono = '51' . $telefono;
        }

        return $telefono;
    }
}

