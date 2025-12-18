@extends('layout.default')

@section('content')
<style>
    .status-badge {
        font-size: 1rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
    }
    .qr-container {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        min-height: 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    .qr-container img {
        max-width: 280px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .whatsapp-card {
        border: none;
        box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        border-radius: 12px;
    }
    .log-table th {
        background: #f8f9fa;
        font-weight: 600;
    }
    .status-enviado { background-color: #28a745; }
    .status-fallido { background-color: #dc3545; }
    .status-pendiente { background-color: #ffc107; color: #000; }
    .pulse {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    .connected-icon {
        font-size: 5rem;
        color: #28a745;
    }
    .service-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
    }
</style>

<div class="container-fluid">
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>Configuración de WhatsApp</h4>
                <p class="mb-0">Gestiona la conexión de WhatsApp para envío de notificaciones</p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
                <li class="breadcrumb-item active">WhatsApp</li>
            </ol>
        </div>
    </div>

    <!-- Alertas -->
    <div id="alertContainer"></div>

    <div class="row">
        <!-- Estado de Conexión -->
        <div class="col-xl-6 col-lg-12">
            <div class="card whatsapp-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-whatsapp text-success mr-2"></i>Estado de Conexión
                    </h4>
                    <span id="serviceStatus" class="service-status bg-secondary text-white">
                        <i class="fa fa-circle-o-notch fa-spin mr-1"></i> Verificando...
                    </span>
                </div>
                <div class="card-body">
                    <!-- Status Badge -->
                    <div class="text-center mb-4">
                        <span id="statusBadge" class="badge status-badge badge-secondary">
                            <i class="fa fa-circle-o-notch fa-spin mr-2"></i>
                            <span id="statusText">Verificando...</span>
                        </span>
                    </div>

                    <!-- Container dinámico -->
                    <div id="dynamicContainer">
                        <!-- Cargando -->
                        <div id="loadingContainer" class="qr-container">
                            <i class="fa fa-circle-o-notch fa-spin fa-3x text-primary mb-3"></i>
                            <p class="text-muted">Conectando con el servicio...</p>
                        </div>

                        <!-- QR Container (oculto inicialmente) -->
                        <div id="qrContainer" class="qr-container d-none">
                            <p class="text-muted mb-3">Escanea el código QR con WhatsApp:</p>
                            <img id="qrImage" src="" alt="Código QR" class="mb-3">
                            <small class="text-muted d-block mb-3">El QR se actualiza automáticamente</small>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btnRefreshQR">
                                <i class="fa fa-refresh mr-1"></i> Refrescar QR
                            </button>
                        </div>

                        <!-- Conectado (oculto inicialmente) -->
                        <div id="connectedContainer" class="qr-container d-none">
                            <i class="fa fa-whatsapp connected-icon mb-3"></i>
                            <h5 class="text-success mb-2">¡WhatsApp Conectado!</h5>
                            <p class="text-muted mb-4">El sistema está listo para enviar mensajes</p>
                            <button type="button" class="btn btn-danger" id="btnDisconnect">
                                <i class="fa fa-sign-out mr-1"></i> Cerrar Sesión
                            </button>
                        </div>

                        <!-- Error de servicio (oculto inicialmente) -->
                        <div id="errorContainer" class="qr-container d-none">
                            <i class="fa fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                            <h5 class="text-danger mb-2">Servicio No Disponible</h5>
                            <p class="text-muted mb-3">No se puede conectar con el servicio de WhatsApp</p>
                            <button type="button" class="btn btn-primary" id="btnRetryConnection">
                                <i class="fa fa-refresh mr-1"></i> Reintentar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instrucciones -->
        <div class="col-xl-6 col-lg-12">
            <div class="card whatsapp-card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-info-circle text-info mr-2"></i>Instrucciones
                    </h4>
                </div>
                <div class="card-body">
                    <ol class="pl-3">
                        <li class="mb-2">Abre WhatsApp en tu teléfono</li>
                        <li class="mb-2">Ve a <strong>Configuración > Dispositivos vinculados</strong></li>
                        <li class="mb-2">Toca <strong>Vincular un dispositivo</strong></li>
                        <li class="mb-2">Escanea el código QR que aparece en esta pantalla</li>
                        <li class="mb-2">Espera a que el estado cambie a "Conectado"</li>
                    </ol>
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="fa fa-exclamation-triangle mr-2"></i>
                        <strong>Importante:</strong> No cierres sesión de WhatsApp Web en tu teléfono.
                    </div>
                </div>
            </div>

            <!-- Info del servicio -->
            <div class="card whatsapp-card mt-4">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-server text-primary mr-2"></i>Información del Servicio
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td><strong>URL del Servicio:</strong></td>
                            <td><code id="serviceUrl">{{ config('services.whatsapp.url') }}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Estado:</strong></td>
                            <td><span id="serviceAlive" class="badge badge-secondary">-</span></td>
                        </tr>
                        <tr>
                            <td><strong>Uptime:</strong></td>
                            <td><span id="serviceUptime">-</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs de Mensajes -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card whatsapp-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-history mr-2"></i>Historial de Mensajes
                    </h4>
                </div>
                <div class="card-body">
                    <form class="row mb-4" id="filterForm">
                        <div class="col-md-3">
                            <label>Fecha Inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" id="fechaInicio" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Fecha Fin</label>
                            <input type="date" class="form-control" name="fecha_fin" id="fechaFin" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label>Tipo</label>
                            <select class="form-control" name="tipo" id="tipoFiltro">
                                <option value="">Todos</option>
                                <option value="noticia">Noticias</option>
                                <option value="moroso">Morosos</option>
                                <option value="recibo">Recibos</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-primary" id="btnBuscarLogs">
                                <i class="fa fa-search mr-1"></i> Buscar
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped log-table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Vecino</th>
                                    <th>Teléfono</th>
                                    <th>Tipo</th>
                                    <th>Mensaje</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="logsTableBody">
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        Haz clic en "Buscar" para cargar los logs
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Prevenir error de selectpicker si no existe
if (typeof $.fn.selectpicker === 'undefined') {
    $.fn.selectpicker = function() { return this; };
}

$(document).ready(function() {
    let pollingInterval = null;
    let currentStatus = 'UNKNOWN';
    const POLLING_INTERVAL = 5000; // 5 segundos

    // Función para mostrar alertas
    function showAlert(type, message) {
        const alert = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fa fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
                ${message}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        `;
        $('#alertContainer').html(alert);
        setTimeout(() => $('#alertContainer').empty(), 5000);
    }

    // Función para actualizar UI según estado
    function updateUI(status, data = {}) {
        // Ocultar todos los containers
        $('#loadingContainer, #qrContainer, #connectedContainer, #errorContainer').addClass('d-none');

        switch(status) {
            case 'CONNECTED':
                $('#connectedContainer').removeClass('d-none');
                $('#statusBadge').removeClass('badge-secondary badge-danger badge-warning').addClass('badge-success');
                $('#statusBadge i').removeClass('fa-circle-o-notch fa-spin fa-times-circle fa-qrcode').addClass('fa-check-circle');
                $('#statusText').text('Conectado');
                break;

            case 'WAITING_QR':
                $('#qrContainer').removeClass('d-none');
                if (data.qr) {
                    $('#qrImage').attr('src', data.qr);
                }
                $('#statusBadge').removeClass('badge-secondary badge-danger badge-success').addClass('badge-warning');
                $('#statusBadge i').removeClass('fa-circle-o-notch fa-spin fa-times-circle fa-check-circle').addClass('fa-qrcode');
                $('#statusText').text('Esperando escaneo');
                break;

            case 'INITIALIZING':
                $('#loadingContainer').removeClass('d-none');
                $('#statusBadge').removeClass('badge-success badge-danger badge-warning').addClass('badge-secondary');
                $('#statusBadge i').removeClass('fa-check-circle fa-times-circle fa-qrcode').addClass('fa-circle-o-notch fa-spin');
                $('#statusText').text('Inicializando...');
                break;

            case 'ERROR':
            case 'SERVICE_DOWN':
                $('#errorContainer').removeClass('d-none');
                $('#statusBadge').removeClass('badge-secondary badge-success badge-warning').addClass('badge-danger');
                $('#statusBadge i').removeClass('fa-circle-o-notch fa-spin fa-check-circle fa-qrcode').addClass('fa-times-circle');
                $('#statusText').text('Error de conexión');
                break;

            default:
                $('#loadingContainer').removeClass('d-none');
        }

        currentStatus = status;
    }

    // Función para verificar estado del servicio
    function checkServiceHealth() {
        $.ajax({
            url: '{{ route("whatsapp.health") }}',
            method: 'GET',
            timeout: 10000,
            success: function(response) {
                if (response.alive) {
                    $('#serviceStatus').removeClass('bg-secondary bg-danger').addClass('bg-success')
                        .html('<i class="fa fa-check-circle mr-1"></i> Servicio Activo');
                    $('#serviceAlive').removeClass('badge-secondary badge-danger').addClass('badge-success').text('Activo');
                    $('#serviceUptime').text(formatUptime(response.uptime));
                    
                    // Si el servicio está activo, verificar estado de WhatsApp
                    checkWhatsAppStatus();
                } else {
                    handleServiceDown();
                }
            },
            error: function() {
                handleServiceDown();
            }
        });
    }

    function handleServiceDown() {
        $('#serviceStatus').removeClass('bg-secondary bg-success').addClass('bg-danger')
            .html('<i class="fa fa-times-circle mr-1"></i> Servicio Caído');
        $('#serviceAlive').removeClass('badge-secondary badge-success').addClass('badge-danger').text('Caído');
        $('#serviceUptime').text('-');
        updateUI('SERVICE_DOWN');
    }

    // Función para verificar estado de WhatsApp
    function checkWhatsAppStatus() {
        $.ajax({
            url: '{{ route("whatsapp.qr") }}',
            method: 'GET',
            timeout: 15000,
            success: function(response) {
                if (response.status === 'CONNECTED') {
                    updateUI('CONNECTED');
                } else if (response.status === 'WAITING_QR' && response.qr) {
                    updateUI('WAITING_QR', { qr: response.qr });
                } else {
                    updateUI('INITIALIZING');
                }
            },
            error: function() {
                updateUI('ERROR');
            }
        });
    }

    // Formatear uptime
    function formatUptime(seconds) {
        if (!seconds) return '-';
        const hrs = Math.floor(seconds / 3600);
        const mins = Math.floor((seconds % 3600) / 60);
        const secs = Math.floor(seconds % 60);
        if (hrs > 0) return `${hrs}h ${mins}m ${secs}s`;
        if (mins > 0) return `${mins}m ${secs}s`;
        return `${secs}s`;
    }

    // Iniciar polling
    function startPolling() {
        checkServiceHealth(); // Verificar inmediatamente
        pollingInterval = setInterval(checkServiceHealth, POLLING_INTERVAL);
    }

    // Detener polling
    function stopPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }
    }

    // Refrescar QR manualmente
    $('#btnRefreshQR').on('click', function() {
        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Cargando...');
        checkWhatsAppStatus();
        setTimeout(() => {
            $(this).prop('disabled', false).html('<i class="fa fa-refresh mr-1"></i> Refrescar QR');
        }, 2000);
    });

    // Reintentar conexión
    $('#btnRetryConnection').on('click', function() {
        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Reintentando...');
        updateUI('INITIALIZING');
        checkServiceHealth();
        setTimeout(() => {
            $(this).prop('disabled', false).html('<i class="fa fa-refresh mr-1"></i> Reintentar');
        }, 3000);
    });

    // Cerrar sesión
    $('#btnDisconnect').on('click', function() {
        if (!confirm('¿Estás seguro de cerrar la sesión de WhatsApp?\n\nDeberás escanear el QR nuevamente para reconectar.')) return;
        
        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Cerrando...');
        
        $.ajax({
            url: '{{ route("whatsapp.disconnect") }}',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    showAlert('success', 'Sesión cerrada correctamente');
                    updateUI('INITIALIZING');
                } else {
                    showAlert('danger', response.error || 'Error al cerrar sesión');
                }
            },
            error: function() {
                showAlert('danger', 'Error de conexión');
            },
            complete: function() {
                $btn.prop('disabled', false).html('<i class="fa fa-sign-out mr-1"></i> Cerrar Sesión');
            }
        });
    });

    // Buscar logs
    $('#btnBuscarLogs').on('click', loadLogs);

    function loadLogs() {
        $('#logsTableBody').html('<tr><td colspan="6" class="text-center"><i class="fa fa-spinner fa-spin"></i> Cargando...</td></tr>');
        
        $.ajax({
            url: '{{ route("whatsapp.logs") }}',
            data: {
                fecha_inicio: $('#fechaInicio').val(),
                fecha_fin: $('#fechaFin').val(),
                tipo: $('#tipoFiltro').val()
            },
            success: function(response) {
                let html = '';
                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(log) {
                        let statusClass = 'status-' + log.status;
                        let vecinoNombre = log.propietario ? (log.propietario.nombre + ' ' + log.propietario.apellido) : '-';
                        html += `
                            <tr>
                                <td>${new Date(log.fecha).toLocaleString('es-PE')}</td>
                                <td>${vecinoNombre}</td>
                                <td>${log.telefono || '-'}</td>
                                <td><span class="badge badge-info">${log.tipo}</span></td>
                                <td>${(log.mensaje || '').substring(0, 50)}...</td>
                                <td>
                                    <span class="badge ${statusClass}">${log.status}</span>
                                    ${log.error_message ? '<br><small class="text-danger">' + log.error_message + '</small>' : ''}
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    html = '<tr><td colspan="6" class="text-center text-muted">No hay registros para las fechas seleccionadas</td></tr>';
                }
                $('#logsTableBody').html(html);
            },
            error: function() {
                $('#logsTableBody').html('<tr><td colspan="6" class="text-center text-danger">Error al cargar logs</td></tr>');
            }
        });
    }

    // Iniciar polling al cargar la página
    startPolling();

    // Detener polling al salir de la página
    $(window).on('beforeunload', stopPolling);
});
</script>
@endsection
