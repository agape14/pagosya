<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class WhatsAppConfigController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Vista principal de configuración
     */
    public function index()
    {
        $status = $this->whatsappService->getStatus();
        $qr = null;

        if (!($status['connected'] ?? false)) {
            $qrData = $this->whatsappService->getQR();
            $qr = $qrData['qr'] ?? null;
        }

        $action = 'whatsapp_config';
        $page_title = 'Configuración WhatsApp';

        return view('configuracion.whatsapp', compact('status', 'qr', 'action', 'page_title'));
    }

    /**
     * API: Obtener estado actual
     */
    public function status()
    {
        return response()->json($this->whatsappService->getStatus());
    }

    /**
     * API: Health check del servicio
     */
    public function health()
    {
        return response()->json($this->whatsappService->healthCheck());
    }

    /**
     * API: Obtener QR
     */
    public function getQR()
    {
        return response()->json($this->whatsappService->getQR());
    }

    /**
     * Cerrar sesión de WhatsApp
     */
    public function disconnect()
    {
        $result = $this->whatsappService->logout();

        if (request()->ajax()) {
            return response()->json($result);
        }

        if ($result['success'] ?? false) {
            return redirect()->route('whatsapp.config')->with('success', 'Sesión de WhatsApp cerrada correctamente');
        }

        return redirect()->route('whatsapp.config')->with('error', 'Error al cerrar sesión: ' . ($result['error'] ?? 'Error desconocido'));
    }

    /**
     * Enviar mensaje individual
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
            'vecino_id' => 'nullable|integer',
            'tipo' => 'nullable|string',
            'image_url' => 'nullable|string'
        ]);

        $result = $this->whatsappService->sendMessage(
            $request->phone,
            $request->message,
            $request->vecino_id,
            $request->tipo ?? 'general',
            $request->image_url
        );

        return response()->json($result);
    }

    /**
     * Obtener grupos de WhatsApp
     */
    public function groups()
    {
        return response()->json($this->whatsappService->getGroups());
    }

    /**
     * Enviar mensaje a grupo
     */
    public function sendToGroup(Request $request)
    {
        $request->validate([
            'group_id' => 'required|string',
            'message' => 'required|string',
            'tipo' => 'nullable|string',
            'image_url' => 'nullable|string'
        ]);

        $result = $this->whatsappService->sendToGroup(
            $request->group_id,
            $request->message,
            $request->tipo ?? 'general',
            $request->image_url
        );

        return response()->json($result);
    }

    /**
     * Obtener logs
     */
    public function logs(Request $request)
    {
        $fechaInicio = $request->fecha_inicio ?? now()->format('Y-m-d');
        $fechaFin = $request->fecha_fin ?? now()->format('Y-m-d');
        $tipo = $request->tipo;

        $logs = $this->whatsappService->getLogs($fechaInicio, $fechaFin, $tipo);

        if ($request->ajax()) {
            return response()->json($logs);
        }

        return view('configuracion.whatsapp-logs', compact('logs', 'fechaInicio', 'fechaFin'));
    }
}

