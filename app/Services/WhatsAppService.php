<?php

namespace App\Services;

use App\Models\WhatsappLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.whatsapp.url', 'http://127.0.0.1:3000');
        $this->apiKey = config('services.whatsapp.api_key', '');
    }

    /**
     * Headers comunes para todas las peticiones
     */
    protected function getHeaders(): array
    {
        return [
            'x-api-key' => $this->apiKey,
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * Verificar estado de conexión
     */
    public function getStatus(): array
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/status");

            if ($response->successful()) {
                return $response->json();
            }

            return ['status' => 'DISCONNECTED', 'connected' => false, 'error' => 'No se pudo conectar'];
        } catch (\Exception $e) {
            Log::error('WhatsApp Status Error: ' . $e->getMessage());
            return ['status' => 'ERROR', 'connected' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Verificar si el servicio está activo (health check)
     */
    public function healthCheck(): array
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/health");

            if ($response->successful()) {
                return array_merge($response->json(), ['alive' => true]);
            }

            return ['alive' => false, 'error' => 'Servicio no responde'];
        } catch (\Exception $e) {
            return ['alive' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Verificar si está conectado
     */
    public function isConnected(): bool
    {
        $status = $this->getStatus();
        return $status['connected'] ?? false;
    }

    /**
     * Obtener código QR
     */
    public function getQR(): array
    {
        try {
            $response = Http::timeout(15)
                ->withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/get-qr");

            if ($response->successful()) {
                return $response->json();
            }

            return ['success' => false, 'qr' => null, 'message' => 'Error al obtener QR'];
        } catch (\Exception $e) {
            Log::error('WhatsApp QR Error: ' . $e->getMessage());
            return ['success' => false, 'qr' => null, 'error' => $e->getMessage()];
        }
    }

    /**
     * Enviar mensaje
     */
    public function sendMessage(string $phone, string $message, ?int $vecinoId = null, string $tipo = 'general', ?string $imageUrl = null): array
    {
        // Verificar conexión primero
        if (!$this->isConnected()) {
            $this->logMessage($vecinoId, $tipo, $message, $phone, 'fallido', 'WhatsApp no vinculado');
            return ['success' => false, 'error' => 'WhatsApp no está conectado'];
        }

        try {
            $payload = [
                'phone' => $phone,
                'message' => $message
            ];

            if ($imageUrl) {
                $payload['imageUrl'] = $imageUrl;
            }

            $response = Http::timeout(120)
                ->withHeaders($this->getHeaders())
                ->post("{$this->baseUrl}/send-message", $payload);

            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                $this->logMessage($vecinoId, $tipo, $message, $phone, 'enviado');
                return ['success' => true, 'message' => 'Mensaje enviado'];
            }

            $error = $result['error'] ?? 'Error desconocido';
            $this->logMessage($vecinoId, $tipo, $message, $phone, 'fallido', $error);
            return ['success' => false, 'error' => $error];

        } catch (\Exception $e) {
            Log::error('WhatsApp Send Error: ' . $e->getMessage());
            $this->logMessage($vecinoId, $tipo, $message, $phone, 'fallido', $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout(): array
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders($this->getHeaders())
                ->post("{$this->baseUrl}/logout");

            if ($response->successful()) {
                return $response->json();
            }

            return ['success' => false, 'error' => 'Error al cerrar sesión'];
        } catch (\Exception $e) {
            Log::error('WhatsApp Logout Error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Registrar log de mensaje
     */
    protected function logMessage(?int $vecinoId, string $tipo, string $mensaje, ?string $telefono, string $status, ?string $error = null): void
    {
        try {
            WhatsappLog::create([
                'vecino_id' => $vecinoId,
                'tipo' => $tipo,
                'mensaje' => $mensaje,
                'telefono' => $telefono,
                'status' => $status,
                'error_message' => $error,
                'fecha' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('WhatsApp Log Error: ' . $e->getMessage());
        }
    }

    /**
     * Obtener grupos de WhatsApp
     */
    public function getGroups(): array
    {
        if (!$this->isConnected()) {
            return ['success' => false, 'error' => 'WhatsApp no está conectado', 'groups' => []];
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders($this->getHeaders())
                ->get("{$this->baseUrl}/groups");

            if ($response->successful()) {
                return $response->json();
            }

            return ['success' => false, 'groups' => [], 'error' => 'Error al obtener grupos'];
        } catch (\Exception $e) {
            Log::error('WhatsApp Groups Error: ' . $e->getMessage());
            return ['success' => false, 'groups' => [], 'error' => $e->getMessage()];
        }
    }

    /**
     * Enviar mensaje a grupo
     */
    public function sendToGroup(string $groupId, string $message, string $tipo = 'general', ?string $imageUrl = null): array
    {
        if (!$this->isConnected()) {
            $this->logMessage(null, $tipo, $message, 'Grupo: ' . $groupId, 'fallido', 'WhatsApp no vinculado');
            return ['success' => false, 'error' => 'WhatsApp no está conectado'];
        }

        try {
            $payload = [
                'groupId' => $groupId,
                'message' => $message
            ];

            if ($imageUrl) {
                $payload['imageUrl'] = $imageUrl;
            }

            $response = Http::timeout(30)
                ->withHeaders($this->getHeaders())
                ->post("{$this->baseUrl}/send-to-group", $payload);
            $result = $response->json();

            if ($response->successful() && ($result['success'] ?? false)) {
                // Log como "enviado" - el mensaje está en proceso en Node
                $this->logMessage(null, $tipo, $message, 'Grupo: ' . $groupId, 'enviado');
                return ['success' => true, 'message' => 'Mensaje enviado al grupo'];
            }

            $error = $result['error'] ?? 'Error desconocido';
            $this->logMessage(null, $tipo, $message, 'Grupo: ' . $groupId, 'fallido', $error);
            return ['success' => false, 'error' => $error];

        } catch (\Exception $e) {
            Log::error('WhatsApp Group Send Error: ' . $e->getMessage());
            $this->logMessage(null, $tipo, $message, 'Grupo: ' . $groupId, 'fallido', $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Obtener logs
     */
    public function getLogs(?string $fechaInicio = null, ?string $fechaFin = null, ?string $tipo = null)
    {
        $query = WhatsappLog::with('propietario')->orderBy('fecha', 'desc');

        if ($fechaInicio) {
            $query->whereDate('fecha', '>=', $fechaInicio);
        }

        if ($fechaFin) {
            $query->whereDate('fecha', '<=', $fechaFin);
        }

        if ($tipo) {
            $query->where('tipo', $tipo);
        }

        return $query->paginate(50);
    }
}

